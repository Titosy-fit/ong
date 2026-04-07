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
                                <form action="<?= base_url('Budget/edit') ?>" method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Ligne budgétaire :</label>
                                        <input type="text" class="form-control " id="ligne_modif" name="ligne_modif" required>
                                        <p class="text-danger d-none" id="des_ligne_modif">Cette ligne budgétaire  est déjà utilisée !</p>

                                        <input type="text" class="form-control d-none" id="id_modif" name="idbudget" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Intitulé</label>
                                        <input type="text" class="form-control " id="designation_modif" name="designation" required>
                                       

                                        <input type="text" class="form-control d-none" id="id_modif" name="idbudget" required>
                                    </div>
                                   

                                    <div class="mb-3">
                                        <label class="form-label">Montant alloué</label>
                                        <input type="number" min='0' class="form-control" id="budget_modif" name="budget" required>
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
                    <!-- Modifier par Mamy -->
                    <!-- <a href="<?= base_url('activite') ?>" class="onglet_btn ">Activité</a> -->
                    <a href="<?= base_url('budget') ?>" class="onglet_btn active">Budget</a>
                    <a href="<?= base_url('mission') ?>" class="onglet_btn ">Activité & Mission</a>
                    <a href="<?= base_url('liquidation') ?>" class="onglet_btn ">Liquidation</a>
                </div>
                 <form  method="post" enctype="multipart/form-data" action="<?= base_url('Budget/import_csv') ?>">
                    <div class="mb-2">
                        <label class="form-label">Mode d'insertion :</label>
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
                        <button  class="btn btn-primary" id="valid-import" type="submit" name="import"><i class="fas fa-check"></i> Valider</button>
                    </div>
                </form>

                <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) :  ?>
                        <form action="" method="post" class="data-saisie">
                        <?php else : ?>
                            <form action="<?= base_url('Budget/register') ?>" method="post" id="prix_modif" class="data-saisie">
                            <?php endif; ?>
                            <div class="mb-2">
                                <label class="form-label">Code projet :</label>
                                <input name="codeprojet" id="codeprojet" type="text" class="form-control input_form-control " required>
                                <input name="projet" id="projet" type="text" class="form-control input_form-control d-none" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Titre du projet : </label>
                                <input name="titreprojet" id="titreprojet" type="text" class="form-control input_form-control " readonly>
                            </div>
                             <div class="mb-3">
                                <label class="form-label">Ligne budgétaire : </label>
                                <input type="number" class="form-control " id="ligne" name="ligne" required>
                                <p class="text-danger d-none" id="ms-ligne">Cette ligne budgétaire existe déjà.</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Intitulé : </label>
                                <input type="text" class="form-control " id="designation" name="designation" required>
                                
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Montant alloué : </label>
                                <input type="number" min='0' class="form-control" id="budget" name="budget" required>
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
                                <?php endif; ?>
                            </div>
                            </form>
                        <?php endif ?>



                        <?php if ($this->session->userdata('added_projet')) :  ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', () => {
                                    Myalert.added();
                                });
                                
                            </script>
                        <?php endif  ?>

                        <?php if ($this->session->userdata('wrong_columns')) :  ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', () => {
                                    Myalert.erreur("Une erreur s'est produite vérifiez votre fichier csv.") ;
                                });
                                
                            </script>
                        <?php endif  ?>
                        <?php $this->session->unset_userdata('wrong_columns') ?>

                        
                        <?php $this->session->unset_userdata('added_projet') ?>
                        </form>
                         <?php if ($this->session->userdata('vide')) :  ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', () => {
                                    Myalert.erreur("Aucune n'a été insérée") ;
                                });
                                
                            </script>
                        <?php endif  ?>
                        <?php $this->session->unset_userdata('vide') ?>
                        <?php if ($this->session->userdata('edit_projet')) : ?>
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

                        <!-- <form action="<?= base_url('Budget/search'); ?>" method="get">
                        <div class="input-group mt-3 mb-3">
                            <input name="recherche" type="text" class="form-control" placeholder="Recherche" value="<?= $_POST['mot'] ?? '' ?>">
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
                    </form> -->

                        <!-- Recherche par projet  -->
                        <?php if (count($projets)) : ?>
                        <?php endif ?>
                        <div class="owl-carousel">
                            <?php foreach ($projets as $key => $projet) :  ?>
                                <form action="<?= base_url('Budget/searchProjet') ?>" method="post">
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
                                        <th>Code du projet</th>
                                        <th>Ligne budgetaire</th>
                                        <th>Intitulé</th>
                                        
                                        <th>Montant alloué</th>
                                        <th>Reliquat</th>
                                        <?php if ($_SESSION['user_type'] == 'admin' || $_SESSION['type_of_user'] == 'Raf') : ?>
                                            <th>Actions</th>
                                        <?php endif ?>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php $datas = $data['datas'] ?>
                                    <?php foreach ($datas as $key => $budget) :  ?>
                                        <tr>
                                            <td><?= $budget->codeprojet ?></td>
                                            <td><?= $budget->ligne_budget ?></td>
                                            <td><?= $budget->designation_budget ?></td>
                                            <!-- <td><?= ucfirst($budget->titre_activite) ?></td> -->
                                            <td><?= number_three($budget->budget) ?>
                                                
                                            </td>
                                            <td><?= number_three($budget->reste) ?>
                                                
                                            </td>
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
                                                        <button class="btn btn-danger delete" type="button" data-id="<?= $budget->idbudget ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-warning edit" data-toggle="modal" data-target="#editModal" data-id="<?= $budget->idbudget ?>">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>

                                                    <?php endif; ?>

                                                </td>
                                            <?php endif ?>

                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            <?php if (isset($nPages) && $nPages > 1) :  ?>
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
                            <?php else : ?>
                                <p class="pagination pagination-sm"><?php echo $data['lien']; ?></p>
                            <?php endif;  ?>
                        </div>
            </div>
        </div>
    </div>
    