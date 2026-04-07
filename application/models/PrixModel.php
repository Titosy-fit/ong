<?php

class PrixModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    // *  pagination 

    public function get_count()
    {
        $prix = $this->db->select('*')
            ->from('prix')
            ->join('materiel', 'materiel.idmateriel = prix.idmateriel')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->order_by('prix.idPrix', 'desc')
            ->group_by('prix.groupe')
            ->get()->result();
        return count($prix);
    }

    public function get_authors($limit, $start)
    {
        $prix = $this->db->select('*' )
            ->from('prix')
            ->join('materiel', 'materiel.idmateriel = prix.idmateriel', 'left')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->order_by('prix.idPrix', 'desc')
            ->group_by('prix.groupe')
            ->limit($limit, $start)
            ->get()->result();



        return $prix;
    }


    public function verifier_prix_materiel($mat)
    {
        $idadmin = $_SESSION['idadmin'];
        return $this->db->query("SELECT * FROM prix  INNER JOIN materiel ON prix.idmateriel = materiel.idmateriel WHERE materiel.refmateriel LIKE '%$mat%'  AND prix.idadmin = '$idadmin' ")->result();
    }

    public function verifRefProd($ref)
    {
        $query = $this->db->select('*')
            ->from('materiel')
            ->where('refmateriel', $ref)
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }

    public function deleteItem($idmateriel, $groupe)
    {
        $this->db->where('idmateriel', $idmateriel);
        $this->db->where('groupe', $groupe);
        $this->db->delete('prix');
    }


    public function insertPrix($data)
    {
        $this->db->insert('prix', $data);

        return $this->db->insert_id();
    }
    public function insert_bacth($data)
    {
        $this->db->insert_batch('prix', $data);
    }



    public function searchPrix($keyword = '', $limit = '', $start = 1)
    {
        $keyword = '%' . $keyword . '%';
        $this->db->select('*')
            ->from('prix')
            ->join('materiel', 'prix.idmateriel = materiel.idmateriel')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('materiel.refmateriel LIKE', $keyword)
            ->or_where('materiel.designationmateriel LIKE', $keyword)
            ->or_where('materiel.fiche LIKE', $keyword)
            ->or_where('prix.prixProduit LIKE', $keyword)
            ->or_where('prix.dateAjoutPrix LIKE', $keyword)
            ->order_by('prix.idPrix', 'DESC')
            ->group_by('prix.groupe');
        if ($limit != '') {
            $this->db->limit($limit, $start);
        }
        $prix =  $this->db->get()->result();
        return $prix;
    }


    public function selectUniterByProduit($idmateriel)
    {
        $this->db->select('*');
        $this->db->from('prix')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->join('materiel', 'prix.idmateriel = materiel.idmateriel', 'left')
            ->join('groups', 'groups.id_group = materiel.id_group', 'left')
            ->join('uniter', 'uniter.idUniter = prix.idUniter', 'left');

        $this->db->where('prix.idmateriel', $idmateriel);

        $this->db->order_by('idPrix ', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }
    public function getPrixByuniter($idunit)
    {
        $query = $this->db->select('*')
            ->from('prix')
            ->where('prix.idadmin', $_SESSION['idadmin'])
            ->where('materiel.idadmin', $_SESSION['idadmin'])
            ->where('prix.idUniter', $idunit)
            ->order_by('idPrix ', 'DESC')
            ->get();
        return $query->result();
    }


    public function getLastgroupe($idmateriel = '')
    {
        $this->db->select('groupe')
            ->from('prix')
        ;

        if ($idmateriel != '') {
            $this->db->where('idmateriel', $idmateriel);
        }
        $groups =  $this->db->order_by('idPrix', 'desc')->get()->result();
        if (count($groups) > 0) {
            return $groups[0]->groupe;
        }
        return '';
    }


    // *************************************************************** //

    // *************************************************************** //
}
