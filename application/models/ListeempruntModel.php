<?php

class ListeempruntModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllPv()
    {
        return $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'desc')
            ->get()->result();
    }

    public function getAllListe($page = '')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('panier')
            ->join('materiel', 'panier.idmateriel = materiel.idmateriel', 'left')
            ->join('(SELECT denomination , idunite FROM unite) unite', 'unite.idunite = panier.idunite', 'left')
            ->join('dispatch', 'dispatch.idfacture = panier.idfacture', 'left')
            ->join('(SELECT denomination_pv , idPointVente   FROM pointvente) pointvente', 'pointvente.idPointVente = dispatch.idPointVente', 'left')

            ->join('user', 'user.idUser  = dispatch.idUser ', 'left')
            ->join('(SELECT idUser as idagent , prenomUser as prenomagent ,  nomUser AS nomagent , contact AS telagent FROM user) agent', 'agent.idagent = dispatch.idagent', 'left')
            ->where('dispatch.idadmin', $_SESSION['idadmin']);

        $this->db->where('type_dispatch', 'emprunt');

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }
        $this->db->order_by('dispatch.Facture', 'desc');

        $query = $this->db->get()->result();


        return $query;
    }





    public function search($debut = '', $fin = '', $mot = '', $lieu = 0, $page = '', $type_dispatch = 'vente')
    {
        // pagination 
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*');
        $this->db->from('panier')
            ->join('materiel', 'panier.idmateriel = materiel.idmateriel', 'left')
            ->join('(SELECT denomination , idunite FROM unite) unite', 'unite.idunite = panier.idunite', 'left')
            ->join('dispatch', 'dispatch.idfacture = panier.idfacture', 'left')
            ->join('(SELECT denomination_pv , idPointVente   FROM pointvente) pointvente', 'pointvente.idPointVente = dispatch.idPointVente', 'left')

            ->join('user', 'user.idUser  = dispatch.idUser ', 'left')
            ->join('(SELECT idUser as idagent , prenomUser as prenomagent ,  nomUser AS nomagent , contact AS telagent FROM user) agent', 'agent.idagent = dispatch.idagent', 'left')
            ->where('dispatch.idadmin', $_SESSION['idadmin']);

        $this->db->where('type_dispatch', 'emprunt');


        if ($mot != '') {
            $this->db->like('dispatch.Facture', $mot);
            // $this->db->like('modepaiement.denom', $mot);
            $this->db->or_like('materiel.refmateriel', $mot);
            $this->db->or_like('materiel.designationmateriel', $mot);
            $this->db->or_like('agent.prenomagent', $mot);
            $this->db->or_like('agent.nomagent', $mot);
            $this->db->or_like('agent.telagent', $mot);

        }

        if ($lieu != 0) {
            $this->db->where('pointvente.idPointVente ', $lieu);
        }

        if ($debut != '' && $fin == '') {
            $debut = '%' . $debut . '%';
            $this->db->where('dispatch.dateVente LIKE', $debut);
        } else if ($debut == '' && $fin != '') {
            $fin = '%' . $fin . '%';
            $this->db->where('dispatch.dateVente like', $fin);
        } else if ($debut != '' && $fin != '') {
            $this->db->where('dispatch.dateVente >=', $debut);
            $this->db->where('dispatch.dateVente <=', $fin);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        $this->db->order_by('dispatch.Facture', 'desc');
        $query = $this->db->get()->result();

        return $query;
    }
}
