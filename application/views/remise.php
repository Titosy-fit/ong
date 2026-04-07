<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

            
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


                <?php if ($_SESSION['user_type'] == 'admin') : ?>

                    <h5>Retours de matériels</h5>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <form action="" method="post">
                        <?php else : ?>
                            <form action="<?= base_url('Remise/register') ?>" method="post">
                            <?php endif  ?>

                            <div class="mb-2">
                                <label class="form-label">N° du demande :</label>
                                <input class="form-control " name="num_demande" id="num_demande" required>
                                <input name="iddispatch" id="iddemande" type="hidden" class="form-control input_form-control" readonly required>
                            </div>
                            <div id="remise_container" class="bg-light p-1 d-none">
                            </div>



                            <!-- <div class="mb-2">
                                <label class="form-label">Désignation du materiel :</label>
                                <input name="designationmateriel" id="designationmateriel" type="text" class="form-control input_form-control" readonly required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Type du materiel :</label>
                                <input name="type_materiel" id="type_materiel" type="text" class="form-control input_form-control" readonly required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Déscription :</label>
                                <textarea name="fiche" id="fiche" class="form-control" readonly></textarea>
                            </div>

                            <div class="mb-2 ">
                                <label class="form-label">Quantité :</label>
                                <input name="quantite" id="quantite" type="number" min="0" class="form-control input_form-control " required>
                            </div>

                            <div class="mb-2 " id="unite_container">
                                <label class="form-label">Unité :</label>
                                <select name="unite" id="unite" class="form-select">
                                </select>
                            </div> -->

                            <div class="mb-2">
                                <label class="form-label">Dépôt : </label>
                                <?php if (count($pv) > 0) : ?>
                                    <select name="pv" class="form-select">
                                        <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                            <option value="<?= $pv[$i]->idPointVente ?>">
                                                <?= $pv[$i]->denomination_pv ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                <?php else : ?>
                                    <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Agent : </label>
                                <?php if (count($agents) > 0) : ?>
                                    <select name="agent" class="form-select">
                                        <?php for ($i = 0; $i < count($agents); $i++) : ?>
                                            <option value="<?= $agents[$i]->idUser ?>">
                                                <?= $agents[$i]->nomUser .' '. $agents[$i]->prenomUser  ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                <?php else : ?>
                                    <p class="text-danger">Aucun agent n'a été trouvé</p>
                                <?php endif; ?>
                            </div>


                            <div class="_boutton mt-4 mb-4">
                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                    <button type="button" class="btn btn-info" disabled>
                                        <i class="fas fa-check"></i>
                                        Valider
                                    </button>
                                <?php else : ?>
                                    <button type="submit" class="btn btn-info" id="valider">
                                        <i class="fas fa-check"></i>
                                        <div class="spinner-wrapper d-none" id="spinner_validation">
                                            <div class="spinner-border"></div>
                                        </div>
                                        Valider
                                    </button>
                                <?php endif  ?>
                            </div>

                            <?php if ($this->session->userdata('added')) : ?>
                                <script>
                                    Myalert.added()
                                </script>
                            <?php endif; ?>
                            <?php $this->session->unset_userdata('added') ?>
                            <?php if ($this->session->flashdata('delete')) : ?>
                                <script>
                                    Myalert.deleted()
                                </script>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('adrres')) : ?>
                                <script>
                                    Myalert.erreur('Veuillez inseret un Dépôt')
                                </script>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('erreur')) : ?>
                                <script>
                                    Myalert.erreur('Veuillez vérifier les données que vous avez saisies.');
                                </script>
                            <?php endif; ?>
                            </form>
                        <?php endif; ?>


                        <h5>Recherche</h5>
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                            <form action="" method="post" class="mb-4 mt-4" id="searchform">
                            <?php else : ?>
                                <form action="<?= base_url('Remise/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                                <?php endif  ?>
                                <!-- <div class="row"> -->
                                <div class="group_form ">
                                    <label for="date_debut" class="form-label">Date de début : </label>
                                    <div class="input-group  mb-3">
                                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                                        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="group_form ">
                                    <label for="date_fin" class="form-label">Date de fin : </label>
                                    <div class="input-group  mb-3">
                                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                                        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="group_form ">
                                    <label for="date_fin" class="form-label"> Recherche : </label>
                                    <div class=" mb-3">
                                        <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="group_form btn_rechreche">
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

                                <!-- <div class="_boutton">
                                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                                        <a class="btn btn-success" disabled>
                                            <i class="fas fa-file-excel"></i>
                                            Exporter
                                        </a>
                                    <?php else : ?>
                                        <a href="<?= base_url('ExportationExel/appro') ?>" class="btn btn-success">
                                            <i class="fas fa-file-excel"></i>
                                            Exporter
                                        </a>
                                    <?php endif  ?>

                                </div> -->

                                <div class="_tableau mt-4">
                                    <table class="table table">
                                        <thead class="table-info">
                                            <tr>
                                                <th>N° demande </th>
                                                <th>Date de remise </th>
                                                <th>Dépôt</th>
                                                <th>Agent </th>
                                                <th>Editeur </th>
                                                <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                    <th>Action</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody class="search-results">
                                            <?php foreach ($datas as $key  => $data) :  ?>
                                                <tr>
                                                    <td><?= $data->Facture ?></td>
                                                    <td><?= $data->date_remise ?></td>
                                                    <td><?= $data->denomination_pv ?></td>
                                                    <td><?= ($data->nomagent != '') ? $data->nomagent . ' ' . $data->prenomagent : '--'  ?></td>
                                                    <td><?= ($data->nomUser != '') ? $data->nomUser . ' ' . $data->prenomUser : 'Admin'  ?></td>
                                                    <?php if ($_SESSION['user_type'] == 'admin') : ?>
                                                        <td>
                                                            <a href="#" data-toggle="modal" data-target="#showDetails" class="btn btn-secondary detail" data-idremise="<?= $data->idremise ?>"><i class="fas fa-list"></i></a>
                                                            <button class="btn btn-danger delete" type="button" data-idremise='<?= $data->idremise  ?>'>
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>

                                                </tr>
                                            <?php endforeach  ?>
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

                                                                <form action="<?= base_url('Remise/search/' . ($current - 1)) ?>" method="post">
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
                                                                                <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                                    </button>
                                                                </form>

                                                            <?php else :  ?>
                                                                <a class="page-link" href='<?= base_url('Remise/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </li>

                                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                                <form action="<?= base_url('Remise/search/' . ($i)) ?>" method="post">
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
                                                                                <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <button class="page-link" type="submit">
                                                                        <?= $i ?>
                                                                    </button>
                                                                </form>
                                                            <?php else :  ?>
                                                                <a class="page-link" href="<?= base_url('Remise/page/' . $i) ?>"><?= $i ?></a>
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
                                                                <form action="<?= base_url('Remise/search/' . ($current +  1)) ?>" method="post">
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
                                                                                <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                                    </button>
                                                                </form>
                                                            <?php else :  ?>
                                                                <a class="page-link" href="<?= base_url('Remise/page/' . ($current + 1)) ?>" aria-label="Next">
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