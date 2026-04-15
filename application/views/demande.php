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
                                <h4 class="modal-title dispatch">Validation du Panier</h4>
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
                                <!-- <div class="form-check" id="tva_include">
                                    <input class="form-check-input" type="checkbox" id="tva_" data-status='off'>
                                    <label class="form-check-label" for="tva_">
                                        Inclure le TVA
                                    </label>
                                </div> -->
                                <button type="button" class="btn btn-info" id="sendvalidation"><i class="fas fa-check"></i> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fin Modal -->
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
                                    <div class="mb-2">
                                        <label class="form-label">Format : </label>
                                        <select class="form-select" name="" id="format">
                                            <option value="A4">A4</option>
                                            <option value="tiquet">Tiquet de caisse</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <div id="loaderFacture" style="display:flex; align-items:center; justify-content: center; width:100%; height:500px;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <iframe src="" class="d-noned" id="pdfFrame" style="width:100%; height:500px;" frameborder="0"></iframe>
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
                                                    <th>Désignation</th>
                                                    <th>Déscription</th>
                                                    <th>Quantité</th>
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

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-bs-dismiss="modal" id="panierValide" data-bs-dismiss="modal"><i class="fas fa-check"></i> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal panier -->
                <div>
                    <button id="affichefacture" class="btn  btn-info d-none" data-bs-toggle="modal" data-bs-target="#modalfacture">Afficher la facture</button>


                    <div class="onglet">
                        <a href="<?= base_url('demande-mat') ?>" id="panier_" class="onglet_btn active">Nouvel demande</a>
                        <a href="<?= base_url('liste-demande') ?>" class="onglet_btn">Récapitulatif de distribution</a>
                        <a href="<?= base_url('rendre-mat') ?>" class="onglet_btn">Reliquat  matériels</a>
                    </div>
                    <div class="mb-2" id="denomination_pv">
                        <div class="row">
                            <div class="col-12 point_vente">
                                <label class="form-label"> Dépôt :</label>
                                <?php if (count($pv) > 0) : ?>

                                    <?php if (!isset($_SESSION['pv'])) :  ?>
                                        <select class="form-select  point_vente" id="pv_vente">
                                            <?php for ($i = 0; $i < count($pv); $i++) : ?>
                                                <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    <?php else : ?>
                                        <select class="form-select  point_vente" id="pv_vente">
                                            <?php for ($i = 0; $i < count($pv); $i++) :
                                                if ($pv[$i]->idPointVente ==  $_SESSION['pv']) : ?>
                                                    <option class="pv" data-idpv="<?= $pv[$i]->idPointVente ?>" value="<?= $pv[$i]->idPointVente ?>"><?= $pv[$i]->denomination_pv ?></option>
                                            <?php endif;
                                            endfor; ?>
                                        </select>
                                    <?php endif  ?>
                                <?php else : ?>
                                    <p class="text-danger">Veuillez inserer un Dépôt s'il vous plaît</p>
                                <?php endif  ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="row">
                            <div class="col-12 ">
                                <label class="form-label">Projet :</label>
                                <?php if (count($pv) > 0) : ?>
                                    <select class="form-select " id="projet">
                                        <option value="">Aucun</option>
                                        <?php for ($i = 0; $i < count($projets); $i++) : ?>
                                                <option value="<?= $projets[$i]->idprojet ?>"><?= $projets[$i]->codeprojet ?></option>
                                        <?php endfor ; ?>
                                    </select>
                                <?php else : ?>
                                    <p class="text-danger">Aucun projet trouvé.</p>
                                <?php endif  ?>
                            </div>
                        </div>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Référence : </label>
                        <input type="text" class="form-control form-control-lg" id="reference" name="reference" required>
                        <input type="number" class="form-control form-control-lg d-none" id="idmateriel" name="idmateriel">
                        <input type="text" class="form-control form-control-lg d-none" id="type_produit" name="type_produit">
                    </div>



                    <div class="mb-2">
                        <label class="form-label">Désignation : </label>
                        <input type="text" class="form-control form-control-lg" id="designationmateriel" name="designationmateriel" readonly>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Description : </label>
                        <textarea name="fiche" id="fiche" class="form-control" style="resize: none;" readonly required></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Quantité disponible :</label>
                        <input class="form-control input_form-control" type="text" id="qte_dipo" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Quantité :</label>
                        <input class="form-control input_form-control" type="number" min='1' id="quantite" name="quantite" value="1" required>
                    </div>

                    <div class="mb-2" id="unite_container">
                        <label class="form-label">Unité :</label>
                        <select name="unite" id="unite" class="form-select">

                        </select>
                    </div>
                    <!-- NOUVEAU CHAMP : SELECTIONNER UN AGENT AVEC DATALIST -->
<!-- RECHERCHE AGENT EXACTEMENT COMME DANS MISSION -->
<div class="mb-3">
    <label class="form-label fw-bold">Agent Utilitaire :</label>
    <div class="input-group">
        <input type="text" 
               id="agent_search" 
               class="form-control form-control-lg" 
               placeholder="Tapez le nom, prénom, téléphone ou CIN de l'agent...">
        <button type="button" class="btn btn-info" id="btn_search_agent">
            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
        </button>
    </div>

    <!-- Résultats de la recherche -->
    <div id="agent_results" class="mt-2" 
         style="max-height: 300px; overflow-y: auto; display: none; border: 1px solid #ddd; border-radius: 5px; background: white;">
    </div>

    <!-- Agent sélectionné -->
    <div id="selected_agent_info" class="mt-2 alert alert-success d-none">
        <i class="fa-solid fa-user-check"></i> 
        <strong>Agent sélectionné :</strong>
        <span id="selected_agent_name"></span>
        <button type="button" class="btn btn-sm btn-danger float-end" id="clear_agent">
            <i class="fa-solid fa-times"></i> Annuler
        </button>
    </div>

    <input type="hidden" id="idagent" name="agent_id" value="">
</div>

<script>
$(document).ready(function() {
    // Créer un mapping nom -> id
    var agentMap = {};
    <?php if (isset($agents) && count($agents) > 0): ?>
        <?php for ($i = 0; $i < count($agents); $i++) : ?>
            agentMap["<?= htmlspecialchars($agents[$i]->nomUser . ' ' . $agents[$i]->prenomUser) ?>"] = "<?= $agents[$i]->idUser ?>";
        <?php endfor; ?>
    <?php endif; ?>

    $('#agent_search').on('change', function() {
        var selectedName = $(this).val();
        if (agentMap[selectedName]) {
            $('#agent_selected').val(agentMap[selectedName]);
        } else {
            $('#agent_selected').val('');
        }
    });
});
</script>

                    <div class="_boutton">
                        <?php if (isset($_SESSION['let_test']) && !$_SESSION['let_test']) : ?>
                            <button type="button" data-self='dispatch' class="btn btn-info" disabled><i class="fas fa-check"></i> Ajouter au panier</button>
                            <button type="button" class="btn btn-success d-none" disabled><i class="fas fa-check"></i> Valider le panier</button>
                            <button class="btn btn-danger annnuler_panier btn_container"><i class="fas fa-ban"></i> Annuler le panier</button>
                        <?php else :   ?>
                            <button type="submit" data-self='dispatch' class="btn btn-info" id="valider"><i class="fas fa-check"></i> Ajouter au panier</button>
                            <button type="button" class="btn btn-success d-none" data-bs-toggle="modal" data-bs-target="#myModal" id="validerPanier"><i class="fas fa-check"></i> Valider la demande</button>
                            <button class="btn btn-danger annnuler_panier btn_container"><i class="fas fa-ban"></i> Annuler le panier</button>
                        <?php endif  ?>
                    </div>

                    <?php if ($this->session->userdata('ajouter')) : ?>
                        <script>
                            Myalert.added();
                        </script>
                    <?php endif ?>
                    <?php $this->session->unset_userdata('ajouter') ?>

                    <div class="panier">
                        <div class="entete">
                            <h5 class="mb-3 mt-3">Panier</h5>

                        </div>

                        <div class="_tableau mt-4">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th>Réference</th>
                                        <th>Désignation</th>
                                        <th>Déscription </th>
                                        <th>Quantité</th>
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