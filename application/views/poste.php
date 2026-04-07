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
                                <form action="<?= base_url('Poste/edit') ?>" method="post">

                                    <div class="mb-2">
                                        <label class="form-label">Désignation :</label>
                                        <input name="designation" id="designation_modif" type="text" class="form-control input_form-control " required readonly>
                                        <input name="idposte" id="idposte" type="text" class="form-control input_form-control d-none" required>
                                        <p class="text-danger d-none" id="ms-designation_modif">Ce désignation est déjà utilisé.</p>

                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Déscription :</label>
                                        <textarea name="description" id="description_modif" class="form-control" style="resize: none;"></textarea>
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
                    <a href="<?= base_url('user') ?>" class="onglet_btn ">Ajout des utilisateurs</a>
                    <a href="<?= base_url('poste') ?>" class="onglet_btn active">Fonction</a>
                    <a href="<?= base_url('admin_inscription') ?>" class="onglet_btn">Ajouter administrateur</a>
                </div>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('fonction/register') ?>" method="post">
                        <?php endif; ?>

                        <div class="mb-2">
                            <label class="form-label">Désignation :</label>
                            <input name="designation" id="designation" type="text" class="form-control input_form-control " required>
                            <p class="text-danger d-none" id="ms-designation">Ce désignation est déjà utilisé.</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Déscription :</label>
                            <textarea name="description" id="description" class="form-control" style="resize: none;"></textarea>
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

                        <div class="_tableau mt-4">
                            <table class="table table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Désignation </th>
                                        <th>Déscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php $datas = $data['poste'] ?>
                                    <?php foreach ($datas as $key => $poste) :  ?>
                                        <tr>
                                            <td><?= ucfirst($poste->designation_poste) ?></td>
                                            <td><?= $poste->description_poste ?></td>

                                            <td>

                                                <?php if ((isset($_SESSION['let_test']) && !$_SESSION['let_test'])) : ?>
                                                    <button class="btn btn-danger" type="button" disabled>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning " disabled>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                <?php else : ?>
                                                    <!-- <button class="btn btn-danger delete" type="button" data-id="<?= $poste->idposte ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button> -->

                                                    <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $poste->idposte ?>">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>

                                                <?php endif; ?>

                                            </td>
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
                                                    <?php if (isset($_POST['date_debut'])) :  ?>

                                                        <form action="<?= base_url('Poste/search/' . ($current - 1)) ?>" method="post">
                                                            <div class="row d-none">
                                                                <div class="col-4 row">
                                                                    <label for="date_debut" class="form-label">Date de début : </label>
                                                                    <div class="input-group  mb-3">
                                                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 row">
                                                                    <label for="date_fin" class="form-label">Date de fin : </label>
                                                                    <div class="input-group  mb-3">
                                                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="date_fin" class="form-label"> Dépôt : </label>
                                                                    <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                                    <div class="input-group  mb-3">
                                                                        <input type="text" class="form-control" name="mot" value="<?= $_POST['mot'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button class="page-link" type="submit" aria-label="Previous">
                                                                <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                            </button>
                                                        </form>

                                                    <?php else :  ?>
                                                        <a class="page-link" href='<?= base_url('Poste/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                            <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </li>

                                            <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                                <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                                    <?php if (isset($_POST['date_debut'])) :  ?>
                                                        <form action="<?= base_url('Poste/search/' . ($i)) ?>" method="post">
                                                            <div class="row d-none">
                                                                <div class="col-4 row">
                                                                    <label for="date_debut" class="form-label">Date de début : </label>
                                                                    <div class="input-group  mb-3">
                                                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 row">
                                                                    <label for="date_fin" class="form-label">Date de fin : </label>
                                                                    <div class="input-group  mb-3">
                                                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="date_fin" class="form-label"> Dépôt : </label>
                                                                    <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                                    <div class="input-group  mb-3">
                                                                        <input type="text" class="form-control" name="mot" value="<?= $_POST['mot'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button class="page-link" type="submit">
                                                                <?= $i ?>
                                                            </button>
                                                        </form>
                                                    <?php else :  ?>
                                                        <a class="page-link" href="<?= base_url('Poste/page/' . $i) ?>"><?= $i ?></a>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endfor; ?>

                                            <li>
                                                <?php if ($current == $nPages) :  ?>
                                                    <span class="page-link __disabled" aria-label="Next">
                                                        <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                                    </span>
                                                <?php else :  ?>
                                                    <?php if (isset($_POST['date_debut'])) :  ?>
                                                        <form action="<?= base_url('Poste/search/' . ($current +  1)) ?>" method="post">
                                                            <div class="row d-none">
                                                                <div class="col-4 row">
                                                                    <label for="date_debut" class="form-label">Date de début : </label>
                                                                    <div class="input-group  mb-3">
                                                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 row">
                                                                    <label for="date_fin" class="form-label">Date de fin : </label>
                                                                    <div class="input-group  mb-3">
                                                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="date_fin" class="form-label"> Dépôt : </label>
                                                                    <input type="text" name="lieu" class="form-control" value="<?= $_POST['lieu'] ?? '' ?>">
                                                                    <div class="input-group  mb-3">
                                                                        <input type="text" class="form-control" name="mot" value="<?= $_POST['mot'] ?? '' ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button class="page-link" type="submit" aria-label="Previous">
                                                                <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                            </button>
                                                        </form>
                                                    <?php else :  ?>
                                                        <a class="page-link" href="<?= base_url('Poste/page/' . ($current + 1)) ?>" aria-label="Next">
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