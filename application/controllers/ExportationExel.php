<?php
class ExportationExel extends CI_Controller 
{
    public function __construct( )
    {
        parent::__construct() ; 
        $this->load->model('ExportationModel', 'exportation');
        $this->load->model('StockModel', 'stock');
        $this->load->model('MaterielModel', 'materiel');
        $this->load->model('ApproModel', 'appro');
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('BudgetModel', 'budget');
    }
      public function exportBudget() {
        $datas = $this->budget->getbudget();

        $tete = [
            'Code du projet',
            'Ligne budgetaire',
            'Intitulé',
            'Montant alloué',
            'Reliquat'
        ];


        // creation du donne a evoyer a exel  ;
        $exel_datas = [];
        foreach ($datas as $key => $data) {
            $temps = [
                'codeprojet' => $data->codeprojet,
                'ligne_budget' => $data->ligne_budget,
                'designation_budget' => $data->designation_budget,
                'budget' => $data->budget,
                'reste' => $data->reste
            ];
            $exel_datas[] = $temps;
            $temps = [] ; 
        }


        $this->exportation->exporter('Budget', $exel_datas, $tete); 
    }
    public function exportProjet() {
        $datas = $this->projet->getpojet();

        $tete = [
            'Code du projet',
            'Titre',
            'Date de début',
            'Date de fin',
            'Région'
        ];


        // creation du donne a evoyer a exel  ;
        $exel_datas = [];
        foreach ($datas as $key => $data) {
            $temps = [
                'codeprojet' => $data->codeprojet,
                'titreprojet' => $data->titreprojet,
                'date_debut' => $data->date_debut,
                'date_fin' => $data->date_fin,
                'regionprojet' => $data->regionprojet
            ];
            $exel_datas[] = $temps;
            $temps = [] ; 
        }


        $this->exportation->exporter('liste_projet', $exel_datas, $tete); 
    }
    public function materiel(){
        $datas = $this->materiel->getProduitExel();

        $tete = [
            'Réference',
            'Désignation',
            'Type ',
            'Déscription ',
        ];


        // creation du donne a evoyer a exel  ;
        $exel_datas = [];
        foreach ($datas as $key => $data) {
            $temps = [
                'ref' => $data->refmateriel,
                'des' => $data->designationmateriel,
                'type' => $data->typemateriel,
                'dec' => $data->fiche,
            ];
            $exel_datas[] = $temps;
            $temps = [] ; 
        }


        $this->exportation->exporter('Produit', $exel_datas, $tete); 
    }
    public function stock(){
        

        $appros = $this->stock->getAll();

        

        $stocks = $this->stock->getStock($appros);
        // convertire 
        foreach ($stocks as $key => $stock) {
            $unites = $stock->unites;
            $min_qte = $stock->stock;
            // donner les qte correspondant a chaque unite
            $unite_convert = $this->covertion($unites, $min_qte);
            $stock->quantite_texte = $this->stock_texte($unite_convert, 0);
        }

        $all_pv = $this->stock->pv_stock();

        $header = [
            'Réference',
            'Désignation',
            'Déscription' , 
            'Quantité',
            'Dépôt ',
        ];

        // creation du donne a evoyer a exel  ;
        $exel_datas = [] ; 
        foreach ($stocks as $key => $stock) {
            $temps = [
                'ref' => $stock->refmateriel , 
                'des' => $stock->designationmateriel , 
                'Fiche' => $stock->fiche , 
                'quantite_texte' => $stock->quantite_texte , 
                'pv' => $stock->denomination_pv .' ' . $stock->adressPv  , 
            ] ; 
            $exel_datas [] = $temps  ; 
                $temps = [] ; 
        }

        $this->exportation->exporter( 'Stock' , $exel_datas , $header ) ; 
    }
    public function appro(){
        $datas = $this->appro->get_authors();

        $header = [
            'N° commande',
            'Transfert',
            'Réference',
            'Désignation',
            'Type',
            'Déscription',
            'Quantité',
            'Dépôt',
            'Projet',
            'Fournisseur',
            'Date',
        ];

        // creation du donner pour exel
        $exel_datas = [];
        foreach ($datas as $key => $data) {
            $temps = [
                'cmfacture' => $data->cmfacture,
                'tranfert' => ($data->pv_1 != '') ?  $data->pv_1 . '=>' . $data->pv_2 : '--'  ,
                'refmateriel' => $data->refmateriel,
                'designationmateriel' => $data->designationmateriel,
                'typep' => $data->typemateriel,
                'descrip' => $data->fiche ,
                'quantite' => ( $data->denomination ) ? $data->quantite .' '. $data->denomination .'(s)' : $data->quantite  ,
                'denomination_pv' => $data->denomination_pv,
                'codep' => ($data->codeprojet != '') ? strtoupper($data->codeprojet) : '--' ,
                'nom_entr' => ($data->nom_entr != '') ? strtoupper($data->nom_entr) : '--' ,
                'date' => form_date($data->dateAppro),
            ];
            $exel_datas[] = $temps;;
            $temps = [];
        }


        $this->exportation->exporter('Approvisionnement', $exel_datas, $header);

    }
}