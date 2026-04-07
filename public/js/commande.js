// Nouveaux js *************************
let real_unite = [];
let data_fournisseur = [];
// Variables panier
let qte_produit_panier_commande = {};
let allunite_panier_commande   = {};
let tableau_panier_commande    = {};
// Variables globales pour stocker l'état
let current_nom_fournisseur = '';
let current_idfournisseur = '';
let current_projet_id = '';
let current_projet_code = '';
let current_projet_titre = '';
// Chargement unique des projets (autocomplétion)
let projets = [];
$.ajax({
    type: "POST",
    url: base_url('Projet/projet_json'),
    dataType: "json",
    success: function (response) {
        if (response.success && Array.isArray(response.data) && response.data.length > 0) {
            projets = response.data;
            console.log(`→ ${projets.length} projets chargés pour l'autocomplétion`);
        } else {
            console.warn("Aucun projet disponible");
        }
    },
    error: function () {
        console.error("Erreur chargement liste projets");
    }
});
let frais_livraison_ = 0;
// LocalStorage
function addLocaleStorage(nom = 'panier', tableau) {
    localStorage.setItem(nom, JSON.stringify(tableau));
}
function getLocaleStorage(nom = '', init = {}) {
    const data = localStorage.getItem(nom);
    return data ? JSON.parse(data) : init;
}
// Reprise localStorage
qte_produit_panier_commande = getLocaleStorage('qte_produit_panier_commande', {});
allunite_panier_commande   = getLocaleStorage('allunite_panier_commande',   {});
tableau_panier_commande    = getLocaleStorage('tableau_panier_commande',    {});
// Affichage initial panier
$('#tableau').html(append_tableau());
// Fonctions utilitaires
function vider() {
    $('#reference').val('');
    $('#reference_fournisseur').val('');
    $('#idmateriel').val('');
    $('#designationmateriel').val('');
    $('#fiche').val('');
    $('#prix').val('');
    $('#quantite').val('');
    $('#montant').val('');
    $('#montant_show').val('0');
    $('#unite').html('');
    $('#unite_container').addClass('d-none');
    real_unite = [];
}
function calcule_total() {
    const prix = parseInt($('#prix').val(), 10);
    const quantite = parseInt($('#quantite').val(), 10);
    if (!isNaN(prix) && !isNaN(quantite)) {
        const montant = prix * quantite;
        $('#montant').val(montant);
        $('#montant_show').val(montant.toLocaleString("fr-FR") + ' Ar');
    } else {
        $('#montant').val('');
        $('#montant_show').val('');
    }
}
// Recherche produit + unités
$(document).on('change', '#reference', function () {
    const reference = $(this).val();
    if (reference != '') {
        $.ajax({
            method: 'post',
            url: base_url('Commande/getProduit'),
            data: { reference: reference },
            dataType: 'json',
        }).done(function (recherche_prod) {
            if (recherche_prod.success) {
                $.ajax({
                    method: 'post',
                    url: base_url('Dispatch/unite'),
                    data: { idmateriel: recherche_prod.datas.idmateriel },
                    dataType: 'json',
                }).done(function (response) {
                    const produit = recherche_prod.datas;
                    const unites_prix = response.data;
                    real_unite = unites_prix;
                    allunite_panier_commande[produit.refmateriel] = unites_prix;
                    addLocaleStorage('allunite_panier_commande', allunite_panier_commande);
                    $('#reference').val(produit.refmateriel);
                    $('#reference_fournisseur').val(produit.refmateriel);
                    $('#idmateriel').val(produit.idmateriel);
                    $('#designationmateriel').val(produit.designationmateriel);
                    $('#fiche').val(produit.fiche);
                    let content = '';
                    for (let i = 0; i < unites_prix.length; i++) {
                        const element = unites_prix[i];
                        if (element.denomination) {
                            content += `<option class='unites' value="${element.idunite}" data-id='${i}' data-prix='${element.prixProduit}'>${element.denomination}</option>`;
                            $('#unite_container').removeClass('d-none');
                        } else {
                            $('#unite_container').addClass('d-none');
                        }
                    }
                    if (unites_prix.length == 0) {
                        $('#unite_container').addClass('d-none');
                    }
                    $('#unite').html(content);
                    $('#reference_fournisseur').focus();
                }).fail(function () {
                    console.error('erreur dans la recuperation du prix');
                })
            } else {
                vider();
                Myalert.erreur('Cette référence n\'existe pas.');
            }
        }).fail(function (err) {
            console.error(err);
        })
    } else {
        vider();
    }
});
$(document).on('keyup change', '#quantite, #prix', function () {
    if ($(this).val() != '') {
        calcule_total();
    } else {
        $('#montant').val('');
        $('#montant_show').val('');
    }
});
// Conversion unité minimale
function qteMinUnit(allUnit = [], qte = 0, unite_identification = 0) {
    while (allUnit[unite_identification + 1]) {
        qte *= allUnit[unite_identification + 1].formule;
        unite_identification++;
    }
    return qte;
}
// Ajout au panier
function addToPanierTab(idmateriel, qte_min, quantite, reference, reference_fournisseur, designationmateriel, montant, prix, unite_texte, identification, id_unite) {
    let texte = '';
    if (tableau_panier_commande[reference] && tableau_panier_commande[reference].length != 0) {
        texte = `${reference}_${tableau_panier_commande[reference].length}`;
        let teste_unite = false;
        for (let i = 0; i < tableau_panier_commande[reference].length; i++) {
            const element = tableau_panier_commande[reference][i];
            element.qte_min = parseInt(element.qte_min) + parseInt(qte_min);
            qte_produit_panier_commande[reference] = element.qte_min;
            addLocaleStorage('qte_produit_panier_commande', qte_produit_panier_commande);
            if (element.identification == identification) {
                teste_unite = true;
                if (parseInt(element.quantite) < 2 && unite_texte) {
                    element.unite_texte += 's';
                }
                element.quantite = parseInt(element.quantite) + parseInt(quantite);
            }
        }
        if (!teste_unite) {
            qte_produit_panier_commande[reference] = tableau_panier_commande[reference][0].qte_min;
            let data = {
                count: texte,
                idmateriel: idmateriel,
                qte_min: tableau_panier_commande[reference][0].qte_min,
                quantite: parseInt(quantite, 10),
                reference: reference,
                reference_fournisseur: reference_fournisseur,
                designationmateriel: designationmateriel,
                montant: montant,
                prix: prix,
                unite_texte: unite_texte,
                identification: identification,
                id_unite: id_unite,
            }
            if (parseInt(quantite, 10) > 1 && unite_texte) {
                data.unite_texte += 's';
            }
            tableau_panier_commande[reference].push(data);
        }
    } else {
        qte_produit_panier_commande[reference] = parseInt(qte_min, 10);
        texte = `${reference}_0`;
        tableau_panier_commande[reference] = [];
        tableau_panier_commande[reference][0] = {
            count: texte,
            idmateriel: idmateriel,
            qte_min: parseInt(qte_min, 10),
            quantite: parseInt(quantite, 10),
            reference: reference,
            reference_fournisseur: reference_fournisseur,
            designationmateriel: designationmateriel,
            montant: montant,
            prix: prix,
            unite_texte: unite_texte,
            identification: identification,
            id_unite: id_unite,
        }
        if (parseInt(quantite, 10) > 1 && unite_texte) {
            tableau_panier_commande[reference][0].unite_texte += 's';
        }
    }
    addLocaleStorage('tableau_panier_commande', tableau_panier_commande);
}
// Affichage panier (CORRIGÉ : utilisation de element.quantite)
function append_tableau() {
    let content = '';
    $("#validerPanier").addClass("d-none");
    for (const reference in tableau_panier_commande) {
        const par_ref = tableau_panier_commande[reference];
        for (let i = 0; i < par_ref.length; i++) {
            $("#validerPanier").removeClass("d-none");
            const element = par_ref[i];
            content += `
                <tr id='${element.count}' data-idmateriel='${element.idmateriel}' data-reference='${reference}' data-designationmateriel='${element.designationmateriel}' data-prix='${element.prix}' data-quantite='${element.quantite}' data-id_unite='${element.id_unite}' data-unite_texte='${element.unite_texte}' data-montant='${element.montant}' data-remise='${element.remise || 0}' data-unite_identification='${element.identification}'>
                    <td>${element.reference}</td>
                    <td>${element.reference_fournisseur}</td>
                    <td>${element.designationmateriel}</td>
                    <td>${element.prix.toLocaleString("fr-FR")} Ar</td>
                    <td>${element.quantite.toLocaleString("fr-FR")} ${element.quantite > 1 ? (element.unite_texte + 's' || '') : (element.unite_texte || '')}</td>
                    <td>${element.montant.toLocaleString("fr-FR")} Ar</td>
                    <td>
                        <button class="btn btn-danger delete" data-id='${element.count}'><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>`;
        }
    }
    return content;
}
// Suppression article
$(document).on('click', '.delete', function () {
    const id = $(this).data('id');
    const tr = $(this).closest('tr');
    Myalert.delete();
    $(document).on('click', '#confirmeDelete', function () {
        $('#cancelDelete').click();
        let identification = id.split('_');
        const unite_identification = $(tr).data('unite_identification');
        const quantite = $(tr).data('quantite');
        const reference = $(tr).data('reference');
        if (tableau_panier_commande[identification[0]]) {
            tableau_panier_commande[identification[0]].splice(identification[1], 1);
            if (allunite_panier_commande[reference]) {
                let qte_deleted = qteMinUnit(allunite_panier_commande[reference], quantite, unite_identification);
                qte_produit_panier_commande[reference] = parseInt(qte_produit_panier_commande[reference]) - parseInt(qte_deleted);
                for (let i = 0; i < tableau_panier_commande[reference].length; i++) {
                    const element = tableau_panier_commande[reference][i];
                    element.qte_min = parseInt(element.qte_min) - parseInt(qte_deleted);
                }
            }
        }
        addLocaleStorage('tableau_panier_commande', tableau_panier_commande);
        addLocaleStorage('qte_produit_panier_commande', qte_produit_panier_commande);
        $('#tableau').html(append_tableau());
    })
});
// Ajouter au panier
$(document.body).on("click", "#valider", function () {
    const reference = $("#reference").val();
    const reference_fournisseur = $("#reference_fournisseur").val();
    const idmateriel = $("#idmateriel").val();
    const designationmateriel = $("#designationmateriel").val();
    const prix = parseInt($("#prix").val(), 10);
    const quantite = $("#quantite").val();
    const id_unite = $('#unite').val();
    const unite_selectione = $('#unite').find('option:selected');
    const unite_texte = $(unite_selectione).text();
    const montant = parseInt($("#montant").val(), 10);
    const identification = $(unite_selectione).data('id');
    if (reference != '' && idmateriel != '' && designationmateriel != '' && quantite > 0 && prix != '' && montant != '') {
        addToPanierTab(idmateriel, qteMinUnit(real_unite, quantite, identification), quantite, reference, reference_fournisseur, designationmateriel, montant, prix, unite_texte, identification, id_unite);
        $('#tableau').html(append_tableau());
        vider();
    }
});
// Modal validation panier (avec input autocomplétion projet)
function panier_modal_content(nom_fournisseur = '', idfournisseur = '') {
    let content = `<div class="_tableau mt-4"><table class="table"><thead class="table-info"><tr><th>Réference</th><th>Réference fournisseur</th><th>Désignation</th><th>Quantité</th><th>Montant total</th></tr></thead><tbody>`;
    let montant_total = 0;
    for (const reference in tableau_panier_commande) {
        $("#validerPanier").removeClass("d-none");
        const par_ref = tableau_panier_commande[reference];
        if (par_ref.length > 0) {
            let tab = {
                reference: par_ref[0].reference,
                reference_fournisseur: par_ref[0].reference_fournisseur,
                designationmateriel: par_ref[0].designationmateriel,
                quantite: '',
                montant: 0,
            };
            for (let j = 0; j < 4; j++) {
                for (let i = 0; i < par_ref.length; i++) {
                    const element = par_ref[i];
                    if (element.identification == j || element.identification == undefined) {
                        if (tab.quantite != '') tab.quantite += ' + ';
                        tab.quantite += `${element.quantite} ${element.quantite > 1 ? (element.unite_texte + 's' || '') : (element.unite_texte || '')}`;
                        tab.montant += parseInt(element.montant);
                    }
                }
            }
            content += `<tr><td>${tab.reference}</td><td>${tab.reference_fournisseur}</td><td>${tab.designationmateriel}</td><td>${tab.quantite}</td><td>${tab.montant.toLocaleString("fr-FR")} Ar</td></tr>`;
            montant_total += tab.montant;
        }
    }
    content += `</tbody></table></div>`;
    let entete = "";
    // Fournisseur
    if (idfournisseur == '' && nom_fournisseur == '') {
        entete += `
        <div class="mt-2">
            <label class="form-label">Fournisseur :</label><br>
            <button id='ajout' class="btn btn-sm btn-primary mb-2">Ajouter</button>
            <button id="recherche" class="btn btn-sm btn-info mb-2">Rechercher</button>
        </div>`;
    } else {
        entete += `
        <div class="mb-2">
            <label class="form-label">Fournisseur :</label>
            <div class="input-group mb-2">
                <input id="nom_fournisseur" type="text" class="form-control" readonly value='${nom_fournisseur}'>
                <button id='annuler_fournisseur' class="btn btn-danger">
                    <i class="fa-solid fa-x"></i>
                </button>
                <input id="idfournisseur" type="hidden" value='${idfournisseur}'>
            </div>
        </div>`;
    }
    // Projet + Titre + Budgets (UNE SEULE FOIS)
    entete += `
        <div class="mb-2">
            <label class="form-label">Projet :</label>
            <input type="text" class="form-control" id="projet_input" placeholder="Tapez le code ou nom du projet..." autocomplete="off" value="${current_projet_code || ''}">
            <input type="hidden" id="projet" name="projet" value="${current_projet_id || ''}">
        </div>
        <div class="mb-2">
            <label class="form-label">Titre du projet :</label>
            <input type="text" class="form-control" id="title_projet" placeholder="Titre du projet" autocomplete="off" readonly value="${current_projet_titre || ''}">
        </div>
        <!-- Bouton Budgets -->
        <div class="mb-3">
            <label class="form-label">Ligne(s) budgétaire(s) concernée(s) :</label><br>
            <button type="button" class="btn btn-info btn-sm" id="select_budget_commande" ${current_projet_id ? '' : 'disabled'}>
                <i class="fas fa-wallet"></i> Choisir budget(s)
            </button>
            <span id="budget_status" class="ms-3 text-muted">
                Aucun budget sélectionné
            </span>
        </div>
        <div id="budget_preview" class="small text-secondary mb-3"></div>
        <div class="mb-2">
            <label class="form-label">Montant total :</label>
            <input class="form-control" type="text" readonly value='${montant_total.toLocaleString("fr-FR")} Ar'>
            <input id='montant_total' class="d-none" type="text" value='${montant_total}'>
        </div>
        <div class="mb-2">
            <label class="form-label">Frais de livraison :</label>
            <input class="form-control" id='frais_livraison' type="number" min='0' value='${frais_livraison_}'>
        </div>
    `;
    entete += `<input id="id_pointdevente" type="text" class="form-control d-none" readonly value='${$("#pv_vente").val() || ''}'>`;
    return entete + content;
}
// Ouverture du modal validation
$(document).on('click', '#validerPanier', function () {
    current_nom_fournisseur = '';
    current_idfournisseur = '';
    current_projet_id = '';
    current_projet_code = '';
    current_projet_titre = '';
    $('#validation').html(panier_modal_content());
    $('.modal-footer').removeClass('d-none');
});
// Déblocage du bouton Budgets quand un projet est sélectionné
$(document).on('change', '#projet', function() {
    const idprojet = $(this).val();
    console.log("Changement projet → ID =", idprojet);
    if (idprojet && idprojet != "0") {
        $('#select_budget_commande').prop('disabled', false);
        console.log("Bouton Budgets → ACTIVÉ");
    } else {
        $('#select_budget_commande').prop('disabled', true);
        $('#budget_status').text('Aucun budget sélectionné');
        $('#budget_preview').empty();
        budgets_commande_selected = [];
        console.log("Bouton Budgets → désactivé");
    }
});
// Autocomplétion projet
$(document).on('focus', '#projet_input', function () {
    if ($(this).hasClass('ui-autocomplete-input')) {
        $(this).autocomplete("search", "");
        return;
    }
    $(this).autocomplete({
        source: function (request, response) {
            const term = request.term.trim().toLowerCase();
            if (!term) return response([]);
            const results = projets
                .filter(p =>
                    (p.codeprojet || '').toLowerCase().includes(term) ||
                    (p.nomprojet || '').toLowerCase().includes(term)
                )
                .map(p => {
                    let label = p.codeprojet || '';
                    if (p.nomprojet && p.nomprojet.trim() !== '') {
                        label += ` — ${p.nomprojet}`;
                    }
                    return {
                        label: label,
                        value: p.codeprojet || '',
                        id: p.idprojet,
                        titre: p.titreprojet || ''
                    };
                });
            response(results);
        },
        minLength: 1,
        select: function (event, ui) {
            $(this).val(ui.item.label);
            $('#projet').val(ui.item.id);
            $('#title_projet').val(ui.item.titre);
            
            // Mettre à jour les variables globales
            current_projet_id = ui.item.id;
            current_projet_code = ui.item.label;
            current_projet_titre = ui.item.titre;
            
            $('#projet').trigger('change');
            return false;
        }
    });
});
// Recherche projet par code (quand on tape manuellement)
$(document).on('input change', '#projet_input', function () {
    const codeprojet = $(this).val();
    if (codeprojet != '') {
        $.ajax({
            method: 'post',
            url: base_url('Projet/bycode_json'),
            data: { codeprojet: codeprojet },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const projet = response.data;
                $('#title_projet').val(projet.titreprojet);
                $('#projet').val(projet.idprojet);
                
                // Mettre à jour les variables globales
                current_projet_id = projet.idprojet;
                current_projet_code = codeprojet;
                current_projet_titre = projet.titreprojet;
                
                $('#projet').trigger('change');
            } else {
                Myalert.erreur('Ce code de projet n\'existe pas.');
                $('#projet').val('');
                $('#title_projet').val('');
                
                // Mettre à jour les variables globales
                current_projet_id = '';
                current_projet_code = '';
                current_projet_titre = '';
                
                $('#projet').trigger('change');
            }
        }).fail(function () {
            console.error('Erreur dans la vérification du projet');
        });
    } else {
        $('#title_projet').val('');
        $('#projet').val('');
        
        // Mettre à jour les variables globales
        current_projet_id = '';
        current_projet_code = '';
        current_projet_titre = '';
        
        $('#projet').trigger('change');
    }
});
// Gestion du bouton Budgets (ouverture et chargement dans même modal)
let budgets_commande_selected = [];
$(document).on('click', '#select_budget_commande', function() {
    const idprojet = $('#projet').val()?.trim();

    if (!idprojet || idprojet === "0") {
        Myalert.erreur("Veuillez sélectionner un projet valide.");
        return;
    }

    // Sauvegarder les valeurs actuelles
    current_nom_fournisseur = $('#nom_fournisseur').val() || '';
    current_idfournisseur = $('#idfournisseur').val() || '';
    current_projet_id = $('#projet').val() || '';
    current_projet_code = $('#projet_input').val() || '';
    current_projet_titre = $('#title_projet').val() || '';

    // Sauvegarde contenu original
    const contenuOriginal = $('#validation').html();

    $('#validation').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3">Chargement des lignes budgétaires...</p>
        </div>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-secondary btn-sm" id="retour_au_recap">
                <i class="fas fa-arrow-left"></i> Retour au récapitulatif
            </button>
        </div>
    `);

    $.ajax({
        url: base_url('Commande/getBudgetsByProjet_json'),
        type: 'POST',
        data: { idprojet: idprojet },
        dataType: 'json',
        success: function(resp) {
            if (resp.success && resp.budgets && resp.budgets.length > 0) {
                let html = `
        <h5 class="mb-3">Ligne budgétaire du projet</h5>
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th style="width:50px">Choisir</th>
                    <th>Ligne</th>
                    <th>Intitulé</th>
                    <th>Budget alloué</th>
                    <th>Reliquat</th>
                </tr>
            </thead>
            <tbody>
    `;
    resp.budgets.forEach(b => {
        const checked = (budgets_commande_selected.length > 0 && budgets_commande_selected[0].idbudget == b.idbudget) ? 'checked' : '';
        html += `
            <tr>
                <td class="text-center">
                    <input type="radio" name="budget_unique" class="form-check-input budget-ligne-radio"
                           data-idbudget="${b.idbudget}"
                           data-ligne="${b.ligne_budget}"
                           data-designation="${b.designation_budget}"
                           data-budget="${b.budget}"
                           data-reliquat="${b.reliquat || 0}"
                           ${checked}>
                </td>
                <td>${b.ligne_budget}</td>
                <td>${b.designation_budget}</td>
                <td>${Number(b.budget||0).toLocaleString('fr-FR')} Ar</td>
                <td>${Number(b.reliquat||0).toLocaleString('fr-FR')} Ar</td>
            </tr>
        `;
    });
    html += '</tbody></table>';
    html += `
        <div class="text-center mt-4">
            <button type="button" class="btn btn-secondary btn-sm me-2" id="retour_au_recap">Retour</button>
            <button type="button" class="btn btn-primary btn-sm" id="valider_selection_budget_unique">
                <i class="fas fa-check"></i> Valider le budget
            </button>
        </div>
    `;
    $('#validation').html(html);


                
            } else {
                $('#validation').html(`
                    <p class="text-center text-muted py-4">${resp.message || "Aucune ligne budgétaire disponible."}</p>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary btn-sm" id="retour_au_recap">Retour</button>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            $('#validation').html(`
                <p class="text-danger text-center py-4">
                    Erreur chargement budgets (code ${xhr.status})<br>
                    <small>${xhr.responseText.substring(0, 200) || 'Aucune réponse'}</small>
                </p>
                <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm" id="retour_au_recap">Retour</button>
                </div>
            `);
        }
    });
});

// Retour au récapitulatif
$(document).on('click', '#retour_au_recap', function() {
    // Recharger le panier depuis localStorage pour être sûr
    tableau_panier_commande = getLocaleStorage('tableau_panier_commande', {});
    
    // Utiliser les variables globales pour restaurer toutes les données
    $('#validation').html(panier_modal_content(
        current_nom_fournisseur,
        current_idfournisseur
    ));
    $('.modal-footer').removeClass('d-none');

    // Réactive le bouton Budgets si projet sélectionné
    if (current_projet_id && current_projet_id != "0") {
        $('#select_budget_commande').prop('disabled', false);
    }
});

// Valider la sélection budgets
$(document).on('click', '#valider_selection_budget_unique', function() {
    budget_commande_selected = null;
    
    const selectedRadio = $('.budget-ligne-radio:checked');
    if (selectedRadio.length === 1) {
        budget_commande_selected = {
            idbudget:     selectedRadio.data('idbudget'),
            ligne:        selectedRadio.data('ligne'),
            designation:  selectedRadio.data('designation'),
            budget:       Number(selectedRadio.data('budget')),
            reliquat:     Number(selectedRadio.data('reliquat'))
        };
    }

    // Retour au récap
    $('#validation').html(panier_modal_content(
        current_nom_fournisseur,
        current_idfournisseur
    ));

    if (!budget_commande_selected) {
        $('#budget_status').text('Aucun budget sélectionné');
        $('#budget_preview').empty();
    } else {
        $('#budget_status').text('1 ligne budgétaire sélectionnée');
        $('#budget_preview').html(
            `<small>• ${budget_commande_selected.ligne} - ${budget_commande_selected.designation.substring(0,55)}${budget_commande_selected.designation.length > 55 ? '...' : ''}</small>`
        );
    }
});
// CLIENT DANS LE PANIER (EXACTEMENT COMME DANS TON EXEMPLE)
function ajout_fournisseur_content() {
    let ajout = `
        <form id="registerFournisseur" method="post">
            <div class="mb-3">
                <label class="form-label">Nom de l'entreprise :</label>
                <input class="form-control" type="text" id="entreprise" name="entreprise" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse :</label>
                <input class="form-control" type="text" id="adresse" name="adresse" placeholder="Adresse" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tél :</label>
                <input class="form-control" type="text" placeholder="Numero" id="telephone" name="telephone" required>
                <p class="d-none text-danger" id="telephone_erreur">Ce téléphone existe déjà.</p>
            </div>
            <div class="mb-3">
                <label class="form-label">Email :</label>
                <input class="form-control" type="email" placeholder="exemple@gmail.com" id="email" name="email">
            </div>
            <div class="mb-3">
                <label class="form-label">NIF :</label>
                <input class="form-control" type="text" placeholder="Nif" name="nif" id="nif">
            </div>
            <div class="mb-3">
                <label class="form-label">STAT :</label>
                <input class="form-control" type="text" placeholder="stat" id="Stat" name="stat">
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-sm btn-info">Valider</button>
                <button type="button" class="btn btn-sm btn-secondary" id="annuler">Annuler l'ajout</button>
            </div>
        </form>`;
    
    return ajout;
}
function recherche_fournisseur_content(recherche = '') {
	let content = '';
	let recherche_tab = `
					<form>
						<div class="input-group mt-3 mb-1">
							<input name="recherche" id= 'client_search' type="text" class="form-control" placeholder="Recherche" value='${recherche}'>
							<a class="btn btn-info" id='recherche_client'>
								<i class="fa-solid fa-magnifying-glass"></i>
							</a>
						</div>
						<p class="text-secondary d-none mb-0"id="msg-search">Aucun résultat...</p>
						<p class="text-danger d-none mb-0"id="msg-search_vide">Veuillez selectionner un client</p>
						
					</form>
					<form>
					<table class="table table">

						<thead class="table-info">
							<tr >
								<th>choix</th>
								<th>Nom</th>
								<th>Adresse</th>
								<th>Tél</th>
								<th>Email</th>
							</tr>
						</thead>
		`;
	$.ajax({
		url: base_url('Commande/fournisseur'),
		type: "post",
		data: { recherche: recherche },
		dataType: 'json',
		async: false
	}).done(function (reponse) {
		const fournisseur = reponse.datas;

		console.log(fournisseur);

		recherche = recherche_tab;
		recherche += `	
					<tbody id= 'tableau_fournisseur'">
					`
		for (var i = 0; i < fournisseur.length; i++) {
			const element = fournisseur[i];
			recherche += `
						<tr >
							<td> 
								<input class='choix_fournisseur' type="radio" name='client' data-tel='${element.idfournisseur}' data-id='${element.idfournisseur}' data-nom = '${element.nom_entr}'>
							</td>
							<td>${element.nom_entr}</td>
	
							<td>${element.adresse_fournisseur}</td>
	
							<td>${element.idfournisseur}</td>`;

			if (element.mail_fournisseur != '') {
				recherche += `<td>${element.mail_fournisseur}</td></tr>
					
				`
			}
			else {
				recherche += `<td>--</td></tr>
				`
			}
		}
		recherche += `	
					</tbody>
					</table>
					</form>
					<button data-nom="" data-prenom="" data-tel="" class="btn btn-info btn-sm " id="search_valide">VALIDER</button>
					<button type="button" class="btn btn-sm btn-secondary" id="annuler">Annuler</button>`  ;
		content = recherche;
	});

	return content;
}

$(document).on('click', '#ajout', function () {
    frais_livraison_ = $('#frais_livraison').val();
    $('#validation').html(ajout_fournisseur_content());
    $('.modal-footer').addClass('d-none');
});

$(document).on('click', '#recherche', function () {
    frais_livraison_ = $('#frais_livraison').val();
    $('#validation').html(recherche_fournisseur_content());
    $('.modal-footer').addClass('d-none');
});

$(document).on('click', '#annuler , #annuler_fournisseur', function () {
    // Recharger le panier depuis localStorage
    tableau_panier_commande = getLocaleStorage('tableau_panier_commande', {});
    
    $('#validation').html(panier_modal_content());
    $('.modal-footer').removeClass('d-none');
});

$(document).on('click', '#recherche_client', function () {
    if ($('#client_search').val() != '') {
        $('#validation').html(recherche_fournisseur_content($('#client_search').val()));
    } else {
        $('#client_search').css({ 'border': '1px solid red' });
    }
});

// valider la recherche client 
$(document).on('change', '.choix_fournisseur', function () {
    $('#search_valide').attr('data-tel', $(this).data('tel'));
    $('#search_valide').attr('data-id', $(this).data('id'));
    $('#search_valide').attr('data-nom', $(this).data('nom'));
});

$(document).on('click', '#search_valide', function () {
    const telephone = $(this).data('tel');
    const idfournisseur = $(this).data('id');
    const nom = $(this).data('nom');
    let contenue = '';
    contenue = nom.toUpperCase();
    
    // Mettre à jour les variables globales
    current_nom_fournisseur = contenue;
    current_idfournisseur = idfournisseur;

    $('#validation').html(panier_modal_content(contenue, idfournisseur));
    $('.modal-footer').removeClass('d-none');
});

// Ajouter client 
$(document).on('click', '#client_type option', function () {
    let type = $(this).val();
    if (type == 1) {
        $('.is_public').addClass('d-none');
        $('.not_public').removeClass('d-none');
    } else {
        $('.not_public').addClass('d-none');
        $('.is_public').removeClass('d-none');
    }
});

$(document.body).on("submit", "#registerFournisseur", function (e) {
    e.preventDefault();

    let isok = true;

    let nom = $('#entreprise').val();
    let adresse = $('#adresse').val();
    let numero = $('#telephone').val();
    let email = $('#email').val();
    let nif = $('#nif').val();
    let stat = $('#Stat').val();

    if (adresse != '' && numero != '') {
        isok = true;
    } else {
        isok = false;
    }

    if (isok == true) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: base_url('Fournisseur/duplicate'),
            data: {
                telephone: numero,
            },
        }).done(function (data) {
            if (data.success) {
                data_fournisseur['nom'] = nom;
                data_fournisseur['adresse'] = adresse;
                data_fournisseur['numero'] = numero;
                data_fournisseur['email'] = email;
                data_fournisseur['nif'] = nif;
                data_fournisseur['stat'] = stat;

                // Mettre à jour les variables globales
                current_nom_fournisseur = nom.toUpperCase();
                current_idfournisseur = numero;

                $('#validation').html(panier_modal_content(nom.toUpperCase(), numero));
                $('.modal-footer').removeClass('d-none');
            } else {
                Myalert.delete('Ce client existe déjà.Voulez-vous utiliser l\'existant ?');

                $('#confirmeDelete').on('click', function () {
                    const info = data.data;
                    let fournisseur = info.nom_entr.toUpperCase();
                    let idfournisseur = info.idfournisseur;
                    
                    // Mettre à jour les variables globales
                    current_nom_fournisseur = fournisseur;
                    current_idfournisseur = idfournisseur;
                    
                    $('#validation').html(panier_modal_content(fournisseur, idfournisseur));
                    $('.modal-footer').removeClass('d-none');
                    $('#close').click();
                });
            }
        }).fail(function () {
            console.log('erreur sur l\'enregistrement du client !');
        });
    } else if (isok == false) {
        Myalert.erreur('Veuiller remplir tout les champs');
    }
});

$(document).on('click', '#tva_', function () {
    let status = $(this).data('status');
    if (status == 'off') {
        $('#tva_').data('status', 'on');
        $('#tmp_').prop('checked', false);
        $('#tmp_').data('status', 'off');
    } else {
        $('#tva_').data('status', 'off');
    }
});

$(document).on('click', '#tmp_', function () {
    let status = $(this).data('status');
    if (status == 'off') {
        $('#tmp_').data('status', 'on');
        $('#tva_').prop('checked', false);
        $('#tva_').data('status', 'off');
    } else {
        $('#tmp_').data('status', 'off');
    }
});

// EVOYER LE PANIER 
$(document.body).on("click", "#sendvalidation", function () {
    // eregistrer le client 
    let idfournisseur = $("#idfournisseur").val();
    const montant_total = $('#montant_total').val();
    const frais_livraison = $('#frais_livraison').val();
    const projet = $('#projet').val();
    if($("#projet").val()=="") {
    		Myalert.erreur('Veuillez de choisir un projet.');
    	}
    else {
    	if (data_fournisseur.numero != undefined) {
        $.ajax({
            method: 'post',
            dataType: 'json',
            url: base_url('Fournisseur/register'),
            async: false,
            data: {
                entreprise: data_fournisseur.nom,
                adresse: data_fournisseur.adresse,
                email: data_fournisseur.email,
                telephone: data_fournisseur.numero,
                nif: data_fournisseur.nif,
                stat: data_fournisseur.stat,
                page: 'appro',
            },
        }).done(function (response) {
            idfournisseur = response.id;
        });
    }
    
    if (idfournisseur) {
        Myalert.spinnerB();
    }

    let tva = '';
    let tmp = '';
    
    if ($('#tva_').data('status') == 'on') {
        tva = true;
    } else {
        tva = false;
    }
    
    if ($('#tmp_').data('status') == 'on') {
        tmp = true;
    } else {
        tmp = false;
    }
    
    	if (idfournisseur) {
    	
        $.ajax({
            url: base_url("Commande/facturation"),
            type: "post",
            data: {
                idfournisseur: idfournisseur,
                tva: tva,
                tmp: tmp,
                projet: projet,
                montant_total: montant_total,
                frais: frais_livraison,
                idbudget: budget_commande_selected ? budget_commande_selected.idbudget : null   // ← clé importante
            },
            dataType: "json",
        }).done(function (response) {
            const data = response;

            let numFacture = "";
            let idcmfacture = "";

            if (data.success) {
                numFacture = response.facture;
                idcommande = response.idfacture;

                let data = [];
                for (const reference in tableau_panier_commande) {
                    const par_ref = tableau_panier_commande[reference];
                    for (let i = 0; i < par_ref.length; i++) {
                        const element = par_ref[i];
                        let donner = {
                            'idcommande': idcommande,
                            'idmateriel': element.idmateriel,
                            'reference_fournisseur': element.reference_fournisseur,
                            'prixunitaire': element.prix,
                            'quantite': element.quantite,
                            'idunite': element.id_unite,
                            'min_qte': element.qte_min,
                        };
                        data.push(donner);
                    }
                }

                // reinitialiser le localstorage
                addLocaleStorage('pointventecontent', null);
                addLocaleStorage('tableau_panier_commande', {});
                addLocaleStorage('allunite_panier_commande', {});
                addLocaleStorage('qte_produit_panier_commande', {});

                // envoyer les données 
                $.ajax({
                    url: base_url("Commande/validate"),
                    type: "post",
                    data: { data: data },
                }).done(function (data) {
                    $('#pdfFrame').attr('src', base_url('Commande/facture/' + numFacture));
                    tableau_panier_commande = {};
                    $('#affichefacture').click();
                    $('#tableau').html(append_tableau());
                    Myalert.removeSpinnerB();

                    setTimeout(() => {
                        // loader 
                        $('#loaderFacture').addClass('d-none');
                        $('#pdfFrame').removeClass('d-none');
                    }, 5000);
                }).fail(function () {
                    console.error('Erreur sur la validation de la vente !!');
                    window.location.reload();
                });
            } else {
                Myalert.erreur('Veuillez choisir le fournisseur.');
            }
        });
    } else {
        Myalert.erreur('Veuillez de choisir un fournisseur.');
    }	
    }
    
    

});

$('#modalfacture').on('hidden.bs.modal', function () {
    // 1. Vider le panier affiché
    $('#tableau').empty();
    $("#validerPanier").addClass("d-none");

    // 2. Réinitialiser les variables du panier
    tableau_panier_commande = {};
    qte_produit_panier_commande = {};
    allunite_panier_commande = {};

    // 3. Nettoyer le localStorage
    localStorage.removeItem('tableau_panier_commande');
    localStorage.removeItem('qte_produit_panier_commande');
    localStorage.removeItem('allunite_panier_commande');

    // 4. Vider le formulaire d'ajout produit
    vider();

    // 5. VIDER LE CONTENU DE LA MODAL VALIDATION (c'est la clé !)
    $('#validation').empty();                    // supprime le récapitulatif panier/fournisseur/projet
    $('#inclusion_container').addClass('d-none'); // cache les checkboxes TMP/TVA si tu veux
    $('.modal-footer #sendvalidation').addClass('d-none'); // cache le bouton Valider

    // 6. FERMER LA MODAL DE VALIDATION (la plus importante)
    $('#myModal').modal('hide');

    // 7. Message de succès visible sur la page principale
    Myalert.added("Commande enregistrée avec succès ! Prêt pour une nouvelle commande.");

    // Optionnel : réafficher le bouton "valider la commande" si panier vide
    $("#validerPanier").addClass("d-none");
});

// Style CSS pour fixer la modal et gérer l'autocomplétion
$(document).ready(function() {
    // Ajouter le CSS nécessaire
    $('head').append(`
        <style>
            /* Fixer la modal - pas de défilement de la page entière */
            body.modal-open {
                overflow: hidden;
                padding-right: 0 !important;
            }
            
            .modal.show .modal-dialog {
                max-height: 90vh;
            }
            
            .modal.show .modal-content {
                max-height: 90vh;
                display: flex;
                flex-direction: column;
            }
            
            .modal.show .modal-body {
                overflow-y: auto;
                max-height: calc(90vh - 150px);
                flex: 1;
            }
            
            /* Style pour l'autocomplétion */
            .ui-autocomplete {
                z-index: 999999 !important;
                max-height: 200px;
                overflow-y: auto !important;
                overflow-x: hidden;
                font-size: 14px;
                position: fixed !important;
            }
            
            .ui-menu {
                max-height: 200px !important;
                overflow-y: auto !important;
                overflow-x: hidden;
            }
            
            .ui-menu-item {
                font-size: 14px;
                padding: 5px 10px;
            }
        </style>
    `);
});

// Désactiver le défilement de la page quand la modal est ouverte
$(document).on('show.bs.modal', '.modal', function () {
    $('body').addClass('modal-open');
});

$(document).on('hidden.bs.modal', '.modal', function () {
    $('body').removeClass('modal-open');
});