<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Liste extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("BeneficiaireModel", "beneficiaire");
    $this->load->model("ProjetModel", "projet");
    $this->load->model('ListeModel', 'liste');
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
    $li['css'] = 'liste.css';
    $js['js'] = 'liste.js';
    $page = 1;


    $data  = $this->liste->getAllListe($page);

    // echo '<pre>' ; 
    // var_dump( $data ) ; 
    // echo '</pre>' ; die  ; 

    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('liste', [
      'data' => $data,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

  public function page($page = 1)
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'liste.css';
    $js['js'] = 'liste.js';

    $data  = $this->liste->getAllListe($page);

    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('liste', [
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
    // if ($heure_debut == '' && $date_debut != '')
    //   $heure_debut = '00:00:00';
    // else 
    if ($heure_debut != '' && $date_debut != '')
      $heure_debut .= ':00';


    $date_fin = trim(strip_tags($_POST['date_fin']));
    $heure_fin = trim(strip_tags($_POST['heure_fin']));


    $_POST['date_fin'] = $date_fin;
    $_POST['heure_fin'] = $heure_fin;
    // if ($heure_fin == '' && $date_fin != '')
    //   $heure_fin = '00:00:00';
    // else 
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


    $recherche = $this->liste->search($date_debut, $date_fin, $mot, $lieu, $page);
    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->search($date_debut, $date_fin, $mot, $lieu)) / PAGINATION);

    $assets['title'] = 'Liste';
    $assets['css'] = 'liste.css';
    $js['js'] = 'liste.js';

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('liste', [
      'data' => $recherche,
      'pv' => $allpv,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }


  /**
   * delete
   *
   * @return void
   */
  public function deleteListe()
  {
    $idfacture = trim(strip_tags($_POST['idfacture']));

    $this->session->set_flashdata('delete', 'success');
    // panier
    $this->liste->deletePanier($idfacture);
    // dispatch
    $this->liste->deleteVente($idfacture);

    echo json_encode([
      'success' => true
    ]);
  }

  /**
   * details sur les distribution par idfacture
   *
   * @return void
   */
  public function getDetails()
  {
    $idfacture = '';
    if (isset($_POST['idfacture']) && $_POST['idfacture'] != '') {
      $idfacture = strip_tags(trim($_POST['idfacture']));
    }

    $data = $this->liste->getFact($idfacture);


    $beneficiaires =  $this->beneficiaire->donner($data->idbeneficiaire);
    $projet = $this->projet->donner($data->idprojet);


    $affichage = '<div class ="row w-100 m-auto " >';

    // Beneficiaire
    $affichage .= "<div class='col-4  pt-1'>";

    if (count($beneficiaires) == 0) {

      $affichage .= "<p><span  style='display :inline-block ; width :150px ; '>Aucun bénéficiaire.</span><span  '></span></p>";
    } else {

      $beneficiaires = $beneficiaires[0];
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> N° bénéficiaire </span><span  '> : " . $beneficiaires->telbene . "</span></p>";

      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Nom </span> <span  '> : " . strtoupper($beneficiaires->nombene) . "</span></p>";
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Prénom </span> <span  '>  : "  . ucfirst($beneficiaires->prenombene) . "</span></p>";
    }

    $affichage .= "</div>";



    // Projet
    $affichage .= "<div class='col-6  pt-1 ms-2'>";

    if (count($projet) == 0) {

      $affichage .= "<p><span  style='display :inline-block ; width :150px ; '>Aucun bénéficiaire.</span><span  '></span></p>";
    } else {

      $projet = $projet[0];

      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Code du projet </span> <span  '> : " . strtoupper($projet->codeprojet) . "</span></p>";
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Titre du projet </span> <span  '>  : "  . ucfirst($projet->titreprojet) . "</span></p>";
    }

    $affichage .= "</div>";










    $affichage .= '</div>';


    $affichage .= '<div style="border-top : 1px solid #cfcfcfcf " class ="row w-100  m-auto pt-1 ">';

    // echo '<pre>' ;
    // var_dump( $data ) ; 
    // echo '</pre>' ; die ;
    $affichage .= '<div class="_tableau">';
    $affichage .= '<table class="table">';
    $affichage .= '<thead class="table-info">';
    $affichage .= '<tr>  
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th>Déscription</th>
                    <th>Quantité</th>
                  </tr>
                </thead>
              <tbody>';

    foreach ($data->panier as $key => $panier) {
      $affichage .= "<tr>";
      $affichage .= "<td>" . $panier->refmateriel . "</td>";
      $affichage .= "<td>" . $panier->designationmateriel . "</td>";
      $affichage .= "<td>" . $panier->fiche . "</td>";


      if ($panier->quantite > 1) {
        if (isset($panier->denomination)) {
          $affichage .= "<td>" . $panier->quantite . ' ' . $panier->denomination .  "(s) </td>";
        } else {
          $affichage .= "<td>" . $panier->quantite . "</td>";
        }
      } else {
        $affichage .= "<td>" . $panier->quantite . "</td>";
      }

      $affichage .= "</tr>";
    }

    $affichage .= '</tbody>
      </table>
      </div class>';

    $affichage .= '<div class ="_boutton " >';
    $affichage .= "<div class='bg-success p-2 text-center text-light ' style ='border-radius:1px'>";

    if ($data->idUser == null) {
      $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Le distributeur </span><span  '> : Admin</span></p>";
    } else {
      $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Le distributeur  </span><span  '> : " . $data->nomUser . " " .  $data->prenomUser  . "</span></p>";
    }

    $affichage .= "</div>";
    $affichage .= "<div class='bg-info p-2 text-light text-center ' style ='border-radius:1px'>";
    $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Dépôt </span> <span  '> : " . $data->denomination_pv . ' ' . $data->adressPv . "</span></p>";
    $affichage .= "</div>";
    $affichage .= "</div>";

    echo $affichage;
  }

  public function facture($fact)
  {
    $info = $this->liste->getAllInfo($fact);
    $idfacture = '';
    if (count($info) > 0) {
      $idfacture = $info[0]->idfacture;
    }

    $tous = $this->liste->getFacture($idfacture);

    $admin = $this->Admin_model->getuseractive($_SESSION['idadmin']);


    $total['ht'] = $tous->montant_payer;
    $TVA = $total['ht'] * 20 / 100;
    $total['tva'] = $TVA;
    $total['ttc'] = $TVA + $total['ht'];


    // echo '<pre>' ; 
    // var_dump( $tous ) ; 
    // echo '</pre>' ; die  ; 

    $this->load->view('facture', [
      'fact' => $tous,
      'total' => $total,
      'admin' => $admin
    ]);
  }

  public function getInfoFact()
  {
    $idfacture = strip_tags(trim($_POST['idfacture']));

    $res = $this->liste->getAllInfo($idfacture);

    if (count($res) > 0) {
      echo json_encode([
        'success' => true,
        'data' => $res[0]->refmateriel
      ]);
    } else {
      echo json_encode([
        'success' => false
      ]);
    }
  }
}
