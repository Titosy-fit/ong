<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminInscription extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model', 'admin');
        $this->load->library('upload');

        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            redirect('connexion');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Inscription Administrateur',
            'css'   => 'stock.css'
        ];

        $js = ['js' => 'dashboard.js'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['user' => true]);
        $this->load->view('templates/tete');
        $this->load->view('admin_inscription', $data);
        $this->load->view('templates/footer', $js);
    }

    public function register()
{
    $mail       = trim($this->input->post('mail', TRUE));
    $pass       = $this->input->post('pass', TRUE);
    $nom        = trim($this->input->post('nom', TRUE));
    $prenom     = trim($this->input->post('prenom', TRUE));
    $entreprise = trim($this->input->post('entreprise', TRUE));
    $adresse    = trim($this->input->post('adresse', TRUE));
    $tel        = trim($this->input->post('tel', TRUE));
    $teladmin   = trim($this->input->post('teladmin', TRUE));
    $nif        = trim($this->input->post('nif', TRUE));
    $stat       = trim($this->input->post('stat', TRUE));

    // Upload logo → on utilise le dossier qui marche déjà
    $logo_name = '';
    if (!empty($_FILES['logo']['name'])) {
        $config = [
            'upload_path'   => './public/upload/logo/',
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE
        ];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('logo')) {
            $upload_data = $this->upload->data();
            $logo_name = $upload_data['file_name'];   // juste le nom
            // OU chemin complet : $logo_name = 'public/upload/logo/' . $upload_data['file_name'];
        } else {
            // On affiche dans la console mais on continue
            $this->session->set_flashdata('console_log', 'Logo non uploadé : ' . $this->upload->display_errors('', ''));
        }
    }

    // Données à insérer
    $data_admin = [
        'mail'       => $mail,
        'pass'       => hash_it($pass),
        'nom'        => $nom,
        'prenom'     => $prenom,
        'entreprise' => $entreprise ?: '',
        'adresse'    => $adresse ?: '',
        'tel'        => $tel ?: '',
        'teladmin'   => $teladmin ?: '',
        'nif'        => $nif ?: '',
        'stat'       => $stat ?: '',
        'logo'       => $logo_name,
        'mode'       => 'actif',
    ];

    $id_new = $this->admin->insert($data_admin);

    if ($id_new && $id_new > 0) {
        // $this->admin->insertModeEspece($id_new);
        $this->session->set_flashdata('ajout', true);
        $this->session->set_flashdata('console_log', 'Insertion réussie ! ID = ' . $id_new);
    } else {
        $error_msg = 'Échec insertion (affected rows = 0)';
        $query = $this->db->last_query();
        $this->session->set_flashdata('error', $error_msg);
        $this->session->set_flashdata('console_log', $error_msg . ' | Requête : ' . $query);
    }

    redirect('admin_inscription');
}
}