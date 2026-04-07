<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Listeproforma extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('ListeProformaModel', 'liste');
    $this->load->model("ClientsModel", "Clients_model");
    $this->load->model('Admin_model');
  }


  // ***************** UTILE *********** //
  public function index()
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'listeproforma.css';
    $js['js'] = 'listeproforma.js';
    $page = 1;


    $data  = $this->liste->getAllListe($page);


    $nPages = ceil(count($this->liste->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listeproforma', [
      'data' => $data,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

  public function page($page = 1)
  {
    $this->jail();
    $li['title'] = 'Liste';
    $li['css'] = 'listeproforma.css';
    $js['js'] = 'listeproforma.js';

    $data  = $this->liste->getAllListe($page);


    $nPages = ceil(count($this->liste->getAllListe()) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listeproforma', [
      'data' => $data,
      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }

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

    $_POST['mot'] = $mot;


    $recherche = $this->liste->search($date_debut, $date_fin, $mot , $page);


    $nPages = ceil(count($this->liste->search($date_debut, $date_fin, $mot)) / PAGINATION);

    $assets['title'] = 'Liste';
    $assets['css'] = 'listeproforma.css';
    $js['js'] = 'listeproforma.js';

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listeproforma', [
      'data' => $recherche,

      'nPages' => $nPages,
      'current' => $page
    ]);
    $this->load->view('templates/footer', $js);
  }


  public function deleteListe()
  {
    $idproforma = trim(strip_tags($_POST['idproforma']));

    $this->session->set_flashdata('delete', 'success');
    // prpanier
    $this->liste->deletePanier($idproforma);
    // dispatch
    $this->liste->deleteVente($idproforma);

    echo json_encode([
      'success' => true
    ]);
  }
  // ***************** UTILE *********** //
  public function getDetails()
  {
    $idproforma = '';
    if (isset($_POST['idproforma']) && $_POST['idproforma'] != '') {
      $idproforma = strip_tags(trim($_POST['idproforma']));
    }

    // $montant = $this->liste->getTotal($facture);
    // $apayer = $this->liste->getApayer($facture);

    $data = $this->liste->getFact($idproforma);




    $client = $data->telClient;


    $affichage = '<div class ="row w-100 m-auto ">';
    if ($client != '') {
      $aboutallClient = $this->Clients_model->getClientByNumClient($client);


      $affichage .= "<div class='col-6'>";

      if (count($aboutallClient) == 0) {

        $affichage .= "<p><span  style='display :inline-block ; width :150px ; '> Numéro du client </span><span  '> :" . $client . "</span></p>";
      } else {

        $aboutClient = $aboutallClient[0];

        $affichage .= "<p><span  style='display :inline-block ; width :80px ; '> Tél client </span><span  '> : " . $client . "</span></p>";

        if ($aboutClient->nomClient != '') {
          $affichage .= "<p><span  style='display :inline-block ; width :80px ; '> Nom </span> <span  '> : " . strtoupper($aboutClient->nomClient) . "</span></p>";
          $affichage .= "<p><span  style='display :inline-block ; width :80px ; '> Prénom </span> <span  '>  : "  . ucfirst($aboutClient->prenomClient) . "</span></p>";
        } else {
          $affichage .= "<p><span  style='display :inline-block ; width :80px ; '> Entreprise </span> <span  '> : " . strtoupper($aboutClient->r_social) . "</span></p>";
        }
      }

      $affichage .= "</div>";
    }









    $affichage .= '</div>';

    if ($client != ''){
      $affichage .= '<div style="border-top : 1px solid #cfcfcfcf " class ="row w-100  m-auto pt-1 ">';

    }else {
      $affichage .= '<div  class ="row w-100  m-auto pt-1 ">';

    }
    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :110px ; '> Montant total  </span><span  '> : " . number_three($data->montant_total) . "</span></p>";
    $affichage .= '</div>';

    $affichage .= '<div class ="col-6">';
    $affichage .= "<p><span  style='display :inline-block ; width :140px ; font-weight : 600 ; '> Montant à payer  </span><span   text-decoration : underline'> : " . number_three($data->montant_payer) . "</span></p>";
    $affichage .= '</div>';
    $affichage .= '</div>';
    $affichage .= '<div  class ="row w-100  m-auto pt-1 ">';
    $affichage .= '<div class ="">';
    $affichage .= "<p><span  style='display :inline-block ;  '> Remise  </span><span > : " . number_three($data->montant_total - $data->montant_payer) . "</span></p>";
    $affichage .= '</div>';
    $affichage .= '</div>';




    // echo '<pre>' ;
    // var_dump( $data ) ; 
    // echo '</pre>' ; die ;
    $affichage .= '<div class="_tableau">';
    $affichage .= '<table class="table">';
    $affichage .= '<thead class="table-info">';
    $affichage .= '<tr>  
                    <th>Référence</th>
                    <th>Désignation</th>
                     
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Montant total</th>
                    <th>Montant à payer</th>
                    <th>Remise</th>
                  </tr>
                </thead>
              <tbody>';

    foreach ($data->prpanier as $key => $prpanier) {
      $affichage .= "<tr>";
      $affichage .= "<td>" . $prpanier->refmateriel . "</td>";
      $affichage .= "<td>" . $prpanier->designationmateriel . "</td>";
      $affichage .= "<td>" . number_three($prpanier->prixunitaire) . "</td>";
      if ($prpanier->quantite > 1) {
        if ($prpanier->denomination) {
          $affichage .= "<td>" . $prpanier->quantite . " </td>";
        } else {
          $affichage .= "<td>" . $prpanier->quantite . "</td>";
        }
      } else {
        $affichage .= "<td>" . $prpanier->quantite . "</td>";
      }
      $affichage .= "<td>" . number_three($prpanier->prixunitaire * $prpanier->quantite) . "</td>";
      $affichage .= "<td>" . number_three($prpanier->prixunitaire * $prpanier->quantite - $prpanier->remise) . "</td>";
      $affichage .= "<td>" . number_three($prpanier->remise) . "</td>";
      $affichage .= "</tr>";
    }

    $affichage .= '</tbody>
      </table>
      </div class>';

    $affichage .= '<div class ="_boutton " >';
    $affichage .= "<div class='bg-success p-2 text-center text-light ' style ='border-radius:1px'>";
    
    if ($data->idUser == null) {
      $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Vendeur(se) </span><span  '> : Admin</span></p>";
    } else {
      $affichage .= "<p class='m-0'><span  style='display :inline-block ; width :110px ; '>Vendeur(se)  </span><span  '> : " . $data->nomUser . " " .  $data->prenomUser  . "</span></p>";
    }
    
    $affichage .= "</div>";
    $affichage .= "</div>";

    echo $affichage;
  }

  public function facture($fact)
  {
    $info = $this->liste->getAllInfo($fact);
    $idproforma = '';
    if (count($info) > 0) {
      $idproforma = $info[0]->idproforma;
    }

    $tous = $this->liste->getFacture($idproforma);

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
    $idproforma = strip_tags(trim($_POST['idproforma']));

    $res = $this->liste->getAllInfo($idproforma);

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
