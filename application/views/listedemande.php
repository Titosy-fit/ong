<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- Modal Facture -->
                <div class="modal fade" id="modalfacture">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Facture</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="validation" style="font-size: 14px;">
                                    <p class="text-info">Veuillez désactiver IDM si vous en avez.</p>
                                    <div class="mb-2">
                                        <label class="form-label">Format :</label>
                                        <select class="form-select" id="format">
                                            <option value="A4">A4</option>
                                            <option value="tiquet">Tiquet de caisse</option>
                                        </select>
                                    </div>
                                    <div>
                                        <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                            <div class="spinner-border" role="status"></div>
                                        </div>
                                        <iframe src="" id="pdfFrame" class="d-none" style="width:100%; height:500px;" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Détails -->
                <div class="modal fade" id="showDetails" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Détails</h4>
                                <button type="button" class="btn" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="modal-body" id="tab"></div>
                        </div>
                    </div>
                </div>

                <!-- ==================== MODAL IMPORT FICHIER ==================== -->
                <div class="modal fade" id="importDataModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Importer des données externes</h4>
                                <button type="button" class="btn" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="importForm" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Fichier à importer :</label>
                                        <input type="file" id="importFile" name="import_file" class="form-control"
                                               accept=".xlsx,.xls,.pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                        <small class="text-muted">Excel, PDF, Word ou Photo</small>
                                    </div>
                                    <div id="importPreview" class="border p-3 bg-light d-none" style="min-height: 150px;"></div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-success" id="btnImportSave">
                                    <i class="fa-solid fa-upload"></i> Importer et Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="affichefacture" class="btn btn-info d-none" data-bs-toggle="modal" data-bs-target="#modalfacture">Afficher la facture</button>

                <!-- Onglets -->
                <div class="onglet">
                    <a href="<?= base_url('demande-mat') ?>" class="onglet_btn">Nouvel demande</a>
                    <a href="<?= base_url('liste-demande') ?>" class="onglet_btn active">Récapitulatif de distribution</a>
                    <a href="<?= base_url('rendre-mat') ?>" class="onglet_btn">Reliquat matériels</a>
                </div>

                <!-- Bouton Import -->
                <div class="mb-3">
                    <button id="btn_import_data" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importDataModal">
                        <i class="fa-solid fa-upload"></i> Importer fichier (Excel / PDF / Word / Photo)
                    </button>
                </div>

                <!-- Formulaire de recherche -->
                <form action="<?= base_url('liste-demande/search') ?>" method="post" class="mb-4" id="searchform">
                    <div class="group_form">
                        <label for="date_debut" class="form-label">Date de début :</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control w-75" id="date_debut" name="date_debut" value="<?= $_POST['date_debut'] ?? '' ?>">
                            <input type="time" class="form-control w-25" id="heure_debut" name="heure_debut" value="<?= $_POST['heure_debut'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form">
                        <label for="date_fin" class="form-label">Date de fin :</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control w-75" id="date_fin" name="date_fin" value="<?= $_POST['date_fin'] ?? '' ?>">
                            <input type="time" class="form-control w-25" id="heure_fin" name="heure_fin" value="<?= $_POST['heure_fin'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form">
                        <label class="form-label">Recherche :</label>
                        <div class="input-group mb-3">
                            <input type="text" placeholder="Recherche" class="form-control input-incarte" name="recherche" value="<?= $_POST['mot'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="group_form">
                        <label class="form-label">Dépôt :</label>
                        <select name="lieu" class="form-select" id="the_pv">
                            <option value="0">Lieu</option>
                            <?php foreach ($pv as $key => $pv_) : ?>
                                <option value="<?= $pv_->idPointVente ?>"><?= ucfirst($pv_->denomination_pv) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="group_form btn_rechreche">
                        <button class="btn btn-info" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                        </button>
                    </div>
                </form>

                <!-- Tableau -->
                <div class="_tableau mt-4">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Code du projet</th>
                                <th>N° de la demande</th>
                                <th>Date</th>
                                <th>Dépôt</th>
                                <th>Nom de l'agent</th>
                                <th>Contact de l'agent</th>
                                <th>Editeur</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="search-results">
                            <?php for ($i = 0; $i < count($data); $i++) : ?>
                                <tr>
                                    <td><?= $data[$i]->codeprojet ?? '--' ?></td>
                                    <td><?= $data[$i]->Facture ?></td>
                                    <td><?= form_date($data[$i]->dateVente) ?></td>
                                    <td><?= $data[$i]->denomination_pv ?></td>
                                    <td><?= ($data[$i]->nomagent == '') ? '--' : $data[$i]->nomagent ?></td>
                                    <td><?= ($data[$i]->telagent == '') ? '...' : $data[$i]->telagent ?></td>
                                    <td><?= $data[$i]->prenomUser ?? 'Admin' ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#showDetails" class="btn btn-secondary detail" data-idfacture="<?= $data[$i]->idfacture ?>"><i class="fas fa-list"></i></a>
                                        <?php if ($_SESSION['user_type'] == 'admin' || (isset($_SESSION['id_user']) && $data[$i]->idUser == $_SESSION['id_user'])) : ?>
                                            <a href="#" data-idfacture="<?= $data[$i]->idfacture ?>" class="btn btn-danger supprimer"><i class="fa-solid fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>

                    <!-- Pagination (identique à ton code original) -->
                    <?php if ($nPages && $nPages > 1) : ?>
                        <div class="__pagination">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center pagination-sm">
                                    <!-- Précédent -->
                                    <li class="page-item">
                                        <?php if ($current == 1) : ?>
                                            <span class="page-link __disabled"><i class="fa-solid fa-angle-left"></i></span>
                                        <?php else : ?>
                                            <?php if (isset($_POST['date_debut'])) : ?>
                                                <!-- form pagination search -->
                                                <form action="<?= base_url('liste-demande/search/' . ($current - 1)) ?>" method="post">
                                                    <!-- hidden fields pour conserver les filtres -->
                                                    <button class="page-link" type="submit"><i class="fa-solid fa-angle-left"></i></button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href="<?= base_url('liste-demande/page/' . ($current - 1)) ?>"><i class="fa-solid fa-angle-left"></i></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) : ?>
                                        <li class="page-item <?= ($i == $current) ? 'active' : '' ?>">
                                            <?php if (isset($_POST['date_debut'])) : ?>
                                                <form action="<?= base_url('liste-demande/search/' . $i) ?>" method="post">
                                                    <button class="page-link" type="submit"><?= $i ?></button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href="<?= base_url('liste-demande/page/' . $i) ?>"><?= $i ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Suivant -->
                                    <li class="page-item">
                                        <?php if ($current == $nPages) : ?>
                                            <span class="page-link __disabled"><i class="fa-solid fa-angle-right"></i></span>
                                        <?php else : ?>
                                            <?php if (isset($_POST['date_debut'])) : ?>
                                                <form action="<?= base_url('liste-demande/search/' . ($current + 1)) ?>" method="post">
                                                    <button class="page-link" type="submit"><i class="fa-solid fa-angle-right"></i></button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href="<?= base_url('liste-demande/page/' . ($current + 1)) ?>"><i class="fa-solid fa-angle-right"></i></a>
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