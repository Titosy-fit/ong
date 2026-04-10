<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class historiquebeneficiaire extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BeneficiaireModel2', 'beneficiaire');
        $this->load->model('MissionModel', 'mission');
        
        // Vérifiez l'authentification si nécessaire
        // $this->jail();
    }

    /**
     * Page d'accueil de la gestion des bénéficiaires
     */
    public function index()
    {
        $data['title'] = 'Gestion des bénéficiaires';
        $data['css'] = 'beneficiaire.css';
        $js['js'] = 'beneficiaire.js';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['beneficiaires' => true]);
        $this->load->view('templates/tete');
        $this->load->view('beneficiaire/liste');
        $this->load->view('templates/footer', $js);
    }

    /**
     * Afficher l'historique d'un bénéficiaire spécifique
     * @param int $id
     */
    public function historique($id)
    {
        // Récupérer les informations du bénéficiaire
        $beneficiaire = $this->beneficiaire->get_by_id($id);
        
        if (!$beneficiaire) {
            show_404('Bénéficiaire non trouvé');
        }
        
        // Pagination
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        
        // Récupérer l'historique
        $historique = $this->beneficiaire->get_historique($id, $per_page, $offset);
        $total = $this->beneficiaire->count_historique($id);
        $statistiques = $this->beneficiaire->get_statistiques($id);
        
        // Configuration de la pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('beneficiaire/historique/' . $id);
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        $this->pagination->initialize($config);
        
        $data['title'] = 'Historique - ' . $beneficiaire->nom;
        $data['beneficiaire'] = $beneficiaire;
        $data['historique'] = $historique;
        $data['statistiques'] = $statistiques;
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['beneficiaire' => true]);
        $this->load->view('templates/tete');
        $this->load->view('beneficiaire/historique', $data);
        $this->load->view('templates/footer');
    }

    /**
     * API pour récupérer l'historique en JSON (pour chargement dynamique)
     */
    public function get_historique_json()
    {
        $idbeneficiaire = $this->input->post('idbeneficiaire');
        $page = $this->input->post('page') ? (int)$this->input->post('page') : 1;
        $per_page = $this->input->post('per_page') ? (int)$this->input->post('per_page') : 10;
        $offset = ($page - 1) * $per_page;
        
        if (!$idbeneficiaire) {
            echo json_encode(['success' => false, 'message' => 'ID bénéficiaire requis']);
            return;
        }
        
        $historique = $this->beneficiaire->get_historique($idbeneficiaire, $per_page, $offset);
        $total = $this->beneficiaire->count_historique($idbeneficiaire);
        $statistiques = $this->beneficiaire->get_statistiques($idbeneficiaire);
        
        echo json_encode([
            'success' => true,
            'historique' => $historique,
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page,
            'statistiques' => $statistiques
        ]);
    }

    /**
     * Rechercher des bénéficiaires
     */
    public function search()
    {
        $recherche = $this->input->get('q');
        $beneficiaires = $this->beneficiaire->search($recherche);
        
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true, 'datas' => $beneficiaires]);
        } else {
            $data['beneficiaires'] = $beneficiaires;
            $this->load->view('beneficiaire/search_results', $data);
        }
    }
}
?>