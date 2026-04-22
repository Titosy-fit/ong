<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SaisieManuelleModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Insérer une saisie manuelle (avec ou sans fichier)
     */
    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('saisie_manuelle_demande', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Récupérer toutes les saisies d'une demande
     */
    public function get_by_demande($idfacture)
    {
        return $this->db->select('*')
                        ->from('saisie_manuelle_demande')
                        ->where('idfacture', $idfacture)
                        ->order_by('created_at', 'DESC')
                        ->get()
                        ->result();
    }
    
    /**
     * Récupérer les saisies par numéro de demande
     */
    public function get_by_num_demande($num_demande)
    {
        return $this->db->select('*')
                        ->from('saisie_manuelle_demande')
                        ->where('num_demande', $num_demande)
                        ->order_by('created_at', 'DESC')
                        ->get()
                        ->result();
    }
    
    /**
     * Récupérer uniquement les entrées avec fichier
     */
    public function get_fichiers_by_demande($idfacture)
    {
        return $this->db->select('*')
                        ->from('saisie_manuelle_demande')
                        ->where('idfacture', $idfacture)
                        ->where('nom_fichier IS NOT NULL')
                        ->order_by('created_at', 'DESC')
                        ->get()
                        ->result();
    }
    
    /**
     * Récupérer uniquement les saisies manuelles sans fichier
     */
    public function get_manuelles_by_demande($idfacture)
    {
        return $this->db->select('*')
                        ->from('saisie_manuelle_demande')
                        ->where('idfacture', $idfacture)
                        ->where('(entana_nozaraina IS NOT NULL OR designation IS NOT NULL)')
                        ->order_by('created_at', 'DESC')
                        ->get()
                        ->result();
    }
}