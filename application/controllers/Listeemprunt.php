<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Listeemprunt extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("BeneficiaireModel", "beneficiaire");
    $this->load->model("ProjetModel", "projet");
    $this->load->model('ListeempruntModel', 'listeemprunt');
    $this->load->model('Admin_model');
  }


  // ***************** UTILE *********** //

  /**
   * page principale 
   *
   * @return void
   */
  public function index()
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'listeemprunt.css';
    $js['js'] = 'listeemprunt.js';
    $page = 1;


    $data  = $this->listeemprunt->getAllListe($page);

    // echo '<pre>' ; 
    // var_dump( $data ) ; 
    // echo '</pre>' ; die  ; 

    $allpv = $this->listeemprunt->getAllPv();

    $nPages = ceil(count($this->listeemprunt->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["listeemprunt" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listeemprunt', [
      'data' => $data,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

  /**
   * pagination
   *
   * @param integer $page
   * @return void
   */
  public function page($page = 1)
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'listeemprunt.css';
    $js['js'] = 'listeemprunt.js';

    $data  = $this->listeemprunt->getAllListe($page);

    $allpv = $this->listeemprunt->getAllPv();

    $nPages = ceil(count($this->listeemprunt->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["listeemprunt" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listeemprunt', [
      'data' => $data,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

  /**
   * rechecher une distribution
   *
   * @param integer $page
   * @return void
   */
  public function search($page = 1)
  {

    // date
    $date_debut = trim(strip_tags($_POST['date_debut']));
    $heure_debut = trim(strip_tags($_POST['heure_debut']));

    $_POST['date_debut'] = $date_debut;
    $_POST['heure_debut'] = $heure_debut;
 
    if ($heure_debut != '' && $date_debut != '')
      $heure_debut .= ':00';


    $date_fin = trim(strip_tags($_POST['date_fin']));
    $heure_fin = trim(strip_tags($_POST['heure_fin']));


    $_POST['date_fin'] = $date_fin;
    $_POST['heure_fin'] = $heure_fin;

    if ($heure_fin != '' && $date_fin != '')
      $heure_fin .= ':00';

    if ($date_debut != '')
      $date_debut .= ' ' . $heure_debut;
    if ($date_fin != '')
      $date_fin .= ' ' . $heure_fin;
    // date


    $mot = strip_tags(trim($_POST['recherche']));
    $lieu = strip_tags(trim($_POST['lieu']));

    $_POST['mot'] = $mot;
    $_POST['lieu'] = $lieu;


    $recherche = $this->listeemprunt->search($date_debut, $date_fin, $mot, $lieu, $page);
    $allpv = $this->listeemprunt->getAllPv();

    $nPages = ceil(count($this->listeemprunt->search($date_debut, $date_fin, $mot, $lieu)) / PAGINATION);

    $assets['title'] = 'Liste';
    $assets['css'] = 'listeemprunt.css';
    $js['js'] = 'listeemprunt.js';

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ["listeemprunt" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listeemprunt', [
      'data' => $recherche,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }


}
