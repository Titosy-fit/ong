<?php
class StockModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getQTE($ref, $pv)
    {
        $idadmin = $_SESSION['idadmin'];
        $qte = $this->db->query("SELECT SUM(quantiter) as qte FROM appro WHERE refmateriel='$ref' AND  idPointVente ='$pv' AND idadmin = '$idadmin'")->result();
        return $qte;
    }

    public function recherche($keyword)
    {
        $this->db->select('*')
            ->from('appro')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'inner')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            // ->where('pointvente.idadmin' , $_SESSION['idadmin'])
            ->where('appro.refmateriel LIKE', $keyword)
            ->or_where('appro.designationmateriel LIKE', $keyword)
            ->or_where('appro.quantiter LIKE', $keyword)
            ->or_where('pointvente.adressPv LIKE', $keyword)
            ->order_by('appro.refmateriel', 'DESC')->get();
        return $this->db->result();
    }

    public function vente()
    {
        $query = $this->db->select('*')
            ->from('vente')
            ->join('panier', 'panier.Facture = vente.Facture', 'right')
            ->where('vente.idadmin', $_SESSION['idadmin'])
            // ->where('panier.idadmin' , $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }


   


    // UTITY
    public function getStock($approvisionnement = [])
    {
        foreach ($approvisionnement as $key => $approv) {
            
            $idmateriel = $approv->idmateriel;
            $id_pv = $approv->idPointVente;

            $qte_min = 0;
            // pour l'appro 
            $appros = $this->db->select('appro.min_qte')
                ->from('appro')
                ->where('idmateriel', $idmateriel)
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idPointVente', $id_pv)
                ->get()->result();
            $qte_min_appro = 0;
            foreach ($appros as $key => $appro) {
                $qte_min_appro += $appro->min_qte;
            }

            // pour les vente

            $ventes =  $this->db->select('SUM(panier.min_qte) as min_qte')
                ->from('panier')
                ->where('idmateriel', $idmateriel)
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idPointVente', $id_pv)
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
                ->where('idPointVente_source', $id_pv)
                ->where('idmateriel', $idmateriel)
                ->get()->result();
            $qte_min_envoie = 0;
            foreach ($envois as $key => $envoi) {
                $qte_min_envoie += $envoi->qte_min_transfert;
            }


            
            // quantite dans le remise materiel

            $remises = $this->db->select('*')
                ->from('rempanier')
                ->join('remise' , 'remise.idremise = rempanier.idremise' , 'left')
                ->where('rempanier.idadmin', $_SESSION['idadmin'])
                ->where('remise.idPointVente', $id_pv)
                ->where('rempanier.idmateriel', $idmateriel)
                ->get()->result();

            $qte_remise = 0;
            foreach ($remises as $key => $remise) {
                $qte_remise += $remise->min_qte;
            }



            $qte_min = $qte_min_appro - $qte_min_vente - $qte_min_envoie + $qte_remise ;
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
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idmateriel')
            ->group_by('appro.idPointVente');
        // if (isset($_SESSION['pv'])) {
        //     $this->db->where('appro.idPointVente', $_SESSION['pv']);
        // } 
        // On applique le filtre pv SEULEMENT si l'utilisateur n'est NI admin NI logistique       nosoloina 
if ($_SESSION['user_type'] !== 'admin' && !can_manage('logistique')) {
    if (isset($_SESSION['pv'])) {
        $this->db->where('appro.idPointVente', $_SESSION['pv']);
    }
}
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

    /**
     * Tout les données dans seuil
     *
     * @param string $page
     * @return void
     */ 
    public function getAll_seuil($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }


        $this->db->select('a.idPointVente, 
                           p.*, 
                           COALESCE(SUM(a.min_qte), 0) AS total_appro, 
                           COALESCE(v.total_vendu, 0) AS total_vendu  , pv.*');
        $this->db->from('appro a');

        // Jointure avec la table materiel
        $this->db->join('materiel p', 'a.idmateriel = p.idmateriel', 'left');
        $this->db->join('pointvente  pv', 'a.idPointVente = pv.idPointVente', 'left');

        // Sous-requête pour obtenir les ventes par point de vente
        $this->db->join('(SELECT idmateriel, idPointVente, SUM(min_qte) AS total_vendu FROM panier GROUP BY idmateriel, idPointVente) v', 'p.idmateriel = v.idmateriel AND a.idPointVente = v.idPointVente', 'left');

        // Regrouper par idPointVente et idmateriel
        $this->db->group_by('a.idPointVente, p.idmateriel');
        $this->db->where('a.idadmin', $_SESSION['idadmin']);

        $this->db->having('(total_appro - total_vendu) <= p.seuil_min');

        // Appliquer une limite pour la pagination
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        // Exécution de la requête
        $appro = $this->db->get()->result();

        return $appro;
    }

    /**
     * Recherche dans le seuil
     *
     * @param string $keyword
     * @param string $page
     * @return void
     */
    public function getAll_search_seuil($keyword = '', $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('a.idPointVente, 
                           p.*, 
                           COALESCE(SUM(a.min_qte), 0) AS total_appro, 
                           COALESCE(v.total_vendu, 0) AS total_vendu  , pv.*');
        $this->db->from('appro a');

        // Jointure avec la table materiel
        $this->db->join('materiel p', 'a.idmateriel = p.idmateriel', 'left');
        $this->db->join('pointvente  pv', 'a.idPointVente = pv.idPointVente', 'left');

        // Sous-requête pour obtenir les ventes par point de vente
        $this->db->join('(SELECT idmateriel, idPointVente, SUM(min_qte) AS total_vendu FROM panier GROUP BY idmateriel, idPointVente) v', 'p.idmateriel = v.idmateriel AND a.idPointVente = v.idPointVente', 'left');

        // Regrouper par idPointVente et idmateriel
        $this->db->group_by('a.idPointVente, p.idmateriel');
        $this->db->where('a.idadmin', $_SESSION['idadmin']);

        $this->db->having('(total_appro - total_vendu) <= p.seuil_min');
        $this->db->having('(total_appro - total_vendu) >  0');
        if (isset($_SESSION['pv'])) {
            $this->db->where('a.idPointVente', $_SESSION['pv']);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }


        if ($keyword != '') {
            $this->db->like('p.refmateriel', $keyword);
            $this->db->or_like('p.designationmateriel', $keyword);
            $this->db->or_like('p.typemateriel', $keyword);
            $this->db->or_like('p.fiche', $keyword);
            $this->db->or_like('pv.adressPv', $keyword);
        }

        $appro =  $this->db->get()->result();

        return $appro;
    }

    /**
     * Recherche dans le stock
     *
     * @param string $keyword
     * @param string $page
     * @return void
     */
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
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idmateriel')
            ->group_by('appro.idPointVente');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }


        if ($keyword != '') {
            $this->db->like('materiel.refmateriel', $keyword);
            $this->db->or_like('materiel.designationmateriel', $keyword);
            $this->db->or_like('materiel.typemateriel', $keyword);
            $this->db->or_like('materiel.fiche', $keyword);
            $this->db->or_like('pointvente.adressPv', $keyword);
        }

        $appro =  $this->db->get()->result();

        return $appro;
    }

    /**
     * filtre dans le stock
     *
     * @param string $type
     * @param string $filtre
     * @param string $page
     * @return void
     */
    public function getAll_filtre($type  = '', $filtre = '', $page = '')
    {

        $the_filter = $filtre[$type];

        $count_pv = count($this->pv_stock());

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('')
            ->from('appro')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION['idadmin'])
            ->group_by('appro.idmateriel')
            ->group_by('appro.idPointVente');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        if ($type == 'reference') {
            $this->db->order_by('materiel.refmateriel', $the_filter);
        } else if ($type == 'designationmateriel') {
            $this->db->order_by('materiel.designationmateriel', $the_filter);
        } else if ($type == 'pv') {
            if (is_array($the_filter) && count($the_filter) > 0) {
                if (!isset($the_filter[0])) {
                    $_where = true;
                    for ($i = 1; $i <= $count_pv; $i++) {
                        if (isset($the_filter[$i])) {
                            if ($_where) {
                                $this->db->where('pointvente.idPointVente', $the_filter[$i]);
                                $_where = false;
                            } else {
                                $this->db->or_where('pointvente.idPointVente', $the_filter[$i]);
                            }
                        }
                    }
                }
            }
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $appro =  $this->db->get()->result();

        return $appro;
    }

    /**
     * filtre dans le stock seuil  
     *
     * @param string $type
     * @param string $filtre
     * @param string $page
     * @return void
     */
    public function getAll_filtre_seuil($type  = '', $filtre = '', $page = '')
    {

        $the_filter = $filtre[$type];

        $count_pv = count($this->pv_stock());

        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('a.idPointVente, 
                           p.*, 
                           COALESCE(SUM(a.min_qte), 0) AS total_appro, 
                           COALESCE(v.total_vendu, 0) AS total_vendu  , pv.*');
        $this->db->from('appro a');

        // Jointure avec la table materiel
        $this->db->join('materiel p', 'a.idmateriel = p.idmateriel', 'left');
        $this->db->join('pointvente  pv', 'a.idPointVente = pv.idPointVente', 'left');

        // Sous-requête pour obtenir les ventes par point de vente
        $this->db->join('(SELECT idmateriel, idPointVente, SUM(min_qte) AS total_vendu FROM panier GROUP BY idmateriel, idPointVente) v', 'p.idmateriel = v.idmateriel AND a.idPointVente = v.idPointVente', 'left');

        // Regrouper par idPointVente et idmateriel
        $this->db->group_by('a.idPointVente, p.idmateriel');
        $this->db->where('a.idadmin', $_SESSION['idadmin']);

        $this->db->having('(total_appro - total_vendu) <= p.seuil_min');
        $this->db->having('(total_appro - total_vendu) >  0');
        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        if ($type == 'reference') {
            $this->db->order_by('p.refmateriel', $the_filter);
        } else if ($type == 'designationmateriel') {
            $this->db->order_by('p.designationmateriel', $the_filter);
        } else if ($type == 'pv') {
            if (is_array($the_filter) && count($the_filter) > 0) {
                if (!isset($the_filter[0])) {
                    $_where = true;
                    for ($i = 1; $i <= $count_pv; $i++) {
                        if (isset($the_filter[$i])) {
                            if ($_where) {
                                $this->db->where('pv.idPointVente', $the_filter[$i]);
                                $_where = false;
                            } else {
                                $this->db->or_where('pv.idPointVente', $the_filter[$i]);
                            }
                        }
                    }
                }
            }
        }
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $appro =  $this->db->get()->result();

        return $appro;
    }

    public function pv_stock()
    {
        $this->db->select('appro.idPointVente, pointvente.denomination_pv, COUNT(appro.idAppro) as total_appro')
            ->from('appro')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->where('appro.idadmin', $_SESSION["idadmin"]);

        if (isset($_SESSION['pv'])) {
            $this->db->where('appro.idPointVente', $_SESSION['pv']);
        }

        $this->db->group_by('appro.idPointVente');


        return $this->db->get()->result();
    }

    // UTIliTY
}
