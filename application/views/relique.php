<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- image modale -->
                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="validation">
                                <div class="container">
                                    <!-- Modal body -->
                                    <div class="image_container">
                                        <div class="_image">
                                            <img id="image" src="<?= base_url('public/images/favicon/photo-camera.png') ?>" alt="">
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer _button">

                                        <a href="#" class="btn btn-info" id="image_ok"><i class="fas fa-check"></i> OK</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success d-none" id="btn_show_image" data-toggle="modal"
                    data-target="#imageModal">show</button>
                <!-- MODAL -->


                <!-- detail modale  -->
                <div class="modal fade" id="showDetails" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Détails</h4>
                                <button type="button" class="btn" data-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="modal-body" id="tab">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- detail modale  -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification du client</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('Liquidation/edit') ?>" id="modifClient" method="post">
                                    <input type="hidden" id="iddepensemodif" name="id">
                                    <div class="mb-1">
                                        <label class="form-label">Raison :</label>
                                        <input name="raison_" id="raison_" type="text" class="form-control input_form-control">
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label">Montant :</label>
                                        <input name="montant_" id="montant_" type="number" min='0' class="form-control input_form-control">
                                    </div>


                                    <div class="mt-2">
                                        <button type="button" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i>Modifier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if ($this->session->flashdata('edition')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php endif; ?>

                <!-- FIN MODAL -->


                <!-- Modal pour le demandeur  -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Choisissez le demandeur </h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal-body" id="demandeur_modal" style="font-size: 14px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn d-none" id="select_demandeur" data-bs-toggle="modal" data-bs-target="#myModal">Demandeur</button>
                <!-- Modal pour le demandeur  -->

                <!-- ********************************** -->
                <div class="onglet">
                   <!--  <a href="<?= base_url('activite') ?>" class="onglet_btn ">Activité</a> -->
                    <a href="<?= base_url('budget') ?>" class="onglet_btn ">Budget</a>
                    <a href="<?= base_url('mission') ?>" class="onglet_btn ">Activité & Mission</a>
                    <a href="<?= base_url('liquidation') ?>" class="onglet_btn">Liquidation</a>
                    <a href="<?= base_url('Relique') ?>" class="onglet_btn active">Reliquat</a>
                </div>


                <form action="<?= base_url('Liquidation/register') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">N° OM ou N° ASM :</label>
                            <input type="text" class="form-control" name="numero_om" id="numero_om" required>
                            <input type="text" class="form-control d-none" name="idmission" id="idmission" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Type </label>
                            <input type="text" class="form-control" name="typeMission" id="typeMission" required readonly>
                        </div>
                        <div class="col">
                            <label class="form-label">Objet </label>
                            <input type="text" class="form-control" name="objet" id="objet" required readonly>
                        </div>
                    </div>
                   
                    <div class="mb-2">
                        <label class="form-label">Nom de l'agent : </label>
                        <input type="text" class="form-control" name="nomagent" id="nomagent" required readonly>
                        <input type="hidden" class="form-control" name="idagent" id="idagent" required readonly>
                    </div>
                    <div class="accordion" id="budget_container">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Montant total de l'avance : </label>
                        <input type="text" class="form-control" id="totalAvance" required readonly>
                        <input type="text" class="form-control d-none" id="totalAvanceHidden" name="totalAvance" required readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Total dépensé : </label>
                        <input type="text" class="form-control" id="_depenser" required readonly>
                        <input type="number" class="form-control d-none" id="_depenser_val" name="_depenser" required readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Total retourné : </label>
                        <input type="text" min='0' class="form-control" id="total_return" name="retourner" required readonly>
                        <input type="number" class="form-control d-none" id="total_return2" name="_retourner2" required readonly>
                    </div>
                    <div class="mb-2 d-none">
                        
                        <input type="text" min='0' class="form-control" id="idMission" name="idMission" required readonly>
                        <input type="text" min='0' class="form-control" id="idLiquidation" name="idLiquidation" required readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Total non retouné : </label>
                        <input type="text" class="form-control" id="nonRetour" required readonly>
                        <input type="text" class="form-control d-none" id="nonRetourHidden"  required readonly>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label">Rendu maintenant : </label>
                        <input type="number" min='0' class="form-control d-none" id="aRendreHidden" name="aRendreHidden" required readonly>
                        <input type="text" min='0' class="form-control" id="aRendre" name="aRendre" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Reste : </label>
                        <input type="number" min='0' class="form-control d-none" id="resteHidden" name="resteHidden" required readonly>
                        <input type="text" min='0' class="form-control" id="reste" name="reste" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de remise de reliquat :</label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control" name="date" value="<?= date("Y-m-d") ?>">
                            <input type="time" class="form-control" name="heure" value="<?= date("H:i") ?>">
                        </div>
                    </div>

                    <div class="_boutton mt-4 mb-4">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <button type="button" class="btn btn-info" disabled>
                                <i class="fas fa-check"></i>
                                Valider
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-info" id="valider">
                                <i class="fas fa-check"></i>
                                <div class="spinner-wrapper d-none" id="spinner_validation">
                                    <div class="spinner-border"></div>
                                </div>
                                Valider
                            </button>
                            <button type="button" class="d-none" id="T-valide">ok</button>
                        <?php endif  ?>
                    </div>
                </form>

                <!-- Liste des reliquats enregistrés -->
<div class="_tableau mt-5">


    <table class="table table-hover table-bordered">
      <div class="table-responsive">
    <table class="table table-hover table-striped table-bordered align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th scope="col">#</th>
                <th scope="col">N° Reliquat</th>
                <th scope="col">Date Liquidation</th>
                <th scope="col">Mission </th>
                <th scope="col">Montant retourné</th>
                <th scope="col">Date retour</th>
                <th scope="col">Reste</th>
                <?php if (can_manage('relique')) : ?>
                    <!-- <th scope="col">Actions</th> -->
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php if (!empty($reliquats)) : ?>
                <?php 
                    $i = ($page ?? 1 - 1) * ($per_page ?? 20) + 1; 
                    foreach ($reliquats as $rel) : 
                ?>
                    <tr>
                        <td class="text-center fw-medium"><?= $i++ ?></td>
                        <td class="text-center"><?= $rel->idRelique ?></td>
                        
                      <!-- Date de liquidation -->
<td class="text-center">
    <?php if (!empty($rel->date_liquidation)) : ?>
        <?= date('d/m/Y', strtotime($rel->date_liquidation)) ?>
        <span class="text-muted ms-2">à <?= date('H:i', strtotime($rel->date_liquidation)) ?></span>
    <?php else : ?>
        <span class="text-muted">—</span>
    <?php endif; ?>
</td>

                        
                        <!-- Type + Objet de la mission -->
                        <td>
                            <!-- <strong><?= htmlspecialchars($rel->typeMission ?: '—') ?></strong> -->
                            <?php if (!empty($rel->objet_mission)) : ?>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($rel->objet_mission) ?></small>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-end pe-4 fw-semibold">
                            <?= number_format($rel->montantReturn, 0, ',', ' ') ?> 
                            <small class="text-muted">Ar</small>
                        </td>
                      <!-- Date de liquidation -->

<!-- Date de retour -->
<td class="text-center">
    <?= date('d/m/Y', strtotime($rel->dateReturn)) ?>
    <span class="text-muted ms-2">à <?= date('H:i', strtotime($rel->dateReturn)) ?></span>
</td>
                        
                        <td class="text-end pe-4 <?= ($rel->resteReturn <= 0) ? 'text-success fw-bold' : 'text-danger fw-bold' ?>">
                            <?= number_format($rel->resteReturn, 0, ',', ' ') ?> 
                            <small>Ar</small>
                        </td>
<!--                         
                        <?php if (can_manage('relique')) : ?>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm delete-relique rounded-pill px-3"
                                        data-id="<?= $rel->idRelique ?>"
                                        title="Supprimer ce reliquat">
                                    <i class="fa-solid fa-trash-can me-1"></i> Suppr.
                                </button>
                            </td>
                        <?php endif; ?> -->
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="<?= can_manage('relique') ? 8 : 7 ?>" class="text-center py-5 text-muted bg-light">
                        <i class="fa-solid fa-folder-open fa-2x mb-3 d-block text-secondary"></i>
                        <div class="fs-5">Aucun reliquat enregistré pour le moment</div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if (isset($pagination)) : ?>
    <div class="d-flex justify-content-center mt-4">
        <?= $pagination ?>
    </div>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>