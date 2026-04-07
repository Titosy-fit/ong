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
                                <form action="<?= base_url('Projet/edit') ?>" method="post">
                                    <div class="mb-2">
                                        <label class="form-label">Code du projet :</label>
                                        <input name="code" id="code_modif" type="text" class="form-control input_form-control " required>
                                        <input name="idprojet" id="id_modif" type="hidden" class="form-control input_form-control " required>
                                        <p class="text-danger d-none" id="ms_code_modif">Ce code de projet est déjà utilisé.</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Titre :</label>
                                        <input name="titre" id="titre_modif" type="text" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Date de début :</label>
                                        <input name="date_debut" id="date_debut_modif" type="date" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Date de fin :</label>
                                        <input name="date_fin" id="date_fin_modif" type="date" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Region :</label>
                                        <input name="region" id="region_modif" type="text" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-info d-none" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <a href="#" id="modifier" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Modifier</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->

                <h5 class="mb-3">Projet</h5>

                <form method="post" enctype="multipart/form-data" action="<?= base_url('Projet/import_csv') ?>">
                    <div class="mb-2">
                        <label class="form-label">Mode d'insertion : </label>
                        <select class="form-control input_form-control" id="choix"> 
                            <option value="saisie">Saisie</option>
                            <option value="importation">Importation CSV</option>
                        </select>
                    </div>
                    <div class="mb-2 import">
                        <label class="form-label">Selectionnez un fichier </label>
                        <input type="file" name="csv_file" accept=".csv" required class="form-control input_form-control">
                    </div>
                    <div class="_boutton import">
                        <button class="btn btn-primary" id="valid-import" type="submit" name="import">
                            <i class="fas fa-check"></i> Valider
                        </button>
                    </div>
                </form>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                    <form action="" method="post" class="data-saisie">
                <?php else : ?>
                    <form action="<?= base_url('Projet/register') ?>" method="post" class="data-saisie">
                <?php endif; ?>

                    <?php if (can_manage('projet')) : ?>
                        <div class="mb-2 ">
                            <label class="form-label">Code du projet :</label>
                            <input name="code" id="code" type="text" class="form-control input_form-control " required>
                            <p class="text-danger d-none" id="ms_code">Ce code de projet est déjà utilisé.</p>
                        </div>
                        <div class="mb-2 ">
                            <label class="form-label">Titre :</label>
                            <input name="titre" id="titre" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Date de début :</label>
                            <input name="date_debut" id="date_debut" type="date" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2 ">
                            <label class="form-label">Date de fin :</label>
                            <input name="date_fin" id="date_fin" type="date" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2 ">
                            <label class="form-label">Region :</label>
                            <input name="region" id="region" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="_boutton">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
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
                                <button type="button" class="btn d-none" id="t-valider">Valider</button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->userdata('added_projet')) : ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                Myalert.added();
                            });
                        </script>
                    <?php endif; ?>
                    <?php $this->session->unset_userdata('added_projet') ?>

                    <?php if ($this->session->userdata('wrong_columns')) : ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                Myalert.erreur("Une erreur s'est produite vérifiez votre fichier csv.") ;
                            });
                        </script>
                    <?php endif; ?>
                    <?php $this->session->unset_userdata('wrong_columns') ?>

                    <?php if ($this->session->userdata('vide')) : ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                Myalert.erreur("Aucune n'a été insérée") ;
                            });
                        </script>
                    <?php endif; ?>
                    <?php $this->session->unset_userdata('vide') ?>
                </form>

                <?php if ($this->session->userdata('adit_projet')) : ?>
                    <script>Myalert.updated()</script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('adit_projet') ?>

                <?php if ($this->session->userdata('delete_projet')) : ?>
                    <script>Myalert.deleted()</script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('delete_projet') ?>

                <?php if ($this->session->userdata('success')) : ?>
                    <script>Myalert.added()</script>
                <?php endif; ?>

                <form action="<?= base_url('Projet/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                    <div class="group_form row">
                        <label for="" class="form-label">Recherche : </label>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="mot" value="<?= $_POST['mot'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form row">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <button class="btn btn-info" type="button" disabled>
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        <?php else : ?>
                            <button class="btn btn-info" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="d-flex">
                    <a href="<?php echo base_url('ExportationExel/exportProjet') ?>" class="btn btn-success btn-export ms-auto">
                        <i class="fa-regular fa-file-excel"></i>
                        Exporter
                    </a>
                </div>

                <div class="_tableau mt-4">
                    <table class="table table">
                        <thead class="table-info">
                            <tr>
                                <th>Code du projet </th>
                                <th>Titre </th>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Region</th>
                                <?php if (can_manage('projet')) : ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $datas = $data['projet'] ?>
                            <?php foreach ($datas as $key => $projet) : ?>
                                <tr>
                                    <td><?= $projet->codeprojet ?></td>
                                    <td><?= $projet->titreprojet ?></td>
                                    <td><?= date("d/m/Y", strtotime($projet->date_debut)) ?></td>
                                    <td><?= date("d/m/Y", strtotime($projet->date_fin)) ?> </td>
                                    <td><?= $projet->regionprojet ?></td>

                                    <?php if (can_manage('projet')) : ?>
                                        <td>
                                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                                <button class="btn btn-danger" type="button" disabled>
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning " disabled>
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>
                                            <?php else : ?>
                                                <button class="btn btn-danger delete" type="button" data-id="<?= $projet->idprojet ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $projet->idprojet ?>">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                    <?php if ($nPages > 1) : ?>
                        <div class="__pagination">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center pagination-sm">
                                    <!-- Previous -->
                                    <li class="page-item">
                                        <?php if ($current == 1) : ?>
                                            <span class="page-link __disabled" aria-label="Previous">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                            </span>
                                        <?php else : ?>
                                            <?php if (isset($_POST['date_debut'])) : ?>
                                                <form action="<?= base_url('Projet/search/' . ($current - 1)) ?>" method="post">
                                                    <!-- tes champs cachés pour conserver les filtres -->
                                                    <div class="row d-none">
                                                        <div class="col-4 row">
                                                            <label for="date_debut" class="form-label">Date de début : </label>
                                                            <div class="input-group mb-3">
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4 row">
                                                            <label for="date_fin" class="form-label">Date de fin : </label>
                                                            <div class="input-group mb-3">
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="lieu" class="form-label"> Dépôt : </label>
                                                            <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                            <div class="input-group mb-3">
                                                                <input type="text" class="form-control" name="mot" value="<?= $_POST['mot'] ?? '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href='<?= base_url('Projet/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) : ?>
                                        <li class="page-item <?= ($i == $current) ? 'active' : '' ?>">
                                            <?php if (isset($_POST['date_debut'])) : ?>
                                                <form action="<?= base_url('Projet/search/' . $i) ?>" method="post">
                                                    <!-- mêmes champs cachés -->
                                                    <div class="row d-none">
                                                        <!-- ... identique à ci-dessus ... -->
                                                    </div>
                                                    <button class="page-link" type="submit"><?= $i ?></button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href="<?= base_url('Projet/page/' . $i) ?>"><?= $i ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>

                                    <li>
                                        <?php if ($current == $nPages) : ?>
                                            <span class="page-link __disabled" aria-label="Next">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                            </span>
                                        <?php else : ?>
                                            <?php if (isset($_POST['date_debut'])) : ?>
                                                <form action="<?= base_url('Projet/search/' . ($current + 1)) ?>" method="post">
                                                    <!-- champs cachés -->
                                                    <div class="row d-none">
                                                        <!-- ... identique ... -->
                                                    </div>
                                                    <button class="page-link" type="submit" aria-label="Next">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href="<?= base_url('Projet/page/' . ($current + 1)) ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
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