<div class="main">
    <div class="wrapper">
        <div class="corps">
            <div class="stock_corps">

                <!-- MODAL (gardée pour modification) -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header" style="background: #820000; color: white;">
                                <h5 class="modal-title" id="editModalLabel">
                                    Modification de l'Utilisateur
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= base_url('edit-bene') ?>" id="modifClient" method="post">
                                    <input id="idbeneficiaire" name="idbeneficiaire" type="text" class="form-control input_form-control" required>
                                    <p class="text-danger d-none" id="ms_error_modif">Le numéro ou le numéro de CIN que vous avez entré existe déjà.</p>
                                    <div class="mb-3 not_public">
                                        <label class="form-label fw-semibold">Nom : </label>
                                        <input id="nom_modif" name="nom" type="text" class="form-control input_form-control" required>
                                    </div>
                                    <div class="mb-3 not_public">
                                        <label class="form-label fw-semibold">Prénom : </label>
                                        <input id="prenom_modif" name="prenom" type="text" class="form-control input_form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Adresse : </label>
                                        <input name="adress" id="address_modif" type="text" class="form-control input_form-control " required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Numéro Téléphone : </label>
                                        <input name="numero" id="num_modif" type="text" class="form-control input_form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email : </label>
                                        <input name="email" id="email_modif" type="email" class="form-control input_form-control" required>
                                        <div class="text-danger fs-5 font-weight-bold">
                                            <?= form_error('email'); ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Numéro CIN : </label>
                                        <input name="num_cin" id="num_cin_modif" type="text" class="form-control input_form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Date CIN : </label>
                                        <input name="date_cin" id="date_cin_modif" type="date" class="form-control input_form-control" required>
                                    </div>

                                    <div class="mt-3 d-flex gap-2">
                                        <button type="button" class="btn btn-info d-none" id="modification">Modifier</button>
                                        <a href="#" id="modifier" class="btn" style="background: #820000; color: white; border: none;">
                                            Modifier
                                        </a>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->

                <!-- SECTION RECHERCHE BÉNÉFICIAIRE -->
                <div class="card mb-4" style="border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                    <div class="card-body" style="padding: 24px;">
                        <h5 style="color: #820000; margin-bottom: 16px; font-weight: 700; font-size: 18px;">
                            Recherche Bénéficiaire
                        </h5>
                        <div class="input-group" style="gap: 12px;">
                            <input type="text" id="searchBeneficiaire" class="form-control" placeholder="Tapez le nom du bénéficiaire..." autocomplete="off" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 12px 16px; font-size: 16px;">
                            <button class="btn" type="button" id="btnSearch" style="background: #820000; color: white; border-radius: 12px; padding: 12px 24px; border: none; transition: all 0.3s; font-size: 15px;">
                                Rechercher
                            </button>
                        </div>
                        <div id="searchResult" class="mt-3" style="max-height: 300px; overflow-y: auto; display: none; border: 1px solid #e0e0e0; border-radius: 12px; background: white;"></div>
                    </div>
                </div>

                <!-- SECTION INFO BÉNÉFICIAIRE SÉLECTIONNÉ -->
                <div id="selectedBenefInfo" class="card mb-4" style="display: none; border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; background: #f8f9fa;">
                    <div class="card-body" style="padding: 20px 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div>
                                <h6 style="color: #820000; margin-bottom: 8px; font-size: 14px; font-weight: 600;">Bénéficiaire sélectionné</h6>
                                <span id="selectedBenefName" style="font-size: 18px; font-weight: 600; color: #333;"></span>
                            </div>
                            <button class="btn btn-sm" id="clearBenef" style="background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; padding: 6px 14px; color: #666; font-size: 13px;">
                                Changer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION HISTORIQUE -->
                <div id="historiqueSection" style="display: none;">
                    <div class="card" style="border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                        
                        <!-- En-tête -->
                        <div style="background: #fafafa; padding: 16px 24px; border-bottom: 1px solid #e0e0e0;">
                            <h5 style="color: #820000; margin: 0; font-weight: 700; font-size: 18px;">
                                Historique complet
                            </h5>
                            <p style="margin: 8px 0 0 0; color: #666; font-size: 14px;">
                                Missions, liquidations et reliquats du bénéficiaire
                            </p>
                        </div>

                        <!-- Statistiques rapides -->
                       
                        
                        <!-- Onglets -->
                        <div class="card-header" style="background: white; border: none; padding: 0;">
                            <div style="display: flex; border-bottom: 2px solid #f0f0f0;">
                                <button class="tab-btn active" data-tab="missions" style="flex: 1; padding: 16px 20px; border: none; background: none; font-weight: 600; color: #820000; border-bottom: 3px solid #820000; cursor: pointer; transition: all 0.3s; font-size: 16px; position: relative;">
                                    Missions
                                    <span style="position: absolute; top: 10px; right: 20px; background: #820000; color: white; border-radius: 20px; padding: 3px 10px; font-size: 12px;" id="countMissions">0</span>
                                </button>
                                <button class="tab-btn" data-tab="liquidations" style="flex: 1; padding: 16px 20px; border: none; background: none; font-weight: 600; color: #999; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.3s; font-size: 16px; position: relative;">
                                    Liquidations
                                    <span style="position: absolute; top: 10px; right: 20px; background: #c17900; color: white; border-radius: 20px; padding: 3px 10px; font-size: 12px;" id="countLiquidations">0</span>
                                </button>
                                <button class="tab-btn" data-tab="reliquats" style="flex: 1; padding: 16px 20px; border: none; background: none; font-weight: 600; color: #999; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.3s; font-size: 16px; position: relative;">
                                    Reliquats
                                    <span style="position: absolute; top: 10px; right: 20px; background: #2e7d32; color: white; border-radius: 20px; padding: 3px 10px; font-size: 12px;" id="countReliquats">0</span>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="padding: 0;">
                            
                            <!-- TAB: MISSIONS -->
                            <div class="tab-content-panel" id="tab-missions" style="display: block;">
                                <div class="_tableau" style="overflow-x: auto;">
                                    <table class="table-modern" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="border-bottom: 2px solid #820000; background: #f8f9fa;">
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Code projet</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Objet</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">ASM / OM</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Agent</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Date début</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Date fin</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Lieu</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Avance</th>
                                              </tr>
                                        </thead>
                                        <tbody id="missionsList">
                                            <tr id="noMissionRow">
                                                <td colspan="8" class="text-center" style="padding: 60px; color: #999; font-size: 15px; text-align: center;">
                                                    Aucune mission pour ce bénéficiaire
                                                  </td>
                                              </tr>
                                        </tbody>
                                      </table>
                                </div>
                            </div>

                            <!-- TAB: LIQUIDATIONS -->
                            <div class="tab-content-panel" id="tab-liquidations" style="display: none;">
                                <div class="_tableau" style="overflow-x: auto;">
                                    <table class="table-modern" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="border-bottom: 2px solid #c17900; background: #f8f9fa;">
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Code projet</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Objet mission</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">N° OM</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Avance</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Dépense</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Reliquat</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Retourné</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Date liquidation</th>
                                              </tr>
                                        </thead>
                                        <tbody id="liquidationsList">
                                            <tr id="noLiquidationRow">
                                                <td colspan="8" class="text-center" style="padding: 60px; color: #999; font-size: 15px; text-align: center;">
                                                    Aucune liquidation pour ce bénéficiaire
                                                  </td>
                                              </tr>
                                        </tbody>
                                      </table>
                                </div>
                            </div>

                            <!-- TAB: RELIQUATS -->
                            <div class="tab-content-panel" id="tab-reliquats" style="display: none;">
                                <div class="_tableau" style="overflow-x: auto;">
                                    <table class="table-modern" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="border-bottom: 2px solid #2e7d32; background: #f8f9fa;">
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Objet mission</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">N° OM</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Montant retourné</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Reste à retourner</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Date retour</th>
                                                <th style="padding: 16px 18px; text-align: left; font-weight: 700; color: #333; font-size: 15px;">Date liquidation</th>
                                              </tr>
                                        </thead>
                                        <tbody id="reliquatsList">
                                            <tr id="noReliquatRow">
                                                <td colspan="6" class="text-center" style="padding: 60px; color: #999; font-size: 15px; text-align: center;">
                                                    Aucun reliquat pour ce bénéficiaire
                                                  </td>
                                              </tr>
                                        </tbody>
                                      </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Message si bénéficiaire non trouvé -->
                <div id="noUserFound" class="alert" style="display: none; background: #fff3f3; border-left: 4px solid #820000; border-radius: 12px; padding: 16px 20px; margin-top: 20px; font-size: 14px;">
                    Aucun bénéficiaire trouvé avec ces critères.
                </div>

                <!-- Loader -->
                <div id="loaderHistorique" style="display: none; text-align: center; padding: 60px;">
                    <div class="spinner-border" style="color: #820000; width: 4rem; height: 4rem;" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-3" style="color: #666; font-size: 15px;">Chargement de l'historique...</p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Styles professionnels et simples */
    * {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    .form-control:focus {
        border-color: #820000;
        box-shadow: 0 0 0 0.2rem rgba(130, 0, 0, 0.25);
    }
    
    #btnSearch:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(130, 0, 0, 0.3);
    }
    
    /* Style principal du tableau - TEXTE AGRANDI */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .table-modern tbody tr:hover {
        background: #f5f5f5;
    }
    
    .table-modern td {
        padding: 16px 18px;
        color: #333;
        font-size: 15px;
        vertical-align: middle;
        line-height: 1.5;
    }
    
    .table-modern th {
        font-size: 15px;
        font-weight: 700;
        color: #555;
        background: #f8f9fa;
    }
    
    /* Résultats de recherche */
    .benef-result-item {
        padding: 14px 18px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 15px;
        color: #333;
    }
    
    .benef-result-item:hover {
        background: #f5f5f5;
    }
    
    .benef-result-item:last-child {
        border-bottom: none;
    }
    
    /* Onglets */
    .tab-btn {
        position: relative;
        outline: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .tab-btn:hover {
        color: #820000 !important;
        background: #fafafa !important;
    }
    
    .tab-btn.active {
        color: #820000 !important;
        border-bottom: 3px solid #820000 !important;
        background: white;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #historiqueSection, #noUserFound, #selectedBenefInfo {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Scrollbar */
    ._tableau::-webkit-scrollbar {
        height: 8px;
    }
    
    ._tableau::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ._tableau::-webkit-scrollbar-thumb {
        background: #820000;
        border-radius: 4px;
    }
    
    ._tableau::-webkit-scrollbar-thumb:hover {
        background: #5c0000;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .tab-btn {
            font-size: 14px !important;
            padding: 12px 8px !important;
        }
        
        .table-modern td, .table-modern th {
            padding: 12px 14px;
            font-size: 14px;
        }
        
        .table-modern th {
            font-size: 14px;
        }
        
        .benef-result-item {
            font-size: 14px;
            padding: 12px 16px;
        }
    }
    
    /* Style simple et épuré */
    .btn, .form-control, .card {
        border-radius: 8px;
    }
    
    .card {
        border: 1px solid #e0e0e0;
    }
    
    /* Montants en valeur */
    .table-modern td:last-child {
        font-weight: 500;
        color: #820000;
    }
</style>