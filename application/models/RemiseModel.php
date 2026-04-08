<?php

class RemiseModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    // ********************** Utile ************************* // 

    /**
     * Recherche une materiel dans // par id et par type 
     *
     * @param [type] $ref
     * @return void
     */
    public function getProduitByRef($ref = '', $type = '')
    {
        $this->db->select('*')
            ->from('materiel')
            ->where('refmateriel', $ref)
            ->where('idadmin', $_SESSION['idadmin']);
        return $this->db->get()->result();
    }


    public function getpanierbyid( $idpanier = '' )
    {
        $this->db->select('*')
            ->from('panier')
            ->where('idPanier', $idpanier)
            ->where('idadmin', $_SESSION['idadmin']);
        return $this->db->get()->result();
    }
    public function return( $idpanier = '' )
    {
        $this->db
            ->where('idPanier', $idpanier)
            ->where('idadmin', $_SESSION['idadmin'])
            ->update('panier' ,['isreturn' => 1 ]);
    }

    /**
     * ajout dans remise 
     *
     * @param [type] $data
     * @return void
     */
    public function insertremise($data)
    {
        $this->db->insert('remise', $data);
        return $this->db->insert_id();
    }

    public function insertremisepanier($data)
    {
        $this->db->insert_batch('rempanier', $data);
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
            ->from('remise')
            ->where('remise.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            // si il'y a une filtre par point de vente 
            $this->db->where('remise.idPointVente', $_SESSION['pv']);
        }

        return   $this->db->order_by('idremise', 'desc')->count_all_results();
    }
    public function get_authors($page = 1)
    {
        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('remise')
            // jointure du transfer
            ->join('pointvente', 'pointvente.idPointVente = remise.idPointVente', 'left')
            ->join('user', 'user.idUser = remise.idUser', 'left')
            ->join('( SELECT Facture , idfacture FROM dispatch ) dispatch', 'dispatch.idfacture = remise.idfacture', 'left')
            ->join('( SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent FROM user ) agent', 'agent.idagent = remise.idagent', 'left')
        ;
        if (isset($_SESSION['pv'])) {
            $this->db->where('remise.idPointVente', $_SESSION['pv']);
        }
        $this->db->where('remise.idadmin', $_SESSION['idadmin']);
        return  $this->db->order_by('idremise', 'desc')
            ->limit(PAGINATION, $realOffset)
            ->get()->result();
    }
    public function getbyid($id = 0 )
    {
        // LIMITE 
        $this->db->select('*')
            ->from('remise')
            // jointure du transfer
            ->join('pointvente', 'pointvente.idPointVente = remise.idPointVente', 'left')
            ->join('user', 'user.idUser = remise.idUser', 'left')
            ->join('( SELECT Facture , idfacture FROM dispatch ) dispatch', 'dispatch.idfacture = remise.idfacture', 'left')
            ->join('( SELECT idUser as idagent , nomUser as nomagent , contact as telagent , prenomUser as prenomagent FROM user ) agent', 'agent.idagent = remise.idagent', 'left')
        ;
        $this->db->where('remise.idremise', $id );
        if (isset($_SESSION['pv'])) {
            $this->db->where('remise.idPointVente', $_SESSION['pv']);
        }
        $this->db->where('remise.idadmin', $_SESSION['idadmin']);
        return  $this->db->order_by('idremise', 'desc')
            ->get()->result();
    }
    
    public function getpanier( $idremise ){
        return $this->db->select('*')
                ->from('rempanier')
                ->join('materiel' , 'materiel.idmateriel = rempanier.idmateriel' , 'left')
                ->join('unite' , 'unite.idunite = rempanier.idunite' , 'left')
                ->where('rempanier.idadmin' , $_SESSION['idadmin'])
                ->where('rempanier.idremise' , $idremise)
                ->get()->result() ; 
    }
    public function searchDate($page = 1, $debut = '', $fin = '', $mot = '', $count = false)
    {

        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('remise')
            // jointure du transfer
            ->join('pointvente', 'pointvente.idPointVente = remise.idPointVente', 'left')
            ->join('user', 'user.idUser = remise.idUser', 'left')
            ->join('( SELECT Facture , idfacture FROM dispatch ) dispatch', 'dispatch.idfacture = remise.idfacture', 'left')
            ->join('( SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent FROM user ) agent', 'agent.idagent = remise.idagent', 'left')
        ;
        if (isset($_SESSION['pv'])) {
            $this->db->where('remise.idPointVente', $_SESSION['pv']);
        }
        $this->db->where('remise.idadmin', $_SESSION['idadmin']);


        if ($mot != '') {
            // $mot = '%' . $mot .'%' ; 
            $this->db->like('dispatch.Facture', $mot);
            $this->db->or_like('agent.nomagent', $mot);
            $this->db->or_like('agent.prenomagent', $mot);
            $this->db->or_like('user.nomUser', $mot);
            $this->db->or_like('user.prenomUser', $mot);
            $this->db->or_like('pointvente.denomination_pv', $mot);
        }

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('date_remise LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('date_remise LIKE', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('date_remise >=', $debut);
            $this->db->where('date_remise <=', $fin);
        }

        if (!$count) {
            $this->db->limit(PAGINATION, $realOffset);
        }
        $q = $this->db->order_by('idremise', 'desc')->get();
        $query = $q->result();

        if ($count) {
            return count($query);
        }
        return $query;
    }


    public function delete($id)
    {
        $this->db->where('idremise ', $id);
        $this->db->delete('remise');

        // panier 
        $this->db->where('idremise' , $id )
                    ->delete('rempanier') ; 
    }
    public function deleteDepense($id)
    {
        $this->db->where('idremise ', $id);
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

    public function depenseAppro($montant = '', $idpv = '', $idcommande = '', $idremise = '')
    {
        $data = [
            'raison' => 'Approvisionnement',
            'montant' => $montant,
            'idadmin' => $_SESSION['idadmin'],
            'idPointVente' => $idpv,
            'idcommande' => $idcommande,
            'idremise' => $idremise,
        ];
        $this->db->insert('depense', $data);
    }

    public function get_sum_remise($idpanier)
    {
        return $this->db->select_sum('min_qte')
            ->from('rempanier')
            ->where('idPanier', $idpanier)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->row();
    }


    // ********************** Utile ************************* // 
}
