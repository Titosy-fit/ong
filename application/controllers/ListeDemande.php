<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListeDemande extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("BeneficiaireModel", "beneficiaire");
    $this->load->model("ProjetModel", "projet");
    $this->load->model('ListeModel', 'liste');
    $this->load->model('UserModel', 'user');
    $this->load->model('ListeDemandeModel', 'listedemande');
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
    $js['js'] = 'listedemande.js';
    $page = 1;


    $data  = $this->liste->getAllListe($page , 'emprunt');


    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->getAllListe( '' ,  'emprunt' )) / PAGINATION);
 
    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listedemande', [
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
    $js['js'] = 'listedemande.js';

    $data  = $this->liste->getAllListe($page , 'emprunt' );

    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->getAllListe( '' , 'emprunt')) / PAGINATION);

    $this->load->view('templates/header', $li);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listedemande', [
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


    $recherche = $this->liste->search($date_debut, $date_fin, $mot, $lieu, $page  ,'emprunt');
    $allpv = $this->liste->getAllPv();

    $nPages = ceil(count($this->liste->search($date_debut, $date_fin, $mot, $lieu , '' ,'emprunt' )) / PAGINATION);

    $assets['title'] = 'Liste';
    $assets['css'] = 'liste.css';
    $js['js'] = 'listedemande.js';

    $this->load->view('templates/header', $assets);
    $this->load->view('templates/sidebar', ["liste" => true]);
    $this->load->view('templates/tete');
    $this->load->view('listedemande', [
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

    $data = $this->liste->getFact($idfacture , 'emprunt');

    // echo '<pre>'  ;
    // var_dump( $data ) ; 
    // echo '</pre>' ; die  ; 


    $users =  $this->user->getAllUserById($data->idagent);
    $projet = $this->projet->donner($data->idprojet);


    $affichage = '<div class ="row w-100 m-auto " >';

    // Beneficiaire
    $affichage .= "<div class='col-4  pt-1'>";

    if (count($users) == 0) {

      $affichage .= "<p><span  style='display :inline-block ; width :150px ; '>Aucun agent.</span><span  '></span></p>";
    } else {

      $users = $users[0];
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Tel agent </span><span  '> : " . $users->contact . "</span></p>";

      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Nom </span> <span  '> : " . strtoupper($users->nomUser) . "</span></p>";
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Prénom </span> <span  '>  : "  . ucfirst($users->prenomUser) . "</span></p>";
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> CIN </span> <span  '>  : CIN: "  . ($users->numero_cin ?? "—") . "</span></p>";
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Fokontany </span> <span  '>  : "  . ($users->fokotany ?? "—") . "</span></p>";
      $affichage .= "<p><span  style='display :inline-block ; width :105px ; '> Commune </span> <span  '>  : "  . ($users->commune ?? "—") . "</span></p>";
    }

    $affichage .= "</div>";



    // Projet
    $affichage .= "<div class='col-6  pt-1 ms-2'>";

    if (count($projet) == 0) {

    //   $affichage .= "<p><span  style='display :inline-block ; width :150px ; '>Aucun bénéficiaire.</span><span  '></span></p>";
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
  /**
 * Vérifier si un numéro de demande existe et est valide
 */
public function verifier_demande()
{
    $num_demande = trim(strip_tags($this->input->post('num_demande')));
    
    if (empty($num_demande)) {
        echo json_encode(['success' => false, 'message' => 'Numéro de demande requis']);
        return;
    }
    
    // Vérifier que la demande existe et appartient à l'admin connecté
    $demande = $this->db->select('dispatch.idfacture, dispatch.Facture, dispatch.idadmin, 
                                   agent.nomUser, agent.prenomUser, agent.contact,
                                   agent.numero_cin, agent.fokotany, agent.commune')
                        ->from('dispatch')
                        ->join('user as agent', 'agent.idUser = dispatch.idagent', 'left')
                        ->where('dispatch.Facture', $num_demande)
                        ->where('dispatch.idadmin', $_SESSION['idadmin'])
                        ->where('dispatch.type_dispatch', 'emprunt')
                        ->get()
                        ->row();
    
    if ($demande) {
        echo json_encode([
            'success' => true,
            'message' => 'Demande trouvée',
            'idfacture' => $demande->idfacture,
            'agent' => [
                'nom' => $demande->nomUser ?? '',
                'prenom' => $demande->prenomUser ?? '',
                'nom_complet' => ($demande->nomUser && $demande->prenomUser) ? 
                                 $demande->nomUser . ' ' . $demande->prenomUser : '',
                'cin' => $demande->numero_cin ?? '',
                'fokontany' => $demande->fokontany ?? '',
                'commune' => $demande->commune ?? ''
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Ce numéro de demande n\'existe pas ou ne vous appartient pas'
        ]);
    }
}

/**
 * Enregistrer la saisie manuelle (AVEC ou SANS fichier)
 */
/**
 * Enregistrer la saisie manuelle (AVEC ou SANS fichier)
 */
public function save_saisie_manuelle()
{
    // Activer l'affichage des erreurs pour le débogage
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Vérifier la session
    if (!isset($_SESSION['idadmin'])) {
        echo json_encode(['success' => false, 'message' => 'Session expirée. Veuillez vous reconnecter.']);
        return;
    }
    
    $this->load->model('SaisieManuelleModel', 'saisie');
    
    $num_demande = trim(strip_tags($this->input->post('num_demande')));
    
    if (empty($num_demande)) {
        echo json_encode(['success' => false, 'message' => 'Numéro de demande requis']);
        return;
    }
    
    // Récupérer l'idfacture à partir du numéro de demande
    $demande = $this->db->select('idfacture')
                        ->from('dispatch')
                        ->where('Facture', $num_demande)
                        ->where('idadmin', $_SESSION['idadmin'])
                        ->get()
                        ->row();
    
    if (!$demande) {
        echo json_encode(['success' => false, 'message' => 'Demande introuvable']);
        return;
    }
    
    // Données de base
    $data = [
        'idfacture' => $demande->idfacture,
        'num_demande' => $num_demande,
        'entana_nozaraina' => trim(strip_tags($this->input->post('entana'))),
        'designation' => trim(strip_tags($this->input->post('designation'))),
        'isany' => (int)$this->input->post('isany'),
        'date_reception' => $this->input->post('date_reception'),
        'nom_prenom' => trim(strip_tags($this->input->post('nom'))),
        'cin' => trim(strip_tags($this->input->post('cin'))),
        'fokontany' => trim(strip_tags($this->input->post('fokontany'))),
        'commune' => trim(strip_tags($this->input->post('commune'))),
        'idadmin' => $_SESSION['idadmin']
    ];
    
    // Vérifier s'il y a un fichier à uploader
    $fichier_present = isset($_FILES['fichier']) && $_FILES['fichier']['name'] != '' && $_FILES['fichier']['error'] === UPLOAD_ERR_OK;
    
    if ($fichier_present) {
        $upload_dir = 'public/upload/saisies_manuelles/';
        $absolute_path = FCPATH . $upload_dir;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($absolute_path)) {
            if (!mkdir($absolute_path, 0777, true)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Impossible de créer le dossier d\'upload'
                ]);
                return;
            }
        }
        
        $filename = $_FILES['fichier']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $unique_name = time() . '_' . uniqid('saisie_', true) . '_' . $_SESSION['idadmin'] . '.' . $ext;
        $destination = $absolute_path . $unique_name;
        
        $uploaded = @move_uploaded_file($_FILES['fichier']['tmp_name'], $destination);
        
        if ($uploaded) {
            $data['nom_fichier'] = $unique_name;
            $data['nom_original'] = $filename;
            $data['type_fichier'] = $_FILES['fichier']['type'];
            $data['taille_fichier'] = $_FILES['fichier']['size'];
            $data['chemin_fichier'] = $upload_dir . $unique_name;
        } else {
            // Erreur lors de l'upload, on continue sans le fichier
            log_message('error', 'Échec upload fichier: ' . print_r($_FILES['fichier'], true));
        }
    }
    
    // Déterminer le type d'entrée
    $has_article = !empty($data['entana_nozaraina']) || !empty($data['designation']);
    $has_fichier = !empty($data['nom_fichier']);
    
    if ($has_article && $has_fichier) {
        $data['type_entree'] = 'manuel_fichier';
    } elseif ($has_fichier) {
        $data['type_entree'] = 'fichier';
    } else {
        $data['type_entree'] = 'manuel';
    }
    
    // Vérifier qu'il y a au moins quelque chose à enregistrer
    if (!$has_article && !$has_fichier) {
        echo json_encode(['success' => false, 'message' => 'Aucune donnée à enregistrer']);
        return;
    }
    
    $insert_id = $this->saisie->insert($data);
    
    if ($insert_id) {
        echo json_encode([
            'success' => true, 
            'message' => 'Saisie enregistrée avec succès', 
            'id' => $insert_id,
            'type' => $data['type_entree']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement en base de données']);
    }
}
}
