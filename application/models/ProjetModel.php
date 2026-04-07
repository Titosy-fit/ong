<?php
class ProjetModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    // verification de doublant
    public function projetVerify($code = '', $idprojet = '')
    {
        if ($code == '') {
            return [];
        }
        $this->db->select('*')
            ->from('projet')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('codeprojet', $code);
        if ($idprojet != '') {
            $this->db->where('idprojet <>', $idprojet);
        }
        return $this->db->get()->result();
    }
    public function insert_csv($data) {
        return $this->db->insert_batch('projet', $data);
    }

    // enregistrement
    public function register($data)
    {
        $this->db->insert('projet', $data);
        return $this->db->insert_id();
    }
    // enregistrement
    public function edit($data, $idprojet)
    {
        $this->db->where('idprojet', $idprojet);
        $this->db->update('projet', $data);
    }

    // les données 
    // public function getpojet($page = '')
    // {
    //     if ((int)$page == 0) {
    //         $start = (int)$page * PAGINATION;
    //     } else {
    //         $start = ((int)$page - 1) * PAGINATION;
    //     }

    //     $this->db->select('*')
    //         ->from('projet')
    //         ->where('idadmin', $_SESSION['idadmin'])
    //         ->order_by('idprojet', 'desc');
    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }
public function getpojet($page = '')
{
    $this->db->select('projet.*');
    $this->db->from('projet');
    $this->db->where('projet.idadmin', $_SESSION['idadmin']);

    // Restriction par rôle et projet assigné
    if ($_SESSION['user_type'] === 'user') {
        $idUser = $_SESSION['id_user'];

        // Récupérer le projet assigné à cet utilisateur
        $this->db->join('user', 'user.idprojet = projet.idprojet', 'left');
        $this->db->where('user.idUser', $idUser);
        $this->db->where('user.idprojet IS NOT NULL'); // → il a un projet → voir SEULEMENT ce projet

        // OU si tu veux aussi permettre à certains users de voir TOUS les projets (ex: admin-like)
        // → tu peux ajouter une condition : if (strpos($roles, 'admin_projet') === false) { ... filtre ... }
    }

    $this->db->order_by('projet.idprojet', 'desc');

    if ($page != '') {
        $start = ($page == 0) ? 0 : (($page - 1) * PAGINATION);
        $this->db->limit(PAGINATION, $start);
    }

    return $this->db->get()->result();
}
    // donner par id 
    public function donner($idprojet)
    {
        return $this->db->select('*')
            ->from('projet')
            ->where('idprojet', $idprojet)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }
    public function donnerByCode($codeprojet)
    {
        return $this->db->select('*')
            ->from('projet')
            ->where('codeprojet', $codeprojet)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }
    public function projet_json()
    {
        return $this->db->select('*')
            ->from('projet')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idprojet', 'desc')
            ->get()->result();
    }
    /**
     * suppression
     *
     * @param [type] $idprojet
     * @return void
     */
    public function delete($idprojet)
    {
        // activite 
        $activites = $this->db->select('*')
                        ->from('activite')
                        ->where('idadmin' , $_SESSION['idadmin'])
                        ->where('idprojet' , $idprojet )
                        ->get()->result() ; 
        $this->db->where('idadmin' , $_SESSION['idadmin'])
                    ->where('idprojet' , $idprojet )
                    ->delete('activite') ; 
        
        // budjet lier au activite et depense 
        foreach ($activites  as $key => $activite) {
            $budgets = $this->db->select('*')
                            ->from('budget')
                            ->where('idadmin') ;
        }
        
        // projet 
        $this->db->where('idprojet', $idprojet)
                ->where('idadmin', $_SESSION['idadmin'])
                ->delete('projet');
        $this->db->where('idprojet', $idprojet)
                ->where('idadmin', $_SESSION['idadmin'])
                ->delete('budget');
        $this->db->where('mission', $idprojet)
                ->where('idadmin', $_SESSION['idadmin'])
                ->delete('budget') ;
    }

    // recherche 
    // public function getpojet_search($mot  = '',  $date_debut  = '', $date_fin   = '', $page = '')
    // {
    //     if ((int)$page == 0) {
    //         $start = (int)$page * PAGINATION;
    //     } else {
    //         $start = ((int)$page - 1) * PAGINATION;
    //     }


    //     $this->db->select('*')
    //         ->from('projet')
    //         ->where('idadmin', $_SESSION['idadmin']);

    //     if ($mot != '') {
    //         $this->db->like('codeprojet', $mot);
    //         $this->db->or_like('titreprojet', $mot);
    //     }

    //     if ($date_debut != '' && $date_fin == '') {
    //         $this->db->like('date_debut', $date_debut);
    //     }
    //     if ($date_debut == '' && $date_fin != '') {
    //         $this->db->like('date_fin', $date_fin);
    //     }
    //     if ($date_debut != '' && $date_fin != '') {
    //         $this->db->where('date_debut >=', $date_debut);
    //         $this->db->where('date_fin <=', $date_fin);
    //     }

    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }
public function getpojet_search($mot = '', $date_debut = '', $date_fin = '', $page = '')
{
    $this->db->select('projet.*');
    $this->db->from('projet');
    $this->db->where('projet.idadmin', $_SESSION['idadmin']);

    // Même restriction que ci-dessus
    if ($_SESSION['user_type'] === 'user') {
        $idUser = $_SESSION['id_user'];
        $this->db->join('user', 'user.idprojet = projet.idprojet', 'left');
        $this->db->where('user.idUser', $idUser);
        $this->db->where('user.idprojet IS NOT NULL');
    }

    if ($mot != '') {
        $this->db->group_start();
        $this->db->like('codeprojet', $mot);
        $this->db->or_like('titreprojet', $mot);
        $this->db->group_end();
    }

    if ($date_debut != '') $this->db->where('date_debut >=', $date_debut);
    if ($date_fin != '')   $this->db->where('date_fin <=', $date_fin);

    if ($page != '') {
        $start = ($page == 0) ? 0 : (($page - 1) * PAGINATION);
        $this->db->limit(PAGINATION, $start);
    }

    return $this->db->get()->result();
}

    /**
     * prendre le projet par code 
     *
     * @param string $codeprojet
     * @return void
     */
    public function getbycode($codeprojet = '')
    {
        return $this->db->select('*')
            ->from('projet')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('codeprojet', $codeprojet)
            ->get()->result();
    }

    public function getAllForSelect()
{
    $this->db->select('idprojet, codeprojet, titreprojet');
    $this->db->from('projet');
    $this->db->where('idadmin', $_SESSION['idadmin']);
    $this->db->order_by('codeprojet', 'ASC');
    $query = $this->db->get();
    return $query->result();
}
}
