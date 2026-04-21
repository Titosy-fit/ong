<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- Titre + retour -->
                <div class="mb-3">
                    <h2>Détails du budget n°<?= $budget->idbudget ?></h2>
                    <?php if (!empty($projet->codeprojet)): ?>
                        <p><?= $projet->codeprojet ?></p>
                    <?php endif; ?>
                    <a href="<?= base_url('budget') ?>" class="btn btn-outline-secondary btn-sm">
                        ← Retour
                    </a>
                </div>

                <!-- SYNTHÈSE : AVANCE EN MISSION EN PREMIER -->
                <div class="mb-4">
                    <h5 class="mb-3">Résumé des avances et reliquat</h5>
                    
                    <?php
                    // Total avances missions
                    $totalAvanceMission = 0;
                    if (!empty($missions)) {
                        foreach ($missions as $m) {
                            $totalAvanceMission += $m->montant_avance ?? 0;
                        }
                    }
                    
                    // Total dépensé en liquidations
                    $totalDepenseLiquidation = 0;
                    if (!empty($liquidations)) {
                        foreach ($liquidations as $l) {
                            $totalDepenseLiquidation += $l->total_depense ?? 0;
                        }
                    }
                    
                    // Total approvisionnements
                    $totalAppro = 0;
                    if (!empty($approvisionnements)) {
                        foreach ($approvisionnements as $app) {
                            $totalAppro += ($app->quantite * ($app->prix_unitaire ?? 0));
                        }
                    }
                    
                    // Reliquat de la DERNIÈRE liquidation
                    $reliquatDerniereLiquidation = 0;
                    $dateDerniereLiquidation = '—';
                    if (!empty($liquidations)) {
                        $derniere = $liquidations[0];
                        $reliquatDerniereLiquidation = $derniere->reliquat ?? 0;
                        $dateDerniereLiquidation = $derniere->date_liquidation 
                            ? date('d/m/Y H:i', strtotime($derniere->date_liquidation)) 
                            : '—';
                    }

                    // Calcul du total des commandes
                    $total_commandes = 0;
                    if (!empty($commandes)) {
                        foreach ($commandes as $cmd) {
                            $montant_ht = 0;
                            if (!empty($cmd->cmpanier)) {
                                foreach ($cmd->cmpanier as $ligne) {
                                    $montant_ht += $ligne->prixunitaire * $ligne->quantite;
                                }
                            } else {
                                $montant_ht = $cmd->montant_total ?? 0;
                            }
                            $frais = $cmd->frais ?? 0;
                            $total_ttc = $montant_ht + $frais;
                            $total_commandes += $total_ttc;
                        }
                    }

                    $budgetTotal = $budget->budget ?? 0;
                    
                    // Calcul du reste budget
                    $resteCalcule = $budgetTotal - $totalAvanceMission + $totalDepenseLiquidation + $reliquatDerniereLiquidation - $total_commandes;
                    ?>

                    <!-- Montant alloué au budget -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr style="background: #333333; color: white;">
                                    <td style="border: none; padding: 20px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                                            <div>
                                                <span style="font-size: 0.9rem; opacity: 0.9;">Montant alloué au budget</span>
                                                <h3 style="margin: 5px 0 0; font-size: 2rem; font-weight: 600;"><?= number_format($budgetTotal, 0, ',', ' ') ?> Ar</h3>
                                            </div>
                                            <div style="background: rgba(255,255,255,0.15); padding: 10px 15px; border-radius: 6px;">
                                                <span style="font-size: 0.9rem;">Reliquat restant</span>
                                                <div style="font-size: 1.5rem; font-weight: 500;"><?= number_format($reste ?? 0, 0, ',', ' ') ?> Ar</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Calcul détaillé du reste budget -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background: #f5f5f5;">
                                    <th style="width: 70%;">Détail du calcul</th>
                                    <th style="width: 30%;">Montant (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Montant alloué au budget</td>
                                    <td class="fw-bold"><?= number_format($budgetTotal, 0, ',', ' ') ?></td>
                                </tr>
                                <tr>
                                    <td>- Avances sur missions</td>
                                    <td class="fw-bold text-danger">- <?= number_format($totalAvanceMission, 0, ',', ' ') ?></td>
                                </tr>
                                <tr>
                                    <td>+ Dépenses liquidées</td>
                                    <td class="fw-bold text-success">+ <?= number_format($totalDepenseLiquidation, 0, ',', ' ') ?></td>
                                </tr>
                                <tr>
                                    <td>+ Reliquat liquidation</td>
                                    <td class="fw-bold text-success">+ <?= number_format($reliquatDerniereLiquidation, 0, ',', ' ') ?></td>
                                </tr>
                                <tr style="background: #fafafa;">
                                    <td><strong>- Total commandes</strong></td>
                                    <td class="fw-bold text-danger"><strong>- <?= number_format($total_commandes, 0, ',', ' ') ?></strong></td>
                                </tr>
                                <tr style="background: #f5f5f5; border-top: 2px solid #333;">
                                    <td><strong>= Reste budget calculé</strong></td>
                                    <td class="fw-bold" style="font-size: 1.1rem;"><?= number_format($resteCalcule, 0, ',', ' ') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tableau des indicateurs -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background: #f5f5f5;">
                                    <th>Indicateur</th>
                                    <th>Montant (Ar)</th>
                                    <th>Opération</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Avances missions</td>
                                    <td class="fw-bold"><?= number_format($totalAvanceMission, 0, ',', ' ') ?></td>
                                    <td>Soustrait du budget</td>
                                </tr>
                                <tr>
                                    <td>Dépenses liquidées</td>
                                    <td class="fw-bold"><?= number_format($totalDepenseLiquidation, 0, ',', ' ') ?></td>
                                    <td>Ajouté au budget</td>
                                </tr>
                                <tr>
                                    <td>Reliquat liquidation</td>
                                    <td class="fw-bold"><?= number_format($reliquatDerniereLiquidation, 0, ',', ' ') ?></td>
                                    <td>Ajouté au budget</td>
                                </tr>
                                <tr>
                                    <td>Commandes fournisseurs</td>
                                    <td class="fw-bold"><?= number_format($total_commandes, 0, ',', ' ') ?></td>
                                    <td>Soustrait du budget</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Note explicative -->
                    <div style="background: #f5f5f5; padding: 12px; border-radius: 4px; margin: 15px 0; border: 1px solid #dddddd;">
                        <p style="margin: 0; font-size: 0.9em; color: #666666;">
                            <span style="font-weight: 500;">Note :</span> Reste budget = Montant alloué - Avances + Dépenses + Reliquat liquidation - Total commandes.<br>
                            Reliquat estimé (contrôleur) : <strong><?= number_format($reste ?? 0, 0, ',', ' ') ?> Ar</strong> | 
                            Reste calculé : <strong><?= number_format($resteCalcule, 0, ',', ' ') ?> Ar</strong>
                        </p>
                    </div>

                    <!-- Statistiques -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" style="width: auto;">
                            <tbody>
                                <tr>
                                    <td style="background: #f5f5f5;"><strong>Missions</strong></td>
                                    <td class="text-center"><?= count($missions ?? 0) ?></td>
                                    <td style="background: #f5f5f5;"><strong>Liquidations</strong></td>
                                    <td class="text-center"><?= count($liquidations ?? 0) ?></td>
                                    <td style="background: #f5f5f5;"><strong>Commandes</strong></td>
                                    <td class="text-center"><?= count($commandes ?? 0) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Projet -->
                <div class="mb-4">
                    <h5>Projet</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px; background-color: #f8f9fa;">Code</th>
                                    <td><?= $projet->codeprojet ?? '—' ?></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Créé par</th>
                                    <td>Admin</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Budget détaillé -->
                <div class="mb-4">
                    <h5>Budget</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px; background-color: #f8f9fa;">Intitulé</th>
                                    <td><?= $budget->designation_budget ?? '—' ?></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Ligne budgétaire</th>
                                    <td><?= $budget->ligne_budget ?? '—' ?></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Montant alloué</th>
                                    <td class="fw-bold"><?= number_format($budget->budget ?? 0, 0, ',', ' ') ?> Ar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Missions liées -->
                <div class="mb-4">
                    <h5>Missions liées</h5>
                    <?php if (!empty($missions)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Avance (Ar)</th>
                                        <th>Objet</th>
                                        <th>Lieu</th>
                                        <th>Période</th>
                                        <th>Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($missions as $m): ?>
                                        <tr>
                                            <td class="fw-bold"><?= number_format($m->montant_avance ?? 0, 0, ',', ' ') ?></td>
                                            <td><?= htmlspecialchars($m->objet_mission ?? '—') ?></td>
                                            <td><?= htmlspecialchars($m->lieu_mission ?? '—') ?></td>
                                            <td>
                                                <?= $m->date_debut_mission ? date('d/m/Y', strtotime($m->date_debut_mission)) : '—' ?>
                                                →
                                                <?= $m->date_fin_mission ? date('d/m/Y', strtotime($m->date_fin_mission)) : '—' ?>
                                            </td>
                                            <td><?= htmlspecialchars($m->agent ?? '—') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>Aucune mission associée.</p>
                    <?php endif; ?>
                </div>

                <!-- Liquidations -->
                <div class="mb-4">
                    <h5>Liquidations</h5>
                    <?php if (!empty($liquidations)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Dépensé (Ar)</th>
                                        <th>Retourné (Ar)</th>
                                        <th>Reliquat (Ar)</th>
                                        <th>Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($liquidations as $l): ?>
                                        <tr>
                                            <td><?= $l->date_liquidation ? date('d/m/Y H:i', strtotime($l->date_liquidation)) : '—' ?></td>
                                            <td class="fw-bold"><?= number_format($l->total_depense ?? 0, 0, ',', ' ') ?></td>
                                            <td>
                                                <?php if (($l->montant_retourne ?? 0) > 0): ?>
                                                    <?= number_format($l->montant_retourne, 0, ',', ' ') ?>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold"><?= number_format($l->reliquat ?? 0, 0, ',', ' ') ?></td>
                                            <td><?= htmlspecialchars($l->agent_liquidation ?? '—') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>Aucune liquidation enregistrée.</p>
                    <?php endif; ?>
                </div>

                <!-- Commandes liées au projet -->
                <div class="mb-5">
                    <h5>Commandes liées au projet</h5>

                    <?php if (!empty($commandes)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° commande</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th class="text-end">Montant HT (Ar)</th>
                                        <th class="text-end">Frais (Ar)</th>
                                        <th class="text-end">Total TTC (Ar)</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_commandes = 0;
                                    foreach ($commandes as $cmd): 
                                        $montant_ht = 0;
                                        if (!empty($cmd->cmpanier)) {
                                            foreach ($cmd->cmpanier as $ligne) {
                                                $montant_ht += $ligne->prixunitaire * $ligne->quantite;
                                            }
                                        } else {
                                            $montant_ht = $cmd->montant_total ?? 0;
                                        }
                                        
                                        $frais = $cmd->frais ?? 0;
                                        $total_ttc = $montant_ht + $frais;
                                        $total_commandes += $total_ttc;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cmd->cmfacture ?? '—') ?></td>
                                        <td><?= $cmd->datecommande ? date('d/m/Y', strtotime($cmd->datecommande)) : '—' ?></td>
                                        <td><?= htmlspecialchars(strtoupper($cmd->nom_entr ?? '—')) ?></td>
                                        <td class="text-end"><?= number_format($montant_ht, 0, ',', ' ') ?></td>
                                        <td class="text-end"><?= number_format($frais, 0, ',', ' ') ?></td>
                                        <td class="text-end fw-bold"><?= number_format($total_ttc, 0, ',', ' ') ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('Commande/facture/' . $cmd->cmfacture) ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               target="_blank">
                                                Voir facture
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Total commandes :</td>
                                        <td class="text-end fw-bold text-primary">
                                            <?= number_format($total_commandes, 0, ',', ' ') ?> Ar
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Aucune commande enregistrée pour ce projet.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Styles professionnels */
    .table {
        font-size: 14px;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
        padding: 4px 12px;
        font-size: 12px;
    }
    
    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }
    
    .btn-outline-secondary {
        padding: 4px 12px;
        font-size: 13px;
    }
    
    h2 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: #333;
    }
    
    h5 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: #555;
        font-weight: 600;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .text-primary {
        color: #0d6efd !important;
    }
    
    .fw-bold {
        font-weight: 700 !important;
    }
    
    .alert-info {
        background-color: #e2f3f5;
        border-color: #b6e1e8;
        color: #0c5460;
        padding: 12px;
        border-radius: 4px;
    }
</style>