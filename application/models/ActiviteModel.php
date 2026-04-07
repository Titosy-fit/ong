<?php
class ActiviteModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    // verification de doublant
    public function verify($titre = '', $idactivite = '')
    {
        $this->db->select('*')
            ->from('activite')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('titre_activite', $titre);
        if ($idactivite != '') {
            $this->db->where('idactivite <>', $idactivite);
        }
        return $this->db->get()->result();
    }

    // enregistrement
    public function register($data)
    {
        $this->db->insert('activite', $data);
        return $this->db->insert_id();
    }
    // enregistrement
    public function edit($data, $idactivite)
    {
        $this->db->where('idactivite', $idactivite);
        $this->db->update('activite', $data);
    }


    /**
     * Datas de l'activites qui est active en moment present 
     *
     * @return void
     */
    public function getActiviteActuel()
    {
        $date = date("Y-m-d H:i:s");

        


        $this->db->select('*')
            ->from('activite')
            ->where('activite.idadmin', $_SESSION['idadmin']);

            $this->db->where('activite.date_debut_act <=', $date);
            $this->db->where('activite.date_fin_act >=', $date);
            $this->db->order_by('idactivite', 'desc');

        return $this->db->get()->result();
    }

    // les données 
    public function getactivite($page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('activite')
            ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
            ->where('activite.idadmin', $_SESSION['idadmin'])
            ->order_by('idactivite', 'desc');
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    // donner par id 
    public function donner($idactivite)
    {
        return $this->db->select('*')
            ->from('activite')
            ->where('idactivite', $idactivite)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }
    public function activite_json()
    {
        return $this->db->select('*')
            ->from('activite')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idactivite', 'desc')
            ->get()->result();
    }
    // delete 
    public function delete($idactivite)
    {
        $this->db->where('idactivite', $idactivite)
            ->where('idadmin', $_SESSION['idadmin'])
            ->delete('activite');
    }

    // recherche 
    public function getactivite_search($mot  = '',  $date_debut_act  = '', $date_fin_act   = '', $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('activite')
            ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
            ->where('activite.idadmin', $_SESSION['idadmin']);

        if ($mot != '') {
            $this->db->like('activite.titre_activite', $mot);
            $this->db->or_like('projet.codeprojet', $mot);
            $this->db->or_like('projet.titreprojet', $mot);
        }

        if ($date_debut_act != '' && $date_fin_act == '') {
            $this->db->like('activite.date_debut_act', $date_debut_act);
        }
        if ($date_debut_act == '' && $date_fin_act != '') {
            $this->db->like('activite.date_fin_act', $date_fin_act);
        }
        if ($date_debut_act != '' && $date_fin_act != '') {
            $this->db->where('activite.date_debut_act <=', $date_debut_act);
            $this->db->where('activite.date_fin_act >=', $date_fin_act);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    /**
     * recherche par projet
     *
     * @param string $idprojet
     * @param string $page
     * @return void
     */
    public function searchProjet($idprojet = '' ,  $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('activite')
            ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
            ->where('activite.idadmin', $_SESSION['idadmin']);

        if ($idprojet != '') {
            $this->db->where('projet.idprojet', $idprojet );
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    public function getByidprojet( $idprojet = ''){
        return $this->db->select('*')
                        ->from('activite')
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->where('idprojet' , $idprojet )
                        ->get()->result() ; 
    }
}
