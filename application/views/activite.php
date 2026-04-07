<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- MODAL -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modification de l'Utilisateur</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form action="<?= base_url('Activite/edit') ?>" method="post">

                                    <div class="mb-2">
                                        <label class="form-label">Titre :</label>
                                        <input name="titre" id="titre_modif" type="text" class="form-control input_form-control " required>
                                        <p class="text-danger d-none" id="ms_titre_modif">Ce titre est déjà utilisé.</p>

                                        <input name="idactivite" id="id_modif" type="text" class="form-control input_form-control d-none" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Date de début :</label>
                                        <input name="date_debut" id="date_debut_modif" type="date" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Date de fin :</label>
                                        <input name="date_fin" id="date_fin_modif" type="date" class="form-control input_form-control " required>
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

                <div class="onglet">
                    <!-- <a href="<?= base_url('activite') ?>" class="onglet_btn active">Activité</a> -->
                    <a href="<?= base_url('budget') ?>" class="onglet_btn ">Budget</a>
                    <a href="<?= base_url('mission') ?>" class="onglet_btn ">Mission</a>
                    <a href="<?= base_url('liquidation') ?>" class="onglet_btn ">Liquidation</a>
                    <a href="<?= base_url('Relique') ?>" class="onglet_btn ">Reliquat</a>
                </div>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('Activite/register') ?>" method="post">
                        <?php endif; ?>
                            <div class="mb-2">
                                <label class="form-label">Code projet :</label>
                                <input name="codeprojet" id="codeprojet" type="text" class="form-control input_form-control " required>
                                <input name="projet" id="projet" type="text" class="form-control input_form-control d-none" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Titre du projet :</label>
                                <input name="titreprojet" id="titreprojet" type="text" class="form-control input_form-control " readonly>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Titre de l'activité :</label>
                                <input name="titre" id="titre" type="text" class="form-control input_form-control " required>

                                <p class="text-danger d-none" id="ms-titre">Ce titre est déjà utilisé.</p>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Date de début :</label>
                                <input name="date_debut" id="date_debut" type="date" class="form-control input_form-control " required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Date de fin :</label>
                                <input name="date_fin" id="date_fin" type="date" class="form-control input_form-control " required>
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

                                    <button type="button" class="btn d-none" id="t-valider">Valider</button>
                                <?php endif  ?>
                            </div>


                        <?php if ($this->session->userdata('added_projet')) : ?>
                            <script>
                                Myalert.added()
                            </script>
                        <?php endif; ?>
                        <?php $this->session->unset_userdata('added_projet') ?>
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

                        <!-- <form action="<?= base_url('Activite/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                            <div class="row">
                            <div class="group_form row">
                                <label for="date_debut" class="form-label">Date de début : </label>
                                <div class="input-group  mb-3">
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['idprojet'] ?? '' ?>">
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
                                    <input type="text" class="form-control" name="mot" value="<?= $_POST['mot'] ?? '' ?>">
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
                            </div>
                            </div>
                        </form> -->



                        <!-- Recherche par projet  -->
                        <?php if (count($projets)) : ?>
                        <?php endif ?>
                        <div class="owl-carousel">
                            <?php foreach ($projets as $key => $projet) :  ?>
                                <form action="<?= base_url('Activite/searchProjet') ?>" method="post">
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
                            <table class="table table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Code du projet </th>
                                        <th>Titre de l'activité </th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Titre du projet</th>
                                        <th>Region</th>
                                        <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                                            <th>Actions</th>
                                        <?php endif ?>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php $datas = $data['activite'] ?>
                                    <?php foreach ($datas as $key => $activite) :  ?>
                                        <tr>
                                            <td><?= $activite->codeprojet ?></td>
                                            <td><?= ucfirst($activite->titre_activite) ?></td>
                                            <td><?= only_date($activite->date_debut_act) ?></td>
                                            <td><?= only_date($activite->date_fin_act) ?></td>
                                            <td><?= $activite->titreprojet ?></td>
                                            <td><?= $activite->regionprojet ?></td>
                                            <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                                                <td>

                                                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                        <button class="btn btn-danger" type="button" disabled>
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-warning " disabled>
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>
                                                    <?php else : ?>
                                                        <button class="btn btn-danger delete" type="button" data-id="<?= $activite->idactivite ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $activite->idactivite ?>">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>

                                                    <?php endif; ?>

                                                </td>
                                            <?php endif ?>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            <?php if ($nPages > 1) :  ?>
                                <div class="__pagination">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center pagination-sm">
                                            <li class="page-item">
                                                <?php if ($current == 1) :  ?>
                                                    <span class="page-link __disabled" aria-label="Previous">
                                                        <small aria-hidden="true"><i class="fa-solid fa-angle-left"></i></small>
                                                    </span>
                                                <?php else :  ?>
                                                    <?php if (isset($_POST['idprojet'])) :  ?>
                                                        <form action="<?= base_url('Activite/searchProjet/' . ($current - 1)) ?>" method="post">
                                                            <input type="hidden" name="idprojet" value="<?= $_POST['idprojet'] ?? '' ?>">

                                                            <button class="page-link" type="submit" aria-label="Previous">
                                                                <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                            </button>
                                                        </form>

                                                    <?php else :  ?>
                                                        <a class="page-link" href='<?= base_url('Activite/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                            <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </li>

                                            <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                                <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                                    <?php if (isset($_POST['idprojet'])) :  ?>
                                                        <form action="<?= base_url('Activite/searchProjet/' . ($i)) ?>" method="post">
                                                            <input type="hidden" name="idprojet" value="<?= $_POST['idprojet'] ?? '' ?>">

                                                            <button class="page-link" type="submit">
                                                                <?= $i ?>
                                                            </button>
                                                        </form>
                                                    <?php else :  ?>
                                                        <a class="page-link" href="<?= base_url('Activite/page/' . $i) ?>"><?= $i ?></a>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endfor; ?>

                                            <li>
                                                <?php if ($current == $nPages) :  ?>
                                                    <span class="page-link __disabled" aria-label="Next">
                                                        <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                                    </span>
                                                <?php else :  ?>
                                                    <?php if (isset($_POST['idprojet'])) :  ?>
                                                        <form action="<?= base_url('Activite/searchProjet/' . ($current +  1)) ?>" method="post">
                                                            <input type="hidden" name="idprojet" value="<?= $_POST['idprojet'] ?? '' ?>">

                                                            <button class="page-link" type="submit" aria-label="Previous">
                                                                <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                            </button>
                                                        </form>
                                                    <?php else :  ?>
                                                        <a class="page-link" href="<?= base_url('Activite/page/' . ($current + 1)) ?>" aria-label="Next">
                                                            <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif;  ?>
                        </div>
            </div>
        </div>
    </div>
</div>