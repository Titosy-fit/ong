<?php
class LiquidationModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }




    /**
     * prendre touts les donnée de liquidation 
     *
     * @param integer $page
     * @return void
     */
    // public function get_authors($page = '')
    // {

    //     // LIMITE 
    //     if ((int)$page == 0) {
    //         $realOffset = (int)$page * PAGINATION;
    //     } else {
    //         $realOffset = ((int)$page - 1) * PAGINATION;
    //     }

    //     $this->db->distinct();
    //    $this->db->select(' 
    //          projet.codeprojet, mission.montant_avance AS avance,
    //         mission.numero_om,
    //         mission.numero_asm,
    //         mission.objet_mission,
    //         liquidation.date_liquidation,
    //         liquidation.date_reliquat ,
    //         liquidation.montant_depense,
    //         liquidation.montant_reliquat,
    //         liquidation.montant_return,
    //         agent.nomUser,  agent.prenomUser ,
    //         editeur.nomUser as nomEdit,editeur.prenomUser AS prenomEdit,
    //         idliquidation, mission.typeMission
    //     ');
    //     $this->db->from('liquidation');
    //     $this->db->join('mission', 'mission.idmission = liquidation.idmission', 'left');
    //     $this->db->join('avance_mission', 'avance_mission.idmission = mission.idmission', 'left');
        
    //     $this->db->join('budget', 'budget.idbudget = avance_mission.idbudget', 'left');

    //     $this->db->join('projet', 'projet.idprojet = budget.idprojet', 'left');
    //     $this->db->join('user AS agent', 'agent.idUser = liquidation.idagent', 'left');
    //     $this->db->join('user AS editeur', 'editeur.idUser = liquidation.idUser', 'left');
    //     $this->db->where('liquidation.idadmin', $_SESSION['idadmin']);
          
    //     if (isset($_SESSION['pv'])) {
    //         $this->db->where('liquidation.idUser', $_SESSION['id_user']);
    //     }
    //     $this->db->order_by('liquidation.idliquidation ', 'desc');
    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $realOffset);
    //     }
    //     $data = $this->db->get()->result();
    //     return $data;
    // }
public function get_authors($page = 1)
{
    $per_page = PAGINATION ?? 10;

    // Forcer page ≥ 1 et calcul offset correct
    $page   = max(1, (int)$page);
    $offset = ($page - 1) * $per_page;

    $this->db->distinct();
    $this->db->select('
        projet.codeprojet, 
        mission.montant_avance AS avance,
        mission.numero_om,
        mission.numero_asm,
        mission.objet_mission,
        liquidation.date_liquidation,
        liquidation.date_reliquat,
        liquidation.montant_depense,
        liquidation.montant_reliquat,
        liquidation.montant_return,
        agent.nomUser, 
        agent.prenomUser,
        editeur.nomUser AS nomEdit,
        editeur.prenomUser AS prenomEdit,
        liquidation.idliquidation,
        mission.typeMission
    ');

    $this->db->from('liquidation');
    $this->db->join('mission', 'mission.idmission = liquidation.idmission', 'left');
    $this->db->join('avance_mission', 'avance_mission.idmission = mission.idmission', 'left');
    $this->db->join('budget', 'budget.idbudget = avance_mission.idbudget', 'left');
    $this->db->join('projet', 'projet.idprojet = budget.idprojet', 'left');
    $this->db->join('user AS agent', 'agent.idUser = liquidation.idagent', 'left');
    $this->db->join('user AS editeur', 'editeur.idUser = liquidation.idUser', 'left');

    // Filtre admin → voit tout
    if (!empty($_SESSION['idadmin'])) {
        $this->db->where('liquidation.idadmin', $_SESSION['idadmin']);
    }
    // Filtre utilisateur normal → voit son projet (comme dans Budget)
    // else if (!empty($_SESSION['id_user'])) {
        
    // }
    // }
    // Sécurité : rien si ni admin ni user
    else {
        $this->db->where('1=0');
    }

    $id_user = $_SESSION['id_user'] ?? null;
        // $this->db->join('user u', 'u.idprojet = projet.idprojet', 'inner');
        // $this->db->where('u.idUser', (int)$_SESSION['id_user']);
        if ($id_user) {
        $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
        }   

    $this->db->order_by('liquidation.idliquidation', 'DESC');

    // TOUJOURS limiter (pas de condition if)
    $this->db->limit($per_page, $offset);

    return $this->db->get()->result();
}
public function count_authors()
{
    $this->db->from('liquidation');
    $this->db->join('mission', 'mission.idmission = liquidation.idmission', 'left');
    $this->db->join('avance_mission', 'avance_mission.idmission = mission.idmission', 'left');
    $this->db->join('budget', 'budget.idbudget = avance_mission.idbudget', 'left');
    $this->db->join('projet', 'projet.idprojet = budget.idprojet', 'left');

    if (!empty($_SESSION['idadmin'])) {
        $this->db->where('liquidation.idadmin', $_SESSION['idadmin']);
    }
    else if (!empty($_SESSION['id_user'])) {
        $this->db->join('user u', 'u.idprojet = projet.idprojet', 'inner');
        $this->db->where('u.idUser', (int)$_SESSION['id_user']);
    }
    else {
        $this->db->where('1=0');
    }

    return $this->db->count_all_results();
}
    /**
     * enregistrement
     *
     * @param [type] $data
     * @return void
     */
    public function register($data)
    {
        $this->db->insert('liquidation', $data);
        return $this->db->insert_id();
    }

    /**
     * enregistrement multiple
     *
     * @param array $data
     * @return void
     */
    public function insert_liquidation_depense_batch($data = [])
    {
        $this->db->insert_batch('depense_liquidation',  $data);
    }

    /**
     * suppression
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id = 0)
    {
        /*$idmissions = $this->db->select('idmission')
                                ->from('liquidation')
                                ->where('idliquidation' , $id )
                                ->get()->result() ; 
        $this->db->where('idadmin', $_SESSION['idadmin'])
                ->where('idliquidation', $id)
                ->delete('liquidation');
        if ( count( $idmissions )){
            $this->db->where('idmission' , $idmissions[0]->idmission)->update('mission' , [
                'isliquider' => 0  , 
            ]) ; 
        }*/
        $this->db->where('idliquidation', $id );
        $this->db->delete('liquidation');
        $this->db->where('idliquidation', $id );
        $this->db->delete('depense_liquidation');

    }


    /**
     * Recherche dans liquidation
     *
     * @param string $keyword
     * @param string $date
     * @param string $limit
     * @param string $offset
     * @return void
     */
    public function getdataDep_search($keyword = '', $d1 = '', $d2 = '',  $page = '')
    {
        $this->db->select('*')
            ->from('liquidation')
            ->join('(SELECT objet_mission , idmission , numero_om FROM mission ) mission', 'mission.idmission = liquidation.idmission', 'left')
            ->join('(SELECT nomUser as nomagent  , prenomUser as prenomagent , idUser as idagent , contact as telagent  FROM user  ) agent ', 'agent.idagent  = liquidation.idagent ', 'left')
            ->join('(SELECT nomUser   , prenomUser  , idUser   FROM user  ) user ', 'user.iduser  = liquidation.iduser ', 'left')
            ->where('liquidation.idadmin', $_SESSION['idadmin']);
        if (isset($_SESSION['pv'])) {
            $this->db->where('liquidation.idUser', $_SESSION['id_user']);
        }

        if ($keyword != '') {
            // mission
            $this->db->like('mission.objet_mission', $keyword);
            $this->db->or_like('mission.numero_om', $keyword);
            // agent
            $this->db->or_like('agent.nomagent', $keyword);
            $this->db->or_like('agent.prenomagent', $keyword);
            // user 
            $this->db->or_like('user.prenomUser', $keyword);
            $this->db->or_like('user.nomUser', $keyword);
        }

        if ($d1 != '' && $d2 != '') {
            $this->db->where('liquidation.date_liquidation >= ', $d1);
            $this->db->where('liquidation.date_liquidation <=', $d2);
        } else if ($d1 != '' && $d2 == '') {
            $d1  = '%' . $d1 . '%';
            $this->db->where('liquidation.date_liquidation LIKE', $d1);
        } else if ($d1 == '' && $d2 != '') {
            $d2  = '%' . $d2 . '%';
            $this->db->where('liquidation.date_liquidation LIKE', $d2);
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
    public function searchProjet($idprojet = '',  $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('liquidation')
            ->join('(SELECT objet_mission , idmission , idactivite  ,  numero_om FROM mission ) mission', 'mission.idmission = liquidation.idmission', 'left')
            ->join('(SELECT idprojet , idactivite  FROM activite ) activite', 'activite.idactivite = mission.idactivite', 'left')
            ->join('(SELECT idprojet , codeprojet  FROM projet ) projet', 'projet.idprojet = activite.idprojet', 'left')
            ->join('(SELECT nomUser as nomagent  , prenomUser as prenomagent , idUser as idagent , contact as telagent  FROM user  ) agent ', 'agent.idagent  = liquidation.idagent ', 'left')
            ->join('(SELECT nomUser   , prenomUser  , idUser   FROM user  ) user ', 'user.iduser  = liquidation.iduser ', 'left')
            ->where('liquidation.idadmin', $_SESSION['idadmin']);

        if ($idprojet != '') {
            $this->db->where('projet.idprojet', $idprojet);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    public function details($idliquidation = '')
    {
        $this->db->select('*,avance_mission.montant_avance AS avance');
        $this->db->from('depense_liquidation');
        $this->db->join('budget', 'budget.idbudget = depense_liquidation.idbudget');
        $this->db->join('avance_mission', 'avance_mission.idbudget = budget.idbudget');
        $this->db->where('depense_liquidation.idliquidation', $idliquidation);

        $query = $this->db->get();
        return $query->result();
        
        
       

       
    }
    public function verifyLiquidation($idmission)
    {
            $this->db->where('idmission', $idmission);
            $query = $this->db->get('liquidation');
            return $query->num_rows(); // retourne le nombre de lignes trouvées
    }
    public function getInfoLiquidation($idmission)
    {
            $this->db->where('idmission', $idmission);
            $query = $this->db->get('liquidation');
            $row = $query->row(); 
            return $row ;
    }
   public function getTotalMontantReturn($idLiquidation)
    {
        $this->db->select_sum('montantReturn');
        $this->db->from('relique');
        $this->db->where('idLiquidation', $idLiquidation);

        $query = $this->db->get();
        $row = $query->row();

        return $row->montantReturn ?? 0;
    }

     public function getMontantReturnByLiquidation2($idLiquidation)
{
    $this->db->select('montant_return');
    $this->db->from('liquidation');
    $this->db->where('idLiquidation', $idLiquidation);
    $this->db->limit(1);

    $query = $this->db->get();
    $row = $query->row();

    return $row->montant_return ?? 0;
}

}
