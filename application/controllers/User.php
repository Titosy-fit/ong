<?php

class User extends CI_Controller
{
    private $postes;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'user');
        $this->load->model('PosteModel', 'poste');
        $this->load->model("DispatchModel", "dispatch");
        $this->load->model('PointVente_model', 'pv');

        $this->postes = $this->poste->getposte();
        if ($_SESSION['user_type'] != 'admin') {
            redirect('stock');
        }
    }

    // public function index()
    // {
    //     $this->jail();
    //     if ($_SESSION['user_type'] == 'admin') {
    //         $config = array();
    //         $config["base_url"] = base_url() . 'user';
    //         $config["total_rows"] = $this->user->get_count();
    //         $config["per_page"] = PAGINATION;
    //         $config['page_query_string'] = TRUE;
    //         $config['query_string_segment'] = 'page';
    //         $config['use_page_numbers'] = TRUE;
    //         $this->pagination->initialize($config);

    //         $page = isset($_GET['page']) ? $_GET['page'] : 0;

    //         $datapag["links"] = $this->pagination->create_links();

    //         if ((int)$page == 0) {
    //             $start = (int)$page * (int)$config["per_page"];
    //         } else {
    //             $start = ((int)$page - 1) * (int)$config["per_page"];
    //         }
    //         $datapag['user'] = $this->user->get_authors($config["per_page"], $start);


    //         $data['title'] = 'Utilisateur';
    //         $data['css'] = 'stock.css';
    //         $js['js'] = 'user.js';

    //         $pv = $this->pv->getAllPv();

    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/sidebar', ['user' => true]);
    //         $this->load->view('templates/tete');
    //         $this->load->view('user', [
    //             'data' => $datapag,
    //             'pv' => $pv , 
    //             'postes' => $this->postes 
    //         ]); 
    //         $this->load->view('templates/footer', $js);
    //     } else {
    //         redirect('dispatch');
    //     }


    //     // Utility::set_notification( ) ; 

    // }
    public function index()
    {
        $this->jail();

        if ($_SESSION['user_type'] !== 'admin') {
            redirect('dispatch');  // ou 'stock' selon ta logique précédente
        }

        // Configuration de la pagination
        $config = [];
        $config["base_url"]             = base_url() . 'user';
        $config["total_rows"]           = $this->user->get_count();
        $config["per_page"]             = PAGINATION;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = TRUE;

        $this->pagination->initialize($config);

        // Gestion de la page courante
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $start = ($page - 1) * $config["per_page"];

        $datapag = [];
        $datapag["links"] = $this->pagination->create_links();
        $datapag['user']  = $this->user->get_authors($config["per_page"], $start);

        // Chargement des projets (remplace l'ancien chargement des PV)
        $this->load->model('ProjetModel', 'projet');
        $projets = $this->projet->getAllForSelect();

        // (Optionnel) Si tu as encore besoin des PV temporairement pour debug
        // $pv = $this->pv->getAllPv();

        // Données pour la vue
        $data = [
            'title' => 'Utilisateur',
            'css'   => 'stock.css'
        ];

        $js = ['js' => 'user.js'];

        // Chargement des vues
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['user' => true]);
        $this->load->view('templates/tete');

        $this->load->view('user', [
            'data'     => $datapag,
            'projets'  => $projets,           // ← Résout l'erreur Undefined variable $projets
            'postes'   => $this->postes,

            // Optionnel : si certaines parties de la vue utilisent encore $pv
            // 'pv'    => $pv ?? [],
        ]);

        $this->load->view('templates/footer', $js);
    }

    // public function registerUser()
    // {
    //     $nom = strip_tags(trim($_POST['nom']));
    //     $prenom = strip_tags(trim($_POST['prenom']));
    //     $adresse = strip_tags(trim($_POST['adresse']));
    //     $email = strip_tags(trim($_POST['email']));
    //     $pv = strip_tags(trim($_POST['pv']));
    //     $numero = strip_tags(trim($_POST['numero']));
    //     $poste = strip_tags(trim($_POST['poste']));

    //     $idadmin = 0  ; 

    //     if ( isset( $_SESSION["idadmin"]) ) {
    //         $idadmin = $_SESSION['idadmin'] ; 
    //     }

    //     $mail = $this->user->verifMail($email);

    //     $num = $this->user->verifNum($numero);

    //     if (count($mail) > 0) {
    //         $this->session->set_flashdata('mail', 'Ajout réussie'); 
    //     } elseif (count($num) > 0) {
    //         $this->session->set_flashdata('num', 'Ajout réussie');
    //     } elseif (empty($pv)){
    //         $this->session->set_flashdata('adrres', 'Ajout réussie');
    //     } else {
    //         $data = [
    //             'nomUser' => $nom, 
    //             'prenomUser' => $prenom,
    //             'contact' => my_trim($numero),
    //             'adress' => $adresse,
    //             'idposte' => $poste,
    //             'mail' => $email,
    //             'idPointVente' => $pv,
    //             'pass' => hash_it('123456') , 
    //             'idadmin' => $idadmin
    //         ];

    //         $this->user->insertUser($data);
    //         $this->session->set_flashdata('ajout', 'Ajout réussie');
    //     }
    //     redirect('user') ;
    // }
    public function registerUser()
    {
        $nom    = trim(strip_tags($this->input->post('nom', true)));
        $prenom = trim(strip_tags($this->input->post('prenom', true)));
        $adresse = trim(strip_tags($this->input->post('adresse', true)));
        $email  = trim(strip_tags($this->input->post('email', true)));
        $numero = trim(strip_tags($this->input->post('numero', true)));
        $numero_cin = trim(strip_tags($this->input->post('numero_cin', true)));
        $commune  = trim(strip_tags($this->input->post('commune', true)));
    $fokotany = trim(strip_tags($this->input->post('fokotany', true)));   // ← NOUVEAU
        $poste  = trim(strip_tags($this->input->post('poste', true)));
        $idprojet = trim($this->input->post('idprojet', true) ?? '');
        $roles  = trim($this->input->post('role', true) ?? '');

        $idadmin = $_SESSION['idadmin'] ?? 0;

        // ──────────────────────────────────────────────
        // Validation progressive – on arrête dès la première erreur
        // ──────────────────────────────────────────────
        $errors = [];

        if (empty($nom))     $errors[] = "Le nom est obligatoire.";
        if (empty($prenom))  $errors[] = "Le prénom est obligatoire.";
        if (empty($email))   $errors[] = "L'email est obligatoire.";
        if (empty($numero))  $errors[] = "Le numéro est obligatoire.";
        if (empty($numero_cin)) $errors[] = "Le numéro CIN est obligatoire."; 
        if (empty($commune))  $errors[] = "La commune est obligatoire.";
    if (empty($fokotany)) $errors[] = "Le fokotany est obligatoire.";  // ← NOUVEAU
        if (empty($poste))   $errors[] = "Veuillez sélectionner un poste.";
        if (empty($idprojet)) $errors[] = "Veuillez sélectionner un projet.";

        // Vérification doublons
        $mailExists = $this->user->verifMail($email);
        if (count($mailExists) > 0) {
            $errors[] = "Cet email est déjà utilisé.";
        }

        $numExists = $this->user->verifNum($numero);
        if (count($numExists) > 0) {
            $errors[] = "Ce numéro existe déjà.";
        }
if (count($this->user->verifCin($numero_cin)) > 0) {   // ← NOUVEAU
        $errors[] = "Ce CIN existe déjà.";
    }
        // ──────────────────────────────────────────────
        // S'il y a des erreurs → on redirige avec message
        // ──────────────────────────────────────────────
        if (!empty($errors)) {
            $this->session->set_flashdata('form_errors', $errors);
            $this->session->set_flashdata('old_input', $this->input->post()); // pour pré-remplir le formulaire
            redirect('user');
            exit;
        }

        // ──────────────────────────────────────────────
        // Tout est OK → insertion
        // ──────────────────────────────────────────────
        $data = [
            'nomUser'     => $nom,
            'prenomUser'  => $prenom,
            'contact'     => $numero,
            'numero_cin'  => $numero_cin,          // ← NOUVEAU
            'adress'      => $adresse,
            'commune'     => $commune,      // ← NOUVEAU
        'fokotany'    => $fokotany,
            'idposte'     => $poste,
            'mail'        => $email,
            'idprojet'    => $idprojet,
            'idPointVente' => 0,                 // ignoré
            'pass'        => hash_it('123456'),
            'idadmin'     => $idadmin,
            'roles'       => $roles
        ];

        $this->user->insertUser($data);

        $this->session->set_flashdata('ajout', 'Utilisateur ajouté avec succès.');
        redirect('user');
    }
    public function deleteUser()
    {

        $this->jail();
        $id = $this->input->post('id');
        $data = $this->user->deleteUser($id);

        echo json_encode([
            'success' => true,
        ]);

        $this->session->set_flashdata('delete', 'Ajout réussie');
    }

    public function DonnerUser()
    {
        $id = $this->input->post('id');

        $userData = $this->user->getAllUserById($id);

        // Charger les projets
        $this->load->model('ProjetModel', 'projet');
        $projets = $this->projet->getAllForSelect();

        echo json_encode([
            'success' => !empty($userData),
            'data'    => $userData ? $userData[0] : null,   // ← objet direct, pas tableau
            'projets' => $projets,                          // ← liste des projets
            'postes'  => $this->postes
        ]);
    }

    // public function DonnerUser()
    // {
    //     $id = $this->input->post('id');
    //     $data = $this->user->getAllUserById($id);
    //     $pv = $this->pv->getAllPv() ; 
    //     echo json_encode([
    //         'success' => true , 
    //         'data' => $data ,
    //         'pv' => $pv ,
    //         // 'projets' => $projets,               // ← très important pour le modal
    //         'postes' => $this->postes
    //     ]);
    // }

    // public function editUser()
    // {
    //     $id = strip_tags(trim($_POST['id_modif']));
    //     $nom = strip_tags(trim($_POST['nom_modif']));
    //     $prenom = strip_tags(trim($_POST['prenom_modif']));
    //     $adresse = strip_tags(trim($_POST['adresse_modif']));
    //     $numero = strip_tags(trim($_POST['numero_modif']));
    //     $poste = strip_tags(trim($_POST['poste']));
    //     $email = strip_tags(trim($_POST['email_modif']));
    //     $idPv_modif = strip_tags(trim($_POST['idPv_modif']));

    //     $data = [
    //         'nomUser' => $nom,
    //         'prenomUser' => $prenom,
    //         'contact' => my_trim($numero),
    //         'adress' => $adresse,
    //         'idposte' => $poste,
    //         'mail' => $email,
    //         'idPointVente' => $idPv_modif
    //     ];

    //     $this->user->updateUser($id, $data);
    //     $this->session->set_flashdata('edit', 'Ajout réussie');
    //     redirect('user') ;
    // }
    public function editUser()
{
    $id         = strip_tags(trim($this->input->post('id_modif')));
    $nom        = strip_tags(trim($this->input->post('nom_modif')));
    $prenom     = strip_tags(trim($this->input->post('prenom_modif')));
    $adresse    = strip_tags(trim($this->input->post('adresse_modif')));
    $commune    = strip_tags(trim($this->input->post('commune_modif')));
    $fokotany   = strip_tags(trim($this->input->post('fokotany_modif')));
    $numero     = strip_tags(trim($this->input->post('numero_modif')));
    $numero_cin = strip_tags(trim($this->input->post('numero_cin_modif')));   // ← NOUVEAU
    $poste      = strip_tags(trim($this->input->post('poste')));
    $email      = strip_tags(trim($this->input->post('email_modif')));
    $idprojet   = trim($this->input->post('idprojet_modif', true) ?? '');
    $roles      = trim($this->input->post('role_modif') ?? '');

    $data = [
        'nomUser'     => $nom,
        'prenomUser'  => $prenom,
        'contact'     => trim($numero),
        'numero_cin'  => $numero_cin,          // ← NOUVEAU
        'adress'      => $adresse,
        'commune'     => $commune,        // ← NOUVEAU
        'fokotany'    => $fokotany,
        'idposte'     => $poste,
        'mail'        => $email,
        'idprojet'    => $idprojet ?: null,
        'roles'       => $roles
    ];

    $this->user->updateUser($id, $data);
    $this->session->set_flashdata('edit', 'Utilisateur modifié avec succès.');
    redirect('user');
}

    public function verifUser()
    {
        $id         = $this->input->post('id');
        $numero     = strip_tags(trim($this->input->post('numero')));
        $numero_cin = strip_tags(trim($this->input->post('numero_cin')));
        $email      = strip_tags(trim($this->input->post('email')));

        $dataId = $this->user->verifyIfUserExiste($id);

        $tab     = [];
        $tab_cin = [];

        for ($i = 0; $i < count($dataId); $i++) {
            array_push($tab,     $dataId[$i]->contact);
            array_push($tab,     $dataId[$i]->mail);
            // Remplir $tab_cin avec les CIN des autres utilisateurs
            if (!empty($dataId[$i]->numero_cin)) {
                array_push($tab_cin, $dataId[$i]->numero_cin);
            }
        }

        $response = ['success' => true];

        if (in_array($numero, $tab)) {
            $response['numExiste'] = true;
            $response['success']   = false;
        }

        if (!empty($numero_cin) && in_array($numero_cin, $tab_cin)) {
            $response['cinExiste'] = true;
            $response['success']   = false;
        }

        if (in_array($email, $tab)) {
            $response['mailExiste'] = true;
            $response['success']    = false;
        }

        echo json_encode($response);
    }

    public function rechercheUser()
    {
        if ($_SESSION['user_type'] == 'admin') {
            $keyword = strip_tags(trim($_GET['recherche']));
            $_POST['post'] = $keyword;
            // * pagination * // 
            $config = array();
            $config["base_url"] = base_url() . 'rechercheUser';
            $config["total_rows"] = count($this->user->searchUser($keyword, '', ''));
            $config["per_page"] = PAGINATION;
            // $config["uri_segment"] = 2;
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'page';
            $config['reuse_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            $this->pagination->initialize($config);

            $page = (isset($_GET['page'])) ? $_GET['page'] : 0;

            $datapag["links"] = $this->pagination->create_links();

            if ((int)$page == 0) {
                $start = (int)$page * (int)$config["per_page"];
            } else {
                $start = ((int)$page - 1) * (int)$config["per_page"];
            }
            $datapag['user'] = $this->user->searchUser($keyword, $config["per_page"], $start);
            // * pagination * // 


            $this->jail();
            $data['title'] = 'Utilisateur';
            $data['css'] = 'stock.css';
            $js['js'] = 'user.js';

            $this->load->model('ProjetModel', 'projet');
            $projets = $this->projet->getAllForSelect();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['user' => true]);
            $this->load->view('templates/tete');
            $this->load->view('user', [
                'data'    => $datapag,
                'post'    => $_POST['post'],
                'projets' => $projets,
                'postes'  => $this->postes
            ]);
            $this->load->view('templates/footer', $js);
        } else {
            redirect('dispatch');
        }
    }

    public function mdpUser()
    {
        $id = $this->input->post('id');

        $data = array(
            'pass' => hash_it('123456'),
        );

        $this->user->mdpUser($id, $data);

        echo json_encode([
            'success' => true
        ]);

        $this->session->set_flashdata('effectuer', 'Ajout réussie');
    }


    // public function search_json()
    // {
    //     $recherche = $this->input->post('recherche');

    //     $this->db->select('user.*, poste.designation_poste');
    //     $this->db->from('user');
    //     $this->db->join('poste', 'poste.idposte = user.idposte', 'left');
    //     $this->db->where('user.idadmin', $_SESSION['idadmin']);

    //     if (!empty($recherche)) {
    //         $this->db->group_start();
    //         $this->db->like('nomUser',    $recherche, 'both');
    //         $this->db->or_like('prenomUser', $recherche, 'both');
    //         $this->db->or_like('contact',    $recherche, 'both');
    //         $this->db->or_like('mail',       $recherche, 'both');
    //         $this->db->or_like('numero_cin', $recherche, 'both');
    //         $this->db->group_end();
    //     }

    //     $this->db->order_by('nomUser', 'asc');
    //     $datas = $this->db->get()->result();

    //     if (count($datas)) {
    //         echo json_encode([
    //             'success' => true,
    //             'datas'   => $datas
    //         ]);
    //     } else {
    //         echo json_encode([
    //             'success' => false,
    //             'datas'   => []
    //         ]);
    //     }
    // }

    public function search_json()
{
    $recherche = trim($this->input->post('recherche') ?? '');

    $this->db->select('idUser, nomUser, prenomUser, adress, commune, fokotany, contact, mail, numero_cin');
    $this->db->from('user');
    $this->db->where('user.idadmin', $_SESSION['idadmin']);

    if (!empty($recherche)) {
        $this->db->group_start();
        $this->db->like('nomUser',    $recherche, 'both');
        $this->db->or_like('prenomUser', $recherche, 'both');
        $this->db->or_like('adress',     $recherche, 'both');
        $this->db->or_like('commune',    $recherche, 'both');
        $this->db->or_like('fokotany',   $recherche, 'both');
        $this->db->or_like('contact',    $recherche, 'both');
        $this->db->or_like('mail',       $recherche, 'both');
        $this->db->or_like('numero_cin', $recherche, 'both');
        $this->db->group_end();
    }

    $datas = $this->db->get()->result();

    echo json_encode([
        'success' => count($datas) > 0,
        'datas'   => $datas
    ]);
}
    public function admin_inscription()
    {
        $this->load->view('admin_inscription');
    }
}
