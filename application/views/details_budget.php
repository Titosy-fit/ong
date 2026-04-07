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
                    // Total avances missions — informatif
                    $totalAvanceMission = 0;
                    if (!empty($missions)) {
                        foreach ($missions as $m) {
                            $totalAvanceMission += $m->montant_avance ?? 0;
                        }
                    }
                    
                    // Total dépensé en liquidations — informatif
                    $totalDepenseLiquidation = 0;
                    if (!empty($liquidations)) {
                        foreach ($liquidations as $l) {
                            $totalDepenseLiquidation += $l->total_depense ?? 0;
                        }
                    }
                    
                    // Total approvisionnements — informatif
                    $totalAppro = 0;
                    if (!empty($approvisionnements)) {
                        foreach ($approvisionnements as $app) {
                            $totalAppro += ($app->quantite * ($app->prix_unitaire ?? 0));
                        }
                    }
                    
                    // Reliquat de la DERNIÈRE liquidation (le plus récent)
                    $reliquatDerniereLiquidation = 0;
                    $dateDerniereLiquidation = '—';
                    if (!empty($liquidations)) {
                        $derniere = $liquidations[0]; // déjà trié DESC dans le contrôleur
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

                    // Calculs pour les pourcentages
                    $budgetTotal = $budget->budget ?? 0;
                    $pourcentageAvance = $budgetTotal > 0 ? round(($totalAvanceMission / $budgetTotal) * 100, 1) : 0;
                    $pourcentageDepense = $budgetTotal > 0 ? round(($totalDepenseLiquidation / $budgetTotal) * 100, 1) : 0;
                    $pourcentageAppro = $budgetTotal > 0 ? round(($totalAppro / $budgetTotal) * 100, 1) : 0;
                    $pourcentageReste = $budgetTotal > 0 ? round((($reste ?? 0) / $budgetTotal) * 100, 1) : 0;
                    
                    // Calcul détaillé du reste budget selon la formule : Montant alloué - Avances + Dépenses + Reliquat liquidation - Total commandes
                    $resteCalcule = $budgetTotal - $totalAvanceMission + $totalDepenseLiquidation + $reliquatDerniereLiquidation - $total_commandes;
                    ?>

                    <!-- Montant alloué au budget - Carte principale grise -->
                    <div style="background: #333333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
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
                    </div>

                    <!-- CALCUL DÉTAILLÉ DU RESTE BUDGET - AVEC TOTAL COMMANDES -->
                    <div style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #dddddd;">
                        <h6 style="margin-top: 0; margin-bottom: 15px; color: #000000; font-weight: 500;">Détail du calcul du reste budget</h6>
                        
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <!-- Ligne Montant alloué -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px dashed #cccccc;">
                                <span style="color: #333333;">Montant alloué au budget</span>
                                <span style="font-weight: 500; color: #000000;"><?= number_format($budgetTotal, 0, ',', ' ') ?> Ar</span>
                            </div>
                            
                            <!-- Ligne Avances sur missions (soustraction) -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px dashed #cccccc;">
                                <span style="color: #333333;">- Avances sur missions</span>
                                <span style="font-weight: 500; color: #000000;">- <?= number_format($totalAvanceMission, 0, ',', ' ') ?> Ar</span>
                            </div>
                            
                            <!-- Ligne Dépenses liquidées (addition) -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px dashed #cccccc;">
                                <span style="color: #333333;">+ Dépenses liquidées</span>
                                <span style="font-weight: 500; color: #000000;">+ <?= number_format($totalDepenseLiquidation, 0, ',', ' ') ?> Ar</span>
                            </div>
                            
                            <!-- Ligne Reliquat liquidation (addition) -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px dashed #cccccc;">
                                <span style="color: #333333;">+ Reliquat liquidation</span>
                                <span style="font-weight: 500; color: #000000;">+ <?= number_format($reliquatDerniereLiquidation, 0, ',', ' ') ?> Ar</span>
                            </div>
                            
                            <!-- NOUVELLE LIGNE : Total commandes (soustraction) -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 2px solid #333333; background: #fafafa;">
                                <span style="color: #333333; font-weight: 500;">- Total commandes </span>
                                <span style="font-weight: 600; color: #000000;">- <?= number_format($total_commandes, 0, ',', ' ') ?> Ar</span>
                            </div>
                            
                            <!-- Résultat -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; background: #f5f5f5; margin-top: 5px; border-radius: 4px; padding: 10px;">
                                <span style="font-weight: 500;">= </span>
                                <span style="font-weight: 600; font-size: 1.2rem; color: <?= $resteCalcule >= 0 ? '#000000' : '#000000' ?>;">
                                    <?= number_format($resteCalcule, 0, ',', ' ') ?> Ar
                                </span>
                            </div>
                            
                            <!-- Note sur l'équation -->
                            <!-- <div style="margin-top: 10px; padding: 8px; background: #f5f5f5; border-radius: 4px; font-size: 0.9rem; color: #666666; text-align: center; border: 1px solid #dddddd;">
                                <span>Formule : Montant alloué - Avances + Dépenses + Reliquat liquidation - Total commandes = Reste budget estimé</span>
                            </div> -->
                        </div>
                    </div>

                    <!-- Tableau de bord des dépenses - Style gris/blanc -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px; margin-bottom: 25px;">
                        <!-- Carte Avances -->
                        <div style="background: #fff; padding: 16px; border-radius: 6px; border: 1px solid #dddddd;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="color: #666666; font-size: 0.9rem;">Avances missions</span>
                                <span style="background: #eeeeee; color: #000000; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;"><?= $pourcentageAvance ?>%</span>
                            </div>
                            <div style="font-size: 1.4rem; font-weight: 500; color: #000000; margin-bottom: 8px;">
                                <?= number_format($totalAvanceMission, 0, ',', ' ') ?> Ar
                            </div>
                            <div style="background: #eeeeee; height: 4px; border-radius: 2px;">
                                <div style="background: #666666; width: <?= $pourcentageAvance ?>%; height: 4px; border-radius: 2px;"></div>
                            </div>
                            <div style="margin-top: 6px; font-size: 0.8rem; color: #999999; text-align: right;">
                                soustrait du budget
                            </div>
                        </div>

                        <!-- Carte Dépenses liquidées -->
                        <div style="background: #fff; padding: 16px; border-radius: 6px; border: 1px solid #dddddd;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="color: #666666; font-size: 0.9rem;">Dépenses liquidées</span>
                                <span style="background: #eeeeee; color: #000000; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;"><?= $pourcentageDepense ?>%</span>
                            </div>
                            <div style="font-size: 1.4rem; font-weight: 500; color: #000000; margin-bottom: 8px;">
                                <?= number_format($totalDepenseLiquidation, 0, ',', ' ') ?> Ar
                            </div>
                            <div style="background: #eeeeee; height: 4px; border-radius: 2px;">
                                <div style="background: #666666; width: <?= $pourcentageDepense ?>%; height: 4px; border-radius: 2px;"></div>
                            </div>
                            <div style="margin-top: 6px; font-size: 0.8rem; color: #999999; text-align: right;">
                                ajouté au budget
                            </div>
                        </div>

                        <!-- Carte Reliquat liquidation -->
                        <div style="background: #fff; padding: 16px; border-radius: 6px; border: 1px solid #dddddd;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="color: #666666; font-size: 0.9rem;">Reliquat liquidation</span>
                                <span style="background: #eeeeee; color: #000000; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">+</span>
                            </div>
                            <div style="font-size: 1.4rem; font-weight: 500; color: #000000; margin-bottom: 8px;">
                                <?= number_format($reliquatDerniereLiquidation, 0, ',', ' ') ?> Ar
                            </div>
                            <?php if ($dateDerniereLiquidation !== '—'): ?>
                                <div style="margin-top: 6px; font-size: 0.8rem; color: #999999; text-align: right;">
                                    dernière liquidation
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- NOUVELLE CARTE : Commandes fournisseurs -->
                        <div style="background: #fff; padding: 16px; border-radius: 6px; border: 1px solid #dddddd;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="color: #666666; font-size: 0.9rem;">Commandes </span>
                                <span style="background: #eeeeee; color: #000000; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    <?= $budgetTotal > 0 ? round(($total_commandes / $budgetTotal) * 100, 1) : 0 ?>%
                                </span>
                            </div>
                            <div style="font-size: 1.4rem; font-weight: 500; color: #000000; margin-bottom: 8px;">
                                <?= number_format($total_commandes, 0, ',', ' ') ?> Ar
                            </div>
                            <div style="background: #eeeeee; height: 4px; border-radius: 2px;">
                                <div style="background: #666666; width: <?= $budgetTotal > 0 ? round(($total_commandes / $budgetTotal) * 100, 1) : 0 ?>%; height: 4px; border-radius: 2px;"></div>
                            </div>
                            <div style="margin-top: 6px; font-size: 0.8rem; color: #999999; text-align: right;">
                                soustrait du budget
                            </div>
                        </div>
                        
                        <!-- Carte Approvisionnements (commentée) -->
                        <!-- <div style="background: #fff; padding: 16px; border-radius: 6px; border: 1px solid #dddddd;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="color: #666666; font-size: 0.9rem;">Approvisionnements</span>
                                <span style="background: #eeeeee; color: #000000; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;"><?= $pourcentageAppro ?>%</span>
                            </div>
                            <div style="font-size: 1.4rem; font-weight: 500; color: #000000; margin-bottom: 8px;">
                                <?= number_format($totalAppro, 0, ',', ' ') ?> Ar
                            </div>
                            <div style="background: #eeeeee; height: 4px; border-radius: 2px;">
                                <div style="background: #666666; width: <?= $pourcentageAppro ?>%; height: 4px; border-radius: 2px;"></div>
                            </div>
                            <div style="margin-top: 6px; font-size: 0.8rem; color: #999999; text-align: right;">
                                engagements
                            </div>
                        </div> -->
                    </div>

                    <!-- Cartes des reliquats - Style gris/blanc -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        <!-- RELIQUAT ESTIMÉ (du contrôleur) -->
                        <!-- <div style="background: #fafafa; padding: 16px; border-radius: 6px; border-left: 4px solid #333333; border-top: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;"> -->
                                <!-- <span style="background: #333333; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500;">reliquat non payer</span> -->
                                <!-- <span style="color: #666666; font-size: 0.85rem;">reliquat non payer</span>
                            </div>
                            <div style="font-size: 1.8rem; font-weight: 500; color: #000000; margin-bottom: 8px;">
                                <?= number_format($reste ?? 0, 0, ',', ' ') ?> Ar
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="color: #666666; font-size: 0.9rem;">% budget</span>
                                <div style="flex: 1; background: #dddddd; height: 4px; border-radius: 2px;">
                                    <div style="background: #666666; width: <?= abs($pourcentageReste) ?>%; height: 4px; border-radius: 2px;"></div>
                                </div>
                                <span style="font-weight: 500; color: #000000;"><?= $pourcentageReste ?>%</span>
                            </div>
                        </div> -->

                        <!-- RELIQUAT DERNIÈRE LIQUIDATION -->
                        <!-- <div style="background: #fafafa; padding: 16px; border-radius: 6px; border-left: 4px solid #333333; border-top: 1px solid #dddddd; border-right: 1px solid #dddddd; border-bottom: 1px solid #dddddd;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                <span style="background: #333333; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500;">DERNIÈRE LIQUIDATION</span>
                                <?php if ($dateDerniereLiquidation !== '—'): ?>
                                    <span style="color: #666666; font-size: 0.8rem;"><?= $dateDerniereLiquidation ?></span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 1.8rem; font-weight: 500; color: #000000;">
                                <?= number_format($reliquatDerniereLiquidation, 0, ',', ' ') ?> Ar
                            </div>
                        </div> -->
                    </div>

                    <!-- Note explicative sobre grise - MODIFIÉE AVEC LA NOUVELLE FORMULE -->
                    <div style="background: #f5f5f5; padding: 12px; border-radius: 4px; margin: 15px 0; border: 1px solid #dddddd;">
                        <p style="margin: 0; font-size: 0.9em; color: #666666;">
                            <span style="font-weight: 500;">Note :</span> Reste budget = Montant alloué - Avances + Dépenses + Reliquat liquidation - Total commandes.<br>
                            Reliquat estimé (contrôleur) : <strong><?= number_format($reste ?? 0, 0, ',', ' ') ?> Ar</strong> | 
                            Reste calculé : <strong><?= number_format($resteCalcule, 0, ',', ' ') ?> Ar</strong>
                        </p>
                    </div>

                    <!-- Statistiques compactes grises -->
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; padding: 12px; background: #fff; border-radius: 4px; border: 1px solid #dddddd; margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #333333; color: white; width: 28px; height: 28px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">📋</span>
                            <div><strong><?= count($missions ?? []) ?></strong> mission(s)</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #333333; color: white; width: 28px; height: 28px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">💰</span>
                            <div><strong><?= count($liquidations ?? []) ?></strong> liquidation(s)</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #333333; color: white; width: 28px; height: 28px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">📦</span>
                            <div><strong><?= count($commandes ?? []) ?></strong> commande(s)</div>
                        </div>
                    </div>
                </div>

                <!-- Projet -->
                <div class="mb-4">
                    <h5>Projet</h5>
                    <p><strong>Code :</strong> <?= $projet->codeprojet ?? '—' ?></p>
                    <p><strong>Créé par :</strong> Admin</p>
                </div>

                <!-- Budget détaillé - MODIFIÉ EN TABLEAU -->
                <div class="mb-4">
                    <h5>Budget</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
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

                <!-- Missions (historique simple) - MODIFIÉ EN TABLEAU -->
                <div class="mb-4">
                    <h5>Missions liées</h5>
                    <?php if (!empty($missions)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Avance</th>
                                        <th>Objet</th>
                                        <th>Lieu</th>
                                        <th>Période</th>
                                        <th>Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($missions as $m): ?>
                                        <tr>
                                            <td class="fw-bold"><?= number_format($m->montant_avance ?? 0, 0, ',', ' ') ?> Ar</td>
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

                <!-- Liquidations (historique simple) - MODIFIÉ EN TABLEAU -->
                <div class="mb-4">
                    <h5>Liquidations</h5>
                    <?php if (!empty($liquidations)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Dépensé</th>
                                        <th>Retourné</th>
                                        <th>Reliquat</th>
                                        <th>Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($liquidations as $l): ?>
                                        <tr>
                                            <td><?= $l->date_liquidation ? date('d/m/Y H:i', strtotime($l->date_liquidation)) : '—' ?></td>
                                            <td class="fw-bold"><?= number_format($l->total_depense ?? 0, 0, ',', ' ') ?> Ar</td>
                                            <td>
                                                <?php if (($l->montant_retourne ?? 0) > 0): ?>
                                                    <?= number_format($l->montant_retourne, 0, ',', ' ') ?> Ar
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold"><?= number_format($l->reliquat ?? 0, 0, ',', ' ') ?> Ar</td>
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

                <!-- COMMANDES / ACHATS LIÉS AU PROJET -->
                <div class="mb-5">
                    <h5>Commandes  liées au projet</h5>

                    <?php if (!empty($commandes)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° commande</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th class="text-end">Montant HT</th>
                                        <th class="text-end">Frais</th>
                                        <th class="text-end">Total TTC</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_commandes = 0;
                                    foreach ($commandes as $cmd): 
                                        $montant_ht = 0;
                                        // Si tu as déjà les lignes dans $cmd->cmpanier (comme dans getFact)
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
                                        <td class="text-end"><?= number_format($montant_ht, 0, ',', ' ') ?> Ar</td>
                                        <td class="text-end"><?= number_format($frais, 0, ',', ' ') ?> Ar</td>
                                        <td class="text-end fw-bold"><?= number_format($total_ttc, 0, ',', ' ') ?> Ar</td>
                                        <td class="text-center">
                                            <!-- Lien vers détail commande (si tu as une route) -->
                                            <!-- <a href="<?= base_url('Listecommande/details/' . $cmd->idcommande) ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir le détail">
                                                <i class="fas fa-eye"></i>
                                            </a> -->
                                            <!-- Ou lien direct facture PDF -->
                                            <a href="<?= base_url('Commande/facture/' . $cmd->cmfacture) ?>" 
                                               class="btn btn-sm btn-outline-info ms-1" 
                                               target="_blank" 
                                               title="Facture PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Total commandes :</td>
                                        <td class="text-end fw-bold fs-5 text-primary">
                                            <?= number_format($total_commandes, 0, ',', ' ') ?> Ar
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <p class="mt-2 text-muted small">
                            Total engagé via commandes  : 
                            <strong class="text-dark"><?= number_format($total_commandes, 0, ',', ' ') ?> Ar</strong>
                        </p>

                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucune commande  enregistrée pour ce projet.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- APPROVISIONNEMENTS -->
                <!-- <div class="mb-4">
                    <h5>Approvisionnements liés au projet</h5>
                    
                    <?php if (!empty($approvisionnements)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Réf</th>
                                        <th>Désignation</th>
                                        <th>Type</th>
                                        <th>Qté</th>
                                        <th>Unité</th>
                                        <th>Prix unitaire</th>
                                        <th>Montant</th>
                                        <th>Dépôt</th>
                                        <th>Fournisseur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($approvisionnements as $app): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($app->dateAppro)) ?></td>
                                            <td><?= htmlspecialchars($app->refmateriel ?? '—') ?></td>
                                            <td><?= htmlspecialchars($app->designationmateriel ?? '—') ?></td>
                                            <td><?= htmlspecialchars($app->typemateriel ?? '—') ?></td>
                                            <td class="text-end"><?= number_format($app->quantite, 0, ',', ' ') ?></td>
                                            <td><?= htmlspecialchars($app->unite ?? '—') ?></td>
                                            <td class="text-end"><?= number_format($app->prix_unitaire ?? 0, 0, ',', ' ') ?> Ar</td>
                                            <td class="text-end fw-bold">
                                                <?= number_format(($app->quantite * ($app->prix_unitaire ?? 0)), 0, ',', ' ') ?> Ar
                                            </td>
                                            <td><?= htmlspecialchars($app->depot ?? '—') ?></td>
                                            <td><?= htmlspecialchars($app->fournisseur ?? '—') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="mt-3 text-muted">
                            <strong>Total engagé en approvisionnement :</strong> 
                            <?= number_format($totalAppro, 0, ',', ' ') ?> Ar
                        </p>
                    <?php else: ?>
                        <p class="text-muted">Aucun approvisionnement enregistré pour ce projet.</p>
                    <?php endif; ?>
                </div> -->

            </div>
        </div>
    </div>
</div>