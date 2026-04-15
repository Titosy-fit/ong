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

    /**
     * Récupérer les missions liées à un bénéficiaire
     */
    public function get_missions($idbeneficiaire)
    {
        $this->db->select('
            mission.*,
            projet.codeprojet,
            projet.titreprojet,
            agent.nomUser AS nomagent,
            agent.prenomUser AS prenomagent,
            agent.contact AS telagent
        ');
        $this->db->from('mission');
        $this->db->join('projet', 'projet.idprojet = mission.idprojet', 'left');
        $this->db->join('user AS agent', 'agent.idUser = mission.idagent', 'left');
        $this->db->where('mission.idbeneficiaire', $idbeneficiaire);
        $this->db->order_by('mission.idmission', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * Récupérer les liquidations liées aux missions d'un bénéficiaire
     */
    public function get_liquidations($idbeneficiaire)
    {
        $this->db->select('
            liquidation.*,
            mission.objet_mission,
            mission.numero_om,
            mission.numero_asm,
            mission.montant_avance,
            mission.typeMission,
            projet.codeprojet
        ');
        $this->db->from('liquidation');
        $this->db->join('mission', 'mission.idmission = liquidation.idmission', 'inner');
        $this->db->join('avance_mission', 'avance_mission.idmission = mission.idmission', 'left');
        $this->db->join('budget', 'budget.idbudget = avance_mission.idbudget', 'left');
        $this->db->join('projet', 'projet.idprojet = budget.idprojet', 'left');
        $this->db->where('mission.idbeneficiaire', $idbeneficiaire);
        $this->db->group_by('liquidation.idliquidation');
        $this->db->order_by('liquidation.idliquidation', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * Récupérer les reliquats liés aux missions d'un bénéficiaire
     */
    public function get_reliquats($idbeneficiaire)
    {
        $this->db->select('
            relique.*,
            mission.objet_mission,
            mission.numero_om,
            mission.numero_asm,
            liquidation.montant_depense,
            liquidation.montant_reliquat,
            liquidation.date_liquidation
        ');
        $this->db->from('relique');
        $this->db->join('mission', 'mission.idmission = relique.idMission', 'inner');
        $this->db->join('liquidation', 'liquidation.idliquidation = relique.idLiquidation', 'left');
        $this->db->where('mission.idbeneficiaire', $idbeneficiaire);
        $this->db->order_by('relique.idRelique', 'DESC');

        return $this->db->get()->result();
    }
}
?>