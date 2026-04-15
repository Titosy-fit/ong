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
                                    <i class="fas fa-user-edit me-2"></i>Modification de l'Utilisateur
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
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
                                        <button type="button" class="btn btn-info d-none" id="modification"><i class="fas fa-pencil-alt"></i> Modifier</button>
                                        <a href="#" id="modifier" class="btn" style="background: #820000; color: white; border: none;">
                                            <i class="fas fa-pencil-alt"></i> Modifier
                                        </a>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN MODAL -->

                <!-- Onglets modernes -->
                <div class="onglet" style="display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px;">
                    
                    
                </div>

                <!-- SECTION RECHERCHE BÉNÉFICIAIRE - DESIGN MODERNE -->
                <div class="card mb-4" style="border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                  
                    <div class="card-body" style="padding: 24px;">
                        <h5 style="color: #820000; margin-bottom: 16px; font-weight: 700;">
                            <i class="fas fa-search me-2"></i> Recherche Bénéficiaire
                        </h5>
                        <div class="input-group" style="gap: 12px;">
                            <input type="text" id="searchBeneficiaire" class="form-control" placeholder="Tapez le nom du bénéficiaire..." autocomplete="off" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 12px 16px; font-size: 16px;">
                            <button class="btn" type="button" id="btnSearch" style="background: #820000; color: white; border-radius: 12px; padding: 12px 24px; border: none; transition: all 0.3s;">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        </div>
                        <!-- Résultats de recherche (liste déroulante) -->
                        <div id="searchResult" class="mt-3" style="max-height: 300px; overflow-y: auto; display: none; border: 1px solid #e0e0e0; border-radius: 12px; background: white;"></div>
                    </div>
                </div>

                <!-- SECTION INFO BÉNÉFICIAIRE SÉLECTIONNÉ -->
                <div id="selectedBenefInfo" class="card mb-4" style="display: none; border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                    <div class="card-body" style="padding: 20px 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div>
                                <h6 style="color: #820000; margin-bottom: 8px;"><i class="fas fa-user-check"></i> Bénéficiaire sélectionné</h6>
                                <span id="selectedBenefName" style="font-size: 18px; font-weight: 600; color: #333;"></span>
                            </div>
                            <button class="btn btn-sm" id="clearBenef" style="background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; padding: 6px 14px; color: #666;">
                                <i class="fa-solid fa-times"></i> Changer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ONGLETS MISSION / LIQUIDATION / RELIQUAT -->
                <div id="historiqueSection" style="display: none;">
                    <div class="card" style="border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                        
                        <!-- Onglets -->
                        <div class="card-header" style="background: white; border: none; padding: 0;">
                            <div style="display: flex; border-bottom: 2px solid #f0f0f0;">
                                <button class="tab-btn active" data-tab="missions" style="flex: 1; padding: 16px; border: none; background: none; font-weight: 600; color: #820000; border-bottom: 3px solid #820000; cursor: pointer; transition: all 0.3s; font-size: 14px;">
                                    <i class="fas fa-plane-departure me-1"></i> Missions
                                    <span class="badge bg-danger ms-1" id="countMissions">0</span>
                                </button>
                                <button class="tab-btn" data-tab="liquidations" style="flex: 1; padding: 16px; border: none; background: none; font-weight: 600; color: #999; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.3s; font-size: 14px;">
                                    <i class="fas fa-file-invoice-dollar me-1"></i> Liquidations
                                    <span class="badge bg-warning ms-1" id="countLiquidations">0</span>
                                </button>
                                <button class="tab-btn" data-tab="reliquats" style="flex: 1; padding: 16px; border: none; background: none; font-weight: 600; color: #999; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.3s; font-size: 14px;">
                                    <i class="fas fa-undo-alt me-1"></i> Reliquats
                                    <span class="badge bg-success ms-1" id="countReliquats">0</span>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="padding: 0;">
                            
                            <!-- TAB: MISSIONS -->
                            <div class="tab-content-panel" id="tab-missions" style="display: block;">
                                <div class="_tableau">
                                    <table class="table table-modern" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                                        <thead style="background: #f8f9fa;">
                                            <tr style="border-bottom: 2px solid #820000;">
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Code projet</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Objet</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">ASM / OM</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Agent</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Date début</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Date fin</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Lieu</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Avance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="missionsList">
                                            <tr id="noMissionRow">
                                                <td colspan="8" class="text-center" style="padding: 40px; color: #999;">
                                                    <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                                                    Aucune mission pour ce bénéficiaire.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB: LIQUIDATIONS -->
                            <div class="tab-content-panel" id="tab-liquidations" style="display: none;">
                                <div class="_tableau">
                                    <table class="table table-modern" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                                        <thead style="background: #f8f9fa;">
                                            <tr style="border-bottom: 2px solid #c17900;">
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Code projet</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Objet mission</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">N° OM</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Avance</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Dépense</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Reliquat</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Retourné</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Date liquidation</th>
                                            </tr>
                                        </thead>
                                        <tbody id="liquidationsList">
                                            <tr id="noLiquidationRow">
                                                <td colspan="8" class="text-center" style="padding: 40px; color: #999;">
                                                    <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                                                    Aucune liquidation pour ce bénéficiaire.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB: RELIQUATS -->
                            <div class="tab-content-panel" id="tab-reliquats" style="display: none;">
                                <div class="_tableau">
                                    <table class="table table-modern" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                                        <thead style="background: #f8f9fa;">
                                            <tr style="border-bottom: 2px solid #2e7d32;">
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Objet mission</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">N° OM</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Montant retourné</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Reste à retourner</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Date retour</th>
                                                <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #333; font-size: 13px;">Date liquidation</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reliquatsList">
                                            <tr id="noReliquatRow">
                                                <td colspan="6" class="text-center" style="padding: 40px; color: #999;">
                                                    <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                                                    Aucun reliquat pour ce bénéficiaire.
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
                <div id="noUserFound" class="alert" style="display: none; background: #fff3f3; border-left: 4px solid #820000; border-radius: 12px; padding: 16px 20px; margin-top: 20px;">
                    <i class="fas fa-exclamation-triangle" style="color: #820000;"></i> Aucun bénéficiaire trouvé avec ces critères.
                </div>

                <!-- Loader -->
                <div id="loaderHistorique" style="display: none; text-align: center; padding: 40px;">
                    <div class="spinner-border" style="color: #820000; width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2" style="color: #999;">Chargement de l'historique...</p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Styles modernes supplémentaires */
    .form-control:focus {
        border-color: #820000;
        box-shadow: 0 0 0 0.2rem rgba(130, 0, 0, 0.25);
    }
    
    #btnSearch:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(130, 0, 0, 0.3);
    }
    
    .table-modern tbody tr {
        transition: all 0.3s;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table-modern tbody tr:hover {
        background: #fafafa;
    }
    
    .table-modern td {
        padding: 12px 16px;
        color: #555;
        font-size: 13px;
    }
    
    .benef-result-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .benef-result-item:hover {
        background: linear-gradient(135deg, #fdf2f2 0%, #fff 100%);
        padding-left: 20px;
    }

    .benef-result-item:last-child {
        border-bottom: none;
    }

    .tab-btn {
        position: relative;
        outline: none;
    }

    .tab-btn:hover {
        color: #820000 !important;
        background: #fdf2f2 !important;
    }

    .tab-btn.active {
        color: #820000 !important;
        border-bottom: 3px solid #820000 !important;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #historiqueSection, #noUserFound, #selectedBenefInfo {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeInRow {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .animated-row {
        animation: fadeInRow 0.3s ease-out both;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    .badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 10px;
    }
</style>