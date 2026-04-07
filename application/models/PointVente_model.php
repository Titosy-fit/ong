<?php

class PointVente_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // *  pagination 

    public function get_count()
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'desc')
            ->get()->result();
        return count($query);
    }

    public function selectDepotByProject($idProjet) {
        $this->db->where('idprojet', $idProjet);
        $query = $this->db->get('pointvente');
        return $query->result();
    }
    public function get_authors($limit, $start)
    {
        $id_user = $_SESSION['id_user'] ?? null;
        $query = $this->db->select('*')
            ->from('pointvente')
            ->join('projet', 'projet.idprojet = pointvente.idprojet')  
            ->limit($limit, $start)
            ->order_by('idPointVente', 'desc');
        if ($id_user) {
            $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
        }
        return $query->get()->result();
    }
    // *  pagination 


    public function ajoutPV($data)
    {
        // echo '<pre>'; var_dump( $data) ;  echo '</pre>' ; die ; 
        $this->db->insert('pointvente', $data);
    }


    public function recherchePV($keyword  = '', $limit  = '', $start = '')
    {
        // ici
        $data = $this->db->select('*')
            ->from('pointvente')
            ->join('projet', 'projet.idprojet = pointvente.idprojet')
            ->where('adressPv LIKE', $keyword)
            ->or_where('denomination_pv LIKE', $keyword)
            ->or_where('codeprojet LIKE', $keyword)
            ->or_where('titreprojet LIKE', $keyword)
            ->or_where('titreprojet LIKE', $keyword);
        // ->or_where('contactPv LIKE', $keyword);  
        if ($limit == '')
            return $data->get()->result();
        else
            return $data->limit($limit, $start)->get()->result();


        // var_dump('eee');
        // die;
    }

    public function editPv($data, $id)
    {
        $this->db->where('idPointVente', $id);
        $this->db->update('pointvente', $data);
        return $this->db->affected_rows() > 0;
    }

    public function deletePv($id)
    {

        // point de vente 
        $this->db->where('idPointVente', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('pointvente');

        // // appro de dispatch 
        $this->db->where('idPointVente', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('appro');


        // // depense de dispatch 
        $this->db->where('idPointVente', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('depense');

        // // depense de dispatch 
        $this->db->where('idPointVente', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('user');

        // // dispatch , demande   et panier 
        $this->db->select('idfacture')
            ->from('dispatch');
        $this->db->where('idPointVente', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $ventes = $this->db->get()->result();
        foreach ($ventes as $key => $dispatch) {
            $fact = $dispatch->idfacture;
            $this->db->where('idfacture', $fact);
            $this->db->where('idadmin', $_SESSION['idadmin']);
            $this->db->delete('panier');
        }

        $this->db->where('idPointVente', $id);
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->delete('dispatch');



        // Transfert 
        $this->db->where('idPointVente_source' , $id )
                ->or_where('idPointVente_destination' , $id )
                ->where('idadmin' , $_SESSION['idadmin'])
                ->delete('transfert') ; 


        // Retours 
        $this->db->where('idadmin', $_SESSION['idadmin'])
            ->where('idPointVente', $id)
            ->delete('remise');
    }


    public function getAllPv()
    {
        $data = $this->db->select('*')
            ->from('pointvente')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idPointVente', 'DESC')
            ->get();
        return $data->result();
    }

    public function verifAddress($address)
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->where('adressPv', $address)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }
    public function verifdenom($denom)
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->where('denomination_pv', $denom)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }

    public function verifContact($tel)
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->where('contactPv', $tel)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get();
        return $query->result();
    }

    public function verifyIfPvExiste($id)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM pointvente WHERE idPointVente <> '$id' AND idadmin = '$idadmin'")->result();
        return $data;
    }
    public function getPvByid($id)
    {
        $idadmin = $_SESSION['idadmin'];
        $data = $this->db->query("SELECT * FROM pointvente WHERE idPointVente = '$id' AND idadmin = '$idadmin '")->result();
        return $data;
    }
}
