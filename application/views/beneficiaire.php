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
                                <form action="<?= base_url('edit-bene') ?>" id="modifClient" method="post">
                                    <input id="idbeneficiaire" name="idbeneficiaire" type="text" class="form-control input_form-control" required>
                                    <p class="text-danger d-none" id="ms_error_modif">Le numéro ou le numéro de CIN que vous avez entré existe déjà.</p>
                                    <div class="mb-2 not_public">
                                        <label class="form-label ">Nom : </label>
                                        <input id="nom_modif" name="nom" type="text" class="form-control input_form-control" required>
                                    </div>
                                    <div class="mb-2 not_public">
                                        <label class="form-label ">Prénom : </label>
                                        <input id="prenom_modif" name="prenom" type="text" class="form-control input_form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Adresse : </label>
                                        <input name="adress" id="address_modif" type="text" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Numéro Téléphone : </label>
                                        <input name="numero" id="num_modif" type="text" class="form-control input_form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Email : </label>
                                        <input name="email" id="email_modif" type="email" class="form-control input_form-control" required>
                                        <div class="text-danger fs-5 font-weight-bold">
                                            <?= form_error('email'); ?>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Numéro CIN : </label>
                                        <input name="num_cin" id="num_cin_modif" type="text" class="form-control input_form-control" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Date CIN : </label>
                                        <input name="date_cin" id="date_cin_modif" type="date" class="form-control input_form-control" required>
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



                <!-- onglet  -->
                <div class="onglet">
                    <a href="<?= base_url('dispatch') ?>" id="panier_" class="onglet_btn ">Nouvel enregistrement</a>
                    <a href="<?= base_url('liste') ?>" class="onglet_btn">Liste des matériels distribués</a>
                    <a href="<?= base_url('beneficiaire') ?>" class="onglet_btn active">Bénéficiaire</a>
                </div>


                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('register-bene') ?>" method="post">
                        <?php endif; ?>
                        <div class="mb-2 not_public">
                            <p class="text-danger d-none" id="ms_error">Le numéro ou le numéro de CIN que vous avez entré existe déjà.</p>
                            <label class="form-label ">Nom : </label>
                            <input id="nom" name="nom" type="text" class="form-control input_form-control" required>
                        </div>
                        <div class="mb-2 not_public">
                            <label class="form-label ">Prénom : </label>
                            <input id="prenom" name="prenom" type="text" class="form-control input_form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Adresse : </label>
                            <input name="adress" id="address" type="text" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Numéro Téléphone : </label>
                            <input name="numero" id="num" type="text" class="form-control input_form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email : </label>
                            <input name="email" id="email" type="email" class="form-control input_form-control" required>
                            <div class="text-danger fs-5 font-weight-bold">
                                <?= form_error('email'); ?>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Numéro CIN : </label>
                            <input name="num_cin" id="num_cin" type="text" class="form-control input_form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Date CIN : </label>
                            <input name="date_cin" id="date_cin" type="date" class="form-control input_form-control" required>
                        </div>

                        <div class="_boutton">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                <button type="button" class="btn btn-info" disabled>
                                    <i class="fas fa-check"></i>
                                    Valider
                                </button>
                            <?php else : ?>
                                <button class="btn d-none" id="t_validation" type="button">T-valider</button>
                                <button type="button" class="btn btn-info" id="valider">
                                    <i class="fas fa-check"></i>
                                    <div class="spinner-wrapper d-none" id="spinner_validation">
                                        <div class="spinner-border"></div>
                                    </div>
                                    Valider
                                </button>
                            <?php endif  ?>
                        </div>
                        </form>
                        <?php if ($this->session->userdata('add_bene')) :  ?>
                            <script>
                                Myalert.added();
                            </script>
                        <?php endif  ?>
                        <?php $this->session->unset_userdata('add_bene') ?>
                        <?php if ($this->session->userdata('delete_bene')) :  ?>
                            <script>
                                Myalert.updated();
                            </script>
                        <?php endif  ?>
                        <?php $this->session->unset_userdata('delete_bene') ?>


                        <form action="<?= base_url('search-bene'); ?>" method="get">
                            <div class="input-group mt-3 mb-3">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $_POST['keyword'] ?? '' ?>">
                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                    <button class="btn btn-info" type="button" disabled>
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                <?php else : ?>
                                    <button class="btn btn-info" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>

                        <div class="_tableau mt-4">
                            <table class="table table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Adresse</th>
                                        <th>Numéro Télephone</th>
                                        <th>Email</th>
                                        <th>N° CIN </th>
                                        <th>Date CIN</th>
                                        <th>Editeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="search-results">
                                    <?php foreach ($datas as $key => $bene) : ?>
                                        <tr>
                                            <td><?= $bene->nombene ?></td>
                                            <td><?= $bene->prenombene  ?></td>
                                            <td><?= $bene->adressebene  ?></td>
                                            <td><?= $bene->telbene  ?></td>
                                            <td><?= $bene->mailbene  ?></td>
                                            <td><?= $bene->numcinbene  ?></td>
                                            <td><?= $bene->datecinbene  ?></td>
                                            <td><?= $bene->prenomUser ?? 'Admin'  ?></td>
                                            <td>

                                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                    <button class="btn btn-danger" type="button" disabled>
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning " disabled>
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>

                                                <?php else : ?>
                                                    <button class="btn btn-danger delete" type="button" data-id="<?= $bene->idbeneficiaire ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $bene->idbeneficiaire ?>">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                    <?php endforeach  ?>
                                </tbody>
                            </table>
                            <p class="pagination pagination-sm"><?php echo $liens; ?></p>
                        </div>
            </div>
        </div>
    </div>
</div>