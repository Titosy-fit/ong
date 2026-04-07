<?php
class DepenseModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_count()
    {
        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('depense.idUser', $_SESSION['id_user']);
        }
        $data = $this->db->get()->result();
        return count($data);
    }
    public function getAlldep()
    {
        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('depense.idUser', $_SESSION['id_user']);
        }
        $data = $this->db->order_by('depense.iddepense ', 'desc')
            ->get()->result();
        return $data;
    }


    /**
     * Somme dans depense 
     *
     * @param [type] $datas
     * @return void
     */
    public function getsomme($datas)
    {

        $somme = 0;
        foreach ($datas as $key => $data) {
            if ($data->montant) {
                $somme += $data->montant;
            }
        }

        return $somme;
    }


    /**
     * prendre touts les donnée de depense 
     *
     * @param integer $page
     * @return void
     */
    public function get_authors($page = 1)
    {

        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }


        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->join('user', 'user.idUser = depense.idUser', 'left')
            ->join('( SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent  FROM user ) agent', 'agent.idagent = depense.idagent', 'left')
            ->join('commande', 'commande.idcommande = depense.idcommande', 'left')
            ->join('budget', 'budget.idbudget = depense.idbudget', 'left')
            // ->join('activite', 'activite.idactivite = budget.idactivite', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('depense.idUser', $_SESSION['id_user']);
        }
        $data = $this->db->order_by('depense.iddepense ', 'desc')
            ->limit(PAGINATION, $realOffset)
            ->get()->result();

        // echo '<pre>';
        // var_dump( $data ) ; 
        // echo '</pre>' ; die ; 
        return $data;
    }

    /**
     * enregistrement
     *
     * @param [type] $data
     * @return void
     */
    public function register($data)
    {
        $this->db->insert('depense', $data);
        return $this->db->insert_id();
    }

    /**
     * suppression
     *
     * @param [type] $id
     * @return void
     */
    public function deleteit($id)
    {
        $this->db->where('idadmin', $_SESSION['idadmin'])->where('iddepense', $id)->delete('depense');
    }


    /**
     * Modification
     *
     * @param [type] $id
     * @param [type] $data
     * @return void
     */
    public function edit($id, $data)
    {
        $this->db->where('iddepense', $id)->update('depense', $data);
    }


    /**
     * Recherche dans depense
     *
     * @param string $keyword
     * @param string $date
     * @param string $limit
     * @param string $offset
     * @return void
     */
    public function searchdepense($keyword = '', $date = "", $limit = "", $offset = "")
    {
        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin'])
            ->where('pointvente.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('depense.idUser', $_SESSION['id_user']);
        }

        if ($keyword != '') {
            $keyword = '%' . $keyword . '%';

            $this->db->where('pointvente.adressPv LIKE ', $keyword);
            $this->db->or_where('depense.raison LIKE ', $keyword);
        }

        if ($date != '') {
            $date = '%' . $date . '%';
            $this->db->where('depense.datedepense LIKE ', $date);
        }
        if ($limit != '')
            $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function getdataDep_search($keyword = '', $d1 = '', $d2 = '', $pv = 0, $page = '')
    {

        // LIMITE 
        if ((int)$page == 0) {
            $realOffset = (int)$page * PAGINATION;
        } else {
            $realOffset = ((int)$page - 1) * PAGINATION;
        }

        // Sortant

        $this->db->select('*')
            ->from('depense')
            ->join('pointvente', 'pointvente.idPointVente = depense.idPointVente', 'left')
            ->join('commande', 'commande.idcommande = depense.idcommande', 'left')
            ->join('user', 'user.idUser = depense.idUser', 'left')
            ->join('( SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent  FROM user ) agent', 'agent.idagent = depense.idagent', 'left')
            ->join('budget', 'budget.idbudget = depense.idbudget', 'left')
            // ->join('activite', 'activite.idactivite = budget.idactivite', 'left')
            ->where('depense.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('depense.idUser', $_SESSION['id_user']);
        }


        if ($keyword != '') {
            $keyword = '%' . $keyword . '%';

            $this->db->where('pointvente.adressPv LIKE ', $keyword);
            $this->db->or_where('depense.raison LIKE ', $keyword);
            /**user  */
            $this->db->or_where('user.nomUser LIKE ', $keyword);
            $this->db->or_where('user.prenomUser LIKE ', $keyword);
            /**agent  */
            $this->db->or_where('agent.nomagent LIKE ', $keyword);
            $this->db->or_where('agent.prenomagent LIKE ', $keyword);
            /**commande */
            $this->db->or_where('commande.cmfacture LIKE ', $keyword);
            /**budget */
            $this->db->or_where('budget.designation_budget LIKE ', $keyword);
            /**activite */
            $this->db->or_where('activite.titre_activite LIKE ', $keyword);
        }
        
        if ($d1 != '' && $d2 != '') {
            $this->db->where('depense.datedepense >= ', $d1);
            $this->db->where('depense.datedepense <=', $d2);
        } else if ($d1 != '' && $d2 == '') {
            $d1  = '%' . $d1 . '%';
            $this->db->where('depense.datedepense LIKE', $d1);
        } else if ($d1 == '' && $d2 != '') {
            $d2  = '%' . $d2 . '%';
            $this->db->where('depense.datedepense LIKE', $d2);
        }

        if ($pv != 0) {
            $this->db->where('depense.idPointVente', $pv);
        }

        if ( $page !=''){
            $this->db->limit(PAGINATION , $realOffset ) ;  
        }



        $depenses = $this->db->order_by('iddepense', 'desc')->where('depense.idadmin', $_SESSION['idadmin'])
            ->get()->result();

        return $depenses;
    }
}
