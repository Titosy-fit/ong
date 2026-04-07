<?php
class Poste extends CI_Controller
{
    private $projets ; 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('PosteModel', 'poste');

        $this->projets = $this->projet->getpojet() ; 

        if (  $_SESSION['user_type'] != 'admin'){
            redirect('stock') ; 
        }

    }


    /**
     * Page pricipale du poste
     *
     * @return void
     */
    public function index()
    {

        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $datasall =  $this->poste->getposte();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['poste'] = $this->poste->getposte($page);

        $data['title'] = 'Projet';
        $data['css'] = 'poste.css';
        $js['js'] = 'poste.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['poste' => true]);
        $this->load->view('templates/tete');
        $this->load->view('poste', [
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
        $datasall =  $this->poste->getposte();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['poste'] = $this->poste->getposte($page);

        $data['title'] = 'Projet';
        $data['css'] = 'poste.css';
        $js['js'] = 'poste.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['poste' => true]);
        $this->load->view('templates/tete');
        $this->load->view('poste', [
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
        $designation  = '';
        if (isset($_POST['designation']) && $_POST['designation'] != '') {
            $designation = trim(strip_tags($_POST['designation']));
        }
        $idposte  = '';
        if (isset($_POST['idposte']) && $_POST['idposte'] != '') {
            $idposte = trim(strip_tags($_POST['idposte']));
        }

        $teste = $this->poste->verify($designation, $idposte);
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

        $designation  = '';
        if (isset($_POST['designation']) && $_POST['designation'] != '') {
            $designation = trim(strip_tags($_POST['designation']));
        }
        $description  = '';
        if (isset($_POST['description']) && $_POST['description'] != '') {
            $description = trim(strip_tags($_POST['description']));
        }

        $teste = $this->poste->verify($designation);
        if (!count($teste)) {
            $data = [
                'designation_poste' => $designation,
                'description_poste' => $description,
                'idadmin' => $_SESSION['idadmin']
            ];


            $this->poste->register($data);
            $this->session->set_userdata('added_projet',  true);
        }

        redirect('fonction');
    }

    /**
     * donner
     *
     * @return void
     */
    public function donner()
    {
        $idposte  = '';
        if (isset($_POST['idposte']) && $_POST['idposte'] != '') {
            $idposte = trim(strip_tags($_POST['idposte']));
        }

        $data = $this->poste->donner($idposte);
        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data[0] ,  
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
        $data = $this->poste->projet_json();
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

        $designation  = '';
        if (isset($_POST['designation']) && $_POST['designation'] != '') {
            $designation = trim(strip_tags($_POST['designation']));
        }
        $description  = '';
        if (isset($_POST['description']) && $_POST['description'] != '') {
            $description = trim(strip_tags($_POST['description']));
        }
        $idposte  = '';
        if (isset($_POST['idposte']) && $_POST['idposte'] != '') {
            $idposte = trim(strip_tags($_POST['idposte']));
        }

        $teste = $this->poste->verify($description , $idposte);
        if (!count($teste)) {
            $data = [
                'designation_poste' => $designation,
                'description_poste' => $description,
            ];

            $this->poste->edit($data, $idposte);
            $this->session->set_userdata('adit_projet',  true);
        }
        
        redirect('poste');
    }

    /**
     * effacer l'poste
     *
     * @return void
     */
    public function delete()
    {
        $idposte  = '';
        if (isset($_POST['idposte']) && $_POST['idposte'] != '') {
            $idposte = trim(strip_tags($_POST['idposte']));
        }

        $this->poste->delete($idposte);
        $this->session->set_userdata('delete_projet',  true);
    }

    /**
     * recherche dans poste
     *
     * @param integer $page
     * @return void
     */ 
    public function search( $page = 1 )
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

        $datasall =  $this->poste->getposte_search( $mot , $date_debut , $date_fin );

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['poste'] = $this->poste->getposte_search($mot , $date_debut , $date_fin , $page );


        $data['title'] = 'Projet';
        $data['css'] = 'poste.css';
        $js['js'] = 'poste.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['poste' => true]);
        $this->load->view('templates/tete');
        $this->load->view('poste', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page  , 
            'projets' => $this->projets 
        ]);
        $this->load->view('templates/footer', $js);
    }


    
}
