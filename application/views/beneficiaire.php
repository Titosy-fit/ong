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
                        <div class="input-group" style="gap: 12px;">
                            <input type="text" id="searchBeneficiaire" class="form-control" placeholder="Nom, prénom, téléphone, CIN..." autocomplete="off" style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 12px 16px; font-size: 16px;">
                            <button class="btn" type="button" id="btnSearch" style="background: #820000; color: white; border-radius: 12px; padding: 12px 24px; border: none; transition: all 0.3s;">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        </div>
                        <div id="searchResult" class="mt-4"></div>
                    </div>
                </div>

                <!-- SECTION HISTORIQUE DU BÉNÉFICIAIRE -->
                <div id="historiqueSection" class="card" style="display: none; border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                    <div class="card-header" style="background: linear-gradient(135deg, #820000 0%, #a00000 100%); color: white; border: none; padding: 20px 24px;">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i> Historique des distributions - <span id="beneficiaireNom" style="font-weight: 600;"></span></h5>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div class="_tableau">
                            <table class="table table-modern" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                                <thead style="background: #f8f9fa;">
                                    <tr style="border-bottom: 2px solid #820000;">
                                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #333;">Matériel</th>
                                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #333;">Série</th>
                                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #333;">Date de distribution</th>
                                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #333;">État</th>
                                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #333;">Observations</th>
                                    </tr>
                                </thead>
                                <tbody id="historiqueList">
                                    <tr id="noHistoryRow">
                                        <td colspan="5" class="text-center" style="padding: 40px; color: #999;">Aucun historique trouvé pour ce bénéficiaire.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Message si utilisateur non trouvé -->
                <div id="noUserFound" class="alert" style="display: none; background: #fff3f3; border-left: 4px solid #820000; border-radius: 12px; padding: 16px 20px; margin-top: 20px;">
                    <i class="fas fa-exclamation-triangle" style="color: #820000;"></i> Aucun bénéficiaire trouvé avec ces critères.
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
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(130, 0, 0, 0.3);
    }
    
    .table-modern tbody tr {
        transition: all 0.3s;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table-modern tbody tr:hover {
        background: #fafafa;
        transform: scale(1.01);
    }
    
    .table-modern td {
        padding: 14px 16px;
        color: #555;
    }
    
    .onglet_btn:not(.active):hover {
        background: #f5f5f5;
        color: #820000;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #searchResult, #historiqueSection, #noUserFound {
        animation: fadeIn 0.4s ease-out;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }
</style>

<script>
    // Simulation de données (à remplacer par vos appels AJAX réels)
    const beneficiairesData = {
        1: {
            id: 1,
            nom: "DIALLO",
            prenom: "Mamadou",
            telephone: "778888999",
            cin: "123456",
            historique: [
                { materiel: "Ordinateur portable Dell XPS", serie: "XPS-15-001", date: "2023-10-15", etat: "Distribué", obs: "Bon état, sous garantie" },
                { materiel: "Clavier USB Logitech", serie: "CLAV-001", date: "2024-01-20", etat: "Distribué", obs: "Neuf" },
                { materiel: "Souris sans fil", serie: "SOU-045", date: "2024-02-10", etat: "Distribué", obs: "Fonctionne parfaitement" }
            ]
        },
        2: {
            id: 2,
            nom: "SOW",
            prenom: "Aissatou",
            telephone: "771112223",
            cin: "654321",
            historique: [
                { materiel: "Tablette Samsung", serie: "TAB-10-002", date: "2024-02-10", etat: "Distribué", obs: "Sous garantie 1 an" }
            ]
        },
        3: {
            id: 3,
            nom: "NDIAYE",
            prenom: "Ousmane",
            telephone: "765432109",
            cin: "789012",
            historique: [
                { materiel: "Imprimante HP LaserJet", serie: "HPL-789", date: "2024-03-05", etat: "Distribué", obs: "Cartouches incluses" },
                { materiel: "Onduleur", serie: "OND-456", date: "2024-03-05", etat: "Distribué", obs: "Neuf" }
            ]
        }
    };

    // Fonction pour rechercher un bénéficiaire (simulée)
    function rechercherBeneficiaire(critere) {
        const searchResultDiv = document.getElementById('searchResult');
        const historiqueSection = document.getElementById('historiqueSection');
        const noUserFound = document.getElementById('noUserFound');
        
        if (!critere.trim()) {
            searchResultDiv.innerHTML = '<div class="alert alert-info" style="background: #e8f4fd; border-left: 4px solid #17a2b8; border-radius: 12px;">Veuillez saisir un critère de recherche.</div>';
            historiqueSection.style.display = 'none';
            noUserFound.style.display = 'none';
            return;
        }

        // Simulation de recherche (à remplacer par un appel AJAX vers votre backend)
        let foundBeneficiaire = null;
        for (let id in beneficiairesData) {
            const bene = beneficiairesData[id];
            if (bene.nom.toLowerCase().includes(critere.toLowerCase()) ||
                bene.prenom.toLowerCase().includes(critere.toLowerCase()) ||
                bene.telephone.includes(critere) ||
                bene.cin.includes(critere)) {
                foundBeneficiaire = bene;
                break;
            }
        }

        if (foundBeneficiaire) {
            // Afficher les infos du bénéficiaire trouvé
            searchResultDiv.innerHTML = `
                <div style="background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%); border-radius: 12px; padding: 20px; border-left: 4px solid #820000;">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap;">
                        <div>
                            <h6 style="color: #820000; margin-bottom: 12px;"><i class="fas fa-user-check"></i> Bénéficiaire trouvé</h6>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                                <div><strong>Nom :</strong> ${foundBeneficiaire.nom}</div>
                                <div><strong>Prénom :</strong> ${foundBeneficiaire.prenom}</div>
                                <div><strong>Téléphone :</strong> ${foundBeneficiaire.telephone}</div>
                                <div><strong>CIN :</strong> ${foundBeneficiaire.cin}</div>
                            </div>
                        </div>
                        <button class="btn" onclick="afficherHistorique(${foundBeneficiaire.id})" style="background: #820000; color: white; border-radius: 10px; padding: 10px 20px; margin-top: 10px; border: none;">
                            <i class="fas fa-chart-line"></i> Voir l'historique
                        </button>
                    </div>
                </div>
            `;
            noUserFound.style.display = 'none';
            historiqueSection.style.display = 'none';
        } else {
            searchResultDiv.innerHTML = '';
            historiqueSection.style.display = 'none';
            noUserFound.style.display = 'block';
        }
    }

    // Fonction pour afficher l'historique d'un bénéficiaire
    function afficherHistorique(beneficiaireId) {
        const bene = beneficiairesData[beneficiaireId];
        if (!bene) return;

        // Mettre à jour le titre
        document.getElementById('beneficiaireNom').innerText = `${bene.prenom} ${bene.nom}`;
        
        // Remplir le tableau d'historique
        const tbody = document.getElementById('historiqueList');
        tbody.innerHTML = '';
        
        if (bene.historique && bene.historique.length > 0) {
            bene.historique.forEach((item, index) => {
                const row = tbody.insertRow();
                row.style.animation = `fadeIn 0.3s ease-out ${index * 0.1}s both`;
                row.insertCell(0).innerHTML = `<i class="fas fa-laptop" style="color: #820000; margin-right: 8px;"></i>${item.materiel}`;
                row.insertCell(1).innerText = item.serie;
                row.insertCell(2).innerHTML = `<i class="far fa-calendar-alt" style="color: #820000; margin-right: 8px;"></i>${item.date}`;
                row.insertCell(3).innerHTML = `<span style="background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${item.etat}</span>`;
                row.insertCell(4).innerText = item.obs;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding: 40px; color: #999;"><i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 12px;"></i>Aucun historique trouvé pour ce bénéficiaire.</td></tr>';
        }
        
        // Afficher la section historique
        const historiqueSection = document.getElementById('historiqueSection');
        historiqueSection.style.display = 'block';
        
        // Scroll vers l'historique
        historiqueSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Événement au clic sur le bouton Rechercher
    document.getElementById('btnSearch').addEventListener('click', function() {
        const critere = document.getElementById('searchBeneficiaire').value;
        rechercherBeneficiaire(critere);
    });

    // Recherche avec touche Entrée
    document.getElementById('searchBeneficiaire').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            rechercherBeneficiaire(this.value);
        }
    });
</script>