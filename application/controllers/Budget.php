<?php
class Budget extends CI_Controller
{
    private $activites;
    private $projets;
    public function __construct()
    {

        parent::__construct();
        $this->load->model('BudgetModel', 'budget');
        $this->load->model('ActiviteModel', 'activite');
        $this->load->model('ProjetModel', 'projet');

        $this->projets = $this->projet->getpojet();
        $this->activites = $this->activite->getactivite();
    }


    /**
     * page principale de budget 
     *
     * @return void
     */
    // public function index()
    // {
    //     $assets['title'] = 'Budget';
    //     $assets['css'] = 'budget.css';
    //     $js['js'] = 'budget.js';

    //     $data['datas'] = $this->budget->getbudget(1);

    //     $data['lien'] = $this->pagination('budget', count($this->budget->getbudget()));


    //     $this->load->view('templates/header', $assets);
    //     $this->load->view('templates/sidebar', ['budget' => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('budget', [
    //         'activites' => $this->activites,
    //         'projets' => $this->projets,
    //         'data' => $data
    //     ]);
    //     $this->load->view('templates/footer', $js);

    // }
    // public function index()
    // {
    //     $assets['title'] = 'Budget';
    //     $assets['css']   = 'budget.css';
    //     $js['js']        = 'budget.js';

    //     $id_user = null;

    //     $page = 1;

    //     // Nombre total sans limite (avec id_user)
    //     $total = 0;


    //     // ✅ id_user requis pour cette page
    //     if(empty($_SESSION['idadmin'])) {
    //         if (empty($_SESSION['id_user'])) {
    //             redirect('auth'); // ou page de connexion
    //             exit;
    //         } else {
    //             $id_user = $_SESSION['id_user'];

    //             $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;

    //             // Nombre total sans limite (avec id_user)
    //             $total = count(
    //                 $this->budget->getbudget(null, null, null, $id_user)
    //             );
    //         }
    //     }


    //     // Données paginées (avec id_user)
    //     $data['datas'] = $this->budget->getbudget(
    //         $page,
    //         null,
    //         null,
    //         $id_user
    //     );

    //     $data['lien'] = $this->_pagination_links(
    //         base_url('budget'),
    //         $total
    //     );

    //     $this->load->view('templates/header', $assets);
    //     $this->load->view('templates/sidebar', ['budget' => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('budget', [
    //         'activites' => $this->activites,
    //         'projets'   => $this->projets,
    //         'data'      => $data
    //     ]);
    //     $this->load->view('templates/footer', $js);
    // }

    // public function index()
    // {
    //     $assets['title'] = 'Budget';
    //     $assets['css']   = 'budget.css';
    //     $js['js']        = 'budget.js';

    //     if (empty($_SESSION['id_user'])) {
    //         redirect('auth');
    //         exit;
    //     }

    //     $idUser = $_SESSION['id_user'];
    //     $page   = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    //     $codeprojet = $this->input->get('codeprojet') ?? null;
    //     $motcle     = $this->input->get('motcle') ?? null;

    //     // Nombre total pour la pagination
    //     $total = $this->budget->countAllBudgets($idUser, $codeprojet, $motcle);

    //     // Données paginées
    //     $data['datas'] = $this->budget->getbudget($page, $idUser, $codeprojet, $motcle);

    //     $data['lien'] = $this->_pagination_links(base_url('budget'), $total);

    //     $this->load->view('templates/header', $assets);
    //     $this->load->view('templates/sidebar', ['budget' => true]);
    //     $this->load->view('templates/tete');
    //     $this->load->view('budget', [
    //         'activites' => $this->activites ?? [],
    //         'projets'   => $this->projets ?? [],
    //         'data'      => $data
    //     ]);
    //     $this->load->view('templates/footer', $js);
    // }

    public function index()
    {
        $assets['title'] = 'Budget';
        $assets['css']   = 'budget.css';
        $js['js']        = 'budget.js';

        if (empty($_SESSION['idadmin']) && empty($_SESSION['id_user'])) {
            redirect('auth');
            exit;
        }


        // if (empty($_SESSION['id_user'])) {
        //     redirect('auth');
        //     exit;
        // }

        // $idUser = empty($_SESSION['idadmin'])  ? $_SESSION['id_user'] : null;
        $idUser = $_SESSION['id_user'] ?? null;

        $page   = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $codeprojet = $this->input->get('codeprojet') ?? null;
        $motcle     = $this->input->get('motcle') ?? null;

        // Nombre total pour la pagination
        $total = $this->budget->countAllBudgets($idUser, $codeprojet, $motcle);

        // Données paginées
        $data['datas'] = $this->budget->getbudget($page, $idUser, $codeprojet, $motcle);

        $data['lien'] = $this->_pagination_links(base_url('budget'), $total);

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['budget' => true]);
        $this->load->view('templates/tete');
        $this->load->view('budget', [
            'activites' => $this->activites ?? [],
            'projets'   => $this->projets ?? [],
            'data'      => $data
        ]);
        $this->load->view('templates/footer', $js);
    }




    public function import_csv()
    {
        $this->load->helper(array('form', 'url'));

        if (isset($_POST['import']) && !empty($_FILES['csv_file']['name'])) {
            $file = $_FILES['csv_file']['tmp_name'];

            if (($handle = fopen($file, 'r')) !== FALSE) {
                $data = [];
                $firstRow = true;
                $nombre = 0;
                while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    if ($firstRow) {
                        $firstRow = false; // skip header
                        continue;
                    }

                    // ✅ Vérification du nombre de colonnes
                    if (count($row) != 4) {
                        fclose($handle);
                        $this->session->set_userdata('wrong_columns', true);
                        redirect('budget');
                        exit;
                    }
                    $teste = $this->budget->verifyLigne($row[1], 1);
                    if ($teste == 0) {
                        $donne = $this->projet->donnerByCode(trim($row[0]));

                        $data[] = [
                            'idprojet '   => $donne[0]->idprojet,
                            'ligne_budget'  => $row[1],
                            'designation_budget'   => $row[2],
                            'budget'     => $row[3],
                            'idadmin'      => 1
                        ];
                        $nombre++;
                    }
                }

                fclose($handle);

                if (!empty($data)) {
                    $this->budget->insert_csv($data);

                    $this->session->set_userdata('added_projet',  true);

                    redirect('budget');
                } else {
                    $this->session->set_userdata('vide',  true);

                    redirect('budget');
                }
            } else {
                echo "Impossible d'ouvrir le fichier.";
            }
        } else {
            $this->session->set_userdata('blank_file',  true);

            redirect('projet');
        }
    }
    /**
     * verification du doublant
     *
     * @return void
     */
    public function verify()
    {

        $ligne  = '';
        if (isset($_POST['ligne']) && $_POST['ligne'] != '') {
            $ligne = intval($_POST['ligne']);
        }


        $teste = $this->budget->verifyLigne($ligne, $_SESSION['idadmin']);
        if ($teste != 0) {
            echo json_encode([
                'success' => false
            ]);
        } else {
            echo json_encode([
                'success' => true
            ]);
        }
    }


    /**
     * enregistrement
     *
     * @return void
     */
    public function register()
    {

        $designation  = '';
        if (isset($_POST['designation']) && $_POST['designation'] != '') {
            $designation = trim(strip_tags($_POST['designation']));
        }

        $budget  = '';
        if (isset($_POST['budget']) && $_POST['budget'] != '') {
            $budget = trim(strip_tags($_POST['budget']));
        }

        $codeProjet = "";
        if (isset($_POST['codeprojet']) && $_POST['codeprojet'] != '') {
            $codeProjet = trim(strip_tags($_POST['codeprojet']));
        }
        $ligne = "";
        if (isset($_POST['ligne']) && $_POST['ligne'] != '') {
            $ligne = trim(strip_tags($_POST['ligne']));
        }

        $idProjet = $this->projet->getbycode($codeProjet)[0]->idprojet;



        $teste = $this->budget->verify($designation, $activite);
        if (!count($teste)) {
            $data = [
                'designation_budget' => $designation,
                'idprojet' => $idProjet,
                'ligne_budget' => $ligne,
                'idadmin' => $_SESSION['idadmin'],
                'budget' => $budget
            ];


            $this->budget->register($data);
            $this->session->set_userdata('added_projet',  true);

            $this->session->set_flashdata('budgetadded', 'Données enregistrées avec succès !');
        }
        redirect('budget');
    }

    // donner budget par id 
    public function donner()
    {
        $idbudget  = '';
        if (isset($_POST['idbudget']) && $_POST['idbudget'] != '') {
            $idbudget = trim(strip_tags($_POST['idbudget']));
        }

        $data = $this->budget->donner($idbudget);

        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data[0],
                'activites' => $this->activites,
                'projets' => $this->projets,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $data
            ]);
        }
    }

    /**
     * Toute les proget en json
     *
     * @return void
     */
    public function projet_json()
    {
        $data = $this->budget->projet_json();
        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $data
            ]);
        }
    }


    /**
     * 
     *
     * @return void
     */
    public function getProduitByactivite_json()
    {
        $idactivite = '';
        if (isset($_POST['idactivite']) && $_POST['idactivite'] != '') {
            $idactivite = trim(strip_tags($_POST['idactivite']));
        }

        $idprojet = 0;
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = intval($_POST['idprojet']);
        }

        // Récupérer TOUS les budgets d'abord
        $allBudgets = $this->budget->getByactivite($idprojet);

        // Si pagination demandée
        if (isset($_POST['page'])) {
            $page = (int)$_POST['page'];
            $per_page = isset($_POST['per_page']) ? (int)$_POST['per_page'] : 10;
            $offset = ($page - 1) * $per_page;

            // Paginer les résultats
            $paginatedBudgets = array_slice($allBudgets, $offset, $per_page);
            $total = count($allBudgets);
            $total_pages = ceil($total / $per_page);

            echo json_encode([
                'success' => true,
                'datas' => $paginatedBudgets,
                'total' => $total,
                'total_pages' => $total_pages
            ]);
        } else {
            // Comportement original
            if (count($allBudgets)) {
                echo json_encode([
                    'success' => true,
                    'datas' => $allBudgets
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'datas' => $allBudgets
                ]);
            }
        }
    }

    /**
     * Modification
     *
     * @return void
     */
    public function edit()
    {

        $idbudget  = 0;
        if (isset($_POST['idbudget']) && $_POST['idbudget'] != '') {
            $idbudget = intval($_POST['idbudget']);
        }

        $designation  = '';
        if (isset($_POST['designation']) && $_POST['designation'] != '') {
            $designation = trim(strip_tags($_POST['designation']));
        }

        $budget  = 0;
        if (isset($_POST['budget']) && $_POST['budget'] != '') {
            $budget = intval($_POST['budget']);
        }

        $ligne  = '';
        if (isset($_POST['ligne']) && $_POST['ligne'] != '') {
            $ligne = trim(strip_tags($_POST['ligne']));
        }



        if ($this->budget->verifyLigne($ligne, 1, $idbudget) != 0) {
            echo json_encode([
                'success' => false,
                'message' => "error-ligne"
            ]);
        } else {

            $data = [
                'designation_budget' => $designation,
                'ligne_budget' => $ligne,
                'budget' => $budget
            ];
            $this->budget->edit($data, $idbudget);
            echo json_encode([
                'success' => true,

            ]);
            // eto 
            $this->session->set_userdata('edit_projet', true);
        }
    }

    /**
     * effacer l'budget
     *
     * @return void
     */
    public function delete()
    {
        $idbudget  = '';
        if (isset($_POST['idbudget']) && $_POST['idbudget'] != '') {
            $idbudget = trim(strip_tags($_POST['idbudget']));
        }

        $this->budget->delete($idbudget);
        $this->session->set_userdata('delete_projet',  true);
    }

    /**
     * recherche dans budget
     *
     * @param integer $page
     * @return void
     */
    public function search()
    {

        $codeProjet  = '';
        if (isset($_GET['codeprojet']) && $_GET['codeprojet'] != '') {
            $codeProjet = trim(strip_tags($_GET['codeprojet']));
        }
        $motCle  = '';
        if (isset($_GET['motcle']) && $_GET['motcle'] != '') {
            $motCle = trim(strip_tags($_GET['motcle']));
        }
        $page  = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = intval($_GET['page']);
        }



        $data['lien'] =  $this->pagination_search('Budget/search', count($this->budget->getbudget(null, $codeProjet, $motCle)));
        //var_dump($data['lien']) ;
        $data['datas'] = $this->budget->getbudget($page, $codeProjet, $motCle);



        $assets['title'] = 'Budget';
        $assets['css'] = 'budget.css';
        $js['js'] = 'budget.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['budget' => true]);
        $this->load->view('templates/tete');
        $this->load->view('budget', [
            'activites' => $this->activites,
            'projets' => $this->projets,
            'data' => $data,
            'code' => $codeProjet,
            'mot' => $motCle
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * datas budgets par id activite
     *
     * @return void
     */
    public function getByactivite()
    {
        $idactivite  = '';
        if (isset($_POST['idactivite']) && $_POST['idactivite'] != '') {
            $idactivite = trim(strip_tags($_POST['idactivite']));
        }


        $budgets = $this->budget->getByactivite($idactivite);
        if (count($budgets)) {
            echo json_encode([
                'success' => true,
                'datas' => $budgets
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'datas' => $budgets
            ]);
        }
    }


    public function projets($id = '')
    {
        if ($id != '') {
            $temps = [];
            $projets_  = [];
            if (count($this->projets)) {
                foreach ($this->projets  as $key => $projet) {
                    if ($projet->idprojet == $id) {
                        $projets_[] = $projet;
                    }
                }
                foreach ($this->projets  as $key => $projet) {
                    if ($projet->idprojet != $id) {
                        $projets_[] = $projet;
                    }
                }
            }
            return $projets_;
        }
        return $this->projets;
    }



    /**
     * recherche par projet
     *
     * @param integer $page
     * @return void
     */
    public function searchProjet($page = 1)
    {

        $idprojet  = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = trim(strip_tags($_POST['idprojet']));
        }

        $_POST['idprojet'] = $idprojet;

        $nPages = ceil(count($this->budget->searchProjet($idprojet)) / PAGINATION);
        $datapag['datas'] = $this->budget->searchProjet($idprojet, $page);
        $datapag['lien'] = '';


        $data['title'] = 'Budget';
        $data['css'] = 'budget.css';
        $js['js'] = 'budget.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['budget' => true]);
        $this->load->view('templates/tete');
        $this->load->view('budget', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page,
            'projets' => $this->projets($idprojet)
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * Génère les liens de pagination avec conservation des paramètres GET
     */
    private function _pagination_links($base_url, $total_rows)
    {
        $this->load->library('pagination');

        $config = array(
            'base_url'              => $base_url,
            'total_rows'            => $total_rows,
            'per_page'              => defined('PAGINATION') ? PAGINATION : 15,  // ← change selon tes besoins
            'use_page_numbers'      => TRUE,
            'page_query_string'     => TRUE,
            'query_string_segment'  => 'page',
            'reuse_query_string'    => TRUE,               // ← ESSENTIEL : garde ?codeprojet=... & motcle=...

            // Style Bootstrap 4/5
            'full_tag_open'         => '<ul class="pagination justify-content-center pagination-sm mt-4">',
            'full_tag_close'        => '</ul>',
            'num_tag_open'          => '<li class="page-item">',
            'num_tag_close'         => '</li>',
            'cur_tag_open'          => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close'         => '</span></li>',
            'attributes'            => array('class' => 'page-link'),
            'first_tag_open'        => '<li class="page-item">',
            'first_tag_close'       => '</li>',
            'last_tag_open'         => '<li class="page-item">',
            'last_tag_close'        => '</li>',
            'next_tag_open'         => '<li class="page-item">',
            'next_tag_close'        => '</li>',
            'prev_tag_open'         => '<li class="page-item">',
            'prev_tag_close'        => '</li>',
            'first_link'            => '&laquo;',
            'last_link'             => '&raquo;',
            'next_link'             => '&rsaquo;',
            'prev_link'             => '&lsaquo;'
        );

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
    // public function donner_details()
    // {
    //     $idbudget = $this->input->post('idbudget');

    //     if (empty($idbudget) || !is_numeric($idbudget)) {
    //         echo json_encode(['success' => false, 'message' => 'ID invalide']);
    //         return;
    //     }

    //     // Récupération du budget de base
    //     $budgetData = $this->budget->donner($idbudget);
    //     if (empty($budgetData)) {
    //         echo json_encode(['success' => false, 'message' => 'Non trouvé']);
    //         return;
    //     }
    //     $budget = $budgetData[0];

    //     // Récupération du projet
    //     $projet = null;
    //     if (!empty($budget->idprojet)) {
    //         $projetData = $this->projet->donner($budget->idprojet);
    //         $projet = !empty($projetData) ? $projetData[0] : null;
    //     }

    //     // Calcul du reliquat exactement comme dans getbudget
    //     $this->db->select("
    //         (
    //             b.budget
    //             - COALESCE(ld.total_depense, 0)
    //             - COALESCE(ld.total_return_liquidation, 0)
    //             - COALESCE(rr.total_return_relique, 0)
    //         ) AS reste
    //     ", false);

    //     $this->db->from('budget b');
    //     $this->db->join("(
    //         SELECT am.idbudget,
    //                SUM(l.montant_depense) AS total_depense,
    //                SUM(l.montant_return)  AS total_return_liquidation
    //         FROM avance_mission am
    //         JOIN liquidation l ON l.idmission = am.idmission
    //         GROUP BY am.idbudget
    //     ) ld", 'ld.idbudget = b.idbudget', 'left', false);

    //     $this->db->join("(
    //         SELECT am.idbudget,
    //                SUM(r.montantReturn) AS total_return_relique
    //         FROM avance_mission am
    //         JOIN liquidation l ON l.idmission = am.idmission
    //         JOIN relique r ON r.idLiquidation = l.idLiquidation
    //         GROUP BY am.idbudget
    //     ) rr", 'rr.idbudget = b.idbudget', 'left', false);

    //     $this->db->where('b.idbudget', $idbudget);
    //     $resteQuery = $this->db->get()->row();

    //     $reste = $resteQuery ? $resteQuery->reste : $budget->budget;

    //     // Réponse complète
    //     $responseData = (object) [
    //         'idbudget'          => $budget->idbudget,
    //         'ligne_budget'      => $budget->ligne_budget ?? '—',
    //         'designation_budget'=> $budget->designation_budget ?? '—',
    //         'budget'            => $budget->budget ?? 0,
    //         'date_creation'     => $budget->date_creation ?? null,
    //         'codeprojet'        => $projet ? $projet->codeprojet : '—',
    //         'titreprojet'       => $projet ? $projet->titreprojet : '—',
    //         'reste'             => $reste   // ← voilà le champ qui manquait !
    //     ];

    //     echo json_encode([
    //         'success' => true,
    //         'data'    => $responseData
    //     ]);
    // }
    // public function donner_details()
    // {
    //     $idbudget = $this->input->post('idbudget');
    //     if (empty($idbudget) || !is_numeric($idbudget)) {
    //         echo json_encode(['success' => false, 'message' => 'ID invalide']);
    //         return;
    //     }
    //     // Récupération du budget de base
    //     $budgetData = $this->budget->donner($idbudget);
    //     if (empty($budgetData)) {
    //         echo json_encode(['success' => false, 'message' => 'Non trouvé']);
    //         return;
    //     }
    //     $budget = $budgetData[0];
    //     // Récupération du projet
    //     $projet = null;
    //     if (!empty($budget->idprojet)) {
    //         $projetData = $this->projet->donner($budget->idprojet);
    //         $projet = !empty($projetData) ? $projetData[0] : null;
    //     }
    //     // Calcul du reliquat exactement comme dans getbudget
    //     $this->db->select("
    //         (
    //             b.budget
    //             - COALESCE(ld.total_depense, 0)
    //             - COALESCE(ld.total_return_liquidation, 0)
    //             - COALESCE(rr.total_return_relique, 0)
    //         ) AS reste
    //     ", false);

    //     $this->db->from('budget b');
    //     $this->db->join("(
    //         SELECT am.idbudget,
    //                SUM(l.montant_depense) AS total_depense,
    //                SUM(l.montant_return)  AS total_return_liquidation
    //         FROM avance_mission am
    //         JOIN liquidation l ON l.idmission = am.idmission
    //         GROUP BY am.idbudget
    //     ) ld", 'ld.idbudget = b.idbudget', 'left', false);

    //     $this->db->join("(
    //         SELECT am.idbudget,
    //                SUM(r.montantReturn) AS total_return_relique
    //         FROM avance_mission am
    //         JOIN liquidation l ON l.idmission = am.idmission
    //         JOIN relique r ON r.idLiquidation = l.idLiquidation
    //         GROUP BY am.idbudget
    //     ) rr", 'rr.idbudget = b.idbudget', 'left', false);

    //     $this->db->where('b.idbudget', $idbudget);
    //     $resteQuery = $this->db->get()->row();
    //     $reste = $resteQuery ? $resteQuery->reste : $budget->budget;

    //     // ─── AJOUT : Récupérer les missions liées à ce budget ───
    //     $missions = $this->db
    //         ->select("
    //             m.idmission,
    //             m.objet_mission,
    //             m.lieu_mission,
    //             m.date_debut_mission,
    //             m.date_fin_mission,
    //             m.montant_avance,
    //             CONCAT(ua.nomUser, ' ', ua.prenomUser) AS agent,
    //             m.numero_asm,
    //             m.numero_om,
    //             ua.contact AS telagent
    //         ")
    //         ->from('avance_mission am')
    //         ->join('mission m', 'm.idmission = am.idmission')
    //         ->join('user ua', 'ua.idUser = m.idagent', 'left')
    //         ->where('am.idbudget', $idbudget)
    //         ->order_by('m.date_debut_mission', 'DESC')
    //         ->get()
    //         ->result();

    //     // Réponse complète (garde l'existant + ajoute 'missions')
    //     $responseData = (object) [
    //         'idbudget'          => $budget->idbudget,
    //         'ligne_budget'      => $budget->ligne_budget ?? '—',
    //         'designation_budget'=> $budget->designation_budget ?? '—',
    //         'budget'            => $budget->budget ?? 0,
    //         'date_creation'     => $budget->date_creation ?? null,
    //         'codeprojet'        => $projet ? $projet->codeprojet : '—',
    //         'titreprojet'       => $projet ? $projet->titreprojet : '—',
    //         'reste'             => $reste,
    //         'missions'          => $missions  // Tableau des missions (mêmes champs que dans le tableau mission.php)
    //     ];
    //     echo json_encode([
    //         'success' => true,
    //         'data'    => $responseData
    //     ]);
    // }
    public function donner_details()
    {
        $idbudget = $this->input->post('idbudget');
        if (empty($idbudget) || !is_numeric($idbudget)) {
            echo json_encode(['success' => false, 'message' => 'ID invalide']);
            return;
        }

        $budgetData = $this->budget->donner($idbudget);
        if (empty($budgetData)) {
            echo json_encode(['success' => false, 'message' => 'Budget non trouvé']);
            return;
        }
        $budget = $budgetData[0];

        // Projet
        $projet = null;
        if (!empty($budget->idprojet)) {
            $projetData = $this->projet->donner($budget->idprojet);
            $projet = !empty($projetData) ? $projetData[0] : null;
        }

        // Calcul reliquat (inchangé)
        $resteQuery = $this->db->select("
        (b.budget
         - COALESCE(ld.total_depense, 0)
         - COALESCE(ld.total_return_liquidation, 0)
         - COALESCE(rr.total_return_relique, 0)
        ) AS reste
    ", false)
            ->from('budget b')
            ->join(
                "(SELECT am.idbudget, SUM(l.montant_depense) AS total_depense, SUM(l.montant_return) AS total_return_liquidation
            FROM avance_mission am JOIN liquidation l ON l.idmission = am.idmission GROUP BY am.idbudget) ld",
                'ld.idbudget = b.idbudget',
                'left'
            )
            ->join(
                "(SELECT am.idbudget, SUM(r.montantReturn) AS total_return_relique
            FROM avance_mission am JOIN liquidation l ON l.idmission = am.idmission
            JOIN relique r ON r.idLiquidation = l.idliquidation GROUP BY am.idbudget) rr",
                'rr.idbudget = b.idbudget',
                'left'
            )
            ->where('b.idbudget', $idbudget)
            ->get()->row();

        $reste = $resteQuery ? $resteQuery->reste : $budget->budget;

        // Missions liées (inchangé)
        $missions = $this->db->select("
        m.idmission, m.objet_mission, m.lieu_mission, m.date_debut_mission, m.date_fin_mission,
        m.montant_avance, CONCAT(ua.nomUser, ' ', ua.prenomUser) AS agent,
        m.numero_asm, m.numero_om, ua.contact AS telagent
    ")
            ->from('avance_mission am')
            ->join('mission m', 'm.idmission = am.idmission')
            ->join('user ua', 'ua.idUser = m.idagent', 'left')
            ->where('am.idbudget', $idbudget)
            ->order_by('m.date_debut_mission', 'DESC')
            ->get()->result();

        // Liquidations liées (inchangé)
        $liquidations = $this->db->select("
        l.idliquidation,
        l.date_liquidation,
        l.date_reliquat,
        l.montant_depense            AS total_depense,
        l.montant_return             AS montant_retourne,
        l.montant_reliquat           AS reliquat,
        CONCAT(ua.nomUser, ' ', ua.prenomUser) AS agent_liquidation,
        (SELECT COUNT(*) FROM depense_liquidation dl WHERE dl.idliquidation = l.idliquidation) AS nb_lignes_depense
    ")
            ->from('liquidation l')
            ->join('mission m', 'm.idmission = l.idmission')
            ->join('avance_mission am', 'am.idmission = m.idmission')
            ->join('user ua', 'ua.idUser = l.idagent', 'left')
            ->where('am.idbudget', $idbudget)
            ->order_by('l.date_liquidation', 'DESC')
            ->get()->result();

        // ────────────────────────────────────────────────────────────────
        // AJOUT : Approvisionnements liés au projet (seule modification)
        // ────────────────────────────────────────────────────────────────
        $approvisionnements = $this->db->select("
        a.idAppro,
        a.dateAppro,
        a.quantite,
        a.prix_unitaire,
        m.refmateriel,
        m.designationmateriel,
        m.typemateriel,
        u.denomination AS unite,
        pv.denomination_pv AS depot,
        f.nom_entr AS fournisseur
    ")
            ->from('appro a')
            ->join('materiel m', 'm.idmateriel = a.idmateriel', 'left')
            ->join('unite u', 'u.idunite = a.idunite', 'left')
            ->join('pointvente pv', 'pv.idPointVente = a.idPointVente', 'left')
            ->join('fournisseur f', 'f.idfournisseur = a.idfournisseur', 'left')
            ->where('a.idprojet', $budget->idprojet)
            ->order_by('a.dateAppro', 'DESC')
            ->get()
            ->result();

        // Réponse finale (on ajoute seulement 'approvisionnements')
        $responseData = (object) [
            'idbudget'           => $budget->idbudget,
            'ligne_budget'       => $budget->ligne_budget ?? '—',
            'designation_budget' => $budget->designation_budget ?? '—',
            'budget'             => $budget->budget ?? 0,
            'date_creation'      => $budget->date_creation ?? null,
            'codeprojet'         => $projet ? $projet->codeprojet : '—',
            'titreprojet'        => $projet ? $projet->titreprojet : '—',
            'reste'              => $reste,
            'missions'           => $missions,
            'liquidations'       => $liquidations,
            'approvisionnements' => $approvisionnements     // ← seule nouveauté
        ];

        echo json_encode([
            'success' => true,
            'data'    => $responseData
        ]);
    }
    public function details_budget($idbudget = 0)
    {
        if (!$idbudget || !is_numeric($idbudget)) {
            show_404();
        }

        $assets['title'] = 'Détails du budget';
        $assets['css']   = 'budget.css'; // ou crée un css spécifique si besoin
        $js['js']        = 'budget.js';

        // Budget de base
        $budgetData = $this->budget->donner($idbudget);
        if (empty($budgetData)) {
            show_404();
        }
        $data['budget'] = $budgetData[0];

        // Projet lié
        $projet = null;
        if (!empty($data['budget']->idprojet)) {
            $projetData = $this->projet->donner($data['budget']->idprojet);
            $data['projet'] = !empty($projetData) ? $projetData[0] : null;
        }

        // Calcul du reliquat (même formule que dans getbudget)
        $resteQuery = $this->db->select("
        (
            b.budget
            - COALESCE(ld.total_depense, 0)
            - COALESCE(ld.total_return_liquidation, 0)
            - COALESCE(rr.total_return_relique, 0)
        ) AS reste
    ", false)
            ->from('budget b')
            ->join("(
        SELECT am.idbudget,
               SUM(l.montant_depense) AS total_depense,
               SUM(l.montant_return)  AS total_return_liquidation
        FROM avance_mission am
        JOIN liquidation l ON l.idmission = am.idmission
        GROUP BY am.idbudget
    ) ld", 'ld.idbudget = b.idbudget', 'left')
            ->join("(
        SELECT am.idbudget,
               SUM(r.montantReturn) AS total_return_relique
        FROM avance_mission am
        JOIN liquidation l ON l.idmission = am.idmission
        JOIN relique r ON r.idLiquidation = l.idliquidation
        GROUP BY am.idbudget
    ) rr", 'rr.idbudget = b.idbudget', 'left')
            ->where('b.idbudget', $idbudget)
            ->get()->row();

        $data['reste'] = $resteQuery ? $resteQuery->reste : ($data['budget']->budget ?? 0);

        // Missions liées
        $data['missions'] = $this->db->select("
        m.idmission, m.objet_mission, m.lieu_mission, m.date_debut_mission, m.date_fin_mission,
        m.montant_avance, CONCAT(ua.nomUser, ' ', ua.prenomUser) AS agent,
        m.numero_asm, m.numero_om, ua.contact AS telagent
    ")
            ->from('avance_mission am')
            ->join('mission m', 'm.idmission = am.idmission')
            ->join('user ua', 'ua.idUser = m.idagent', 'left')
            ->where('am.idbudget', $idbudget)
            ->order_by('m.date_debut_mission', 'DESC')
            ->get()->result();

        // Liquidations liées
        $data['liquidations'] = $this->db->select("
        l.idliquidation,
        l.date_liquidation,
        l.date_reliquat,
        l.montant_depense            AS total_depense,
        l.montant_return             AS montant_retourne,
        l.montant_reliquat           AS reliquat,
        CONCAT(ua.nomUser, ' ', ua.prenomUser) AS agent_liquidation
    ")
            ->from('liquidation l')
            ->join('mission m', 'm.idmission = l.idmission')
            ->join('avance_mission am', 'am.idmission = m.idmission')
            ->join('user ua', 'ua.idUser = l.idagent', 'left')
            ->where('am.idbudget', $idbudget)
            ->order_by('l.date_liquidation', 'DESC')
            ->get()->result();

        // PAS DE REQUÊTE SUR approvisionnement → on passe un tableau vide
        $data['approvisionnements'] = [];
        // ... après les liquidations ...
        // Dans la méthode details_budget($idbudget) du contrôleur Budget

        // ... (après avoir chargé $data['budget'], $data['projet'], $data['missions'], $data['liquidations'], etc.)

        $this->load->model('ListeCommandeModel', 'listecommande');

        $commandes = [];

        // On récupère l'objet budget pour plus de clarté
        $budget = $data['budget'] ?? null;

        if (!empty($budget) && !empty($budget->idbudget)) {

            // Étape 1 : Récupérer toutes les commandes liées à ce budget
            // → On utilise une requête directe ici (car il n'y a pas encore de getCommandesByBudget)
            $commandes_raw = $this->db
                ->select('c.*, f.nom_entr, f.tel_fournisseur')
                ->from('commande c')
                ->join('fournisseur f', 'f.idfournisseur = c.idfournisseur', 'left')
                ->where('c.idbudget', $budget->idbudget)
                ->where('c.idadmin', $_SESSION['idadmin'])
                ->order_by('c.datecommande', 'DESC')
                ->get()
                ->result();

            // Étape 2 : Pour chaque commande, on charge son panier avec la fonction existante
            foreach ($commandes_raw as $cmd) {
                // On utilise getPanierByCommande() qui existe déjà
                $cmd->cmpanier = $this->listecommande->getPanierByCommande($cmd->idcommande);

                // Optionnel : on peut aussi enrichir avec getFact() si besoin d'autres infos
                // $full_cmd = $this->listecommande->getFact($cmd->idcommande);
                // Mais ici on garde simple

                $commandes[] = $cmd;
            }
        }

        $data['commandes'] = $commandes;
        // Chargement de la vue
        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['budget' => true]);
        $this->load->view('templates/tete');
        $this->load->view('details_budget', $data);
        $this->load->view('templates/footer', $js);
        // Chargement de la vue
        //     $this->load->view('templates/header', $assets);
        //     $this->load->view('templates/sidebar', ['budget' => true]);
        //     $this->load->view('templates/tete');
        //     $this->load->view('details_budget', $data);
        //     $this->load->view('templates/footer', $js);
    }
}
