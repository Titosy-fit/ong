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
<!-- MODAL VOIR PLUS / DÉTAILS -->
<!-- MODAL VOIR PLUS / DÉTAILS -->
<div class="modal fade" id="viewMoreModal" tabindex="-1" role="dialog" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- modal-xl pour 5 colonnes -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #991b1b; color: white;">
                <h5 class="modal-title" id="viewMoreModalLabel">Détails budgétaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- Tableau principal -->
                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <!-- En-têtes du tableau -->
                    <thead>
                        <tr style="background-color: #991b1b; color: white;">
                            <th style="padding: 0.5rem; text-align: center; width: 20%;">Projet</th>
                            <th style="padding: 0.5rem; text-align: center; width: 20%;">Budget</th>
                            <th style="padding: 0.5rem; text-align: center; width: 20%;">Mission</th>
                            <th style="padding: 0.5rem; text-align: center; width: 20%;">Liquidation</th>
                            <!-- <th style="padding: 0.5rem; text-align: center; width: 20%;">Approvisionnement</th> -->

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ligne 1 : Informations principales -->
                        <tr>
                            <!-- Colonne Projet -->
                            <td style="padding: 0.5rem; vertical-align: top; background-color: #fef2f2;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Code projet :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_codeprojet"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Créé par :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_createur"></span></td>
                                    </tr>
                                </table>
                            </td>
                            
                            <!-- Colonne Budget -->
                            <td style="padding: 0.5rem; vertical-align: top;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Intitulé :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_designation"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Ligne budgétaire :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_ligne"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Montant alloué :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_budget"></span> Ar</td>
                                    </tr>
                                </table>
                            </td>
                            
                            <!-- Colonne Approvisionnement (NOUVELLE) -->
                            
                            
                            <!-- Colonne Mission -->
                            <td style="padding: 0.5rem; vertical-align: top;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Avance sur mission :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_avance"></span> Ar</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Objet :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_objet"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Lieu :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_lieu"></span></td>
                                    </tr>
                                </table>
                            </td>
                            
                            <!-- Colonne Liquidation -->
                            <td style="padding: 0.5rem; vertical-align: top; background-color: #fef2f2;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Total dépensé :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_total_depense"></span> Ar</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Total reliquat :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_total_reliquat"></span> Ar</td>
                                    </tr>
                                </table>
                                
                                <!-- Séparateur -->
                                <div style="border-top: 1px dashed #991b1b; margin: 0.5rem 0;"></div>
                                
                                <!-- Agents -->
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td colspan="2" style="padding: 0.25rem; font-weight: 600;">Agent(s) concerné(s) :</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.15rem 0.25rem;" colspan="2">
                                            <div style="display: flex; flex-direction: column; gap: 0.15rem;">
                                                <div><i class="fas fa-user me-1"></i> <span id="agent_1">Non assigné</span></div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                
                                <!-- Informations complémentaires -->
                                <table style="width: 100%; border-collapse: collapse; margin-top: 0.5rem;">
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Date liquidation :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_date_liquidation">--/--/----</span></td>
                                    </tr>
                                </table>
                            </td>
                            <!-- <td style="padding: 0.5rem; vertical-align: top; background-color: #fef2f2;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Montant approvisionné :</td>
                                        <td style="padding: 0.25rem; text-align: right;"><span id="detail_montant_appro"></span> Ar</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Date approvisionnement :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_date_appro"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Mode :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_mode_appro"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Référence :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_reference_appro"></span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.25rem; font-weight: 600;">Statut :</td>
                                        <td style="padding: 0.25rem;"><span id="detail_statut_appro" class="badge" style="background-color: #991b1b; color: white;">En attente</span></td>
                                    </tr>
                                </table>
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
                <div class="onglet">
                    <a href="<?= base_url('budget') ?>" class="onglet_btn active">Budget</a>
                    <a href="<?= base_url('mission') ?>" class="onglet_btn ">Activité & Mission</a>
                    <a href="<?= base_url('liquidation') ?>" class="onglet_btn ">Liquidation</a>
                    <a href="<?= base_url('Relique') ?>" class="onglet_btn ">Reliquat</a>
                </div>

                <form method="post" enctype="multipart/form-data" action="<?= base_url('Budget/import_csv') ?>">
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
                        <button class="btn btn-primary" id="valid-import" type="submit" name="import">
                            <i class="fas fa-check"></i> Valider
                        </button>
                    </div>
                </form>

                <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                    <form action="" method="post" class="data-saisie">
                <?php else : ?>
                    <form action="<?= base_url('Budget/register') ?>" method="post" id="prix_modif" class="data-saisie">
                <?php endif; ?>

                    <?php if (can_manage('budget')) : ?>
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
                            <input type="text" class="form-control " id="ligne" name="ligne" required>
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

                <?php if ($this->session->userdata('edit_projet')) : ?>
                    <script>Myalert.updated()</script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('edit_projet') ?>

                <?php if ($this->session->userdata('delete_projet')) : ?>
                    <script>Myalert.deleted()</script>
                <?php endif; ?>
                <?php $this->session->unset_userdata('delete_projet') ?>

                <!-- Recherche -->
                <div class="place-search">
                    <form class="row" action="<?= base_url('Budget/search') ?>" method="get">
                        <div class="col-md-3">
                            <label for="projet" class="form-label">Projet : </label>
                            <input type="" class="form-control" id="codeprojet" name="codeprojet" value="<?= isset($code) ? html_escape($code) : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="projet" class="form-label">Mot-clé : </label>
                            <input type="" class="form-control" id="motcle" name="motcle" value="<?= isset($mot) ? html_escape($mot) : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info btn-block" id="search-budget">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Rechercher
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex">
                    <a href="<?php echo base_url('ExportationExel/exportBudget') ?>" class="btn btn-success btn-export ms-auto">
                        <i class="fa-regular fa-file-excel"></i>
                        Exporter
                    </a>
                </div>

                <div class="_tableau mt-4">
                    <table class="table table">
                        <thead class="table-info">
                            <tr>
                                <th>Code du projet</th>
                                <th>Ligne budgetaire</th>
                                <th>Intitulé</th>
                                <th>Montant alloué</th>
                                <th>Reliquat</th>
                                <?php if (can_manage('budget')) : ?>
                                    <th>Actions</th>
                                <?php endif ?>
                            </tr>
                        </thead>
                        <tbody class="">
                            <?php $datas = $data['datas'] ?>
                            <?php foreach ($datas as $key => $budget) : ?>
                                <tr>
                                    <td><?= $budget->codeprojet ?></td>
                                    <td><?= $budget->ligne_budget ?></td>
                                    <td><?= $budget->designation_budget ?></td>
                                    <td><?= number_three($budget->budget) ?></td>
                                    <td><?= number_three($budget->reste) ?></td>
                                    <?php if (can_manage('budget')) : ?>
    <td>
        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
            <!-- Utilisateur restreint : tout désactivé -->
            <button class="btn btn-danger" type="button" disabled>
                <i class="fa-solid fa-trash"></i>
            </button>
            <button type="button" class="btn btn-warning" disabled>
                <i class="fa-solid fa-edit"></i>
            </button>
            <button type="button" class="btn btn-primary" disabled>
                <i class="fa-solid fa-eye"></i> Voir plus
            </button>
        <?php else : ?>
            <!-- Utilisateur normal : boutons actifs -->
            <button class="btn btn-danger delete" type="button" data-id="<?= $budget->idbudget ?>">
                <i class="fa-solid fa-trash"></i>
            </button>
            
            <button type="button" class="btn btn-warning edit" 
                    data-toggle="modal" 
                    data-target="#editModal" 
                    data-id="<?= $budget->idbudget ?>">
                <i class="fa-solid fa-edit"></i>
            </button>
            
            <!-- Bouton Voir plus → ACTIF -->
            <!-- <button type="button" class="btn btn-primary view-more" 
                    data-toggle="modal" 
                    data-target="#viewMoreModal" 
                    data-id="<?= $budget->idbudget ?>">
                <i class="fa-solid fa-eye"></i> 
            </button> -->
            <!-- CHANGEMENT ICI : lien vers la nouvelle page au lieu de modal -->
            <a href="<?= base_url('budget/details_budget/' . $budget->idbudget) ?>" 
               class="btn btn-primary">
                <i class="fa-solid fa-eye"></i> Voir détails
            </a>
        <?php endif; ?>
    </td>
<?php endif; ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                    <?php if (isset($nPages) && $nPages > 1) : ?>
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
                                            <?php if (isset($_POST['idprojet'])) : ?>
                                                <form action="<?= base_url('Activite/searchProjet/' . ($current - 1)) ?>" method="post">
                                                    <input type="hidden" name="idprojet" value="<?= $_POST['idprojet'] ?? '' ?>">
                                                    <button class="page-link" type="submit" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                    </button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href='<?= base_url('Activite/page/' . ($current - 1)) ?>' aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>

                                    <?php for ($i = 1; $i <= $nPages; $i++) : ?>
                                        <li class="page-item <?= ($i == $current) ? 'active' : '' ?>">
                                            <?php if (isset($_POST['idprojet'])) : ?>
                                                <form action="<?= base_url('Activite/searchProjet/' . $i) ?>" method="post">
                                                    <input type="hidden" name="idprojet" value="<?= $_POST['idprojet'] ?? '' ?>">
                                                    <button class="page-link" type="submit">
                                                        <?= $i ?>
                                                    </button>
                                                </form>
                                            <?php else : ?>
                                                <a class="page-link" href="<?= base_url('Activite/page/' . $i) ?>"><?= $i ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endfor; ?>

                                    <li>
                                        <?php if ($current == $nPages) : ?>
                                            <span class="page-link __disabled" aria-label="Next">
                                                <small aria-hidden="true"><i class="fa-solid fa-angle-right"></i></small>
                                            </span>
                                        <?php else : ?>
                                            <?php if (isset($_POST['idprojet'])) : ?>
                                                <form action="<?= base_url('Activite/searchProjet/' . ($current + 1)) ?>" method="post">
                                                    <input type="hidden" name="idprojet" value="<?= $_POST['idprojet'] ?? '' ?>">
                                                    <button class="page-link" type="submit" aria-label="Next">
                                                        <span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>
                                                    </button>
                                                </form>
                                            <?php else : ?>
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
                       <?php if (!empty($data['lien'])): ?>
    <div class="__pagination">
        <?php echo $data['lien']; ?>
    </div>
<?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>