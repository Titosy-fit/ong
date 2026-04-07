<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <div id="specifique">
                </div>
                <h5>Materiel </h5>

                <?php if (can_manage('logistique')) : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                        <form action="" method="post">
                    <?php else : ?>
                        <form action="<?= base_url('registerMat') ?>" method="post" enctype="multipart/form-data">
                    <?php endif; ?>
                        <div class="">
                            <div class="mb-3">
                                <label class="form-label">Type :</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="projet">Matériel de projet</option>
                                    <option value="bureau">Matériel de bureau</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Réference :</label>
                                <input class="form-control input_form-control" type="text" id="referenceMat"
                                    name="referenceMat" required>
                            </div>
                            <!-- <div id="keyboard">ito</div> -->
                            <div class="mb-3">
                                <label class="form-label">Désignation :</label>
                                <input type="text" class="form-control input_form-control" id="designationMat"
                                    name="designationMat" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description :</label>
                                <textarea name="fiche" class="form-control" id="fiche"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Unité :</label>
                                <div class="input-group" id="unite_parent_container">
                                    <input class="form-control input_form-control all_unite" type="text"
                                        id="unite_parent" name="unite_parent" data-nbr="0">
                                    <button type="button" class="btn btn-info" id="add_unite"><i
                                            class="fas fa-plus"></i> Sous unité</button>
                                </div>
                            </div>
                            <div id="sous_unite_container" class="d-none">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Seuil d'alerte :</label>
                                <div class="input-group">
                                    <input class="form-control input_form-control" type="number" id="seuil"
                                        name="seuil" required>
                                    <select name="seul_unite" id="seul_unite" class="form-select d-none">
                                    </select>
                                </div>
                                <input type="number" class="d-none" id="seul_min" name="seuil_min">
                            </div>
                            <div class="_boutton mt-3">
                                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                    <button type="button" class="btn btn-info" disabled>
                                        <i class="fas fa-check"></i>
                                        <div class="spinner-wrapper d-none" id="spinner_validation">
                                            <div class="spinner-border"></div>
                                        </div>
                                        Valider
                                    </button>
                                <?php else : ?>
                                    <button type="button" class="d-none" id="real_validation">ok</button>
                                    <button type="button" class="btn btn-info" id="valider">
                                        <i class="fas fa-check"></i>
                                        <div class="spinner-wrapper d-none" id="spinner_validation">
                                            <div class="spinner-border"></div>
                                        </div>
                                        Valider
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if ($this->session->flashdata('ref')) : ?>
                    <script>
                        Myalert.erreur('Cette référence existe déjà')
                    </script>
                <?php endif; ?>

                <?php if ($this->session->flashdata('designationmateriel')) : ?>
                    <script>
                        Myalert.erreur('Cette référence existe déjà')
                    </script>
                <?php endif; ?>

                <?php if ($this->session->userdata('delete')) : ?>
                    <script>
                        Myalert.deleted();
                    </script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('delete'); ?>

                <?php if ($this->session->flashdata('produit_add')) : ?>
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            Myalert.added();
                        });
                    </script>
                <?php endif; ?>

                <?php if ($this->session->flashdata('edit')) : ?>
                    <script>
                        Myalert.updated();
                    </script>
                <?php endif; ?>

                <?php if (can_manage('logistique')) : ?>
                    <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                        <form action="" method="GET">
                            <div class="input-group mt-3 mb-3">
                                <input type="text" class="form-control" placeholder="Recherche" value="<?= $post ?? '' ?>">
                                <button class="btn btn-info " type="button" disabled>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                    <?php else : ?>
                        <form action="<?= base_url('recherche') ?>" method="GET">
                            <div class="input-group mt-3 mb-3">
                                <input name="recherche" type="text" class="form-control" placeholder="Recherche"
                                    value="<?= $post ?? '' ?>">
                                <button class="btn btn-info " type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                <?php else : ?>
                    <!-- Pas de formulaire de recherche pour les non-logistique -->
                    <div class="input-group mt-3 mb-3">
                        <input type="text" class="form-control" placeholder="Recherche" disabled>
                        <button class="btn btn-info" type="button" disabled>
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="entete">
                    <h5 class="mt-5 mb-3">Liste des produits</h5>
                    <div class="_boutton w-50">
                        <?php if (can_manage('logistique')) : ?>
                            <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                <a class="btn btn-success" disabled>
                                    <i class="fas fa-file-excel"></i>
                                    Exporter
                                </a>
                            <?php else : ?>
                                <a href="<?= base_url('ExportationExel/materiel') ?>" class="btn btn-success">
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
                </div>

                <table class="table table">
                    <thead class="table-info">
                        <tr>
                            <th>Réference</th>
                            <th>Désignation</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Unité</th>
                            <th>Seuil d'alerte</th>
                            <?php if (can_manage('logistique')) : ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $materiel = $data['materiel'];
                        for ($i = 0; $i < count($materiel); $i++) : ?>
                            <tr>
                                <td>
                                    <?= $materiel[$i]->refmateriel; ?>
                                </td>
                                <td>
                                    <?= $materiel[$i]->designationmateriel; ?>
                                </td>
                                <td>
                                    <?= $materiel[$i]->typemateriel; ?>
                                </td>
                                <td>
                                    <?= ($materiel[$i]->fiche != '') ? $materiel[$i]->fiche : '--' ?>
                                </td>
                                <td>
                                    <?php $unites =  $materiel[$i]->unites ?>
                                    <?php if (count($unites)) : ?>
                                        <?php foreach ($unites as $key => $unite) : ?>
                                            <?php if ($unite->formule != 0) : ?>
                                                <?php if ($unite->formule < 1) : ?>
                                                    <span><i class="fas fa-minus-square text-info"></i><?= ' 1  ' . $unites[$key - 1]->denomination . ' = ' . $unite->formule . ' ' . $unite->denomination ?></span><br>
                                                <?php else : ?>
                                                    <span><i class="fas fa-minus-square text-info"></i><?= ' 1  ' . $unites[$key - 1]->denomination . ' = ' . $unite->formule . ' ' . $unite->denomination . "(s)" ?></span><br>
                                                <?php endif ?>
                                            <?php endif ?>
                                            <?php if (count($unites) == 1) : ?>
                                                <?= '<i class="fas fa-minus-square text-info"></i> ' .  $unites[0]->denomination ?>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    <?php else : ?>
                                        --
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $materiel[$i]->seuil . " " . $materiel[$i]->denomination  ?>
                                    <?= ($materiel[$i]->denomination != '') ? '(s)' : ''; ?>
                                </td>
                                <?php if (can_manage('logistique')) : ?>
                                    <td>
                                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                                            <button class="btn btn-danger " type="button" disabled>
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning " disabled>
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                        <?php else : ?>
                                            <?php $unites =  $materiel[$i]->unites;
                                            $idunites = '' ?>
                                            <?php foreach ($unites as $key => $unite) : ?>
                                                <?php $idunites .= $unite->idunite . ','; ?>
                                            <?php endforeach ?>
                                            <?php $idunites = trim($idunites, ',') ?>
                                            <button class="btn btn-danger  delete" type="button" onclick="deleteIt(this)"
                                                data-id="<?= $materiel[$i]->idmateriel; ?>"
                                                data-ref="<?= $materiel[$i]->refmateriel; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning edit" onclick="DonnerProduit(this)"
                                                data-toggle="modal" data-target="#editModal"
                                                data-id="<?= $materiel[$i]->idmateriel; ?>"
                                                data-ref="<?= $materiel[$i]->refmateriel; ?>" data-unite='<?= $idunites ?>'>
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>

                <button class="btn btn-success d-none" id="btn_show_image" data-toggle="modal"
                    data-target="#imageModal">show</button>

                <p class="pagination pagination-sm"><?php echo $data['links']; ?></p>
            </div>
        </div>
    </div>

    <!-- Modal modification (inchangé) -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modification</h5>
                    <button type="button" id="close_editModal"
                        class="btn-close d-flex justify-content-center alin-items-center" data-bs-dismiss="modal"><i
                            class="fas fa-x"></i></button>
                </div>
                <form enctype="multipart/form-data" action="<?= base_url('editProd') ?>" method="post"
                    id="modifMateriel">
                    <div class="container">
                        <div class="modal-body" id="validation">
                            <input type="hidden" name="id" id="idMateriel-modif">
                            <div class="mb-3">
                                <label class="form-label">Type :</label>
                                <select name="type" id="type-modif" class="form-select">
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Réference:</label>
                                <input class="form-control input_form-control" type="text" id="reference-modif"
                                    name="reference_modif" required>
                                <p class="text-danger d-none" id="msg-ref">Cette reference existe déjà</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Désignation:</label>
                                <input class="form-control input_form-control" id="designationmateriel-modif" type="text"
                                    name="designation_modif" required>
                                <p class="text-danger d-none" id="msg-designationmateriel">Ce désignation existe déjà</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description :</label>
                                <textarea name="fiche" class="form-control" id="fiche_modif"></textarea>
                            </div>
                            <div class="mb-3">
                                <p class="text-info d-none" id="msg-uniter_">Le materiel est déjà utilisé, donc vous ne pouvez plus modifier les unités</p>
                                <label class="form-label">Unité :</label>
                                <div class="input-group" id="unite_modif_parent_container">
                                    <input class="form-control input_form-control unite_name" data-self="0"
                                        data-parent="no_parent" id="unite_modif_parent" type="text" name="unite_parent">
                                </div>
                                <input class="form-control input_form-control" id="idunite" type="hidden"
                                    name="idunite" required>
                            </div>
                            <div class="mb-3" id="sous_unite_modif">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Seuil d'alerte :</label>
                                <div class="input-group">
                                    <input class="form-control input_form-control" type="number" id="seuil_modif"
                                        name="seuil_modif" required>
                                    <select name="seul_unite_modif" id="seul_unite_modif" class="form-select d-none">
                                    </select>
                                </div>
                                <input type="number" class="d-none" id="seuil_min_modif" name="seuil_min_modif">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer _button">
                        <button type="button" class="btn btn-info d-none" id="modification"><i
                                class="fas fa-pencil-alt"></i> Modifier</button>
                        <a href="#" class="btn btn-info" id="modifier"><i class="fas fa-pencil-alt"></i> Modifier</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal image (inchangé) -->
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
                        <div class="image_container">
                            <div class="_image">
                                <img id="image" src="<?= base_url('public/images/favicon/photo-camera.png') ?>" alt="">
                            </div>
                        </div>
                        <div class="modal-footer _button">
                            <a href="#" class="btn btn-info" id="image_ok"><i class="fas fa-check"></i> OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>