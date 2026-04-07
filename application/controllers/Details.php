<?php
class Details extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BudgetModel', 'budget');
        $this->load->model('MissionModel', 'mission');
        $this->load->model('LiquidationModel', 'liquidation');
        $this->load->model('ProjetModel', 'projet');
        $this->load->model('UserModel', 'user');
    }

    /**
     * Récupère tous les détails pour un budget donné
     * 
**/
public function details_json()
{
    $idbudget = $this->input->post('idbudget');

    if (!$idbudget || !is_numeric($idbudget)) {
        echo json_encode(['success' => false, 'message' => 'ID budget invalide']);
        return;
    }

    // On récupère déjà une bonne partie des données via donner()
    $budget = $this->budget->donner($idbudget);
    if (empty($budget)) {
        echo json_encode(['success' => false, 'message' => 'Budget introuvable']);
        return;
    }
    $budget = $budget[0];

    // Projet
    $projet = $this->projet->donner($budget->idprojet);
    $projet = !empty($projet) ? $projet[0] : (object)['codeprojet' => '—', 'titreprojet' => '—'];

    // Calcul du reste (déjà présent dans getbudget, on le réutilise si possible)
    $reste = $budget->reste ?? $budget->budget;

    // Pour les missions / avances / liquidations → on simplifie pour commencer
    // (tu pourras enrichir après)

    $response = [
        'success'       => true,
        'codeprojet'    => $projet->codeprojet,
        'ligne'         => $budget->ligne_budget,
        'date_creation' => date('d/m/Y', strtotime($budget->date_creation ?? 'now')),
        'createur'      => 'Admin', // ← à améliorer plus tard
        'designation'   => $budget->designation_budget,
        'budget'        => number_format($budget->budget, 0, ',', ' '),
        'reste'         => number_format($reste, 0, ',', ' '),
        'depenses'      => '—',           // à calculer plus tard
        'date_debut'    => '—',
        'date_fin'      => '—',
        'avance'        => '—',
        'objet'         => '—',
        'lieu'          => '—',
        'date_depart'   => '—',
        'date_retour'   => '—',
        'total_depense' => '—',
        'total_reliquat'=> '—',
        'montant_retour'=> '—',
        'agent_1'       => 'Non assigné',
        // agent_2, agent_3... idem
        'mode_remboursement'   => 'Espèces',
        'date_liquidation'     => '—',
        'statut_liquidation'   => 'En attente'
    ];

    echo json_encode($response);
}
}