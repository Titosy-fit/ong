<?php
class Dashclient extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdministrateurModel', 'admin');
    }

    public function jail()
    {
        if (!isset($_SESSION['user'])) {
            redirect('admin');
        }
    }

    public function index()
    {

        $this->jail();
        $assets['title'] = 'Admin';
        $assets['css'] = 'dashclient.css';
        $js['js'] = 'dashboard.js';

        $data = $this->admin->getAllUser();

        $nPages = ceil(count($data) / PAGINATION);

        // LIMITE 
        $page = 1;
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
            if (isset($data[$i])) {
                $temps[] = $data[$i];
            }
        }
        $data  = $temps;

        // echo '<pre>' ;
        // var_dump( $data )  ; 
        // echo '</pre>'  ; die ; 


        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('dashclient', [
            'datas' => $data,
            'nPages' =>  $nPages,
            'current' => 1
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }
    public function page($page  = 1)
    {
        $assets['title'] = 'Admin';
        $assets['css'] = 'dashclient.css';
        $js['js'] = 'dashboard.js';


        $data = $this->admin->getAllUser();

        $nPages = ceil(count($data) / PAGINATION);
        $current = $page;



        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
            if (isset($data[$i])) {
                $temps[] = $data[$i];
            }
        }
        $data  = $temps;

        // echo '<pre>' ;
        // var_dump( $data )  ; 
        // echo '</pre>'  ; die ; 

        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('dashclient', [
            'datas' => $data,
            'nPages' =>  $nPages,
            'current' => $page
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }



    public function search($page  = 1)
    {
        // date
        $date_debut = trim(strip_tags($_POST['date_debut']));
        $heure_debut = trim(strip_tags($_POST['heure_debut']));

        $_POST['date_debut'] = $date_debut;
        $_POST['heure_debut'] = $heure_debut;
        // if ($heure_debut == '' && $date_debut != '')
        //     $heure_debut = '00:00:00';
        // else 
        if ($heure_debut != '' && $date_debut != '')
            $heure_debut .= ':00';


        $date_fin = trim(strip_tags($_POST['date_fin']));
        $heure_fin = trim(strip_tags($_POST['heure_fin']));


        $_POST['date_fin'] = $date_fin;
        $_POST['heure_fin'] = $heure_fin;
        // if ($heure_fin == '' && $date_fin != '')
        //     $heure_fin = '00:00:00';
        // else 
        if ($heure_fin != '' && $date_fin != '')
            $heure_fin .= ':59';

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '' )
            $heure_fin .= '23:59:59';


        $recherche = trim(strip_tags($_POST['recherche']));
        $_POST['mot'] = $recherche;


        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date




        $data = $this->admin->getAllClient_search($date_debut, $date_fin, $recherche);


        $assets['title'] = 'Admin';
        $assets['css'] = 'dashclient.css';
        $js['js'] = 'dashboard.js';

        $nPages = ceil(count($data) / PAGINATION);
        $current = $page;



        // LIMITE 
        if ($page == 0) {
            $realOffset = $page * PAGINATION;
        } else {
            $realOffset = ($page - 1) * PAGINATION;
        }

        $temps = [];
        for ($i = $realOffset; $i < PAGINATION + $realOffset; $i++) {
            if (isset($data[$i])) {
                $temps[] = $data[$i];
            }
        }
        $data  = $temps;

        // echo '<pre>' ;
        // var_dump( $data )  ; 
        // echo '</pre>'  ; die ; 

        $this->load->view('templates/header', $assets);
        $this->load->view(TEMPLATEADMIN . 'sidebar');
        $this->load->view(TEMPLATEADMIN . 'tete');
        $this->load->view('dashclient', [
            'datas' => $data,
            'nPages' =>  $nPages,
            'current' => $page
        ]);
        $this->load->view(TEMPLATEADMIN . 'footer', $js);
    }
}
