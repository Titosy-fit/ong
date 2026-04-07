<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("ClientsModel", "Clients_model");
    }

     

    public function index()
    {
        $this->jail();
        $config = array();
        $config["base_url"] = base_url() . 'client';
        $config["total_rows"] = $this->Clients_model->get_count();
        $config["per_page"] = PAGINATION;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datapag["links"] = $this->pagination->create_links();

        if ((int)$page == 0) {
            $start = (int)$page * (int)$config["per_page"];
        } else {
            $start = ((int)$page - 1) * (int)$config["per_page"];
        }
        $datapag['client'] = $this->Clients_model->get_authors($config["per_page"], $start);
        // * pagination * // 


        // echo '<pre>' ; 
        // var_dump( $datapag ) ; 
        // echo '</pre>' ; die ; 

        $data['title'] = 'Ajout de Client';
        $data['css'] = 'client.css';
        $js['js'] = 'client.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['client' => true]);
        $this->load->view('templates/tete');
        $this->load->view('clients', ['data' => $datapag]);
        $this->load->view('templates/footer', $js);
    }

    public function verify_client_js()
    {
        $num = trim(strip_tags($this->input->post('numero')));
        $mail = trim(strip_tags($this->input->post('email')));

        if ($mail != '') {
            $data = $this->Clients_model->verify_client_js($num, $mail);
        } else {
            $data = $this->Clients_model->verify_client_js_num($num);
        }

        if (count($data) == 0) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $data[0]
            ]);
        }
    }

    public function registerClient()
    {

        $nom = strip_tags(trim($this->input->post('nom')));
        $prenom = strip_tags(trim($this->input->post('prenom')));
        $adress = strip_tags(trim($this->input->post('adress')));
        $numero = my_trim($this->input->post('numero'));
        $mail_ = my_trim($this->input->post('email'));


        $nif = my_trim($this->input->post('nif'));
        $stat = my_trim($this->input->post('stat'));
        $raison = my_trim($this->input->post('r_social'));

        $iduser = '' ; 

        $idadmin =  0 ; 
        if ( isset( $_SESSION['idadmin'])){
            $idadmin = $_SESSION['idadmin'] ; 
        }
        if ( isset (  $_SESSION['id_user'] )) {
            $iduser = $_SESSION['id_user'] ; 
        }



        $data  = [
            "nomClient" => $nom,
            "prenomClient" => $prenom,
            "adresseClient" => $adress,
            "telClient" => $numero,
            "emailClient" => $mail_,
            "nif" => $nif ,
            "stat" => $stat ,
            "r_social" => $raison  ,
            "idadmin" => $idadmin ,
            "idUser" => $iduser ,
        ]   ;


        $num = $this->Clients_model->getclientByNumero($numero);

        $email = $this->Clients_model->getclientByMail($mail_);

        $confirm_mail = true;
        foreach ($email as $mail) {
            if ($mail->emailClient != '') {
                $confirm_mail = false;
            }
        }


    
        if (isset($_POST['page_'])) {

            if (count($num) == 0 && $confirm_mail == true) {
                // echo "<pre>" ; 
                // var_dump( $data ) ; 
                // echo '</pre>' ; 
                // die ; 
                $this->Clients_model->insertClients( $data );
                
                $this->session->set_flashdata('success', 'true');
            } else {
                if (count($num) > 0) {
                    $this->session->set_flashdata('num', 'Ajout réussie');
                } elseif ($confirm_mail == false) {
                    $this->session->set_flashdata('mail', 'Ajout réussie');
                }
            }
            if (isset($_POST['page_'])) {

                $this->session->set_flashdata('vente_client', 'Ajout réussie');
                redirect('dispatch');
            }
        } else {
            // var_dump( $nif , $stat  ) ; die  ; 
            if (count($num) > 0) {
                $this->session->set_flashdata('num', 'Ajout réussie');
            } elseif ($confirm_mail == false) {
                $this->session->set_flashdata('mail', 'Ajout réussie');
            } else {

                $this->Clients_model->insertClients( $data );
                $this->session->set_flashdata('success', 'Ajout réussie');
            }
        }

        redirect('client');
    }

    public function validationClient()
    {
        $numero = strip_tags(trim($this->input->post('numero')));
        $nif = strip_tags(trim($this->input->post('nif')));
        $stat = strip_tags(trim($this->input->post('stat')));

        $tel = $this->Clients_model->verify_num_clients($numero);

        $response = ['success' => true];

        if (count($tel) > 0) {
            $response['numeroInsertExiste'] = true;
            $response['success'] = false;
        }

        echo json_encode($response);
    }

    private function getclients()
    {
        $clients = $this->Clients_model->getALLclients();
        return $clients;
    }

    public function getclient($id)
    {
        $clients = $this->Clients_model->editClient($id);
        echo json_encode($clients);
    }

    public function fetchClient()
    {
        $num = $this->input->post('client');
        $client = $this->Clients_model->getClientByNum($num);

        if (count($client) > 0) {
            echo json_encode([
                'success' => true,
                'data' => $client[0],
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => [],
            ]);
        }
    }

    public function editClient()
    {
        $id = $this->input->post('idClient_modif');
        $data = array(
            'nomClient' => trim($this->input->post('nom_modif')),
            'prenomClient' => trim($this->input->post('prenom_modif')),
            'adresseClient' => trim($this->input->post('adresse_modif')),
            'telClient' => my_trim($this->input->post('numero_modif')),
            'emailClient' => my_trim($this->input->post('email_modif')),
            'stat' => my_trim($this->input->post('stat_modif')),
            'nif' => my_trim($this->input->post('nif_modif')),
            'r_social' => my_trim($this->input->post('r_social_modif')),
            'idClient ' => $id
        );

        $this->Clients_model->updateclient($data, $id);
        $this->session->set_flashdata('edit', 'Ajout réussie');

        if (isset($_POST['page_'])) {
            $this->session->set_flashdata('vente_client', 'ok');
            redirect('dispatch ');
        } else {
            redirect('client');
        }
    }

    public function donnerclient()
    {
        $id = $this->input->post('client');
        $data = $this->Clients_model->getclientById($id);

        echo json_encode($data[0]);
    }

    public function deleteclient()
    {
        $id = $this->input->post('id');

        $res = $this->Clients_model->verifyIfClientInUse($id);

        $data = $this->Clients_model->delete($id);

        echo json_encode([
            'success' => true,
            'error' => '',
            'data' => $data,
        ]);

        $this->session->set_flashdata('delete', 'Ajout réussie');
        $this->session->set_flashdata('vente_client', 'ok');
    }


    public function rechercheClient()
    {

        if (isset($_POST['page_'])) {
            $keyword = strip_tags(trim($_POST['recherche']));
            $res = $this->Clients_model->searchClients($keyword, '', '');

            echo json_encode(['data' => $res]);
        } else {
            $keyword = strip_tags(trim($_GET['recherche']));
            $_POST['post'] = $keyword;
            // * pagination * // 
            $config = array();
            $config["base_url"] = base_url() . 'rechercheClient';
            $config["total_rows"] = count($this->Clients_model->searchClients($keyword, '', ''));
            $config["per_page"] = PAGINATION;
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'page';
            $config['reuse_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            $this->pagination->initialize($config);

            $page = isset($_GET['page']) ? $_GET['page'] : 0;

            $datapag["links"] = $this->pagination->create_links();

            if ((int)$page == 0) {
                $start = (int)$page * (int)$config["per_page"];
            } else {
                $start = ((int)$page - 1) * (int)$config["per_page"];
            }
            $datapag['client'] = $this->Clients_model->searchClients($keyword, $config["per_page"], $start);
            // * pagination * // 



            $data['title'] = 'Ajout de Client';
            $data['css'] = 'client.css';
            $js['js'] = 'client.js';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', ['client' => true]);
            $this->load->view('templates/tete');
            $this->load->view('clients', [
                'data' => $datapag,
                'post' => $_POST['post']
            ]);
            $this->load->view('templates/footer', $js);
        }
    }

    public function verifiClient()
    {
        $email = strip_tags(trim($this->input->post('emailModif')));
        $numero = strip_tags(trim($this->input->post('numeroModif')));
        $old = $this->input->post('old');
        $data = $this->Clients_model->verify_client($numero);

        $dataAll = $this->Clients_model->getALLClientWithCriteria($old);
        $tab = [];
        for ($i = 0; $i < count($dataAll); $i++) {
            array_push($tab, $dataAll[$i]->telClient);
            array_push($tab, $dataAll[$i]->emailClient);
        }
        if (in_array($numero, $tab)) {
            echo json_encode(
                ['numero' => true]
            );
        } elseif (in_array($email, $tab) && $email  != '') {
            echo json_encode(
                ['email' => true]
            );
        } else {
            echo json_encode(
                ['success' => true]
            );
        }
    }

    public function rechercherClient()
    {
        $numClient = htmlspecialchars(my_trim($_POST['numClient']));
        $client = $this->Clients_model->getClientByNum($numClient);

        if (empty($client)) {
            echo json_encode(['success' => false, 'data' => '',]);
        } else {
            echo json_encode(['success' => true, 'data' => $client[0],]);
        }
    }

    public function rechercherClientForFacturation()
    {
        $numClient = htmlspecialchars(my_trim($_POST['numClient']));
        $client = $this->Clients_model->getClientByNumForFacturation($numClient);

        if (empty($client)) {
            echo json_encode(['success' => false, 'data' => '',]);
        } else {
            echo json_encode(['success' => true, 'data' => $client[0],]);
        }
    }

    public function getAllClient()
    {
        $clients = $this->Clients_model->getALLclients();

        echo json_encode([
            'data' => $clients
        ]);
    }
}
