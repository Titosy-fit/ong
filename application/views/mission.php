<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('Mission/edit') ?>" method="post">

                                    <input name="idmission" id="idmission" type="text" class="form-control input_form-control d-none" required>
                                    <div class="mb-2">
                                        <label class="form-label">Objet du mission :</label>
                                        <input name="objet" id="objet_modif" type="text" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Lieu du mission :</label>
                                        <input name="lieu" id="lieu_modif" type="text" class="form-control input_form-control " required>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label for="asm" class="form-label">N° ASM : </label>
                                            <input type="text" class="form-control" name="asm" id="asm_modif">
                                            <p class="text-danger d-none" id="ams_sm_modif">Cet ASM est déjà utilisé.</p>
                                        </div>
                                        <div class="col">
                                            <label for="om" class="form-label">N° OM : </label>
                                            <input type="text" class="form-control" name="om" id="om_modif">
                                            <p class="text-danger d-none" id="om_sm_modif">Cet OM est déjà utilisé.</p>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Date de début :</label>
                                        <input name="date_debut" id="date_debut_modif" type="date" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Date de fin :</label>
                                        <input name="date_fin" id="date_fin_modif" type="date" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mt-2 _boutton">
                                        <button type="button" class="btn btn-info" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <button type="button" id="TM-valider" class="d-none">Ok</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->

                <!-- Modal pour le demandeur  -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Choisissez l'agent </h4>
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
                <a href="#" id="select_demandeur" data-toggle="modal" data-target="#myModal" class="btn btn-secondary d-none"><i class="fas fa-list"></i></a>
                <!-- Modal pour le demandeur  -->
                <!-- Modal pour le demandeur  -->
                <div class="modal fade" id="mybudgetmodal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Veuillez sélectionner les budgets.</h4>
                                <button type="button" class="btn-close close_budget d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal-body" id="budget_modal" style="font-size: 14px;">
                                    <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal pour le demandeur  -->
                <!-- Modal pour le details  -->
                <div class="modal fade" id="details_modal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Les budgets utilisés</h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal-body" id="details_modal_containt" style="font-size: 14px;">
                                    <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal pour le details  -->

                <div class="onglet">
                    <!-- <a href="<?= base_url('activite') ?>" class="onglet_btn ">Activité</a> -->
                    <a href="<?= base_url('budget') ?>" class="onglet_btn ">Budget</a>
                    <a href="<?= base_url('mission') ?>" class="onglet_btn active">Activité & Mission</a>
                    <a href="<?= base_url('liquidation') ?>" class="onglet_btn ">Liquidation</a>
                    <a href="<?= base_url('Relique') ?>" class="onglet_btn ">Reliquat</a>
                </div>

                <form action="<?= base_url('Mission/register') ?>" method="post">
                    <!-- Selection Agent direct sur la page -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Agent utilitaire :</label>
                        <div class="input-group">
                            <input type="text" id="agent_search" class="form-control" placeholder="Tapez le nom ou prénom de l'agent...">
                            <button type="button" class="btn btn-info" id="btn_search_agent">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        </div>
                        <div id="agent_results" class="mt-2" style="max-height: 250px; overflow-y: auto; display: none; border: 1px solid #ddd; border-radius: 5px; background: white;">
                        </div>
                        <div id="selected_agent_info" class="mt-2 alert alert-success d-none">
                            <i class="fa-solid fa-user-check"></i> <strong>Agent sélectionné :</strong>
                            <span id="selected_agent_name"></span>
                            <button type="button" class="btn btn-sm btn-danger float-end" id="clear_agent">
                                <i class="fa-solid fa-times"></i> Annuler
                            </button>
                        </div>
                        <input type="hidden" id="idagent" name="idagent">
                    </div>
                    <!-- ==================== AGENT BÉNÉFICIAIRE (nouvelle table) ==================== -->
<div class="mb-3">
    <label class="form-label fw-bold">Agent bénéficiaire :</label>
    <div class="input-group">
        <input type="text" id="beneficiaire_search" class="form-control" placeholder="Nom ou prénom du bénéficiaire...">
        <button type="button" class="btn btn-info" id="btn_search_beneficiaire">
            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
        </button>
        <button type="button" class="btn btn-success" id="btn_add_new_beneficiaire">
            <i class="fa-solid fa-plus"></i> Nouveau bénéficiaire
        </button>
    </div>

    <div id="beneficiaire_results" class="mt-2" style="max-height: 250px; overflow-y: auto; display: none; border: 1px solid #ddd; border-radius: 5px; background: white;"></div>

    <div id="selected_beneficiaire_info" class="mt-2 alert alert-success d-none">
        <i class="fa-solid fa-user-check"></i> <strong>Bénéficiaire sélectionné :</strong>
        <span id="selected_beneficiaire_name"></span>
        <button type="button" class="btn btn-sm btn-danger float-end" id="clear_beneficiaire">
            <i class="fa-solid fa-times"></i> Annuler
        </button>
    </div>
    <input type="hidden" id="idbeneficiaire" name="idbeneficiaire" required>
</div>

                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label">Type :</label>
                            <select name="typeMission" id="typeMission" class="form-control input_form-control" required>
                                <option value="Mission">Mission</option>
                                <option value="Activité">Activité</option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label">Code projet :</label>
                            <input name="codeprojet" id="codeprojet" type="text" class="form-control input_form-control " required>
                            <input name="projet" id="projet" type="text" class="form-control input_form-control d-none" required>

                            <p class="text-danger d-none" id="ms_projet">Veuillez choisir le projet et l'activité.</p>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-8">
                            <label class="form-label">Titre du projet :</label>
                            <input name="titreprojet" id="titreprojet" type="text" class="form-control input_form-control " readonly>
                        </div>

                        <div class="col-4">
                            <div>
                                <label class="form-label">Choisir les budgets à utilisés :</label>
                            </div>
                            <button type="button" class="btn btn-info" id="select_budget"><i class="fas fa-dollar-sign"></i> Budgets </button>
                            <a href="#" id="active_buget_modal" data-toggle="modal" data-target="#mybudgetmodal" class="btn btn-secondary d-none"><i class="fas fa-list"></i></a>
                        </div>
                    </div>

                    <div id="budget_container" class="d- p-1">
                    </div>



                    <div class="mb-2">
                        <label class="form-label">Montant total avancé :</label>
                        <input name="avance" id="avance" type="text" class="form-control input_form-control " required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Objet :</label>
                        <input name="objet" id="objet" type="text" class="form-control input_form-control " required>
                    </div>

                    <div class="row mb-2">
                        <div class="col">
                            <label for="asm" class="form-label">N° ASM : </label>
                            <input type="text" class="form-control" name="asm" id="asm">
                            <p class="text-danger d-none" id="ams_sm">Cet ASM est déjà utilisé.</p>
                        </div>
                        <div class="col">
                            <label for="om" class="form-label">N° OM : </label>
                            <input type="text" class="form-control" name="om" id="om">

                            <p class="text-danger d-none" id="om_sm">Cet OM est déjà utilisé.</p>
                        </div>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Date de début :</label>
                        <input name="date_debut" id="date_debut" type="date" class="form-control input_form-control " required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Date de fin :</label>
                        <input name="date_fin" id="date_fin" type="date" class="form-control input_form-control " required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Lieu :</label>
                        <input name="lieu" id="lieu" type="text" class="form-control input_form-control " required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Date de réception de l'avance :</label>
                        <input name="date_reception" id="date_reception" type="date" class="form-control input_form-control " required>
                    </div>

                    <div class="_boutton">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <button type="button" class="btn btn-info" disabled>
                                <i class="fas fa-check"></i>
                                Valider
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-info " id="valider">
                                <i class="fas fa-check"></i>
                                <div class="spinner-wrapper d-none" id="spinner_validation">
                                    <div class="spinner-border"></div>
                                </div>
                                Valider
                            </button>

                            <button type="button" class="btn d-none" id="T-valider">Valider</button>
                        <?php endif  ?>
                    </div>



                    <?php if ($this->session->userdata('added_projet')): ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                Myalert.added();
                            });
                        </script>
                        <?php $this->session->unset_userdata('added_projet'); ?>
                    <?php endif; ?>
                </form>

                <?php if ($this->session->userdata('adit_projet')) : ?>
                    <script>
                        Myalert.updated()
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('adit_projet') ?>
                <?php if ($this->session->userdata('delete_projet')) : ?>
                    <script>
                        Myalert.deleted()
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('delete_projet') ?>

                <!-- <h5>Filtre :</h5> -->
                <!-- Recherche par projet  -->
                <!-- <?php if (count($projets)) : ?>
                <?php endif ?>
                <div class="owl-carousel">
                    <?php foreach ($projets as $key => $projet) :  ?>
                        <form action="<?= base_url('Mission/searchProjet') ?>" method="get">
                            <input type="hidden" name="projet" value="<?= $projet->idprojet ?>">
                            <button type="submit" class="projet_ <?= (isset($_POST['idprojet']) && $_POST['idprojet'] == $projet->idprojet) ? 'projet_active' : '' ?>">
                                <div>
                                    <span class="icone_search"><i class="fas fa-key text-info"></i></span>
                                    <?= $projet->codeprojet ?>
                                </div>
                                <div>
                                    <span class="icone_search"><i class="fas fa-map-marker-alt text-primary"></i></span>
                                    <?= $projet->regionprojet ?>
                                </div>
                            </button>
                        </form>

                    <?php endforeach ?>
                </div> -->
                <!-- Recherche par projet  -->
                <!-- Filtre  -->
                <!-- <div class="filtre_mission mt-3">
                    <form action="<?= base_url('Mission/filtre-reliquat') ?>" method="get">
                        <input type="hidden" name="projet" value="<?= $_POST['idprojet'] ?? '' ?>">
                        <button class="btn btn-info"><i class="fas fa-money-bill-wave"></i> Reliquat non retourné</button>
                    </form>
                    <form action="<?= base_url('Mission/filtre-liquidation') ?>" method="get">
                        <input type="hidden" name="projet" value="<?= $_POST['idprojet'] ?? '' ?>">
                        <button class="btn btn-danger"><i class="fas fa-exclamation-circle"></i> Liquidation non éffectué</button>
                    </form>
                </div> -->
                <!-- Filtre  -->
                <div class="_tableau mt-4">
                    <table class="table table">
                        <thead class="table-info">
                            <tr>
                                <th>Code projet</th>

                                <th>Objet du mission</th>
                                <th>Numero</th>
                                <th>Agent</th>
                                <th>Tel agent</th>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Lieu</th>
                                <th>Avance</th>
                                <th>Editeur</th>
                                <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') :  ?>
                                    <th>Actions</th>
                                <?php endif  ?>
                            </tr>
                        </thead>
                        <?php $datas = $data['mission'];

                        ?>
                        <tbody class="">

                            <?php foreach ($datas as $key => $mission) :  ?>
                                <tr class="<?= (isset($_POST['filtre']))  ? 'table-danger' : '' ?>">
                                    <td><?= $mission->codeprojet  ?></td>

                                    <td><?= $mission->objet_mission  ?></td>
                                    <td>
                                        <p class="m-0"><span style="display: inline-block; width:40px ">ASM </span>: <?= $mission->numero_asm  ?></p>
                                        <p class="m-0"><span style="display: inline-block; width:40px ">OM </span>: <?= $mission->numero_om  ?></p>
                                    </td>
                                    <td><?= strtoupper($mission->nomagent) . ' ' . ucfirst($mission->prenomagent)  ?></td>
                                    <td><?= $mission->telagent  ?></td>
                                    <td><?= only_date($mission->date_debut_mission) ?></td>
                                    <td><?= only_date($mission->date_fin_mission) ?></td>
                                    <td><?= $mission->lieu_mission ?></td>
                                    <td><?= number_three($mission->montant_avance) ?></td>
                                    <td><?= ($mission->nomUser != '') ? strtoupper($mission->nomUser) . ' ' .  ucfirst($mission->prenomUser) : 'Admin'  ?></td>
                                    <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') :  ?>
                                        <td>
                                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                <a href="#" class="btn btn-secondary"><i class="fas fa-list"></i></a>

                                                <button class="btn btn-danger" type="button" disabled>
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>


                                            <?php else : ?>

                                                <a href="#" data-toggle="modal" data-target="#details_modal" class="btn btn-secondary detail" data-id="<?= $mission->idmission ?>"><i class="fas fa-list"></i></a>
                                                <button class="btn btn-danger delete" type="button" data-id="<?= $mission->idmission ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>

                                            <?php endif; ?>
                                        </td>
                                    <?php endif  ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php if (!empty($data['lien'])): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?= $data['lien'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>