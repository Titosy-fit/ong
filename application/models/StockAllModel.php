<?php 
class StockAllModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct() ; 
    }

    public function getAll($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('')
            ->from('appro')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idmateriel');

        $appros =  $this->db->get()->result();
        // $data_pv = [];
        // $data_prod = [];
        // foreach ($appros  as $key => $appro) {
        //     $data_prod[] = $appro->idmateriel;
        //     $data_pv[] = $appro->idPointVente;
        // }

        // $transferts = $this->db->select('transfert.idmateriel , transfert.idPointVente_destination as idPointVente' )
        //     ->from('transfert')
        //     ->join('materiel', 'materiel.idmateriel = transfert.idmateriel', 'left')
        //     ->join('pointvente', 'pointvente.idPointVente = transfert.idPointVente_destination', 'left')
        //     ->where('transfert.idadmin', $_SESSION['idadmin'])
        //     ->where('transfert.reception_transfert', 1)
        //     ->get()->result();

        

        // foreach ($transferts  as $key => $transfert) {
        //     if ( !in_array( $transfert->idmateriel , $data_prod  ) || !in_array( $transfert->idPointVente , $data_pv  ) ){
        //         $appros[] = $transfert ;  
        //     }
        // }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        // echo '<pre>' ;
        // var_dump( $transferts ) ; 
        // echo '</pre>' ; die ; 



        return $appros;
    }
    public function getStock($approvisionnement = [])
    {
        foreach ($approvisionnement as $key => $approv) {
            
            $idmateriel = $approv->idmateriel;

            $qte_min = 0;
            // pour l'appro 
            $appros = $this->db->select('appro.min_qte')
                ->from('appro')
                ->where('idmateriel', $idmateriel)
                ->where('idadmin', $_SESSION['idadmin'])
                ->get()->result();
            $qte_min_appro = 0;
            foreach ($appros as $key => $appro) {
                $qte_min_appro += $appro->min_qte;
            }

            // pour les vente

            $ventes =  $this->db->select('panier.min_qte')
                ->from('panier')
                ->where('idmateriel', $idmateriel)
                ->where('idadmin', $_SESSION['idadmin'])
                ->group_by('idmateriel')
                ->group_by('idfacture')
                ->get()->result();

            $qte_min_vente = 0;
            foreach ($ventes as $key => $vente) {
                $qte_min_vente += $vente->min_qte;
            }

            // quantiter envoyer vers une autre pv

            $envois = $this->db->select('*')
                ->from('transfert')
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idmateriel', $idmateriel)
                ->get()->result();
            $qte_min_envoie = 0;
            foreach ($envois as $key => $envoi) {
                $qte_min_envoie += $envoi->qte_min_transfert;
            }

            $recues = $this->db->select('*')
                ->from('transfert')
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idmateriel', $idmateriel)
                ->get()->result();
            $qte_min_recue = 0;
            // foreach ($recues as $key => $recue) {
            //     $qte_min_recue += $recue->qte_min_transfert;
            // }



            $qte_min = $qte_min_appro - $qte_min_vente + $qte_min_recue - $qte_min_envoie;
            //  

            // unite 
            $unites = $this->db->select('*')
                ->from('unite')
                ->where('idmateriel', $idmateriel)
                ->get()->result();
            $approv->stock = $qte_min;
            $approv->unites = $unites;
        }

        return $approvisionnement;
    }
    public function pv_stock()
    {
        $this->db->select('*')
            ->from('appro')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION["idadmin"]);
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        $this->db->group_by('appro.idPointVente');

        return $this->db->get()->result();
    }


    public function getAll_search($keyword = '', $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('')
            ->from('appro')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idmateriel');

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        if ($keyword != '') {
            $this->db->like('materiel.refmateriel', $keyword);
            $this->db->or_like('materiel.designationmateriel', $keyword);
            $this->db->or_like('materiel.type', $keyword);
            $this->db->or_like('materiel.marque', $keyword);
        }
        $appro =  $this->db->get()->result();
        return $appro;
    }
}