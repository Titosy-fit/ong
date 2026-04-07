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
                                <form action="<?= base_url('Reliquat/edit') ?>" method="post">

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
                                    <!-- <div class="mb-2">
                                        <label class="form-label">Projet :</label>
                                        <select name="projet" id="projet_modif" class="form-control">

                                        </select>

                                    </div> -->
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

                <!-- Modal pour le demandeur  -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Choisissez l' Agent </h4>
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

                <div class="onglet">
                    <a href="<?= base_url('depense') ?>" class="onglet_btn ">Dépense</a>
                    <a href="<?= base_url('reliquat') ?>" class="onglet_btn active">Reliquat</a>
                </div>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                    <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('Reliquat/register') ?>" method="post" enctype="multipart/form-data">
                        <?php endif ?>

                        <input type="hidden" id="idagent" name="idagent">


                        <div class="mb-3">
                            <label class="form-label">Activité liée aux dépenses :</label>
                            <select name="activite" id="activite" class="form-select">
                                <?php foreach ($activites as $key => $activite) : ?>
                                    <option value="<?= $activite->idactivite ?>"> <?= $activite->titre_activite ?></option>
                                <?php endforeach  ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Raison :</label>
                            <select name="budget" id="budget" class="form-select" required>
                                <?php foreach ($budgets as $key => $budget) : ?>
                                    <option value="<?= $budget->idbudget ?>"> <?= $budget->designation_budget ?></option>
                                <?php endforeach  ?>
                            </select>

                            <p id="budget-ms" class="text-danger d-none">
                                La raison de la dépense est obligatoire.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Montant :</label>
                            <input type="number" min='0' class="form-control " id="montant" name="montant" required>
                        </div>

                        <?php if ($_SESSION['user_type'] == 'admin') :  ?>
                            <div class="mb-3">
                                <label class="form-label">Date et heure :</label>
                                <div class="input-group  mb-3">
                                    <input type="date" class="form-control" name="date" value="<?= date("Y-m-d") ?>">
                                    <input type="time" class="form-control" name="heure" value="<?= date("H:i") ?>">
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="_boutton">
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
                                <button type="button" id="T-valider" class="d-none">ok</button>
                            <?php endif  ?>
                        </div>
                        </form>
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

                    <form action="<?= base_url('Reliquat/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                        <!-- <div class="row"> -->
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
                        <!-- </div> -->
                    </form>

                    <div class="_tableau mt-4">
                        <table class="table table">
                            <thead class="table-info">
                                <tr>
                                    <th>Code du projet </th>
                                    <th>Titre de l'activité </th>
                                    <th>Raison </th>
                                    <th>Agent</th>
                                    <th>Date du reliquat </th>
                                    <th>Montant</th>
                                    <th>Editeur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                <?php $datas = $data['reliquat'] ?>
                                <?php foreach ($datas as $key => $reliquat) :  ?>
                                    <tr>
                                        <td><?= $reliquat->codeprojet ?></td>
                                        <td><?= ucfirst($reliquat->titre_activite) ?></td>
                                        <td><?= $reliquat->designation_budget ?></td>
                                        <td><?= ($reliquat->prenomagent != '') ? strtoupper($reliquat->nomagent) . ' ' . ucfirst($reliquat->prenomagent) : '--'  ?></td>
                                        <td><?= only_date($reliquat->date_reliquat) ?></td>
                                        <td><?= number_three($reliquat->montant_reliquat) ?></td>

                                        <td><?= ($reliquat->prenomUser != '') ? strtoupper($reliquat->nomUser) . ' ' . ucfirst($reliquat->prenomUser) : 'Admin'  ?></td>
                                        <td>

                                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                                <button class="btn btn-danger" type="button" disabled>
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>

                                                <!-- <button type="button" class="btn btn-warning " disabled>
                                                    <i class="fa-solid fa-edit"></i>
                                                </button> -->
                                            <?php else : ?>
                                                <button class="btn btn-danger delete" type="button" data-id="<?= $reliquat->idreliquat  ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>

                                                <!-- <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $reliquat->idreliquat ?>">
                                                    <i class="fa-solid fa-edit"></i>
                                                </button> -->

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

                                                    <form action="<?= base_url('Activite/search/' . ($current - 1)) ?>" method="post">
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
                                                    <a class="page-link" href='<?= base_url('Activite/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </li>

                                        <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                            <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                                <?php if (isset($_POST['date_debut'])) :  ?>
                                                    <form action="<?= base_url('Activite/search/' . ($i)) ?>" method="post">
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
                                                <?php if (isset($_POST['date_debut'])) :  ?>
                                                    <form action="<?= base_url('Activite/search/' . ($current +  1)) ?>" method="post">
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