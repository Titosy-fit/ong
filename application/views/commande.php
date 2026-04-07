<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">
                <!-- Modal -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Validation de la commande</h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal-body" id="validation" style="font-size: 14px;">
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <div class="form-check" id="inclusion_container">
                                    <div>
                                        <input class="form-check-input" type="checkbox" id="tmp_" data-status='off'>
                                        <label class="form-check-label" for="tva_">
                                            TMP
                                        </label>
                                    </div>
                                    <div >
                                        <input class="form-check-input" type="checkbox" id="tva_" data-status='off'>
                                        <label class="form-check-label" for="tva_">
                                            TVA
                                        </label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info" id="sendvalidation"><i class="fas fa-check"></i> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal -->

                <!-- Modal panier -->
                <div class="modal fade" id="PanierModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Panier</h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="modal_body" id="panier_body" style="font-size: 14px;">
                                    <div class="_tableau mt-4">
                                        <table class="table">
                                            <thead class="table-info">
                                                <tr class="to_filtre">
                                                    <th>
                                                        Désignation
                                                    </th>
                                                    <th>
                                                        Déscription
                                                    </th>
                                                    <th>
                                                        Quantité
                                                    </th>
                                                    <th>
                                                        Prix unitaire
                                                    </th>
                                                    <th>
                                                        Prix Total
                                                    </th>
                                                    <th>
                                                        Remise
                                                </tr>
                                            </thead>
                                            <tbody id="table_panier_validation">
                                            </tbody>
                                        </table>
                                        <input type="text" class="d-none" id="pointdeventPanier">
                                    </div>
                                </div>
                            </div>
<!-- Modal sélection budgets pour commande -->
<!-- <div class="modal fade" id="budgetCommandeModal" tabindex="-1" aria-labelledby="budgetCommandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="budgetCommandeModalLabel">Lignes budgétaires disponibles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="budget_modal_body">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Chargement des lignes budgétaires...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="valider_selection_budgets">Valider la sélection</button>
            </div>
        </div>
    </div>
</div> --><!-- Modal budgets pour COMMANDE (identique à Mission) -->
<div class="modal fade" id="budgetCommandeModal" tabindex="-1" aria-labelledby="budgetCommandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="budgetCommandeModalLabel">Veuillez sélectionner les budgets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="budget_modal_body_commande">
                <!-- Le loader ou la table sera injecté ici -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Chargement des lignes budgétaires...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="valider_selection_budgets_commande">
                    <i class="fas fa-check"></i> Valider la sélection
                </button>
            </div>
        </div>
    </div>
</div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-bs-dismiss="modal" id="panierValide" data-bs-dismiss="modal"><i class="fas fa-check"></i> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal panier -->
                <!-- Modal -->
                <div class="modal fade" id="modalfacture">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title dispatch">Facture </h4>
                                <button type="button" class="btn-close d-flex" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-x"></i>
                                </button>
                            </div>
                            <div class="container">
                                <!-- Modal body -->
                                <div class="" id="validation" style="font-size: 14px;">
                                    <p class="text-info">Veuillez désactiver IDM si vous en avez.</p>
                                    <div class="">
                                        <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <iframe src="" class="d-none" id="pdfFrame" style="width:100%; height:500px;" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal -->
                <div>
                    <button id="affichefacture" class="btn  btn-info d-none" data-bs-toggle="modal" data-bs-target="#modalfacture">Afficher la facture</button>

                    <div class="onglet">
                        <a href="<?= base_url('appro') ?>" class="onglet_btn "> Approvisionnement </a>
                        <a href="#" id="panier_" class="onglet_btn active"> Commande</a>
                        <a href="<?= base_url('listecommande') ?>" class="onglet_btn">Liste des commandes </a>
                        <a href="<?= base_url('reception') ?>" class="onglet_btn">Reception de commande </a>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Réference : </label>
                        <input type="text" class="form-control form-control-lg" id="reference" name="reference" required>
                        <input type="number" class="form-control form-control-lg d-none" id="idmateriel" name="idmateriel">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Réference fournisseur : </label>
                        <input type="text" class="form-control form-control-lg" id="reference_fournisseur" name="reference_fournisseur" required>
                        <input type="number" class="form-control form-control-lg d-none" id="idmateriel" name="idmateriel">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Désignation : </label>
                        <input type="text" class="form-control form-control-lg" id="designationmateriel" name="designationmateriel">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Déscription : </label>
                        <textarea name="fiche" id="fiche" class="form-control" style="resize: none;" readonly></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Prix Unitaire:</label>
                        <input class="form-control input_form-control " type="text" id="prix" name="prix_unitaire">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Quantité :</label>
                        <input class="form-control input_form-control" type="number" min='1' id="quantite" name="quantite" required>
                    </div>

                    <div class="mb-2" id="unite_container">
                        <label class="form-label">Unité :</label>
                        <select name="unite" id="unite" class="form-select">

                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Montant :</label>
                        <input class="form-control input_form-control" type="text" id="montant_show" name="montant_show" value="0" readonly>
                        <input class="form-control input_form-control d-none" type="text" id="montant" name="montant" required>
                    </div>

                    <!-- <div class="mb-2">
                        <label class="form-label">Remise (en MGA)</label>
                        <input id='remise' type="number" value="0" min="0" max="100" class="form-control mb-1" />
                    </div> -->


                    <div class="_boutton">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <button type="button" data-self='dispatch' class="btn btn-info" disabled><i class="fas fa-check"></i> Ajouter au panier</button>
                            <button type="button" class="btn btn-success d-none" disabled><i class="fas fa-check"></i> Valider le panier</button>
                            <button class="btn btn-danger annnuler_panier btn_container"><i class="fas fa-ban"></i> Annuler le panier</button>
                        <?php else :   ?>
                            <button type="submit" data-self='dispatch' class="btn btn-info" id="valider"><i class="fas fa-check"></i> Ajouter au panier</button>
                            <button type="button" class="btn btn-success d-none" data-bs-toggle="modal" data-bs-target="#myModal" id="validerPanier"><i class="fas fa-check"></i> valider la commande</button>
                            <button class="btn btn-danger annnuler_panier btn_container"><i class="fas fa-ban"></i> Annuler le commande</button>
                        <?php endif  ?>
                    </div>

                    <?php if ($this->session->flashdata('success')) : ?>
                        <script>
                            Myalert.added();
                        </script>
                    <?php endif ?>

                    <div class="panier">
                        <div class="entete">
                            <h5 class="mb-3 mt-3">Panier</h5>

                        </div>

                        <div class="_tableau mt-4">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Réference</th>
                                        <th>Réference fournisseur</th>
                                        <th>Désignation</th>
                                        <th>Prix Unitaire</th>
                                        <th>Quantité</th>
                                        <th>Montant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tableau">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" placeholder="Remise" class="form-control remise_panier" style="width: 50px ;">