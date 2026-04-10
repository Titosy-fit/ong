<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeneficiaireModel2 extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ajouter un nouveau bénéficiaire
     */
    public function insert($data)
    {
        // Vérifier que les données sont valides
        if (empty($data['nom'])) {
            return false;
        }
        
        $this->db->insert('beneficiaire', $data);
        return $this->db->insert_id();
    }

    /**
     * Recherche pour le select inline
     */
    public function search($recherche = '')
    {
        $this->db->select('idbeneficiaire, nom');
        $this->db->from('beneficiaire');
        if (!empty($recherche)) {
            $this->db->group_start();
            $this->db->like('nom', $recherche);
            $this->db->group_end();
        }
        $this->db->order_by('nom', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Récupérer un bénéficiaire par ID (pour affichage)
     */
    public function get_by_id($id)
    {
        return $this->db->get_where('beneficiaire', ['idbeneficiaire' => $id])->row();
    }
}
?>