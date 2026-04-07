<?php
class Activite extends CI_Controller
{
    private $projets;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ActiviteModel', 'activite');
        
        $this->load->model('ProjetModel', 'projet');
        $this->projets = $this->projet->getpojet();

        

    }


    /**
     * Page pricipale du activite
     *
     * @return void
     */
    public function index()
    {

        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $datasall =  $this->activite->getactivite();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['activite'] = $this->activite->getactivite($page);

        $data['title'] = 'Projet';
        $data['css'] = 'activite.css';
        $js['js'] = 'activite.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['activite' => true]);
        $this->load->view('templates/tete');
        $this->load->view('activite', [
            'data' => $datapag,
            'nPages' => $nPages,
            'projets' => $this->projets,
            'current' => 1
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * Pagination
     *
     * @param integer $page
     * @return void
     */
    public function page($page = 0)
    {
        $datasall =  $this->activite->getactivite();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['activite'] = $this->activite->getactivite($page);

        $data['title'] = 'Projet';
        $data['css'] = 'activite.css';
        $js['js'] = 'activite.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['activite' => true]);
        $this->load->view('templates/tete');
        $this->load->view('activite', [
            'data' => $datapag,
            'nPages' => $nPages,
            'projets' => $this->projets,
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
        $titre  = '';
        if (isset($_POST['titre']) && $_POST['titre'] != '') {
            $titre = trim(strip_tags($_POST['titre']));
        }
        $idactivite  = '';
        if (isset($_POST['idactivite']) && $_POST['idactivite'] != '') {
            $idactivite = trim(strip_tags($_POST['idactivite']));
        }

        $teste = $this->activite->verify($titre, $idactivite);
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
        $projet  = '';
        if (isset($_POST['projet']) && $_POST['projet'] != '') {
            $projet = trim(strip_tags($_POST['projet']));
        }

        $teste = $this->activite->verify($titre);
        if (!count($teste)) {
            $data = [
                'titre_activite' => $titre,
                'date_debut_act' => $date_debut,
                'date_fin_act' => $date_fin,
                'idprojet' => $projet,
                'idadmin' => $_SESSION['idadmin']
            ];


            $this->activite->register($data);
            $this->session->set_userdata('added_projet',  true);
        }


        redirect('activite');
    }

    // donner activite par id 
    public function donner()
    {
        $idactivite  = '';
        if (isset($_POST['idactivite']) && $_POST['idactivite'] != '') {
            $idactivite = trim(strip_tags($_POST['idactivite']));
        }

        $data = $this->activite->donner($idactivite);
        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data[0],
                'projets' => $this->projets,
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
        $data = $this->activite->projet_json();
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
     * Modification
     *
     * @return void
     */
    public function edit()
    {

        $idactivite  = '';
        if (isset($_POST['idactivite']) && $_POST['idactivite'] != '') {
            $idactivite = trim(strip_tags($_POST['idactivite']));
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
        $projet  = '';
        if (isset($_POST['projet']) && $_POST['projet'] != '') {
            $projet = trim(strip_tags($_POST['projet']));
        }

        $teste = $this->activite->verify($titre, $idactivite);
        if (!count($teste)) {
            $data = [
                'titre_activite' => $titre,
                'date_debut_act' => $date_debut,
                'date_fin_act' => $date_fin,
                'idprojet' => $projet,
                'idadmin' => $_SESSION['idadmin']
            ];

            $this->activite->edit($data, $idactivite);
            $this->session->set_userdata('adit_projet',  true);
        }

        redirect('activite');
    }

    /**
     * effacer l'activite
     *
     * @return void
     */
    public function delete()
    {
        $idactivite  = '';
        if (isset($_POST['idactivite']) && $_POST['idactivite'] != '') {
            $idactivite = trim(strip_tags($_POST['idactivite']));
        }

        $this->activite->delete($idactivite);
        $this->session->set_userdata('delete_projet',  true);
    }

    /**
     * recherche dans activite
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
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '')
            $heure_fin .= '23:59:59';

        $mot = trim(strip_tags($_POST['mot']));
        $_POST['mot'] = $mot;

        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        $datasall =  $this->activite->getactivite_search($mot, $date_debut, $date_fin);

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['activite'] = $this->activite->getactivite_search($mot, $date_debut, $date_fin, $page);


        $data['title'] = 'Projet';
        $data['css'] = 'activite.css';
        $js['js'] = 'activite.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['activite' => true]);
        $this->load->view('templates/tete');
        $this->load->view('activite', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page,
            'projets' => $this->projets
        ]);
        $this->load->view('templates/footer', $js);
    }

    public function projets( $id = '' ){
        if ( $id != ''){
            $temps = [] ; 
            $projets_  = [] ; 
            if ( count( $this->projets )){
                foreach ($this->projets  as $key => $projet ) {
                    if ( $projet->idprojet == $id ){
                        $projets_ [] = $projet ; 
                    }
                }
                foreach ($this->projets  as $key => $projet ) {
                    if ( $projet->idprojet != $id ){
                        $projets_ [] = $projet ; 
                    }
                }
            }
            return $projets_ ; 
        }
        return $this->projets ; 
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

        $nPages = ceil(count($this->activite->searchProjet($idprojet)) / PAGINATION);
        $datapag['activite'] = $this->activite->searchProjet($idprojet, $page);


        $data['title'] = 'Projet';
        $data['css'] = 'activite.css';
        $js['js'] = 'activite.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['activite' => true]);
        $this->load->view('templates/tete');
        $this->load->view('activite', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page,
            'projets' => $this->projets( $idprojet )
        ]);
        $this->load->view('templates/footer', $js);
    }
}
