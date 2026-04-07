<?php
class Projet extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('ActiviteModel', 'activite');
        $this->load->model('PointVente_model', 'pv');
    }


    /**
     * Page pricipale du projet
     *
     * @return void
     */
    public function index()
    {

        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $datasall =  $this->projet->getpojet();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['projet'] = $this->projet->getpojet($page);

        $data['title'] = 'Projet';
        $data['css'] = 'projet.css';
        $js['js'] = 'projet.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['projet' => true]);
        $this->load->view('templates/tete');
        $this->load->view('projet', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => 1
        ]);
        $this->load->view('templates/footer', $js);
    }
    private function convertDate($dateStr) {
        $date = DateTime::createFromFormat('d/m/Y', trim($dateStr));
        if ($date) {
            return $date->format('Y-m-d H:i:s');
        } else {
            return null; // ou '1970-01-01 00:00:00' si tu veux un défaut
        }
    }

   public function import_csv() {
    $this->load->helper(array('form', 'url'));

    if (isset($_POST['import']) && !empty($_FILES['csv_file']['name'])) {
        $file = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($file, 'r')) !== FALSE) {
            $data = [];
            $firstRow = true;
            $nombre = 0;
            while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
                if ($firstRow) {
                    $firstRow = false; // ignorer l'en-tête
                    continue;
                }

                // ✅ Vérification du nombre de colonnes
                if (count($row) != 5) {
                    fclose($handle);
                    $this->session->set_userdata('wrong_columns', true);
                    redirect('projet');
                    exit;
                }

                $teste = $this->projet->projetVerify($row[0]);
                if (!count($teste)) {
                    $data[] = [
                        'codeprojet'   => $row[0],
                        'titreprojet'  => $row[1],
                        'date_debut'   => $this->convertDate($row[2]),
                        'date_fin'     => $this->convertDate($row[3]),
                        'regionprojet' => $row[4],
                        'idadmin'      => 1
                    ];
                    $nombre++;
                }
            }

            fclose($handle);

            if (!empty($data)) {
                $this->projet->insert_csv($data);

                $this->session->set_userdata('added_projet', true);
                redirect('projet');
            } else {
                $this->session->set_userdata('vide', true);
                redirect('projet');
            }
        } else {
            echo "Impossible d'ouvrir le fichier.";
        }
    } else {
        $this->session->set_userdata('blank_file', true);
        redirect('projet');
    }
}

    /**
     * Pagination
     *
     * @param integer $page
     * @return void
     */
    public function page($page = 0)
    {
        $datasall =  $this->projet->getpojet();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['projet'] = $this->projet->getpojet($page);

        $data['title'] = 'Projet';
        $data['css'] = 'projet.css';
        $js['js'] = 'projet.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['projet' => true]);
        $this->load->view('templates/tete');
        $this->load->view('projet', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page
        ]);
        $this->load->view('templates/footer', $js);
    }


    /**
     * verification du doublant
     *
     * @return void
     */
    public function verify()
    {
        $code  = '';
        if (isset($_POST['code']) && $_POST['code'] != '') {
            $code = trim(strip_tags($_POST['code']));
        }
        $idprojet  = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = trim(strip_tags($_POST['idprojet']));
        }

        $teste = $this->projet->projetVerify($code, $idprojet);
        if (count($teste)) {
            echo json_encode([
                'success' => false
            ]);
        } else {
            echo json_encode([
                'success' => true
            ]);
        }
    }

    // enregistrement 
    public function register()
    {
        $code  = '';
        if (isset($_POST['code']) && $_POST['code'] != '') {
            $code = trim(strip_tags($_POST['code']));
        }
        $titre  = '';
        if (isset($_POST['titre']) && $_POST['titre'] != '') {
            $titre = trim(strip_tags($_POST['titre']));
        }
        $date_debut  = '';
        if (isset($_POST['date_debut']) && $_POST['date_debut'] != '') {
            $date_debut = trim(strip_tags($_POST['date_debut']));
        }
        $date_fin  = '';
        if (isset($_POST['date_fin']) && $_POST['date_fin'] != '') {
            $date_fin = trim(strip_tags($_POST['date_fin']));
        }
        $region  = '';
        if (isset($_POST['region']) && $_POST['region'] != '') {
            $region = trim(strip_tags($_POST['region']));
        }

        $teste = $this->projet->projetVerify($code);
        if (!count($teste)) {
            $data = [
                'codeprojet' => $code,
                'titreprojet' => $titre,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'regionprojet' => $region,
                'idadmin' => $_SESSION['idadmin']
            ];


            $this->projet->register($data);
            $this->session->set_userdata('added_projet',  true);
            $this->session->set_flashdata('projectadded', 'Données enregistrées avec succès !');
        }


        redirect('projet');
    }

    // donner projet par id 
    public function donner()
    {
        $idprojet  = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = trim(strip_tags($_POST['idprojet']));
        }

        $data = $this->projet->donner($idprojet);
        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data[0]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $data
            ]);
        }
    }
    
    public function projet_json()
    {
        $data = $this->projet->projet_json();
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

    // modification 
    public function edit()
    {
        $code  = '';
        if (isset($_POST['code']) && $_POST['code'] != '') {
            $code = trim(strip_tags($_POST['code']));
        }
        $idprojet  = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = trim(strip_tags($_POST['idprojet']));
        }
        $titre  = '';
        if (isset($_POST['titre']) && $_POST['titre'] != '') {
            $titre = trim(strip_tags($_POST['titre']));
        }
        $date_debut  = '';
        if (isset($_POST['date_debut']) && $_POST['date_debut'] != '') {
            $date_debut = trim(strip_tags($_POST['date_debut']));
        }
        $date_fin  = '';
        if (isset($_POST['date_fin']) && $_POST['date_fin'] != '') {
            $date_fin = trim(strip_tags($_POST['date_fin']));
        }
        $region  = '';
        if (isset($_POST['region']) && $_POST['region'] != '') {
            $region = trim(strip_tags($_POST['region']));
        }

        $teste = $this->projet->projetVerify($code, $idprojet);
        if (!count($teste)) {
            $data = [
                'codeprojet' => $code,
                'titreprojet' => $titre,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'regionprojet' => $region,
                'idadmin' => $_SESSION['idadmin']
            ];

            $this->projet->edit($data, $idprojet);
            $this->session->set_userdata('adit_projet',  true);
        }
        redirect('projet');
    }

    /**
     * suppression
     *
     * @return void
     */
    public function delete()
    {
        
        $idprojet  = '';
        if (isset($_POST['idprojet']) && $_POST['idprojet'] != '') {
            $idprojet = trim(strip_tags($_POST['idprojet']));
        }

        $this->projet->delete($idprojet);
        $this->session->set_userdata('delete_projet',  true);
    }

    // recherche  
    public function search( $page = 1 )
    {
        
        $mot = trim(strip_tags($_POST['mot']));
        

     

        $datasall =  $this->projet->getpojet_search( $mot , null , null );

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['projet'] = $this->projet->getpojet_search($mot , null , null , $page );


        $data['title'] = 'Projet';
        $data['css'] = 'projet.css';
        $js['js'] = 'projet.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['projet' => true]);
        $this->load->view('templates/tete');
        $this->load->view('projet', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page 
        ]);
        $this->load->view('templates/footer', $js);
    }


    /**
     * Prendre le projet par code
     *
     * @return void
     */
    public function bycode_json( ){
        // etoe
        $codeprojet  = '';
        if (isset($_POST['codeprojet']) && $_POST['codeprojet'] != '') {
            $codeprojet = trim(strip_tags($_POST['codeprojet']));
        }

        $projets = $this->projet->getbycode( $codeprojet ) ; 
        $activites = [] ; 
        if ( count( $projets )){
            $activites = $this->activite->getByidprojet( $projets[0]->idprojet ) ; 
        }

        $listeDep = $this->pv->selectDepotByProject( $projets[0]->idprojet ) ;
        if ( count( $projets )){
            echo json_encode([
                'success' => true , 
                'data' => $projets[0] , 
                'activites' => $activites , 
                'depot'=>$listeDep
            ]) ; 
        }else {
            echo json_encode([
                'success' => false , 
            ]) ; 
        }
    }
}
