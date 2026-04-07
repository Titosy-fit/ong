<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relique extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->jail();
    $this->load->model('reliquatModel');
   
  }


  // ***************** UTILE *********** //

  /**
   * page principale 
   *
   * @return void
   */
  // public function index()
  // {
  //       $page = isset($_GET['page']) ? $_GET['page'] : 0;
  //      // $datasall =  $this->mission->getmission();

  //       //var_dump($this->mission->getmission()) ;
  //      /* $datapag['mission'] = $this->mission->getmission($page);
  //       $datapag['lien'] = $this->pagination('mission' , count( $datasall ));*/


  //       $data['title'] = 'Reliquat';
  //       $data['css'] = 'mission.css';
  //       $js['js'] = 'relique.js';

  //       $this->load->view('templates/header', $data);
  //       $this->load->view('templates/sidebar', ['relique' => true]);
  //       $this->load->view('templates/tete');
  //       $this->load->view('relique');
  //       $this->load->view('templates/footer', $js);
  // }
//   public function index()
//     {
//         $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

//         // Nombre total pour la pagination
//         $total = $this->reliquatModel->countAllReliquats();

//         // Nombre d'éléments par page (à définir dans config ou constante)
//         $per_page = PAGINATION ?? 10;

//         // Récupération des reliquats avec pagination
//         $data['reliquats'] = $this->reliquatModel->getReliquatsPaginated($page, $per_page);

//         // Génération de la pagination (à adapter selon ta fonction existante)
//         $data['pagination'] = $this->pagination('Relique', $total, $per_page);

//         $data['title'] = 'Reliquat';
//         $data['css']   = 'mission.css';
//         $js['js']      = 'relique.js';

//         $this->load->view('templates/header', $data);
//         $this->load->view('templates/sidebar', ['relique' => true]);
//         $this->load->view('templates/tete');
//         $this->load->view('relique', $data);   // ← on passe $data['reliquats'] et $data['pagination']
//         $this->load->view('templates/footer', $js);
//     }
public function index()
{
    $assets['title'] = 'Reliquat';
    $assets['css']   = 'mission.css';  // ou reliquat.css si tu as un fichier dédié
    $js['js']        = 'relique.js';

    // Vérification connexion (comme dans ton exemple Budget)
    if (empty($_SESSION['idadmin']) && empty($_SESSION['id_user'])) {
        redirect('auth');
        exit;
    }

    // Récupération page (GET)
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    if ($page < 1) $page = 1;

    // Nombre total pour la pagination
    $total = $this->reliquatModel->countAllReliquats();  // ton compteur existant

    // Nombre par page
    $per_page = PAGINATION ?? 10;

    // Données paginées
    $data['reliquats'] = $this->reliquatModel->getReliquatsPaginated($page, $per_page);

    // Génération pagination (même style que Budget)
    $data['pagination'] = $this->_pagination_links(base_url('Relique'), $total, $per_page);

    // Chargement des vues (structure identique à Budget)
    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ['relique' => true]);  // ← adapte le flag si besoin
    $this->load->view('templates/tete');
    $this->load->view('relique', $data);  // ← $data['reliquats'] et $data['pagination']
    $this->load->view('templates/footer', $js);
}
  public function register()
{
    $this->load->model('reliquatModel');

    // Récupérer les données POST
    $data = array(
        'idLiquidation'  => $this->input->post('idLiquidation'),
        'montantReturn'  => $this->input->post('montantReturn'),
        'dateReturn'     => $this->input->post('dateReturn'),
        'idMission'      => $this->input->post('idMission'),
        'resteReturn'    => $this->input->post('resteReturn'),
     
    );

    // Vérifications basiques (optionnel mais recommandé)
    if (empty($data['idMission']) || empty($data['idLiquidation']) || $data['montantReturn'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes']);
        return;
    }

    // Enregistrement via le modèle
    $id = $this->reliquatModel->register($data);

    if ($id) {
        echo json_encode(['success' => true, 'message' => 'Enregistré avec ID ' . $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'enregistrement']);
    }
}
  private function _pagination_links($base_url, $total_rows, $per_page = 10)
{
    $this->load->library('pagination');

    $config = array();

    $config['base_url']       = $base_url;
    $config['total_rows']     = $total_rows;
    $config['per_page']       = $per_page;
    $config['use_page_numbers'] = TRUE;
    $config['page_query_string'] = TRUE;   // ← important pour utiliser ?page=2
    $config['query_string_segment'] = 'page';

    // Bootstrap 4/5 style (comme dans l'image : centrée, Previous / numéros / Next)
    $config['full_tag_open']   = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    $config['full_tag_close']  = '</ul></nav>';

    $config['num_tag_open']    = '<li class="page-item">';
    $config['num_tag_close']   = '</li>';
    $config['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']   = '</span></li>';

    $config['attributes']      = array('class' => 'page-link');

    $config['prev_tag_open']   = '<li class="page-item">';
    $config['prev_tag_close']  = '</li>';
    $config['next_tag_open']   = '<li class="page-item">';
    $config['next_tag_close']  = '</li>';

    $config['first_tag_open']  = '<li class="page-item">';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']   = '<li class="page-item">';
    $config['last_tag_close']  = '</li>';

    $config['prev_link']       = 'Previous';
    $config['next_link']       = 'Next';

    // Option : flèches au lieu de texte (si tu préfères comme dans certaines images)
    // $config['prev_link']    = '&laquo;';
    // $config['next_link']    = '&raquo;';

    $config['num_links']       = 2;  // → affiche 2 numéros de chaque côté (ex: ... 3 4 5 6 ...)

    $this->pagination->initialize($config);

    return $this->pagination->create_links();
}
}
