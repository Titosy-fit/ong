<?php

// class ApproModel extends CI_Model
// {
//     public function __construct()
//     {
//         parent::__construct();
//         $this->load->database();
//     }


//     // ********************** Utile ************************* // 

//     /**
//      * Recherche une materiel dans // par id et par type 
//      *
//      * @param [type] $ref
//      * @return void
//      */
//     public function getProduitByRef($ref = '', $type = '')
//     {
//         $this->db->select('*')
//             ->from('materiel')
//             ->where('refmateriel', $ref)
//             ->where('idadmin', $_SESSION['idadmin']);
//         return $this->db->get()->result();
//     }

//     public function insertAppro($data)
//     {
//         $this->db->insert('appro', $data);
//         return $this->db->insert_id();
//     }
//     public function insert_batch($data)
//     {
//         $this->db->insert_batch('appro', $data);
//     }

//     public function commande_recue($idcommande)
//     {
//         $this->db->where('idcommande', $idcommande)->update('commande', ['recue' => 1]);
//     }

//     public function teste_commande($idcommande)
//     {
//         return $this->db->select('*')
//             ->from('depense')
//             ->where('idcommande', $idcommande)
//             ->where('idadmin', $_SESSION['idadmin'])
//             ->get()->result();
//     }


//     public function get_count()
//     {
//         $this->db->select('*')
//             ->from('appro')
//             ->where('appro.idadmin', $_SESSION['idadmin']);
//         if (isset($_SESSION['pv'])) {
//             // si il'y a une filtre par point de vente 
//             $this->db->where('appro.idPointVente', $_SESSION['pv']);
//         }

//         return   $this->db->order_by('idAppro', 'desc')->count_all_results();
//     }
//     public function get_authors($page = 1)
//     {
//         // LIMITE 
//         if ($page == 0) {
//             $realOffset = $page * PAGINATION;
//         } else {
//             $realOffset = ($page - 1) * PAGINATION;
//         }

//         $this->db->select(' appro.* ,projet.* , pointvente.* , materiel.* , unite.* , commande.* , fournisseur.* , pv_1 ,  pv_2 ')
//             ->from('appro')
//             // jointure du transfert
//             ->join('transfert', 'transfert.idtransfert = appro.idtransfert', 'left')
//             ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
//             ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
//             // jointure du transfert


//             ->join('projet', 'projet.idprojet = appro.idprojet', 'left')
//             ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
//             ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
//             ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
//             ->join('unite', 'unite.idunite = appro.idunite', 'left')
//             ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
//             ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left')
//             // ->where('unite.idadmin', $_SESSION['idadmin'])
//             // ->where('materiel.idadmin', $_SESSION['idadmin'])
//             // ->where('appro.idadmin', $_SESSION['idadmin'])
//             // ->where('pointvente.idadmin', $_SESSION['idadmin'])
//         ;
//         if (isset($_SESSION['pv'])) {
//             $this->db->where('appro.idPointVente', $_SESSION['pv']);
//         }
//         $this->db->where('appro.idadmin', $_SESSION['idadmin']);
//         return  $this->db->order_by('idAppro', 'desc')
//             ->limit(PAGINATION, $realOffset)
//             ->get()->result();
//     }

//     public function searchDate($page = 1, $debut = '', $fin = '', $mot = '', $count = false)
//     {

//         if ((int)$page == 0) {
//             $start = (int)$page * PAGINATION;
//         } else {
//             $start = ((int)$page - 1) * PAGINATION;
//         }


//         // LIMITE 
//         if ($page == 0) {
//             $realOffset = $page * PAGINATION;
//         } else {
//             $realOffset = ($page - 1) * PAGINATION;
//         }

//         $this->db->select('appro.* , projet.* , pointvente.* , materiel.* , unite.* , commande.* , fournisseur.* , pv_1 ,  pv_2 ')
//             ->from('appro')

//             // jointure du transfert
//             ->join('transfert', 'transfert.idtransfert = appro.idtransfert', 'left')
//             ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
//             ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
//             // jointure du transfert

//             ->join('projet', 'projet.idprojet = appro.idprojet', 'left')
//             ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
//             ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
//             ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
//             ->join('unite', 'unite.idunite = appro.idunite', 'left')
//             ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
//             ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left')
//             // ->where('unite.idadmin', $_SESSION['idadmin'])
//             // ->where('materiel.idadmin', $_SESSION['idadmin'])
//             // ->where('appro.idadmin', $_SESSION['idadmin'])
//             // ->where('pointvente.idadmin', $_SESSION['idadmin'])
//         ;
//         if (isset($_SESSION['pv'])) {
//             $this->db->where('appro.idPointVente', $_SESSION['pv']);
//         }
//         $this->db->where('appro.idadmin', $_SESSION['idadmin']);

//         if ($mot != '') {
//             // $mot = '%' . $mot .'%' ; 
//             $this->db->like('materiel.refmateriel', $mot);
//             $this->db->or_like('materiel.designationmateriel', $mot);
//             $this->db->or_like('materiel.typemateriel', $mot);
//             $this->db->or_like('materiel.fiche', $mot);
//             $this->db->or_like('unite.denomination', $mot);
//             $this->db->or_like('appro.quantite', $mot);
//             $this->db->or_like('pointvente.denomination_pv', $mot);
//             $this->db->or_like('commande.cmfacture', $mot);
//             $this->db->or_like('fournisseur.nom_entr', $mot);
//             $this->db->or_like('projet.codeprojet', $mot);
//             $this->db->or_like('projet.titreprojet', $mot);
//         }

//         if ($debut != '' && $fin == '') {
//             $debut = '%' . $debut . '%';
//             $this->db->where('dateAppro LIKE', $debut);
//         } else if ($debut == '' && $fin != '') {
//             $fin = '%' . $fin . '%';
//             $this->db->where('dateAppro LIKE', $fin);
//         } else if ($debut != '' && $fin != '') {
//             $this->db->where('dateAppro >=', $debut);
//             $this->db->where('dateAppro <=', $fin);
//         }

//         if (!$count) {
//             $this->db->limit(PAGINATION, $start);
//         }
//         $q = $this->db->order_by('idAppro', 'desc')->get();
//         $query = $q->result();

//         if ($count) {
//             return count($query);
//         }
//         return $query;
//     }


//     public function deleteAppro($id)
//     {
//         $this->db->where('idAppro ', $id);
//         $this->db->delete('appro');
//     }
//     public function deleteDepense($id)
//     {
//         $this->db->where('idAppro ', $id);
//         $this->db->delete('depense');
//     }



//     public function getAllMat()
//     {
//         $q = $this->db->select('*')
//             ->from('materiel')
//             ->where('idadmin', $_SESSION['idadmin'])
//             ->order_by('idmateriel ')
//             ->get();
//         return $q->result();
//     }

//     public function depenseAppro($montant = '', $idpv = '', $idcommande = '', $idAppro = '')
//     {
//         $data = [
//             'raison' => 'Approvisionnement',
//             'montant' => $montant,
//             'idadmin' => $_SESSION['idadmin'],
//             'idPointVente' => $idpv,
//             'idcommande' => $idcommande,
//             'idAppro' => $idAppro,
//         ];
//         $this->db->insert('depense', $data);
//     }

//     // ********************** Utile ************************* // 
// }



class ApproModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ********************** Utile ************************* //

    /**
     * Recherche une materiel dans // par id et par type
     */
    public function getProduitByRef($ref = '', $type = '')
    {
        $this->db->select('*')
            ->from('materiel')
            ->where('refmateriel', $ref)
            ->where('idadmin', $_SESSION['idadmin']);
        return $this->db->get()->result();
    }

    public function insertAppro($data)
    {
        $this->db->insert('appro', $data);
        return $this->db->insert_id();
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch('appro', $data);
    }

    public function commande_recue($idcommande)
    {
        $this->db->where('idcommande', $idcommande)->update('commande', ['recue' => 1]);
    }

    public function teste_commande($idcommande)
    {
        return $this->db->select('*')
            ->from('depense')
            ->where('idcommande', $idcommande)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }

    public function get_count()
    {
        $this->db->select('*')
            ->from('appro')
            ->where('appro.idadmin', $_SESSION['idadmin']);

        // ← CONDITION RÔLE LOGISTIQUE
        if ($_SESSION['user_type'] !== 'admin' && !can_manage('logistique')) {
            if (isset($_SESSION['pv'])) {
                $this->db->where('appro.idPointVente', $_SESSION['pv']);
            }
        }

        return $this->db->order_by('idAppro', 'desc')->count_all_results();
    }

    public function get_authors($page = 1)
    {
        $idadmin = $_SESSION['idadmin'] ?? null;
        $id_user = $_SESSION['id_user'] ?? null;
        if ((int)$page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $this->db->select(' appro.* ,projet.* , pointvente.* , materiel.* , unite.* , commande.* , fournisseur.* , pv_1 ,  pv_2 ')
            ->from('appro')
            // jointure du transfert
            ->join('transfert', 'transfert.idtransfert = appro.idtransfert', 'left')
            ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
            ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
            // jointure du transfert
            ->join('projet', 'projet.idprojet = appro.idprojet', 'left')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->join('unite', 'unite.idunite = appro.idunite', 'left')
            ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
            ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left');

            if($idadmin){
           
            $this->db->where('appro.idadmin', $_SESSION['idadmin']);
                // ← CONDITION RÔLE LOGISTIQUE
                
            }
        if ($id_user) {
            $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
        }

        // if ($_SESSION['user_type'] !== 'admin' && !can_manage('logistique')) {
        //     if (isset($_SESSION['pv'])) {
        //         $this->db->where('appro.idPointVente', $_SESSION['pv']);
        //     }
        // }


        return $this->db->order_by('idAppro', 'desc')
            ->limit(PAGINATION, $realOffset)
            ->get()->result();
    }

    public function searchDate($page = 1, $debut = '', $fin = '', $mot = '', $count = false)
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('appro.* , projet.* , pointvente.* , materiel.* , unite.* , commande.* , fournisseur.* , pv_1 ,  pv_2 ')
            ->from('appro')
            // jointure du transfert
            ->join('transfert', 'transfert.idtransfert = appro.idtransfert', 'left')
            ->join('(SELECT idPointVente as id_1 , denomination_pv as pv_1 , idadmin  FROM pointvente ) p1', 'p1.id_1 = transfert.idPointVente_source AND p1.idadmin = transfert.idadmin', 'left')
            ->join('(SELECT idPointVente as id_2 , denomination_pv as pv_2 , idadmin  FROM pointvente ) p2', 'p2.id_2 = transfert.idPointVente_destination AND p2.idadmin = transfert.idadmin', 'left')
            // jointure du transfert
            ->join('projet', 'projet.idprojet = appro.idprojet', 'left')
            ->join('pointvente', 'pointvente.idPointVente = appro.idPointVente', 'left')
            ->join('fournisseur', 'fournisseur.idfournisseur = appro.idfournisseur', 'left')
            ->join('materiel', 'materiel.idmateriel = appro.idmateriel', 'left')
            ->join('unite', 'unite.idunite = appro.idunite', 'left')
            ->join('cmpanier', 'cmpanier.idcmfacture  = appro.idcmfacture ', 'left')
            ->join('commande', 'cmpanier.idcommande  = commande.idcommande ', 'left');

        $this->db->where('appro.idadmin', $_SESSION['idadmin']);

        // ← CONDITION RÔLE LOGISTIQUE
        if ($_SESSION['user_type'] !== 'admin' && !can_manage('logistique')) {
            if (isset($_SESSION['pv'])) {
                $this->db->where('appro.idPointVente', $_SESSION['pv']);
            }
        }

        if ($mot != '') {
            $this->db->like('materiel.refmateriel', $mot);
            $this->db->or_like('materiel.designationmateriel', $mot);
            $this->db->or_like('materiel.typemateriel', $mot);
            $this->db->or_like('materiel.fiche', $mot);
            $this->db->or_like('unite.denomination', $mot);
            $this->db->or_like('appro.quantite', $mot);
            $this->db->or_like('pointvente.denomination_pv', $mot);
            $this->db->or_like('commande.cmfacture', $mot);
            $this->db->or_like('fournisseur.nom_entr', $mot);
            $this->db->or_like('projet.codeprojet', $mot);
            $this->db->or_like('projet.titreprojet', $mot);
        }

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('dateAppro LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('dateAppro LIKE', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('dateAppro >=', $debut);
            $this->db->where('dateAppro <=', $fin);
        }

        if (!$count) {
            $this->db->limit(PAGINATION, $start);
        }

        $q = $this->db->order_by('idAppro', 'desc')->get();
        $query = $q->result();

        if ($count) {
            return count($query);
        }

        return $query;
    }

    public function deleteAppro($id)
    {
        $this->db->where('idAppro ', $id);
        $this->db->delete('appro');
    }

    public function deleteDepense($id)
    {
        $this->db->where('idAppro ', $id);
        $this->db->delete('depense');
    }

    public function getAllMat()
    {
        $q = $this->db->select('*')
            ->from('materiel')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idmateriel ')
            ->get();
        return $q->result();
    }

    public function depenseAppro($montant = '', $idpv = '', $idcommande = '', $idAppro = '')
    {
        $data = [
            'raison' => 'Approvisionnement',
            'montant' => $montant,
            'idadmin' => $_SESSION['idadmin'],
            'idPointVente' => $idpv,
            'idcommande' => $idcommande,
            'idAppro' => $idAppro,
        ];
        $this->db->insert('depense', $data);
    }

    // ********************** Utile ************************* //
}