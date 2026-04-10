<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- En-tête avec infos bénéficiaire -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4>Historique des missions - <?= htmlspecialchars($beneficiaire->nom) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <h5>Total missions</h5>
                                    <h3><?= $statistiques->total_missions ?? 0 ?></h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <h5>Total avances</h5>
                                    <h3><?= number_format($statistiques->total_avances ?? 0, 0, ',', ' ') ?> Ar</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <h5>Moyenne avance</h5>
                                    <h3><?= number_format($statistiques->moyenne_avance ?? 0, 0, ',', ' ') ?> Ar</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <h5>Première mission</h5>
                                    <h3><?= $statistiques->premiere_mission ? date('d/m/Y', strtotime($statistiques->premiere_mission)) : 'N/A' ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau de l'historique -->
                <div class="_tableau">
                    <table class="table table-bordered">
                        <thead class="table-info">
                            <tr>
                                <th>N° OM</th>
                                <th>N° ASM</th>
                                <th>Objet</th>
                                <th>Lieu</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Montant avance</th>
                                <th>Projet</th>
                                <th>Agent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($historique)): ?>
                                <tr>
                                    <td colspan="10" class="text-center">Aucune mission trouvée pour ce bénéficiaire</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($historique as $mission): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($mission->numero_om) ?></td>
                                        <td><?= htmlspecialchars($mission->numero_asm) ?></td>
                                        <td><?= htmlspecialchars($mission->objet_mission) ?></td>
                                        <td><?= htmlspecialchars($mission->lieu_mission) ?></td>
                                        <td><?= date('d/m/Y', strtotime($mission->date_debut_mission)) ?></td>
                                        <td><?= date('d/m/Y', strtotime($mission->date_fin_mission)) ?></td>
                                        <td class="text-end"><?= number_format($mission->montant_avance, 0, ',', ' ') ?> Ar</td>
                                        <td><?= htmlspecialchars($mission->codeprojet) ?></td>
                                        <td><?= htmlspecialchars($mission->agent_nom . ' ' . $mission->agent_prenom) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-detail" data-id="<?= $mission->idmission ?>">
                                                <i class="fas fa-eye"></i> Détails
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <?php if (!empty($pagination)): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?= $pagination ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la mission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center">
                    <div class="spinner-border"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btn-detail').click(function() {
        var idmission = $(this).data('id');
        $.ajax({
            url: base_url('Mission/details'),
            type: 'post',
            data: { idmission: idmission },
            success: function(response) {
                $('#detailContent').html(response);
                $('#detailModal').modal('show');
            }
        });
    });
});
</script>