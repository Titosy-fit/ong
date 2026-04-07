<?php
class MissionModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    // verification de doublant
    public function verify_om($om = '', $idmission = '')
    {
        $this->db->select('*')
            ->from('mission')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('numero_om', $om);
        if ($idmission != '') {
            $this->db->where('idmission <>', $idmission);
        }
        return $this->db->get()->result();
    }
    public function verify_asm($asm = '', $idmission = '')
    {
        $this->db->select('*')
            ->from('mission')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('numero_asm', $asm);
        if ($idmission != '') {
            $this->db->where('idmission <>', $idmission);
        }
        return $this->db->get()->result();
    }

    // enregistrement
    public function register($data)
    {
        $this->db->insert('mission', $data);
        return $this->db->insert_id();
    }
    public function insertAvanceMission($data)
    {
        $this->db->insert('avance_mission', $data);
        return $this->db->insert_id();
    }
    // enregistrement
    public function edit($data, $idmission)
    {
        $this->db->where('idmission', $idmission);
        $this->db->update('mission', $data);
    }


    /**
     * Datas de l'missions qui est active en moment present 
     *
     * @return void
     */
    public function getActiviteActuel()
    {
        $date = date("Y-m-d H:i:s");




        $this->db->select('*')
            ->from('mission')
            ->where('mission.idadmin', $_SESSION['idadmin']);

        $this->db->where('mission.date_debut_mission <=', $date);
        $this->db->where('mission.date_fin_mission >=', $date);
        $this->db->order_by('idmission', 'desc');

        return $this->db->get()->result();
    }

    /**
     * Prendre touts les missions 
     *
     * @param string $page
     * @return void
     */
    // public function getmission($page = '')
    // {
    //     if ((int)$page == 0) {
    //         $start = (int)$page * PAGINATION ;
    //     } else {
    //         $start = ((int)$page - 1) * PAGINATION ;
    //     }


    //     $this->db->select('*');
    //     $this->db->from('projet');

    //     $this->db->join('mission', 'mission.idprojet = projet.idprojet', 'inner');
    //     $this->db->join('(SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent , contact as telagent  FROM user ) agent', 'agent.idagent = mission.idagent', 'left') ;
    //     $this->db->join('(SELECT idUser  , nomUser  , prenomUser   FROM user ) user', 'user.idUser = mission.idUser', 'left') ;
    //     $this->db->where('mission.idadmin', $_SESSION['idadmin']) 
    //              ->order_by('idmission', 'desc'); 

    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }

    // public function getmission($page = '')
    // {
    //     $id_user = $_SESSION['id_user'] ?? null;  // récupère l'ID du user connecté

    //     // Pagination
    //     $page = (int)$page;
    //     $start = ($page <= 0) ? 0 : ($page - 1) * PAGINATION;

    //     $this->db->select('
    //     projet.*,
    //     mission.*,
    //     agent.nomagent,
    //     agent.prenomagent,
    //     agent.telagent,
    //     user.nomUser,
    //     user.prenomUser
    // ');

    //     $this->db->from('projet');
    //     $this->db->join('mission', 'mission.idprojet = projet.idprojet', 'inner');

    //     // Infos sur l'agent
    //     $this->db->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent, contact as telagent FROM user) agent', 'agent.idagent = mission.idagent', 'left');

    //     // Infos sur l'utilisateur qui a créé la mission
    //     $this->db->join('(SELECT idUser, nomUser, prenomUser FROM user) user', 'user.idUser = mission.idUser', 'left');

    //     // Filtre admin : toutes les missions de l'admin connecté
    //     $this->db->where('mission.idadmin', $_SESSION['idadmin']);

    //     // Filtre utilisateur standard : missions uniquement liées à son projet
    //     if ($id_user) {
    //         $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
    //     }

    //     $this->db->order_by('mission.idmission', 'desc');

    //     // Limite pagination
    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }

    public function getmission($page = 1)
{
    $per_page = PAGINATION ?? 10;  // sécurité si constante absente

    // Forcer un entier positif
    $page   = max(1, (int)$page);
    $offset = ($page - 1) * $per_page;

    $this->db->select('
        projet.*,
        mission.*,
        agent.nomagent,
        agent.prenomagent,
        agent.telagent,
        user.nomUser,
        user.prenomUser
    ');
    
    $this->db->from('projet');
    $this->db->join('mission', 'mission.idprojet = projet.idprojet', 'inner');
    
    // Infos agent
    $this->db->join(
        '(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent, contact as telagent FROM user) agent',
        'agent.idagent = mission.idagent',
        'left'
    );
    
    // Infos éditeur
    $this->db->join(
        '(SELECT idUser, nomUser, prenomUser FROM user) user',
        'user.idUser = mission.idUser',
        'left'
    );

    // Filtre admin
    $this->db->where('mission.idadmin', $_SESSION['idadmin'] ?? 0);

    // Filtre utilisateur normal (uniquement son projet)
    $id_user = $_SESSION['id_user'] ?? null;
    if ($id_user) {
        $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
    }

    $this->db->order_by('mission.idmission', 'DESC');

    // TOUJOURS appliquer la limite (sauf si tu veux tout pour un export par exemple)
    $this->db->limit($per_page, $offset);

    return $this->db->get()->result();
}

    // donner par id 
    public function donner($idmission)
    {
        return $this->db->select('*')
            ->from('mission')
            ->where('idmission', $idmission)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }
    public function getMissionByom($om = '')
    {
        return $this->db->select('*')
            ->from('mission')
            ->join('(SELECT idactivite , titre_activite FROM activite) activite ', 'mission.idactivite = activite.idactivite', 'left')
            ->join('(SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent  FROM user) agent ', 'mission.idagent = agent.idagent', 'left')
            ->where('mission.idadmin', $_SESSION['idadmin'])
            ->where('mission.numero_om', $om)
            ->get()->result();
    }
    // mise à jour de getMission 
    public function obtenirMission($om="") {
        $this->db->select('*,mission.montant_avance as total_avance');
        $this->db->from('mission');
        $this->db->join('avance_mission', 'mission.idmission = avance_mission.idmission');
        $this->db->join('budget', 'avance_mission.idbudget = budget.idbudget');
        $this->db->join('user', 'user.idUser = mission.idagent');
        $this->db->where('mission.numero_om', $om);
        $this->db->or_where('numero_asm', $om);

        $query = $this->db->get();

        $data  = $query->result() ;

        foreach ($data as $item) {
            $item->totalRetour = $this->get_total_retour($item->idmission); 
        }
        return $data; 

    }
    public function get_total_retour($idmission) {
        $this->db->select_sum('montant_return', 'total_retour');
        $this->db->where('idmission', $idmission);
        $query = $this->db->get('liquidation');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->total_retour ?? 0;
        } else {
            return 0;
        }
    }
    // delete 
    public function delete($idmission)
    {
        $this->db->where('idmission', $idmission)
            ->where('idadmin', $_SESSION['idadmin'])
            ->delete('mission');
        $this->db->where('idmission', $idmission)
                ->delete('avance_mission');
    }

    // recherche 
    public function getmission_search($mot  = '',  $date_debut_mission  = '', $date_fin_mission   = '', $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('mission')
            ->join('(SELECT idactivite , titre_activite , idprojet FROM activite ) activite', 'activite.idactivite = mission.idactivite', 'left')
            ->join('(SELECT idprojet , codeprojet FROM projet ) projet', 'projet.idprojet = activite.idprojet', 'left')
            ->join('(SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent , contact as telagent  FROM user ) agent', 'agent.idagent = mission.idagent', 'left')
            ->join('(SELECT idUser  , nomUser  , prenomUser   FROM user ) user', 'user.idUser = mission.idUser', 'left')
            ->where('mission.idadmin', $_SESSION['idadmin'])
            ->order_by('idmission', 'desc');

        if ($mot != '') {
            // mission
            $this->db->like('mission.objet_mission', $mot);
            $this->db->or_like('mission.lieu_mission', $mot);
            $this->db->or_like('mission.montant_avance', $mot);
            $this->db->or_like('mission.numero_asm', $mot);
            $this->db->or_like('mission.numero_om', $mot);
            // projet
            $this->db->or_like('projet.codeprojet', $mot);
            // acticite
            $this->db->or_like('activite.titre_activite', $mot);
            // agent 
            $this->db->or_like('agent.nomagent', $mot);
            $this->db->or_like('agent.prenomagent', $mot);
            $this->db->or_like('agent.telagent', $mot);
            // user
            $this->db->or_like('user.nomUser', $mot);
            $this->db->or_like('user.prenomUser', $mot);
        }

        if ($date_debut_mission != '' && $date_fin_mission == '') {
            $this->db->like('mission.date_debut_mission', $date_debut_mission);
        }
        if ($date_debut_mission == '' && $date_fin_mission != '') {
            $this->db->like('mission.date_fin_mission', $date_fin_mission);
        }
        if ($date_debut_mission != '' && $date_fin_mission != '') {
            $this->db->where('mission.date_debut_mission <=', $date_debut_mission);
            $this->db->where('mission.date_fin_mission >=', $date_fin_mission);
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
    public function searchProjet($idprojet = '',  $page = '')
    {
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('mission')
            ->join('(SELECT idactivite , titre_activite , idprojet FROM activite ) activite', 'activite.idactivite = mission.idactivite', 'left')
            ->join('(SELECT idprojet , codeprojet FROM projet ) projet', 'projet.idprojet = activite.idprojet', 'left')
            ->join('(SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent , contact as telagent  FROM user ) agent', 'agent.idagent = mission.idagent', 'left')
            ->join('(SELECT idUser  , nomUser  , prenomUser   FROM user ) user', 'user.idUser = mission.idUser', 'left')
            ->where('mission.idadmin', $_SESSION['idadmin'])
            ->order_by('idmission', 'desc');

        if ($idprojet != '') {
            $this->db->where('projet.idprojet', $idprojet);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    /**
     * recherche par liquidation
     *
     * @param string $idprojet
     * @param string $page
     * @return void
     */
    // public function filtreliquidation($idprojet = '',  $page = '')
    // {
    //     if ((int)$page == 0) {
    //         $start = (int)$page * PAGINATION;
    //     } else {
    //         $start = ((int)$page - 1) * PAGINATION;
    //     }

    //     $this->db->select('*')
    //         ->from('mission')
    //         ->join('(SELECT idactivite , titre_activite , idprojet FROM activite ) activite', 'activite.idactivite = mission.idactivite', 'left')
    //         ->join('(SELECT idprojet , codeprojet FROM projet ) projet', 'projet.idprojet = activite.idprojet', 'left')
    //         ->join('(SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent , contact as telagent FROM user ) agent', 'agent.idagent = mission.idagent', 'left')
    //         ->join('(SELECT idUser, nomUser, prenomUser FROM user ) user', 'user.idUser = mission.idUser', 'left')
    //         ->where('mission.idadmin', $_SESSION['idadmin'])
    //         ->where('mission.isliquider', '0')
    //         ->where('DATE_ADD(mission.date_fin_mission, INTERVAL 48 HOUR) < NOW()')  // Filtre sur les 48 heures après la date de fin de mission
    //         ->order_by('idmission', 'desc');

    //     if ($idprojet != '') {
    //         $this->db->where('projet.idprojet', $idprojet);
    //     }

    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }

    public function filtreliquidation($idprojet = '',  $page = '')
    {
        $id_user = $_SESSION['id_user'] ?? null;
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('mission')
            ->join('(SELECT idactivite , titre_activite , idprojet FROM activite ) activite', 'activite.idactivite = mission.idactivite', 'left')
            ->join('(SELECT idprojet , codeprojet FROM projet ) projet', 'projet.idprojet = activite.idprojet', 'left')
            ->join('(SELECT idUser as idagent , nomUser as nomagent , prenomUser as prenomagent , contact as telagent FROM user ) agent', 'agent.idagent = mission.idagent', 'left')
            ->join('(SELECT idUser, nomUser, prenomUser FROM user ) user', 'user.idUser = mission.idUser', 'left')
            ->where('mission.idadmin', $_SESSION['idadmin'])
            ->where('mission.isliquider', '0')
            ->where('DATE_ADD(mission.date_fin_mission, INTERVAL 48 HOUR) < NOW()')  // Filtre sur les 48 heures après la date de fin de mission
            ->order_by('idmission', 'desc');

        if ($id_user) {
            $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
        }

        if ($idprojet != '') {
            $this->db->where('projet.idprojet', $idprojet);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    /**
     * recherche par reliquat
     *
     * @param string $idprojet
     * @param string $page
     * @return void
     */
    //     public function filtreReliquat($idprojet = '',  $page = '')
    //     {
    //         if ((int)$page == 0) {
    //             $start = (int)$page * PAGINATION;
    //         } else {
    //             $start = ((int)$page - 1) * PAGINATION;
    //         }
    //         // $this->db->select('*')
    //         //     ->from('mission')
    //         //     ->join('(SELECT idactivite, titre_activite, idprojet FROM activite) activite', 'activite.idactivite = mission.idactivite', 'left')
    //         //     ->join('(SELECT idprojet, codeprojet FROM projet) projet', 'projet.idprojet = activite.idprojet', 'left')
    //         //     ->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent, contact as telagent FROM user) agent', 'agent.idagent = mission.idagent', 'left')
    //         //     ->join('(SELECT idUser, nomUser, prenomUser FROM user) user', 'user.idUser = mission.idUser', 'left')
    //         //     ->join('(SELECT idliquidation, idmission, montant_reliquat, montant_return FROM liquidation) liquidation', 'liquidation.idmission = mission.idmission', 'left')
    //         //     ->where('mission.idadmin', $_SESSION['idadmin'])
    //         //     ->where('mission.isliquider', '1')
    //         //     // Condition : montant_return < montant_reliquat
    //         //     ->where('liquidation.montant_return < liquidation.montant_reliquat')
    //         //     ->order_by('mission.idmission', 'desc');

    //         $this->db->select('
    //     mission.*,
    //     projet.codeprojet,
    //     agent.nomUser AS nomagent,
    //     agent.prenomUser AS prenomagent,
    //     agent.contact AS telagent,
    //     user.nomUser,
    //     user.prenomUser
    // ')
    // ->from('mission')
    // ->join('projet', 'projet.idprojet = mission.idprojet', 'left')
    // ->join('user agent', 'agent.idUser = mission.idagent', 'left')
    // ->join('user user', 'user.idUser = mission.idUser', 'left')
    // ->join('liquidation', 'liquidation.idmission = mission.idmission', 'left')
    // ->where('mission.idadmin', $_SESSION['idadmin'])
    // ->where('mission.isliquider', '1')
    // ->where('liquidation.montant_return < liquidation.montant_reliquat')
    // ->order_by('mission.idmission', 'desc');

    //         if ($idprojet != '') {
    //             $this->db->where('projet.idprojet', $idprojet);
    //         }

    //         if ($page != '') {
    //             $this->db->limit(PAGINATION, $start);
    //         }

    //         return $this->db->get()->result();
    //     }

    public function filtreReliquat($idprojet = '',  $page = '')
    {
        $id_user = $_SESSION['id_user'] ?? null;
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }
        // $this->db->select('*')
        //     ->from('mission')
        //     ->join('(SELECT idactivite, titre_activite, idprojet FROM activite) activite', 'activite.idactivite = mission.idactivite', 'left')
        //     ->join('(SELECT idprojet, codeprojet FROM projet) projet', 'projet.idprojet = activite.idprojet', 'left')
        //     ->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent, contact as telagent FROM user) agent', 'agent.idagent = mission.idagent', 'left')
        //     ->join('(SELECT idUser, nomUser, prenomUser FROM user) user', 'user.idUser = mission.idUser', 'left')
        //     ->join('(SELECT idliquidation, idmission, montant_reliquat, montant_return FROM liquidation) liquidation', 'liquidation.idmission = mission.idmission', 'left')
        //     ->where('mission.idadmin', $_SESSION['idadmin'])
        //     ->where('mission.isliquider', '1')
        //     // Condition : montant_return < montant_reliquat
        //     ->where('liquidation.montant_return < liquidation.montant_reliquat')
        //     ->order_by('mission.idmission', 'desc');

        $this->db->select('
    mission.*,
    projet.codeprojet,
    agent.nomUser AS nomagent,
    agent.prenomUser AS prenomagent,
    agent.contact AS telagent,
    user.nomUser,
    user.prenomUser
')
            ->from('mission')
            ->join('projet', 'projet.idprojet = mission.idprojet', 'left')
            ->join('user agent', 'agent.idUser = mission.idagent', 'left')
            ->join('user user', 'user.idUser = mission.idUser', 'left')
            ->join('liquidation', 'liquidation.idmission = mission.idmission', 'left')
            ->where('mission.idadmin', $_SESSION['idadmin'])
            ->where('mission.isliquider', '1')
            ->where('liquidation.montant_return < liquidation.montant_reliquat')
            ->order_by('mission.idmission', 'desc');

        if ($id_user) {
            $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
        }

        if ($idprojet != '') {
            $this->db->where('projet.idprojet', $idprojet);
        }

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    public function getByidprojet($idprojet = '')
    {
        return $this->db->select('*')
            ->from('mission')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('idprojet', $idprojet)
            ->get()->result();
    }
    public function details($idmission=0)
    {
        return $this->db
                    ->select('budget.*, avance_mission.montant_avance as avTemp,avance_mission.*, mission.*')   // * sur les 3 tables
                    ->from('budget')
                    ->join('avance_mission', 'avance_mission.idbudget = budget.idbudget', 'inner')
                    ->join('mission',        'mission.idmission = avance_mission.idmission', 'inner')
                    ->get()
                    ->result();  
    }
    

    /**
     * Undocumented function
     *
     * @param array $idbudgets
     * @return void
     */
    public function getBudgetTb($idbudgets = [])
    {
        $budgets = [];
        for ($i = 0; $i < count($idbudgets); $i++) {
            $temps = $this->db->select('*')
                ->from('budget')
                ->where('idadmin', $_SESSION['idadmin'])
                ->where('idbudget', $idbudgets[$i])
                ->get()->result();
            if (count($temps)) {
                $budgets[] = $temps[0];
            }
        }

        return $budgets;
    }

    public function setidliquide($val = false, $idmission = '')
    {
        $this->db->where('idmission', $idmission)
            ->where('idadmin', $_SESSION['idadmin'])
            ->update('mission', [
                'isliquider' => $val
            ]);
    }
    public function count_missions()
{
    $this->db->from('mission');
$this->db->where('mission.idadmin', $_SESSION['idadmin'] ?? 0);  // ← AJOUTE mission.
    $id_user = $_SESSION['id_user'] ?? null;
    if ($id_user) {
        $this->db->join('projet', 'projet.idprojet = mission.idprojet');
        $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$id_user . ')', null, false);
    }

    return $this->db->count_all_results();
}
}
