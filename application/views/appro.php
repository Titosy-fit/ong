<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <div id="specifique">
                </div>

                <?php if (can_manage('logistique')) : ?>
                    <div class="onglet">
                        <a href="#" class="onglet_btn active"> Approvisionnement </a>
                        <a href="<?= base_url('commande') ?>" id="panier_" class="onglet_btn"> Commande</a>
                        <a href="<?= base_url('listecommande') ?>" class="onglet_btn">Liste des commandes </a>
                        <a href="<?= base_url('reception') ?>" class="onglet_btn">Reception de commande </a>
                    </div>

                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                        <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('registerAppro') ?>" method="post">
                    <?php endif; ?>
                        <div class="mb-2">
                            <label class="form-label">Réference du materiel :</label>
                            <input class="form-control " name="reference" id="reference" required>
                            <input class="" type="hidden" id="type">
                        </div>
                        <input name="idmateriel" id="idmateriel" type="hidden" class="form-control input_form-control" readonly required>
                        <div class="mb-2">
                            <label class="form-label">Désignation du materiel :</label>
                            <input name="designationmateriel" id="designationmateriel" type="text" class="form-control input_form-control" readonly required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Type du materiel :</label>
                            <input name="type_materiel" id="type_materiel" type="text" class="form-control input_form-control" readonly required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Description :</label>
                            <textarea name="fiche" id="fiche" class="form-control" readonly></textarea>
                        </div>
                        <div class="mb-2  ">
                            <label class="form-label">Prix unitaire :</label>
                            <input name="prix" id="prix" type="number" min="0" class="form-control input_form-control">
                        </div>
                        <div class="mb-2 ">
                            <label class="form-label">Quantité :</label>
                            <input name="quantite" id="quantite" type="number" min="0" class="form-control input_form-control " required>
                        </div>
                        <div class="mb-2 " id="unite_container">
                            <label class="form-label">Unité :</label>
                            <select name="unite" id="unite" class="form-select">
                            </select>
                        </div>
                        <div class="mb-2 ">
                            <label class="form-label ">Montant :</label>
                            <input name="montant_show" id="montant_show" type="text" min="0" value="0 Ar" class="form-control input_form-control">
                            <input name="montant" id="montant" type="number" min="0" value="0" class="form-control input_form-control d-none">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Code projet :</label>
                            <input name="codeprojet" id="codeprojet" type="text" class="form-control input_form-control " required>
                            <input name="projet" id="projet" type="number" class="form-control input_form-control d-none" required value="0">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Titre du projet : </label>
                            <input name="titreprojet" id="titreprojet" type="text" class="form-control input_form-control " readonly >
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Dépôt : </label>
                            <select name="pv" class="form-select" id="liste-pv">
                                <option value="0">-- Choisissez un dépôt --</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Fournisseur :</label>
                            <select name="fournisseur" class="form-select" id="fournisseur">
                                <option value="0">Aucun</option>
                                <?php foreach ($fournisseurs as $key => $fournisseur) : ?>
                                    <option value="<?= $fournisseur->idfournisseur ?>"><?= strtoupper($fournisseur->nom_entr) ?></option>
                                <?php endforeach  ?>
                            </select>
                        </div>
                        <div class="_boutton mt-4 mb-4">
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
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

                        <?php if ($this->session->flashdata('added')) : ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', () => {
                                    Myalert.added()
                                });
                            </script>
                        <?php endif; ?>
                        <?php //$this->session->unset_userdata('added') ?>

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

                <?php if (can_manage('logistique')) : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                        <form action="" method="post" class="mb-4 mt-4" id="searchform">
                    <?php else : ?>
                        <form action="<?= base_url('Appro/search') ?>" method="post" class="mb-4 mt-4" id="searchform">
                    <?php endif; ?>
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
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                <button class="btn btn-info" type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                                </button>
                            <?php else : ?>
                                <button class="btn btn-info" type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                                </button>
                            <?php endif  ?>
                        </div>
                    </form>
                <?php else : ?>
                    <!-- Recherche désactivée pour les non-logistique -->
                    <div class="mb-4 mt-4">
                        <div class="group_form ">
                            <label class="form-label">Date de début : </label>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" disabled>
                                <input type="time" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="group_form ">
                            <label class="form-label">Date de fin : </label>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" disabled>
                                <input type="time" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="group_form ">
                            <label class="form-label"> Recherche : </label>
                            <div class="mb-3">
                                <input type="text" placeholder="Recherche" class="form-control input-incarte" disabled>
                            </div>
                        </div>
                        <div class="group_form btn_rechreche">
                            <button class="btn btn-info" type="button" disabled>
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="_boutton">
                    <?php if (can_manage('logistique')) : ?>
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <a class="btn btn-success" disabled>
                                <i class="fas fa-file-excel"></i>
                                Exporter
                            </a>
                        <?php else : ?>
                            <a href="<?= base_url('ExportationExel/appro') ?>" class="btn btn-success">
                                <i class="fas fa-file-excel"></i>
                                Exporter
                            </a>
                        <?php endif; ?>
                    <?php else : ?>
                        <a class="btn btn-success" disabled>
                            <i class="fas fa-file-excel"></i>
                            Exporter
                        </a>
                    <?php endif; ?>
                </div>

                <div class="_tableau mt-4">
                    <table class="table table">
                        <thead class="table-info">
                            <tr>
                                <th>Transfert</th>
                                <th>Réference</th>
                                <th>Désignation</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Prix unitaire </th>
                                <th>Quantité </th>
                                <th>Montant </th>
                                <th>Code projet</th>
                                <th>Titre projet</th>
                                <th>Dépôt</th>
                                <th>Fournisseur</th>
                                <th>Date</th>
                                <?php if (can_manage('logistique')) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="search-results">
                            <?php foreach ($datas as $key  => $data) :  ?>
                                <tr>
                                    <td><?= ($data->pv_1 != '') ?  $data->pv_1 . ' <i class="fas fa-arrow-right"></i> ' . $data->pv_2 : '--' ?></td>
                                    <td><?= $data->refmateriel ?></td>
                                    <td><?= $data->designationmateriel ?></td>
                                    <td><?= $data->typemateriel ?></td>
                                    <td><?= $data->fiche ?></td>
                                    <td><?= number_three( (int)$data->prix_unitaire ) ?></td>
                                    <td><?= $data->quantite ?> <?= ($data->denomination) ? $data->denomination . '(s)' : '' ?> </td>
                                    <td><?= number_three( (int)$data->prix_unitaire * (int)$data->quantite ) ?></td>
                                    <td><?= $data->codeprojet ?? "--" ?></td>
                                    <td><?= $data->titreprojet ?? "--" ?></td>
                                    <td><?= $data->denomination_pv ?></td>
                                    <td><?= $data->nom_entr ?? "--" ?></td>
                                    <td><?= form_date($data->dateAppro) ?></td>
                                    <?php if (can_manage('logistique')) : ?>
                                        <td>
                                            <button class="btn btn-danger delete" type="button" data-idappro='<?= $data->idAppro  ?>'>
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
                                                <form action="<?= base_url('Appro/search/' . ($current - 1)) ?>" method="post">
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
                                                <a class="page-link" href='<?= base_url('Appro/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) :  ?>
                                        <li class="page-item <?= ($i == $current) ? 'active'  :  '' ?>">
                                            <?php if (isset($_POST['date_debut'])) :  ?>
                                                <form action="<?= base_url('Appro/search/' . ($i)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <!-- mêmes champs cachés -->
                                                    </div>
                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Appro/page/' . $i) ?>"><?= $i ?></a>
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
                                                <form action="<?= base_url('Appro/search/' . ($current +  1)) ?>" method="post">
                                                    <div class="row d-none">
                                                        <!-- mêmes champs cachés -->
                                                    </div>
                                                    <button class="page-link" type="submit" aria-label="Next">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else :  ?>
                                                <a class="page-link" href="<?= base_url('Appro/page/' . ($current + 1)) ?>" aria-label="Next">
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

<?php if ($this->session->userdata('added')) :  ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Myalert.added()
        });
    </script>
<?php endif  ?>
<?php $this->session->unset_userdata('added') ?>