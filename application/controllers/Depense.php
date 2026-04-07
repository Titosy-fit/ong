<?php
class Depense extends CI_Controller
{

    private $activites;
    private $budgets;
    private $agents;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DepenseModel', 'depense');
        $this->load->model('PointVente_model', 'pv');
        $this->load->model("EtatModel", 'etat');
        $this->load->model("ActiviteModel", 'activite');
        $this->load->model("BudgetModel", 'budget');
        $this->load->model("UserModel", 'user');


        if (isset($_SESSION['type_of_user']) &&  $_SESSION['type_of_user'] == 'Agent') {
            redirect('stock');
        }

        // activite du moment present
        $this->activites = $this->activite->getActiviteActuel();
        $this->agents = $this->user->getAllUser();

        if (count($this->activites)) {
            $this->budgets = $this->budget->getByactivite($this->activites[0]->idactivite);
        } else {
            $this->budgets = [];
        }
    }

    public function jail()
    {
        if (!isset($_SESSION['user_type'])) {
            redirect('connexion');
        }
    }


    /**
     * page principale de depense 
     */
    public function index($page = 1)
    {
        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();

        $pv = $this->pv->getAllPv();

        $nPages = ceil($this->depense->get_count() / PAGINATION);
        $current = 1;

        $datapag['depense'] = $this->depense->get_authors($current);


        $alldep = $this->depense->getAlldep();
        $somme = $this->depense->getsomme($alldep);

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense', [
            'data' => $datapag,
            'pv' => $pv,
            'activites' => $this->activites,
            'budgets' => $this->budgets,
            'agents' => $this->agents,
            'somme' => $somme,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $assets);
    }


    /**
     * pagination
     *
     * @param integer $page
     * @return void
     */
    public function page($page = 1)
    {
        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();


        $pv = $this->pv->getAllPv();

        $nPages = ceil($this->depense->get_count() / PAGINATION);
        $current = $page;

        // * pagination * // 
        // $config = array();
        // $config["base_url"] = base_url() . 'depense';
        // $config["total_rows"] = $this->depense->get_count();
        // $config["per_page"] = PAGINATION;
        // $config['page_query_string'] = TRUE;
        // $config['query_string_segment'] = 'page';
        // $config['use_page_numbers'] = TRUE;
        // $this->pagination->initialize($config);

        // $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // $datapag["links"] = $this->pagination->create_links();

        $datapag['depense'] = $this->depense->get_authors($current);



        $alldep = $this->depense->getAlldep();
        $somme = $this->depense->getsomme($alldep);

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense', [
            'data' => $datapag,
            'pv' => $pv,
            'somme' => $somme,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $assets);
    }


    /**
     * enregistrement
     *
     * @return void
     */
    public function register()
    {
        $montant = '';
        if (isset($_POST['montant']) && $_POST['montant'] != '') {
            $montant = strip_tags(trim($_POST['montant']));
        }
        $budget = '';
        if (isset($_POST['budget']) && $_POST['budget'] != '') {
            $budget = strip_tags(trim($_POST['budget']));
        }
        $date = '';
        if (isset($_POST['date']) && $_POST['date'] != '') {
            $date = strip_tags(trim($_POST['date']));
        }
        $heure = '';
        if (isset($_POST['heure']) && $_POST['heure'] != '') {
            $heure = strip_tags(trim($_POST['heure']));
        }
        $idagent = '';
        if (isset($_POST['idagent']) && $_POST['idagent'] != '') {
            $idagent = strip_tags(trim($_POST['idagent']));
        }

        // $pv = strip_tags(trim($_POST['pv']));

        $user = '';

        if ($_SESSION['user_type'] != 'admin') {
            $user = $_SESSION['id_user'];
        }


        $filename = $_FILES['justification']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $unique_name = time() . '_' . uniqid('logo_', true) . '_' . $_SESSION['idadmin'] . '.' . $ext;

        $photo = move_uploaded_file($_FILES['justification']['tmp_name'], 'public/upload/justification/' . $unique_name);


        $the_date = '';

        if ($date == '') {
            $the_date = date("Y-m-d H:i:s");
        } else {
            if ($heure == '') {
                $the_date = $date . ' 00:00:00';
            } else {
                $the_date = $date . ' ' . $heure . ':' . date("s");
            }
        }


        if ($budget != '' && $montant != '') {

            /** idagent est la demandeur d'argent  */
            /** iduser est le comptable  */
            $data = [
                'idbudget' => $budget,
                'justification' => 'public/upload/justification/' . $unique_name,
                'montant' => $montant,
                'idadmin' => $_SESSION['idadmin'],
                'datedepense' => $the_date,
                'idUser' => $user,
                'idagent' => $idagent
            ];
            $this->depense->register($data);
            $this->session->set_flashdata('register', true);
        }
        redirect('depense');
    }


    /**
     * suppression
     *
     * @return void
     */
    public function deleteit()
    {
        $id = trim(strip_tags($_POST['id']));

        $this->depense->deleteit($id);
        $this->session->set_flashdata('delete', true);

        echo json_encode([
            'success' => true
        ]);
    }


    /**
     * Modification
     *
     * @return void
     */
    public function edit()
    {
        $id = trim(strip_tags($_POST['id']));
        $montant = trim(strip_tags($_POST['montant_']));
        $raison = trim(strip_tags($_POST['raison_']));


        if ($montant != '' && $raison != "" &&  $id != '') {
            $data = [
                'montant' => $montant,
                'raison' => $raison
            ];

            $this->depense->edit($id, $data);
            $this->session->set_flashdata('edition', 'ok');
            redirect('Depense');
        }
        redirect('depense');
    }

    /**
     * recherche dans depense 
     *
     * @param integer $page
     * @return void
     */
    public function search($page = 1)
    {

        $assets['css'] = 'depense.css';
        $assets['title'] = 'Depense';
        $assets['js'] = 'depense.js';
        $this->jail();

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

        if ($heure_fin == '' && $date_fin != '' &&  $date_debut != '')
            $heure_fin .= '23:59:59';


        $pv = trim(strip_tags($_POST['lieu']));
        $_POST['lieu'] = $pv;


        if ($date_debut != '')
            $date_debut .= ' ' . $heure_debut;
        if ($date_fin != '')
            $date_fin .= ' ' . $heure_fin;
        // date

        $mot = '';
        if (isset($_POST['recherche']) && $_POST['recherche'] != '') {
            $mot = trim(strip_tags($_POST['recherche']));
        }
        $_POST['mot'] = $mot;

        // Data Etat
        $dataDep =  $this->depense->getdataDep_search($mot, $date_debut, $date_fin, $pv);
        // Data 

        $nPages = ceil(count($dataDep) / PAGINATION);
        $current = $page;

        $datapag['depense'] = $this->depense->getdataDep_search($mot,  $date_debut, $date_fin, $pv, $page);

        //  somme entrant et sortant 

        $somme = $this->depense->getsomme($dataDep);

        //  somme entrant et sortant 

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ["liste" => true]);
        $this->load->view('templates/tete');
        $this->load->view('depense', [
            'data' => $datapag,
            'pv' => $this->pv->getAllPv(),
            'activites' => $this->activites,
            'budgets' => $this->budgets,
            'agents' => $this->agents,
            'somme' => $somme,
            'nPages' => $nPages,
            'current' => $current
        ]);
        $this->load->view('templates/footer', $assets);
    }
}
