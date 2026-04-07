<?php
class Reliquat extends CI_Controller
{
    private $projets;
    private $activites;
    private $budgets;
    private $agents;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('reliquatModel', 'reliquat');
        $this->load->model("ActiviteModel", 'activite');
        $this->load->model("BudgetModel", 'budget');
        $this->load->model("UserModel", 'user');

        $this->projets = $this->projet->getpojet();

        // if (  $_SESSION['user_type'] != 'admin'){
        //     redirect('stock') ; 
        // }

        $this->activites = $this->activite->getActiviteActuel();
        $this->agents = $this->user->getAllUser();

        if (count($this->activites)) {
            $this->budgets = $this->budget->getByactivite($this->activites[0]->idactivite);
        } else {
            $this->budgets = [];
        }
    }


    /**
     * Page pricipale du reliquat
     *
     * @return void
     */
    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $datasall =  $this->reliquat->getreliquat();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['reliquat'] = $this->reliquat->getreliquat($page);

        $data['title'] = 'Reliquat';
        $data['css'] = 'reliquat.css';
        $js['js'] = 'reliquat.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['reliquat' => true]);
        $this->load->view('templates/tete');
        $this->load->view('reliquat', [
            'data' => $datapag,
            'nPages' => $nPages,
            'projets' => $this->projets,
            'activites' => $this->activites,
            'budgets' => $this->budgets,
            'agents' => $this->agents,
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
        $datasall =  $this->reliquat->getreliquat();

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['reliquat'] = $this->reliquat->getreliquat($page);

        $data['title'] = 'Reliquat';
        $data['css'] = 'reliquat.css';
        $js['js'] = 'reliquat.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['reliquat' => true]);
        $this->load->view('templates/tete');
        $this->load->view('reliquat', [
            'data' => $datapag,
            'nPages' => $nPages,
            'current' => $page
        ]);
        $this->load->view('templates/footer', $js);
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

        // $pv = strip_tags(trim($_POST['pv']));

        $user = '';

        if ($_SESSION['user_type'] != 'admin') {
            $user = $_SESSION['id_user'];
        }

        $data = [
            'idbudget' => $budget,
            'montant_reliquat' => $montant,
            'idadmin' => $_SESSION['idadmin'],
            'date_reliquat' => $the_date,
            'idUser' => $user,
            'idagent' => $idagent
        ];


        $this->reliquat->register($data);
        $this->session->set_userdata('added_projet',  true);



        redirect('reliquat');
    }

    /**
     * reliquat par id 
     *
     * @return void
     */
    public function donner()
    {
        $idreliquat  = '';
        if (isset($_POST['idreliquat']) && $_POST['idreliquat'] != '') {
            $idreliquat = trim(strip_tags($_POST['idreliquat']));
        }

        $data = $this->reliquat->donner($idreliquat);
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

    /**
     * toute les reliquats
     *
     * @return void
     */
    public function reliquat_json()
    {
        $data = $this->reliquat->reliquat_json();
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

        $idreliquat  = '';
        if (isset($_POST['idreliquat']) && $_POST['idreliquat'] != '') {
            $idreliquat = trim(strip_tags($_POST['idreliquat']));
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

        $teste = $this->reliquat->verify($titre, $idreliquat);
        if (!count($teste)) {
            $data = [
                'titre_reliquat' => $titre,
                'date_debut_act' => $date_debut,
                'date_fin_act' => $date_fin,
                'idprojet' => $projet,
                'idadmin' => $_SESSION['idadmin']
            ];

            $this->reliquat->edit($data, $idreliquat);
            $this->session->set_userdata('adit_projet',  true);
        }

        redirect('reliquat');
    }

    /**
     * suppression
     *
     * @return void
     */
    public function delete()
    {
        $idreliquat  = '';
        if (isset($_POST['idreliquat']) && $_POST['idreliquat'] != '') {
            $idreliquat = trim(strip_tags($_POST['idreliquat']));
        }


        $this->reliquat->delete($idreliquat);
        $this->session->set_userdata('delete_projet',  true);
    }

    /**
     * recherche dans reliquat
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

        $datasall =  $this->reliquat->getreliquat_search($mot, $date_debut, $date_fin);

        $nPages = ceil(count($datasall) / PAGINATION);

        $datapag['reliquat'] = $this->reliquat->getreliquat_search($mot, $date_debut, $date_fin, $page);


        $data['title'] = 'Reliquat';
        $data['css'] = 'reliquat.css';
        $js['js'] = 'reliquat.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['reliquat' => true]);
        $this->load->view('templates/tete');
        $this->load->view('reliquat', [
           'data' => $datapag,
            'nPages' => $nPages,
            'projets' => $this->projets,
            'activites' => $this->activites,
            'budgets' => $this->budgets,
            'agents' => $this->agents,
            'current' => $page,
        ]);
        $this->load->view('templates/footer', $js);
    }
}
