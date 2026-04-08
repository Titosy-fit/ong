<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApproBudgetModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insertion d'un budget pour un approvisionnement
     */
    public function insert($data)
    {
        $this->db->insert('appro_budget', $data);
        return $this->db->insert_id();
    }

    /**
     * Insertion multiple (batch)
     */
    public function insert_batch($datas)
    {
        $this->db->insert_batch('appro_budget', $datas);
    }

    /**
     * Récupérer les budgets d'un approvisionnement
     */
    public function getByAppro($idAppro)
    {
        $this->db->select('ab.*, b.ligne_budget, b.designation_budget, b.budget')
            ->from('appro_budget ab')
            ->join('budget b', 'b.idbudget = ab.idbudget')
            ->where('ab.idAppro', $idAppro);
        return $this->db->get()->result();
    }

    /**
     * Supprimer les budgets d'un approvisionnement
     */
    public function deleteByAppro($idAppro)
    {
        $this->db->where('idAppro', $idAppro)->delete('appro_budget');
    }
}
