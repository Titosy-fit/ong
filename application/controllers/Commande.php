<?php
class Commande extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('FournisseurModel', 'fournisseur');
        $this->load->model('CommandeModel', 'commande');
        $this->load->model('Admin_model');
    }

    // ******************** UTILE ***************************** //
    public function index()
    {

        $this->jail();
        $data['title'] = 'Commande';
        $data['css'] = 'commande.css';
        $js['js'] = 'commande.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/tete');
        $this->load->view('templates/sidebar', ['dispatch' => true]);
        $this->load->view('commande');
        $this->load->view('templates/footer', $js);
    }

    public function getProduit()
    {
        $reference = '';
        if (isset($_POST['reference']) && $_POST['reference'] != '') {
            $reference = trim(strip_tags($_POST['reference']));
        }

        $datas = $this->commande->getProduit($reference);
        if (count($datas) > 0) {
            echo json_encode([
                'success' => true,
                'datas' => $datas[0]
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }







    public function getPrixUnite()
    {
        $idmateriel = '';
        if (isset($_POST['idmateriel']) && $_POST['idmateriel'] != '') {
            $idmateriel = trim(strip_tags($_POST['idmateriel']));
        }

        $id_pv = '';
        if (isset($_POST['id_pv']) && $_POST['id_pv'] != '') {
            $id_pv = trim(strip_tags($_POST['id_pv']));
        }


        $prixUnites = $this->dispatch->getPrixUnite($idmateriel);

        // verification du quantiter disponnible 
        $quantite =  $this->dispatch->getStock($idmateriel, $id_pv);
        if (count($prixUnites) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $prixUnites,
                'quantite' => $quantite
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    public function fournisseur()
    {
        $recherche = '';
        if (isset($_POST['recherche']) && $_POST['recherche'] != '') {
            $recherche = trim(strip_tags($_POST['recherche']));
        }


        $datas = $this->fournisseur->search($recherche);
        echo json_encode([
            'datas' => $datas
        ]);
    }


    // public function facturation()
    // {
    //     $idfournisseur = '';
    //     if (isset($_POST['idfournisseur']) && $_POST['idfournisseur'] != '') {
    //         $idfournisseur = strip_tags(trim($this->input->post('idfournisseur')));
    //         // $idfournisseur = str_replace(' ', '', $idfournisseur);
    //     }

    //     $tva = '';
    //     if (isset($_POST['tva']) && $_POST['tva'] != '') {
    //         $tva = strip_tags(trim($this->input->post('tva')));
    //     }
    //     $tmp = '';
    //     if (isset($_POST['tmp']) && $_POST['tmp'] != '') {
    //         $tmp = strip_tags(trim($this->input->post('tmp')));
    //     }
    //     $projet = '';
    //     if (isset($_POST['projet']) && $_POST['projet'] != '') {
    //         $projet = strip_tags(trim($this->input->post('projet')));
    //     }


    //     $montant_total = '';
    //     if (isset($_POST['montant_total']) && $_POST['montant_total'] != '') {
    //         $montant_total = trim(strip_tags($_POST['montant_total']));
    //     }
    //     $frais = '';
    //     if (isset($_POST['frais']) && $_POST['frais'] != '') {
    //         $frais = trim(strip_tags($_POST['frais']));
    //     }






    //     // $fournisseur = $this->fournisseur->getFournisseurByNumero($numfournisseur);


    //     $lastFacture = $this->commande->getLastFacture();

    //     if (!empty($lastFacture)) {
    //         $last = $lastFacture[0]->cmfacture;
    //         $explodeLastFacture = explode("-", $last);
    //         $numAI = intval($explodeLastFacture[1]);
    //         $numAI++;
    //         $numFacture = date("Y") . "-" . str_pad($numAI, 5, 0, STR_PAD_LEFT);
    //     } else {
    //         $numFacture = date("Y") . "-" . str_pad("1", 5, 0, STR_PAD_LEFT);
    //     }


    //     $data = [
    //         'cmfacture' => $numFacture,
    //         'idfournisseur' => my_trim($idfournisseur),
    //         'idadmin' => $_SESSION['idadmin'],
    //         'tva' => $tva,
    //         'tmp' => $tmp,
    //         'idprojet' => $projet,
    //         'montant_total' => $montant_total,
    //         'frais' => $frais,
    //     ];
    //     $idfacture = $this->commande->insertcommande($data);
    //     echo json_encode([
    //         'success' => true,
    //         'facture' => $numFacture,
    //         'idfacture' => $idfacture,
    //     ]);
    // }
public function facturation()
{
    $idfournisseur = $this->input->post('idfournisseur') ? trim(strip_tags($this->input->post('idfournisseur'))) : null;
    $tva           = $this->input->post('tva') === 'true' || $this->input->post('tva') === '1';
    $tmp           = $this->input->post('tmp') === 'true' || $this->input->post('tmp') === '1';
    $idprojet      = $this->input->post('projet')     ? (int)$this->input->post('projet') : null;
    $idbudget      = $this->input->post('idbudget')   ? (int)$this->input->post('idbudget') : null;
    $montant_total = $this->input->post('montant_total') ? (float)$this->input->post('montant_total') : 0;
    $frais         = $this->input->post('frais')      ? (float)$this->input->post('frais') : 0;

    // Génération numéro facture
    $lastFacture = $this->commande->getLastFacture();
    if (!empty($lastFacture)) {
        $last = $lastFacture[0]->cmfacture;
        $explode = explode("-", $last);
        $numAI = (int)($explode[1] ?? 0) + 1;
        $numFacture = date("Y") . "-" . str_pad($numAI, 5, "0", STR_PAD_LEFT);
    } else {
        $numFacture = date("Y") . "-00001";
    }

    $data = [
        'cmfacture'     => $numFacture,
        'idfournisseur' => $idfournisseur,
        'idadmin'       => $_SESSION['idadmin'],
        'tva'           => $tva ? 'true' : 'false',
        'tmp'           => $tmp ? 'true' : 'false',
        'idprojet'      => $idprojet,
        'idbudget'      => $idbudget,
        'montant_total' => $montant_total,
        'frais'         => $frais,
        'datecommande'  => date('Y-m-d H:i:s'),
    ];

    $idcommande = $this->commande->insertcommande($data);

    if (!$idcommande) {
        echo json_encode([
            'success' => false,
            'message' => 'Échec insertion commande'
        ]);
        return;
    }

    echo json_encode([
        'success'    => true,
        'facture'    => $numFacture,
        'idcommande' => $idcommande,     // ← nom cohérent avec la table
    ]);
}
    public function validate()
    {
        $datas = [];
        if (isset($_POST['data'])) {
            $datas = $_POST['data'];
        }

        // echo '<pre>' ;
        // var_dump( $datas ) ; 
        // echo '</pre>' ; die ;

        foreach ($datas  as $key => $data) {
            $datas[$key]['idadmin'] = $_SESSION['idadmin'];
        }
        $this->commande->insertPanier($datas);
        $this->session->set_flashdata('success', true);
    }


    public function facture($facture = '')
    {
        if ($facture != '') {
            $facture = trim(strip_tags($facture));
        }


        $fact = $this->commande->getFature($facture);



        $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);

        $inclus =null ;
        if ( $fact->tmp == 'true' ){
            $inclus = TMP ; 
        }
        if ( $fact->tva == 'true' ){
            $inclus = TVA ; 
        }

        if ($inclus==null) {
             $inclus = 0 ;
             $total['ht'] = $fact->montant_total + $fact->frais;
             $total['inclus'] = $total['ht'] * $inclus ;
             $total['ttc'] = $total['inclus'] + $total['ht'];
        }
        else {
             $total['ht'] = $fact->montant_total + $fact->frais;
             $total['inclus'] = $total['ht'] * $inclus ;
             $total['ttc'] = $total['inclus'] + $total['ht'];
        }
       


        $this->load->library('Commandefacture');
        $pdf = new Commandefacture("P", "mm", "A4");
        $pdf->AddPage();


        for ($i = 0; $i < $pdf->nbr_page; $i++) {
            $offest = $i * 10;
            $limite = ($i + 1) * 10;
            if ($pdf->nbr_page > 1 && $i != ($pdf->nbr_page - 1)) {
                $pdf->pos_foot = 158;
                $pdf->affiche = false;
            } else {
                $pdf->pos_foot = 100;
                $pdf->affiche = true;
            }

            $pdf->head($fact, $admin);
            $pdf->Ln(8);
            $pdf->corps($fact, $total, $offest,  $limite);
            $pdf->Ln($pdf->pos_foot);
            // $pdf->foot();

            $pdf->pos_foot = 100;
        }
        $pdf->Output();
    }
// Budget.php ou Commande.php
public function getBudgetsByProjet_json()
{
    $idprojet = $this->input->post('idprojet');

    if (!$idprojet || !is_numeric($idprojet)) {
        echo json_encode(['success' => false, 'message' => 'ID projet manquant ou invalide']);
        return;
    }

    $this->db->select('
        budget.idbudget,
        budget.ligne_budget,
        budget.designation_budget,
        budget.budget,
        (
            budget.budget - COALESCE(SUM(avance_mission.montant_avance), 0) 
                          - COALESCE(SUM(commande_budget.montant_impute), 0)
        ) AS reliquat
    ');
    $this->db->from('budget');
    $this->db->join('avance_mission', 'avance_mission.idbudget = budget.idbudget', 'left');
    $this->db->join('commande_budget', 'commande_budget.idbudget = budget.idbudget', 'left');
    $this->db->where('budget.idprojet', $idprojet);
    $this->db->where('budget.idadmin', $_SESSION['idadmin']);
    $this->db->group_by('budget.idbudget');
    $this->db->order_by('budget.ligne_budget', 'ASC');

    $query = $this->db->get();
    $budgets = $query->result_array();

    echo json_encode([
        'success' => true,
        'budgets' => $budgets
    ]);
}

    // ******************** UTILE ***************************** //
}
