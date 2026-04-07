<?php
class Beneficiaire extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BeneficiaireModel', 'beneficiaire');
    }

    /**
     * page pricipale beneficiaire
     *
     * @return void
     */
    public function index()
    {
        $page = $_GET['page'] ?? 0;
        $assets['title'] = 'Beneficiaire';
        $assets['css'] = 'beneficiaire.css';
        $js['js'] = 'beneficiaire.js';

        $datas = $this->beneficiaire->getAutor($page);

        $liens = $this->pagination('beneficiaire', count($this->beneficiaire->getAutor()));




        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['beneficiaire' => true]);
        $this->load->view('templates/tete');
        $this->load->view('beneficiaire', [
            'datas' => $datas,
            'liens' => $liens
        ]);
        $this->load->view('templates/footer', $js);
    }

    /**
     * verification de doublant 
     *
     * @return void
     */
    public function verify()
    {
        $telbene = '';
        if (isset($_POST['telbene']) && $_POST['telbene'] != '') {
            $telbene = trim(strip_tags($_POST['telbene']));
        }
        $numcinbene = '';
        if (isset($_POST['numcinbene']) && $_POST['numcinbene'] != '') {
            $numcinbene = trim(strip_tags($_POST['numcinbene']));
        }
        $idbeneficiaire = '';
        if (isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire'] != '') {
            $idbeneficiaire = trim(strip_tags($_POST['idbeneficiaire']));
        }


        $teste = $this->beneficiaire->verify($telbene, $numcinbene, $idbeneficiaire);
        if (count($teste)) {
            echo json_encode([
                'success' => false,
                'data' => $teste[0]
            ]);
        } else {
            echo json_encode([
                'success' => true
            ]);
        }
    }

    /**
     * enregistrement beneficiaire 
     *
     * @return void
     */
    public function register()
    {
        $nom = '';
        if (isset($_POST['nom']) && $_POST['nom']) {
            $nom = trim(strip_tags($_POST['nom']));
        }
        $prenom = '';
        if (isset($_POST['prenom']) && $_POST['prenom']) {
            $prenom = trim(strip_tags($_POST['prenom']));
        }
        $adress = '';
        if (isset($_POST['adress']) && $_POST['adress']) {
            $adress = trim(strip_tags($_POST['adress']));
        }
        $numero = '';
        if (isset($_POST['numero']) && $_POST['numero']) {
            $numero = trim(strip_tags($_POST['numero']));
        }
        $email = '';
        if (isset($_POST['email']) && $_POST['email']) {
            $email = trim(strip_tags($_POST['email']));
        }
        $num_cin = '';
        if (isset($_POST['num_cin']) && $_POST['num_cin']) {
            $num_cin = trim(strip_tags($_POST['num_cin']));
        }
        $date_cin = '';
        if (isset($_POST['date_cin']) && $_POST['date_cin']) {
            $date_cin = trim(strip_tags($_POST['date_cin']));
        }

        $teste = $this->beneficiaire->verify($numero, $num_cin);

        if (!count($teste)) {
            $data = [
                'nombene' => $nom,
                'prenombene' => $prenom,
                'adressebene' => $adress,
                'datecinbene' => $date_cin,
                'numcinbene' => $num_cin,
                'mailbene' => $email,
                'telbene' => $numero,
                'idadmin' => $_SESSION['idadmin'],
                'idUser' =>  $_SESSION['id_user'] ?? ''
            ];

            $this->beneficiaire->insert($data);
            $this->session->set_userdata('add_bene', true);
        }

        redirect('beneficiaire');
    }
    /**
     * enregistrement beneficiaire dans js
     *
     * @return void
     */
    public function register_json()
    {
        $nom = '';
        if (isset($_POST['nom']) && $_POST['nom']) {
            $nom = trim(strip_tags($_POST['nom']));
        }
        $prenom = '';
        if (isset($_POST['prenom']) && $_POST['prenom']) {
            $prenom = trim(strip_tags($_POST['prenom']));
        }
        $adress = '';
        if (isset($_POST['adress']) && $_POST['adress']) {
            $adress = trim(strip_tags($_POST['adress']));
        }
        $numero = '';
        if (isset($_POST['numero']) && $_POST['numero']) {
            $numero = trim(strip_tags($_POST['numero']));
        }
        $email = '';
        if (isset($_POST['email']) && $_POST['email']) {
            $email = trim(strip_tags($_POST['email']));
        }
        $num_cin = '';
        if (isset($_POST['num_cin']) && $_POST['num_cin']) {
            $num_cin = trim(strip_tags($_POST['num_cin']));
        }
        $date_cin = '';
        if (isset($_POST['date_cin']) && $_POST['date_cin']) {
            $date_cin = trim(strip_tags($_POST['date_cin']));
        }

        $data = [
            'nombene' => $nom,
            'prenombene' => $prenom,
            'adressebene' => $adress,
            'datecinbene' => $date_cin,
            'numcinbene' => $num_cin,
            'mailbene' => $email,
            'telbene' => $numero,
            'idadmin' => $_SESSION['idadmin'],
            'idUser' =>  $_SESSION['id_user'] ?? ''
        ];

        $idbeneficiaire = $this->beneficiaire->insert($data);

        echo json_encode([
            'idbeneficiaire' => $idbeneficiaire 
        ]) ; 
    }

    // get beneficiaire par id
    public function donner()
    {
        $idbeneficiaire = '';
        if (isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']) {
            $idbeneficiaire = trim(strip_tags($_POST['idbeneficiaire']));
        }

        $data = $this->beneficiaire->donner($idbeneficiaire);
        if (count($data)) {
            echo json_encode([
                'success' => true,
                'data' => $data[0]
            ]);
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }

    // delete 
    public function delete()
    {
        $idbeneficiaire = '';
        if (isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']) {
            $idbeneficiaire = trim(strip_tags($_POST['idbeneficiaire']));
        }

        $this->beneficiaire->delete($idbeneficiaire);
        $this->session->set_userdata('delete_bene', true);

        echo json_encode([
            'success' => true,
        ]);
    }

    // Modification 
    public function edit()
    {
        $idbeneficiaire = '';
        if (isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']) {
            $idbeneficiaire = trim(strip_tags($_POST['idbeneficiaire']));
        }

        $nom = '';
        if (isset($_POST['nom']) && $_POST['nom']) {
            $nom = trim(strip_tags($_POST['nom']));
        }
        $prenom = '';
        if (isset($_POST['prenom']) && $_POST['prenom']) {
            $prenom = trim(strip_tags($_POST['prenom']));
        }
        $adress = '';
        if (isset($_POST['adress']) && $_POST['adress']) {
            $adress = trim(strip_tags($_POST['adress']));
        }
        $numero = '';
        if (isset($_POST['numero']) && $_POST['numero']) {
            $numero = trim(strip_tags($_POST['numero']));
        }
        $email = '';
        if (isset($_POST['email']) && $_POST['email']) {
            $email = trim(strip_tags($_POST['email']));
        }
        $num_cin = '';
        if (isset($_POST['num_cin']) && $_POST['num_cin']) {
            $num_cin = trim(strip_tags($_POST['num_cin']));
        }
        $date_cin = '';
        if (isset($_POST['date_cin']) && $_POST['date_cin']) {
            $date_cin = trim(strip_tags($_POST['date_cin']));
        }

        $teste = $this->beneficiaire->verify($numero, $num_cin, $idbeneficiaire);

        if (!count($teste)) {
            $data = [
                'nombene' => $nom,
                'prenombene' => $prenom,
                'adressebene' => $adress,
                'datecinbene' => $date_cin,
                'numcinbene' => $num_cin,
                'mailbene' => $email,
                'telbene' => $numero,
                'idUser' =>  $_SESSION['id_user'] ?? ''
            ];

            $this->beneficiaire->updatebene($data, $idbeneficiaire);
            $this->session->set_userdata('add_bene', true);
        }

        redirect('beneficiaire');
    }

    // recherche 
    public function search()
    {
        $page = (isset($_GET['page'])) ? $_GET['page'] : 0;
        $keyword = '';
        if (isset($_GET['recherche']) && $_GET['recherche']) {
            $keyword = trim(strip_tags($_GET['recherche']));
        }

        $_POST['keyword'] = $keyword;

        $liens = $this->pagination_search('search-bene', count($this->beneficiaire->search($keyword)));
        $datas = $this->beneficiaire->search($keyword, $page);

        $page = $_GET['page'] ?? 0;
        $assets['title'] = 'Beneficiaire';
        $assets['css'] = 'beneficiaire.css';
        $js['js'] = 'beneficiaire.js';

        $this->load->view('templates/header', $assets);
        $this->load->view('templates/sidebar', ['beneficiaire' => true]);
        $this->load->view('templates/tete');
        $this->load->view('beneficiaire', [
            'datas' => $datas,
            'liens' => $liens
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function search_json()
    {
        $keyword = '';
        if (isset($_POST['recherche']) && $_POST['recherche']) {
            $keyword = trim(strip_tags($_POST['recherche']));
        }

        $datas = [];
        if ($keyword == '') {
            // Tout prendre 
            $datas = $this->beneficiaire->getAutor();
        } else {
            // recherche
            $datas = $this->beneficiaire->search($keyword);
        }


        if (count($datas)) {
            echo json_encode([
                'success' => true,
                'datas' => $datas
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'datas' => $datas
            ]);
        }
    }
}
