<?php
class PosteModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * verification
     *
     * @param string $titre
     * @param string $idposte
     * @return void
     */
    public function verify($designation = '', $idposte = '')
    {
        $this->db->select('*')
            ->from('poste')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('designation_poste', $designation);
        if ($idposte != '') {
            $this->db->where('idposte <>', $idposte);
        }
        return $this->db->get()->result();
    }

    // enregistrement
    public function register($data)
    {
        $this->db->insert('poste', $data);
        return $this->db->insert_id();
    }
    // enregistrement
    public function edit($data, $idposte)
    {
        $this->db->where('idposte', $idposte);
        $this->db->update('poste', $data);
    }


    /**
     * Datas de l'postes qui est active en moment present 
     *
     * @return void
     */
    public function getposteActuel()
    {
        $date = date("Y-m-d H:i:s");

        


        $this->db->select('*')
            ->from('poste')
            ->where('poste.idadmin', $_SESSION['idadmin']);

            $this->db->where('poste.date_debut_act <=', $date);
            $this->db->where('poste.date_fin_act >=', $date);
            $this->db->order_by('idposte', 'desc');

        return $this->db->get()->result();
    }

    // les données 
    public function getposte($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('poste')
            ->where('poste.idadmin', $_SESSION['idadmin'])
            ->order_by('idposte', 'desc');
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    // donner par id 
    public function donner($idposte)
    {
        return $this->db->select('*')
            ->from('poste')
            ->where('idposte', $idposte)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }
    public function getByid($idposte)
    {
        return $this->db->select('*')
            ->from('poste')
            ->where('idposte', $idposte)
            ->get()->result();
    }
    public function poste_json()
    {
        return $this->db->select('*')
            ->from('poste')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idposte', 'desc')
            ->get()->result();
    }
    // delete 
    public function delete($idposte)
    {
        $this->db->where('idposte', $idposte)
            ->where('idadmin', $_SESSION['idadmin'])
            ->delete('poste');
    }

    // recherche 
    public function getposte_search($mot  = '',  $date_debut_act  = '', $date_fin_act   = '', $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('poste')
            ->join('projet', 'projet.idprojet = poste.idprojet', 'left')
            ->where('poste.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            $this->db->like('poste.titre_poste', $mot);
            $this->db->or_like('projet.codeprojet', $mot);
            $this->db->or_like('projet.titreprojet', $mot);
        }

        if ($date_debut_act != '' && $date_fin_act == '') {
            $this->db->like('poste.date_debut_act', $date_debut_act);
        }
        if ($date_debut_act == '' && $date_fin_act != '') {
            $this->db->like('poste.date_fin_act', $date_fin_act);
        }
        if ($date_debut_act != '' && $date_fin_act != '') {
            $this->db->where('poste.date_debut_act <=', $date_debut_act);
            $this->db->where('poste.date_fin_act >=', $date_fin_act);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }
}
