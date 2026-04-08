<?php

class UserModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_count()
    {
        $query = $this->db->select('user.idUser')
            ->from('user')
            ->where('user.idadmin', $_SESSION['idadmin'])
            ->get()->result();

        return count($query);
    }

    public function getAllPv()
    {
        $query = $this->db->select('*')
            ->from('pointvente')
            ->order_by('idPointVente')
            ->get();
        return $query->result();
    }

    // public function get_authors($limit, $start)
    // {
    //     $query = $this->db->select('*')
    //         ->from('user')
    //         ->join('pointvente', 'pointvente.idPointVente = user.idPointVente', 'inner')
    //         ->join('poste', 'poste.idposte = user.idposte', 'inner')
    //         ->order_by('user.idUser', 'desc')
    //         ->where('user.idadmin', $_SESSION['idadmin'])
    //         ->where('pointvente.idadmin', $_SESSION['idadmin'])
    //         ->limit($limit, $start)
    //         ->get();

    //     return $query->result();
    // }
    // public function get_authors($limit, $start)
    // {
    //     $this->db->select('user.*, 
    //                   projet.codeprojet, 
    //                   projet.titreprojet, 
    //                   poste.designation_poste')
    //         ->from('user')
    //         ->join('projet', 'projet.idprojet = user.idprojet', 'left')
    //         ->join('poste', 'poste.idposte = user.idposte', 'inner')
    //         ->where('user.idadmin', $_SESSION['idadmin'])
    //         ->order_by('user.idUser', 'desc')
    //         ->limit($limit, $start);

    //     return $this->db->get()->result();
    // }
    public function get_authors($limit, $start)
{
    $this->db->select('user.*, 
                      projet.codeprojet, 
                      projet.titreprojet, 
                      poste.designation_poste')
        ->from('user')
        ->join('projet', 'projet.idprojet = user.idprojet', 'left')
        ->join('poste', 'poste.idposte = user.idposte', 'inner')
        ->where('user.idadmin', $_SESSION['idadmin'])
        ->order_by('user.idUser', 'desc')
        ->limit($limit, $start);

    return $this->db->get()->result();
}
    public function insertUser($data)
    {
        $this->db->insert('user', $data);
    }

    public function getAllUser()
    {
        $query = $this->db->select('*')
            ->from('user')
            ->order_by('idUser', 'DESC')
            ->get();

        return $query->result();
    }

    public function verifMail($mail)
    {
        $query = $this->db->select('*')
            ->from('user')
            ->where('mail', $mail)
            ->get()->result();

        if (count($query) == 0) {
            $query = $this->db->select('*')
                ->from('admin')
                ->where('mail', $mail)
                ->get()->result();
        }

        return $query;
    }

    public function verifNum($num)
    {
        $query = $this->db->select('*')
            ->from('user')
            ->where('contact', $num)
            ->get();
        return $query->result();
    }

    public function deleteUser($id)
    {
        // user 
        $this->db->where('idUser', $id);
        $this->db->delete('user');

        // dispatch panier  
        $ventes = $this->db->select('Facture')->from('dispatch')->where('idadmin', $_SESSION['idadmin'])->where('idUser', $id)
            ->get()->result();
        foreach ($ventes as $key => $value) {
            $fact = $value->Facture;
            $this->db->where('idadmin', $_SESSION['idadmin']);
            $this->db->where('Facture', $fact);
            $this->db->delete('panier');
        }
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->where('idUser', $id);
        $this->db->delete('dispatch');

        // Depense 
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->where('idUser', $id);
        $this->db->delete('depense');

        // client  
        $this->db->where('idadmin', $_SESSION['idadmin']);
        $this->db->where('idUser', $id);
        $this->db->delete('client');
    }

    public function updateUser($id, $data)
    {
        $this->db->where('idUser', $id);
        $this->db->update('user', $data);
        return $this->db->affected_rows() >  0;
    }

    public function getAllUserById($id)
    {
        $query = $this->db->select('user.*, poste.designation_poste, projet.codeprojet, projet.titreprojet')
            ->from('user')
            ->join('poste', 'poste.idposte = user.idposte', 'left')
            ->join('projet', 'projet.idprojet = user.idprojet', 'left')
            ->where('user.idUser', $id)
            ->get();
        return $query->result();
    }

    public function verifyIfUserExiste($id)
    {
        $data = $this->db->query("SELECT * FROM user WHERE idUser <> '$id'")->result();
        return $data;
    }

    public function searchUser($mot = '', $limit = '', $start = '', $type = '')
    {

        $this->db->select('*');
        $this->db->from('user');
        // $this->db->join('pointvente', 'pointvente.idPointVente = user.idPointVente')
        $this->db->join('pointvente', '1 = 1')
            ->join('poste', 'poste.idposte = user.idposte', 'inner');
        $this->db->where('user.idadmin', $_SESSION['idadmin']);
        $this->db->where('pointvente.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            $this->db->where('user.nomUser like', $mot);
            $this->db->or_where('user.prenomUser like', $mot);
            $this->db->or_where('user.contact like', $mot);
            $this->db->or_where('user.adress like', $mot);
            $this->db->or_where('poste.designation_poste like', $mot);
            $this->db->or_where('user.mail like', $mot);
            $this->db->or_where('pointvente.adressPv like', $mot);
        }

        // if ( $type !=''){
        //     $this->db->where('user.typeUser', $type );
        // }

        if ($limit != '') {
            $this->db->limit($limit, $start);
        }
        $this->db->order_by('user.idUser', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function connexion($mail)
    {
        $query = $this->db->select('*')
            ->from('user')
            ->where('mail', $mail)
            ->get();
        return $query->result();
    }
public function verifCin($cin)
{
    $query = $this->db->select('*')
        ->from('user')
        ->where('numero_cin', $cin)
        ->get();
    return $query->result();
}
    public function mdpUser($id, $data)
    {
        $this->db->where('idUser', $id);
        $this->db->update('user', $data);
        return $this->db->affected_rows() >  0;
    }
}
