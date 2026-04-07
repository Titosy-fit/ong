
let all_unite = {};
let tableau = [];
let idcommande = 0;
let montant_total = 0;
let frais = 0;

// Chargement des projets pour l'autocomplétion
let projets = [];
$.ajax({
    type: "POST",
    url: base_url('Projet/projet_json'),
    dataType: "json",
    success: function (response) {
        if (response.success && Array.isArray(response.data) && response.data.length > 0) {
            projets = response.data;
        }
    },
    error: function () {
        console.error("Erreur chargement liste projets");
    }
});

$(document).on('focus', '.qte_recue', function () {
    const idunite = $(this).closest('tr').data('idunite');
    const idmateriel = $(this).closest('tr').data('idproduit');
    const idcmfacture = $(this).closest('tr').data('idcmfacture');

    $('#valider').attr('disabled', '');
    $('#valider #spinner_validation').removeClass('d-none');
    $('#valider .fa-check').addClass('d-none');
    let identifiant = 0;
    let unites = [];

    if (idunite != '') {
        $.ajax({
            method: 'post',
            url: base_url('Reception/getUnite'),
            data: { idmateriel: idmateriel },
            dataType: 'json',
            async: false
        }).done(function (response) {
            unites = response.datas
            if (response.success) {

                for (let i = 0; i < unites.length; i++) {
                    const element = unites[i];
                    if (element.idunite == idunite) {
                        identifiant = i;
                    }
                }

            }
        }).fail(function (err) {
            console.error('erreur lors de la recherche d\'unite ');
        })
    }
    all_unite[idcmfacture] = {
        id: identifiant,
        unites: unites
    };

    $('#valider').removeAttr('disabled');
    $('#valider #spinner_validation').addClass('d-none');
    $('#valider .fa-check').removeClass('d-none');
})

$(document).on('keyup , change', '.qte_recue', function () {
    const idcmfacture = $(this).closest('tr').data('idcmfacture');
    const prix = $(this).closest('tr').data('prix');

    $('#valider').attr('disabled', '');
    $('#valider #spinner_validation').removeClass('d-none');
    $('#valider .fa-check').addClass('d-none');

    const tr = $(this).closest('tr');
    let new_qte = $(this).val();

    if (new_qte == '') {
        new_qte = 0;
        $(this).val(0);
    }

    let new_montant = parseInt(new_qte) * parseInt(prix);

    let content = `${new_montant.toLocaleString("fr-FR")} Ar`;

    $('#' + idcmfacture + '_montant').text(content);

    const min_qte = qteMinUnit(all_unite[idcmfacture].unites, new_qte, all_unite[idcmfacture].identifiant);

    $(tr).attr('data-min_qte', min_qte);
    $(tr).attr('data-quantite', new_qte);
    $(tr).attr('data-montant', new_montant);

    setTimeout(function () {
        $('#valider').removeAttr('disabled');
        $('#valider #spinner_validation').addClass('d-none');
        $('#valider .fa-check').removeClass('d-none');
    }, 100)
})

$(document).on('click', '#valider', function () {
    let table = $('#tableau tr');

    if (table.length > 0) {
        $('#panier_reception').click();
    }
})

function reception_panier_content() {
    montant_total = 0;
    tableau = []; // Réinitialiser le tableau à chaque appel

    let tr = $('#tableau tr');
    let idfournisseur = $("#idfournisseur").val();

    let pv = $('#point_vente').html();
    pv = pv.toLocaleString()

    let content = `
    <div class="_tableau mt-4">
            <table class="table">
                <thead class="table-info">
                    <tr>
                        <th>Réference</th>
                        <th>Désignation</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>`;

    for (let i = 0; i < tr.length; i++) {
        const ligne = tr[i];

        if (ligne.getAttribute('data-quantite') > 0) {
            let data = {
                'idcmfacture': ligne.getAttribute('data-idcmfacture'),
                'idmateriel': ligne.getAttribute('data-idproduit'),
                'idunite': ligne.getAttribute('data-idunite'),
                'quantite': ligne.getAttribute('data-quantite'),
                'min_qte': ligne.getAttribute('data-min_qte'),
                'prix_unitaire': ligne.getAttribute('data-prix'),
                'idprojet': ligne.getAttribute('data-idprojet'),
                'idfournisseur': idfournisseur,
            }

            idcommande = ligne.getAttribute('data-idcommande');

            tableau.push(data);

            let montant = parseInt(ligne.getAttribute('data-prix')) * parseInt(ligne.getAttribute('data-quantite'));
            montant_total += montant;

            frais = parseInt(ligne.getAttribute('data-frais'));

            content += `<tr>
                <td>${ligne.getAttribute('data-reference')}</td>
                <td>${ligne.getAttribute('data-designationmateriel')}</td>
                <td>${parseInt(ligne.getAttribute('data-prix')).toLocaleString("fr-FR")} Ar</td>
                <td>${ligne.getAttribute('data-quantite')}</td>
                <td>${(ligne.getAttribute('data-unite') != '') ? ligne.getAttribute('data-unite') : '--'}</td>
                <td>${montant.toLocaleString("fr-FR")} Ar</td>
                </tr>
            `;
        }
    }

    content += `
            </tbody>
        </table>
    </div>
    `

    let entete = `
    <div class="mb-3">
        <label class="form-label">Montant total :</label>
        <input type="text" class="form-control" value='${montant_total.toLocaleString("fr-FR")} Ar' readonly >
    </div>
    <div class="mb-3">
        <label class="form-label">Frais de livraison :</label>
        <input type="text" class="form-control" value='${frais.toLocaleString("fr-FR")} Ar' readonly >
    </div>
    <div class="mb-3">
        <label class="form-label">Projet :</label>
        <input type="text" class="form-control" id="projet_input" placeholder="" autocomplete="off" readonly>
        <input type="hidden" id="projet" name="projet" value="${$('#tableau tr:first').data('idprojet') || ''}">
    </div>
    <div class="mb-3">
        <label class="form-label">Dépôt :</label>
        <select id="idpv" class="form-select">
                ${pv}
        </select>
    </div>
    `;
    content = entete + content;

    return content;
}

$(document).on('click', '#panier_reception', function () {
    let content = reception_panier_content();
    $('#validation').html(content);
    
    // Initialiser l'autocomplétion du projet
    setTimeout(function() {
        initProjetAutocomplete();
    }, 100);
})

// Fonction pour initialiser l'autocomplétion du projet (EXACTEMENT comme dans la commande)
function initProjetAutocomplete() {
    // Détruire l'autocomplétion existante si elle existe
    if ($('#projet_input').hasClass('ui-autocomplete-input')) {
        $('#projet_input').autocomplete('destroy');
    }
    
    $('#projet_input').autocomplete({
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
                        id: p.idprojet
                    };
                });

            response(results);
        },
        minLength: 1,
        select: function (event, ui) {
            $(this).val(ui.item.label);
            $('#projet').val(ui.item.id);
            return false;
        }
    });
    
    // Pré-remplir si déjà un projet dans la commande
    const idprojet = $('#projet').val();
    if (idprojet) {
        const projet = projets.find(p => p.idprojet == idprojet);
        if (projet) {
            let label = projet.codeprojet || '';
            if (projet.nomprojet && projet.nomprojet.trim() !== '') {
                label += ` — ${projet.nomprojet}`;
            }
            $('#projet_input').val(label);
        }
    }
}

$(document).on('click', '#to_validate', function () {
    let idPointVente = $("#idpv").val();
    let projet = $("#projet").val();

    if (idPointVente > 0) {
        $('#to_validate #spinner_to_validate').removeClass('d-none');
        $('#to_validate .fa-check').addClass('d-none');
        
        // Mettre à jour le projet dans toutes les lignes
        if (projet) {
            for (let i = 0; i < tableau.length; i++) {
                tableau[i].idprojet = projet;
            }
        }
        
        $.ajax({
            method: 'post',
            url: base_url('Appro/cmregister'),
            data: { 
                datas: tableau, 
                idPointVente: idPointVente, 
                idcommande: idcommande, 
                montant_total: montant_total, 
                frais: frais,
                projet: projet 
            },
        }).done(function (response) {
            location.href = base_url('Appro');
        }).fail(function (err) {
            console.error(err);
            $('#to_validate #spinner_to_validate').addClass('d-none');
            $('#to_validate .fa-check').removeClass('d-none');
        })
    } else {
        Myalert.erreur("Veuillez choisir un dépôt.");
    }
})

// Fonction utilitaire pour la conversion d'unités
function qteMinUnit(allUnit = [], qte = 0, unite_identification = 0) {
    while (allUnit[unite_identification + 1]) {
        qte *= allUnit[unite_identification + 1].formule;
        unite_identification++;
    }
    return qte;
}

// Style CSS pour l'autocomplétion (EXACTEMENT comme dans la commande)
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
            
            /* Style pour l'autocomplétion - EXACTEMENT comme dans la commande */
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

// Désactiver le défilement de la page quand la modal est ouverte (EXACTEMENT comme dans la commande)
$(document).on('show.bs.modal', '.modal', function () {
    $('body').addClass('modal-open');
});

$(document).on('hidden.bs.modal', '.modal', function () {
    $('body').removeClass('modal-open');
});

