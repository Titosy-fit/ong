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
                    <a href="<?= base_url('liquidation') ?>" class="onglet_btn active">Liquidation</a>
                    <a href="<?= base_url('Relique') ?>" class="onglet_btn ">Reliquat</a>
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
                    <!-- <div class="mb-2">
                        <label class="form-label">Titre de l'acticité : </label>
                        <input type="text" class="form-control" name="titre_activite" id="titre_activite" required readonly>
                    </div> -->
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
                        <label class="form-label">Reste : </label>
                        <input type="text" class="form-control" id="reliquat" required readonly>
                        <input type="text" class="form-control d-none" id="resteHidden"  required readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Montant retourné : </label>
                        <input type="number" min='0' class="form-control" id="retourner" name="retourner" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Reliquat non retourné : </label>
                        <input type="number" min='0' class="form-control d-none" id="nonRetournerHidden" name="nonRetournerHidden" required readonly>
                        <input type="text" min='0' class="form-control" id="nonRetourner" name="nonRetourner" required readonly>
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

                <?php if ($this->session->userdata('erreur_register')) :  ?>
                    <script>
                        Myalert.erreur();
                    </script>
                <?php endif  ?>
                <?php $this->session->unset_userdata('erreur_register') ?>
                <?php if ($this->session->userdata('added')) :  ?>
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            Myalert.added();
                        });
                        
                    </script>
                <?php endif  ?>
                <?php $this->session->unset_userdata('added') ?>

                <!-- <form action="<?= base_url('Liquidation/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                    <div class="group_form row">
                        <label for="date_debut" class="form-label">Date de début : </label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                            <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form row">
                        <label for="date_fin" class="form-label">Date de fin : </label>
                        <div class="input-group  mb-3">
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                            <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form row">
                        <label for="date_fin" class="form-label">Recherche : </label>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form row">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <button class="btn btn-info" type="button" disabled>
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        <?php else : ?>
                            <button class="btn btn-info" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        <?php endif  ?>
                    </div> -->
                <!-- </div> -->
                <!-- </form> -->


                <!-- Recherche par projet  -->
                <?php if (count($projets)) : ?>
                <?php endif ?>
                <div class="owl-carousel">
                    <?php foreach ($projets as $key => $projet) :  ?>
                        <form action="<?= base_url('Liquidation/searchProjet') ?>" method="post">
                            <input type="hidden" name="idprojet" value="<?= $projet->idprojet ?>">
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
                </div>
                <!-- Recherche par projet  -->

                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Code du projet </th>
                                <th>N° OM </th>
                                <th>N° ASM</th>
                                <th>Objet </th>
                                <th>Type </th>
                                <th>Date de liquidation</th>
                                <th>Avance</th>
                                <th>Total dépensé</th>
                                <th>Total reliquat</th>
                                <th>Montant retourné</th>
                                <th>Agent</th>
                                <th>Editeur</th>
                                <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                                    <th>Action</th>
                                <?php endif; ?>

                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php foreach ($datas as $key => $data) : ?>
                                <tr>
                                    <td><?= $data->codeprojet ?></td>
                                    <td><?= $data->numero_om ?></td>
                                    <td><?= $data->numero_asm ?></td>
                                    <td><?= $data->objet_mission ?></td>
                                    <td><?= $data->typeMission?></td>
                                    <td><?= form_date($data->date_liquidation) ?></td>
                                    <td><?=  number_three($data->avance)?></td>
                                    <td><?= number_three($data->montant_depense) ?></td>
                                    <td><?= number_three($data->montant_reliquat) ?></td>
                                    <td><?= number_three($data->montant_return) ?></td>
                                    <td><?= strtoupper($data->nomUser) . ' '  . ucfirst($data->prenomUser)   ?></td>
                                    <td><?= 
                                    ($data->nomEdit != null) ? $data->nomEdit . ' '  . $data->prenomEdit : 'Admin'  ?>
                                        
                                    </td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#showDetails" class="btn btn-secondary detail" data-id="<?= $data->idliquidation ?>"><i class="fas fa-list"></i></a>

                                        <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                                            <a href="#" data-id="<?= $data->idliquidation ?>" class="btn btn-danger supprimer"><i class="fa-solid fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                    <?php if (isset($nPages) && $nPages > 1): ?>
    <div class="__pagination mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center pagination-sm">
                <!-- Précédent -->
                <li class="page-item <?= ($current <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= base_url('liquidation?page=' . max(1, $current - 1)) ?>" aria-label="Previous">
                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                    </a>
                </li>

                <!-- Numéros de page -->
                <?php for ($i = 1; $i <= $nPages; $i++): ?>
                    <li class="page-item <?= ($i == $current) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= base_url('liquidation?page=' . $i) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Suivant -->
                <li class="page-item <?= ($current >= $nPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= base_url('liquidation?page=' . ($current + 1)) ?>" aria-label="Next">
                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>


                </div>
            </div>
        </div>
    </div>
</div>