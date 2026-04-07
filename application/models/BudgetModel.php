<?php
class BudgetModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    

    /**
     * verification de doublant
     *
     * @param string $designation
     * @param string $idbudget
     * @return void
     */
    public function insert_csv($data) {
        return $this->db->insert_batch('budget', $data);
    }


    public function verifyLigne($ligne, $idadmin, $idbudget = 0) {
        $this->db->from('budget')
                 ->where('idadmin', $idadmin)
                 ->where('ligne_budget', $ligne);

        if ($idbudget != 0) {
            $this->db->where('idbudget !=', $idbudget);
        }
        
        return $this->db->count_all_results();
    }
    public function verify($designation = '' , $idactivite = '' , $idbudget = '')
    {
 
        $this->db->select('*')
            ->from('budget')
            ->where('idadmin', 1)
            ->where('designation_budget', $designation);
        if ($idbudget != '') {
            $this->db->where('idbudget <>', $idbudget);
        }
        if ($idactivite != '') {
            $this->db->where('idactivite ', $idactivite);
        }

        // var_dump( $this->db->get()->result() ) ; die; 
        return $this->db->get()->result();
    }

    // enregistrement
    public function register($data)
    {
        $this->db->insert('budget', $data);
        return $this->db->insert_id();
    }
    // enregistrement
    public function edit($data, $idbudget)
    {
        $this->db->where('idbudget', $idbudget);
        $this->db->update('budget', $data);
    }

    /**
     * datas budget
     *
     * @param string $page
     * @return void
     */
    //   public function getbudget($page = null, $codeprojet = null, $motcle = null)
    // {
    //         // Pagination
    //         $page = (int) $page;
    //         $start = ($page <= 0) ? 0 : ($page - 1) * PAGINATION;

    //        $this->db->select("
    //     b.idbudget,
    //     b.ligne_budget,
    //     b.designation_budget,
    //     b.budget,
    //     p.codeprojet,
    //     (
    //         b.budget
    //         - COALESCE(ld.total_depense, 0)
    //         - COALESCE(ld.total_return_liquidation, 0)
    //         - COALESCE(rr.total_return_relique, 0)
    //     ) AS reste
    // ", false);
    // $this->db->order_by('b.idbudget', 'DESC');
    // $this->db->from('budget b');
    // $this->db->join('projet p', 'p.idprojet = b.idprojet', 'left');

    // $this->db->join("(
    //     SELECT 
    //         am.idbudget,
    //         SUM(l.montant_depense) AS total_depense,
    //         SUM(l.montant_return)  AS total_return_liquidation
    //     FROM avance_mission am
    //     JOIN liquidation l ON l.idmission = am.idmission
    //     GROUP BY am.idbudget
    // ) ld", 'ld.idbudget = b.idbudget', 'left', false);

    // $this->db->join("(
    //     SELECT 
    //         am.idbudget,
    //         SUM(r.montantReturn) AS total_return_relique
    //     FROM avance_mission am
    //     JOIN liquidation l ON l.idmission = am.idmission
    //     JOIN relique r ON r.idLiquidation = l.idLiquidation
    //     GROUP BY am.idbudget
    // ) rr", 'rr.idbudget = b.idbudget', 'left', false);


    //         // Filtrer par code projet si fourni
    //         if (!empty($codeprojet)) {
    //             $this->db->where('p.codeprojet', $codeprojet);
    //         }

    //         // Recherche par mot clé sur plusieurs colonnes
    //         if (!empty($motcle)) {
    //             $this->db->group_start(); // Ouvre une parenthèse
    //             $this->db->like('b.ligne_budget', $motcle);
    //             $this->db->or_like('b.designation_budget', $motcle);
    //             $this->db->or_like('b.budget', $motcle);
    //             $this->db->group_end(); // Ferme la parenthèse
    //         }

    //         // Regroupement obligatoire avec agrégations
    //         $this->db->group_by('b.idbudget, p.codeprojet');

    //         // Limite pagination
    //         if ($page != null) {
    //             $this->db->limit(PAGINATION, $start);
    //         }

    //         return $this->db->get()->result();
    // }

        // public function getbudget($page = null, $codeprojet = null, $motcle = null, $id_user = null)
        // {
        //     // Pagination
        //     $page  = (int) $page;
        //     $start = ($page <= 0) ? 0 : ($page - 1) * PAGINATION;

        //     $this->db->select("
        //     b.idbudget,
        //     b.ligne_budget,
        //     b.designation_budget,
        //     b.budget,
        //     p.codeprojet,
        //     (
        //         b.budget
        //         - COALESCE(ld.total_depense, 0)
        //         - COALESCE(ld.total_return_liquidation, 0)
        //         - COALESCE(rr.total_return_relique, 0)
        //     ) AS reste
        // ", false);

        //     $this->db->from('budget b');
        //     $this->db->join('projet p', 'p.idprojet = b.idprojet', 'left');

        //     // 🔐 USER → seulement ses projets
        //     if (!empty($id_user)) {
        //         $this->db->join('user u', 'u.idprojet = p.idprojet', 'inner');
        //         $this->db->where('u.idUser', $id_user);
        //     }


        //     // Dépenses
        //     $this->db->join("(
        //     SELECT am.idbudget,
        //            SUM(l.montant_depense) AS total_depense,
        //            SUM(l.montant_return)  AS total_return_liquidation
        //     FROM avance_mission am
        //     JOIN liquidation l ON l.idmission = am.idmission
        //     GROUP BY am.idbudget
        // ) ld", 'ld.idbudget = b.idbudget', 'left', false);

        //     // Reliques
        //     $this->db->join("(
        //     SELECT am.idbudget,
        //            SUM(r.montantReturn) AS total_return_relique
        //     FROM avance_mission am
        //     JOIN liquidation l ON l.idmission = am.idmission
        //     JOIN relique r ON r.idLiquidation = l.idLiquidation
        //     GROUP BY am.idbudget
        // ) rr", 'rr.idbudget = b.idbudget', 'left', false);

        //     // Filtre projet
        //     if (!empty($codeprojet)) {
        //         $this->db->where('p.codeprojet', $codeprojet);
        //     }

        //     // Recherche
        //     if (!empty($motcle)) {
        //         $this->db->group_start();
        //         $this->db->like('b.ligne_budget', $motcle);
        //         $this->db->or_like('b.designation_budget', $motcle);
        //         $this->db->or_like('b.budget', $motcle);
        //         $this->db->group_end();
        //     }

        //     $this->db->group_by('b.idbudget, p.codeprojet');
        //     $this->db->order_by('b.idbudget', 'DESC');

        //     if ($page !== null) {
        //         $this->db->limit(PAGINATION, $start);
        //     }

        //     return $this->db->get()->result();
        // }
/*/** */
    // public function getbudget($page = 1, $idUser = null, $codeprojet = null, $motcle = null)
    // {
    //     $start = ($page - 1) * PAGINATION;

    //     $this->db
    //         ->select('
    //             budget.idbudget,
    //             budget.ligne_budget,
    //             budget.designation_budget,
    //             budget.budget,
    //             projet.codeprojet,
    //             (budget.budget - IFNULL(SUM(depense.montant), 0)) AS reste
    //         ', false)
    //         ->from('budget')
    //         ->join('user', 'user.idprojet = budget.idprojet')
    //         ->join('projet', 'projet.idprojet = budget.idprojet')
    //         ->join('depense', 'depense.idbudget = budget.idbudget', 'left')
    //         ->where('user.idUser', (int)$idUser);

    //     // Filtre par code projet
    //     if (!empty($codeprojet)) {
    //         $this->db->where('projet.codeprojet', $codeprojet);
    //     }

    //     // Recherche par mot clé sur ligne_budget ou designation_budget
    //     if (!empty($motcle)) {
    //         $this->db->group_start();
    //         $this->db->like('budget.ligne_budget', $motcle);
    //         $this->db->or_like('budget.designation_budget', $motcle);
    //         $this->db->group_end();
    //     }

    //     $this->db->group_by('budget.idbudget')
    //         ->order_by('budget.idbudget', 'DESC');

    //     if ($page !== null) {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }

    // public function countAllBudgets($idUser = null, $codeprojet = null, $motcle = null)
    // {
    //     $this->db->from('budget')
    //         ->join('user', 'user.idprojet = budget.idprojet')
    //         ->join('projet', 'projet.idprojet = budget.idprojet')
    //         ->where('user.idUser', (int)$idUser);

    //     if (!empty($codeprojet)) {
    //         $this->db->where('projet.codeprojet', $codeprojet);
    //     }

    //     if (!empty($motcle)) {
    //         $this->db->group_start();
    //         $this->db->like('budget.ligne_budget', $motcle);
    //         $this->db->or_like('budget.designation_budget', $motcle);
    //         $this->db->group_end();
    //     }

    //     return $this->db->count_all_results();
    // }



 
    // public function getbudget($page = 1, $idUser = null, $codeprojet = null, $motcle = null)
    // {
    //     $start = ($page - 1) * PAGINATION;

    //     $this->db
    //         ->select('
    //             budget.idbudget,
    //             budget.ligne_budget,
    //             budget.designation_budget,
    //             budget.budget,
    //             projet.codeprojet,
    //             (budget.budget - IFNULL(SUM(depense.montant), 0)) AS reste
    //         ', false)
    //         ->from('budget')
    //         // ->join('user', 'user.idprojet = budget.idprojet')
    //         ->join('projet', 'projet.idprojet = budget.idprojet')
    //         ->join('depense', 'depense.idbudget = budget.idbudget', 'left');

    //     if ($idUser !== null) {
    //     //    $this->db->join('user', 'user.idprojet = budget.idprojet');
    //         $this->db->where('projet.idprojet = (SELECT idprojet FROM user WHERE idUser = ' . (int)$idUser . ')', null, false);

    //         // $this->db->where('user.idUser', (int)$idUser);
    //     }


    //     // Filtre par code projet
    //     if (!empty($codeprojet)) {
    //         $this->db->where('projet.codeprojet', $codeprojet);
    //     }

    //     // Recherche par mot clé sur ligne_budget ou designation_budget
    //     if (!empty($motcle)) {
    //         $this->db->group_start();
    //         $this->db->like('budget.ligne_budget', $motcle);
    //         $this->db->or_like('budget.designation_budget', $motcle);
    //         $this->db->group_end();
    //     }

    //     $this->db->group_by('budget.idbudget')
    //         ->order_by('budget.idbudget', 'DESC');

    //     if ($page !== null) {
    //         $this->db->limit(PAGINATION, $start);
    //     }

    //     return $this->db->get()->result();
    // }

    // public function countAllBudgets($idUser = null, $codeprojet = null, $motcle = null)
    // {
    //     $this->db->from('budget')
    //         ->join('user', 'user.idprojet = budget.idprojet')
    //         ->join('projet', 'projet.idprojet = budget.idprojet');

    //     if ($idUser !== null) {
    //         $this->db->where('user.idUser', (int)$idUser);
    //     }

    //     if (!empty($codeprojet)) {
    //         $this->db->where('projet.codeprojet', $codeprojet);
    //     }

    //     if (!empty($motcle)) {
    //         $this->db->group_start();
    //         $this->db->like('budget.ligne_budget', $motcle);
    //         $this->db->or_like('budget.designation_budget', $motcle);
    //         $this->db->group_end();
    //     }

    //     return $this->db->count_all_results();
    // }

public function getbudget($page = 1, $idUser = null, $codeprojet = null, $motcle = null)
{
    $start = ($page - 1) * PAGINATION;
    $this->db->select("
        b.idbudget,
        b.ligne_budget,
        b.designation_budget,
        b.budget,
        p.codeprojet,
        (
            b.budget
            - COALESCE(ld.total_depense, 0)
            - COALESCE(ld.total_return_liquidation, 0)
            - COALESCE(rr.total_return_relique, 0)
        ) AS reste
    ", false);
    $this->db->from('budget b');
    $this->db->join('projet p', 'p.idprojet = b.idprojet');

    // Restriction utilisateur SEULEMENT si idUser fourni (voit seulement son projet dédié)
    if ($idUser !== null) {
        $this->db->join('user u', 'u.idprojet = p.idprojet', 'inner');
        $this->db->where('u.idUser', (int)$idUser);
    }

    // Sous-requête pour Dépenses et Liquidations
    $this->db->join("(
        SELECT am.idbudget,
               SUM(l.montant_depense) AS total_depense,
               SUM(l.montant_return)  AS total_return_liquidation
        FROM avance_mission am
        JOIN liquidation l ON l.idmission = am.idmission
        GROUP BY am.idbudget
    ) ld", 'ld.idbudget = b.idbudget', 'left', false);

    // Sous-requête pour Reliques
    $this->db->join("(
        SELECT am.idbudget,
               SUM(r.montantReturn) AS total_return_relique
        FROM avance_mission am
        JOIN liquidation l ON l.idmission = am.idmission
        JOIN relique r ON r.idLiquidation = l.idLiquidation
        GROUP BY am.idbudget
    ) rr", 'rr.idbudget = b.idbudget', 'left', false);

    // Filtre par code projet
    if (!empty($codeprojet)) {
        $this->db->where('p.codeprojet', $codeprojet);
    }

    // Recherche par mot clé (sur ligne, designation, et budget comme en version 2)
    if (!empty($motcle)) {
        $this->db->group_start();
        $this->db->like('b.ligne_budget', $motcle);
        $this->db->or_like('b.designation_budget', $motcle);
        $this->db->or_like('b.budget', $motcle);
        $this->db->group_end();
    }

    $this->db->group_by('b.idbudget')
              ->order_by('b.idbudget', 'DESC');

    if ($page !== null) {
        $this->db->limit(PAGINATION, $start);
    }

    return $this->db->get()->result();
}

public function countAllBudgets($idUser = null, $codeprojet = null, $motcle = null)
{
    $this->db->from('budget b')
             ->join('projet p', 'p.idprojet = b.idprojet');

    // Restriction utilisateur SEULEMENT si idUser fourni
    if ($idUser !== null) {
        $this->db->join('user u', 'u.idprojet = p.idprojet', 'inner');
        $this->db->where('u.idUser', (int)$idUser);
    }

    if (!empty($codeprojet)) {
        $this->db->where('p.codeprojet', $codeprojet);
    }

    if (!empty($motcle)) {
        $this->db->group_start();
        $this->db->like('b.ligne_budget', $motcle);
        $this->db->or_like('b.designation_budget', $motcle);
        $this->db->or_like('b.budget', $motcle);  // Ajouté comme en version 2
        $this->db->group_end();
    }

    return $this->db->count_all_results();
}


    // donner par id 
    public function donner($idbudget = "")
    {
        return $this->db->select('*')
            ->from('budget')
            ->where('idbudget', $idbudget)
            ->where('idadmin', $_SESSION['idadmin'])
            ->get()->result();
    }
    public function activite_json()
    {
        return $this->db->select('*')
            ->from('budget')
            ->where('idadmin', $_SESSION['idadmin'])
            ->order_by('idactivite' , 'desc')
            ->get()->result();
    }
    // delete 
    public function delete($idbudget)
    {
        $this->db->where('idbudget' , $idbudget )
            ->where('idadmin', $_SESSION['idadmin'])
            ->delete('budget');
    }

    // recherche 
    public function search( $mot  ='' , $page ='' ){
        if ((int)$page == 0) {
            $start = (int)$page * PAGINATION;
        } else {
            $start = ((int)$page - 1) * PAGINATION;
        }

        $this->db->select('*')
            ->from('budget')
            ->join('activite' , 'activite.idactivite = budget.idactivite' , 'left')
            ->join('projet' , 'activite.idprojet = projet.idprojet' , 'left')
            ->where('budget.idadmin', $_SESSION['idadmin']);

        if ( $mot != ''){
            $this->db->like('budget.designation_budget' , $mot ) ; 
            $this->db->or_like('activite.titre_activite' , $mot ) ;  
            $this->db->or_like('projet.titreprojet' , $mot ) ;  
            $this->db->or_like('projet.codeprojet' , $mot ) ;  
        }

        
        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }

    public function getByactivite( $idprojet = ''){
        
        $this->db->select('
            b.*, 
            b.ligne_budget, 
            b.designation_budget, 
            b.budget,
            p.*, 
            (b.budget - COALESCE(SUM(am.montant_avance), 0) + COALESCE(SUM(l.montant_return), 0)) AS reste
        ');
        $this->db->from('budget b');
        $this->db->join('projet p', 'b.idprojet = p.idprojet', 'left');

        // Jointure avec avance_mission pour récupérer montant_avance
        $this->db->join('avance_mission am', 'am.idbudget = b.idbudget', 'left');

        // Jointure liquidation sur idmission d'avance_mission
        $this->db->join('liquidation l', 'l.idmission = am.idmission', 'left');

        $this->db->where('b.idAdmin', $_SESSION['idadmin']);
        $this->db->where('p.idprojet' , $idprojet ) ;
        // Regroupement obligatoire avec agrégations
        $this->db->group_by('b.idbudget, p.codeprojet');



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
         // ici
       $this->db->select('
        b.idbudget, 
        b.ligne_budget, 
        b.designation_budget, 
        b.budget,
        p.codeprojet, 
        (b.budget - COALESCE(SUM(m.montant_avance), 0) + COALESCE(SUM(l.montant_return), 0)) AS reste
        ');
        $this->db->from('budget b');
        $this->db->join('projet p', 'b.idprojet = p.idprojet', 'left');
        $this->db->join('mission m', 'm.idbudgets = b.idbudget', 'left');
        $this->db->join('liquidation l', 'l.idmission = m.idmission', 'left'); // Jointure avec liquidation
        $this->db->where('b.idAdmin',$_SESSION['idadmin']);
        $this->db->group_by('b.idbudget, p.codeprojet');
        

       /* if ($idprojet != '') {
            $this->db->where('projet.idprojet', $idprojet );
        }*/

        if ($page != '') {
            $this->db->limit(PAGINATION, $start);
        }

        return $this->db->get()->result();
    }
}
