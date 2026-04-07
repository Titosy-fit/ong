<?php


class reliquatModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Vérification de doublon
    public function verify($titre = '', $idreliquat = '')
    {
        $this->db->select('*')
            ->from('relique')
            ->where('idadmin', $_SESSION['idadmin'])
            ->where('titre_reliquat', $titre);
        if ($idreliquat != '') {
            $this->db->where('idreliquat <>', $idreliquat);
        }
        return $this->db->get()->result();
    }

    /**
     * Enregistrement
     */
    public function register($data)
    {
        $this->db->insert('relique', $data);
        return $this->db->insert_id();
    }

    // Modification
    public function edit($data, $idreliquat)
    {
        $this->db->where('idreliquat', $idreliquat);
        $this->db->update('relique', $data);
    }

    // Liste des reliquats (avec pagination)
    // public function getreliquat($page = '')
    // {
    //     if ((int)$page == 0) {
    //         $start = (int)$page * PAGINATION;
    //     } else {
    //         $start = ((int)$page - 1) * PAGINATION;
    //     }

    //     $this->db->select('*')
    //         ->from('relique')
    //         ->join('budget', 'budget.idbudget = relique.idbudget', 'left')
    //         ->join('activite', 'activite.idactivite = budget.idactivite', 'left')
    //         ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
    //         ->join('user', 'user.idUser = relique.idUser', 'left')
    //         ->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent FROM user) agent', 'agent.idagent = relique.idagent', 'left')
    //         ->where('relique.idadmin', $_SESSION['idadmin'])
    //         ->order_by('idreliquat', 'desc');

    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }
// Liste des reliquats (avec pagination) – le plus récent en premier
public function getreliquat($page = 1)
{
    // Forcer page ≥ 1
    $page = max(1, (int)$page);
    $offset = ($page - 1) * PAGINATION;

    $this->db->select('*')
        ->from('relique')
        ->join('budget', 'budget.idbudget = relique.idbudget', 'left')
        ->join('activite', 'activite.idactivite = budget.idactivite', 'left')
        ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
        ->join('user', 'user.idUser = relique.idUser', 'left')
        ->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent FROM user) agent', 'agent.idagent = relique.idagent', 'left')
        ->where('relique.idadmin', $_SESSION['idadmin'] ?? 0)
        ->order_by('relique.idreliquat', 'DESC')   // ← le plus récent en premier
        ->limit(PAGINATION, $offset);

    return $this->db->get()->result();
}

    // Détails par ID
    public function donner($idreliquat)
    {
        return $this->db->select('*')
            ->from('relique')
            ->where('idreliquat', $idreliquat)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }

    public function reliquat_json()
    {
        return $this->db->select('*')
            ->from('relique')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idreliquat', 'desc')
            ->get()->result();
    }

    // Suppression
    public function delete($idreliquat)
    {
        $this->db->where('idreliquat', $idreliquat)
            ->where('idadmin', $_SESSION['idadmin'])
            ->delete('relique');
    }

    // Recherche avancée
    // public function getreliquat_search($keyword = '', $d1 = '', $d2 = '', $page = '')
    // {
    //     if ((int)$page == 0) {
    //         $start = (int)$page * PAGINATION;
    //     } else {
    //         $start = ((int)$page - 1) * PAGINATION;
    //     }

    //     $this->db->select('*')
    //         ->from('relique')
    //         ->join('budget', 'budget.idbudget = relique.idbudget', 'left')
    //         ->join('activite', 'activite.idactivite = budget.idactivite', 'left')
    //         ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
    //         ->join('user', 'user.idUser = relique.idUser', 'left')
    //         ->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent FROM user) agent', 'agent.idagent = relique.idagent', 'left')
    //         ->where('relique.idadmin', $_SESSION['idadmin'])
    //         ->order_by('idreliquat', 'desc');

    //     if ($keyword != '') {
    //         $this->db->like('user.nomUser', $keyword);
    //         $this->db->or_like('user.prenomUser', $keyword);
    //         $this->db->or_like('agent.nomagent', $keyword);
    //         $this->db->or_like('agent.prenomagent', $keyword);
    //         $this->db->or_like('budget.designation_budget', $keyword);
    //         $this->db->or_like('activite.titre_activite', $keyword);
    //     }

    //     if ($d1 != '' && $d2 != '') {
    //         $this->db->where('relique.date_reliquat >=', $d1);
    //         $this->db->where('relique.date_reliquat <=', $d2);
    //     } elseif ($d1 != '' && $d2 == '') {
    //         $this->db->like('relique.date_reliquat', $d1);
    //     } elseif ($d1 == '' && $d2 != '') {
    //         $this->db->like('relique.date_reliquat', $d2);
    //     }

    //     if ($page != '') {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }

// Recherche avancée – le plus récent en premier également
public function getreliquat_search($keyword = '', $d1 = '', $d2 = '', $page = 1)
{
    // Forcer page ≥ 1
    $page = max(1, (int)$page);
    $offset = ($page - 1) * PAGINATION;

    $this->db->select('*')
        ->from('relique')
        ->join('budget', 'budget.idbudget = relique.idbudget', 'left')
        ->join('activite', 'activite.idactivite = budget.idactivite', 'left')
        ->join('projet', 'projet.idprojet = activite.idprojet', 'left')
        ->join('user', 'user.idUser = relique.idUser', 'left')
        ->join('(SELECT idUser as idagent, nomUser as nomagent, prenomUser as prenomagent FROM user) agent', 'agent.idagent = relique.idagent', 'left')
        ->where('relique.idadmin', $_SESSION['idadmin'] ?? 0)
        ->order_by('relique.idreliquat', 'DESC');   // ← le plus récent en premier

    // ... (tes conditions like et dates restent inchangées)

    if ($keyword != '') {
        $this->db->group_start();
        $this->db->like('user.nomUser', $keyword);
        $this->db->or_like('user.prenomUser', $keyword);
        $this->db->or_like('agent.nomagent', $keyword);
        $this->db->or_like('agent.prenomagent', $keyword);
        $this->db->or_like('budget.designation_budget', $keyword);
        $this->db->or_like('activite.titre_activite', $keyword);
        $this->db->group_end();
    }

    if ($d1 != '' && $d2 != '') {
        $this->db->where('relique.date_reliquat >=', $d1);
        $this->db->where('relique.date_reliquat <=', $d2);
    } elseif ($d1 != '' && $d2 == '') {
        $this->db->where('DATE(relique.date_reliquat)', $d1);
    } elseif ($d1 == '' && $d2 != '') {
        $this->db->where('DATE(relique.date_reliquat)', $d2);
    }

    $this->db->limit(PAGINATION, $offset);

    return $this->db->get()->result();
}

    /**
 * Compte le nombre total de reliquats (sans filtre idadmin)
 */
    // public function countAllReliquats()
    // {
    //     return $this->db->count_all_results('relique');
    // }

    public function countAllReliquats()
    {
        $this->db->from('relique');
        $this->db->join('mission', 'mission.idmission = relique.idMission', 'left');

        // Filtrer par utilisateur connecté
        if (!empty($_SESSION['id_user'])) {
            $this->db->where('mission.idUser', $_SESSION['id_user']);
        }

        return $this->db->count_all_results();
    }


    /**
     * Récupère les reliquats paginés – uniquement les colonnes demandées
     */
    public function getReliquatsPaginated($page = 1, $per_page = 20)
    {
        $offset = ($page - 1) * $per_page;

        $this->db->select('
        relique.idRelique,
        relique.idLiquidation,
        relique.montantReturn,
        relique.dateReturn,
        relique.idMission,
        relique.resteReturn,
        
        mission.typeMission,
        mission.objet_mission,
        
        liquidation.date_liquidation
    ');

        $this->db->from('relique');

        // Jointures
        $this->db->join('mission', 'mission.idmission = relique.idMission', 'left');
        $this->db->join('liquidation', 'liquidation.idliquidation = relique.idLiquidation', 'left');

        // Filtrer par utilisateur connecté
        if (!empty($_SESSION['id_user'])) {
            $this->db->where('mission.idagent', $_SESSION['id_user']);
        }

        $this->db->order_by('relique.dateReturn', 'DESC');
        $this->db->limit($per_page, $offset);

        return $this->db->get()->result();
    }
}