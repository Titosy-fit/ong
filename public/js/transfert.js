// Nouveaux js ************************* 
let quantite_dispo_tab = [];
let real_unite = [];
let data_client = [];
// qte de chaque materiel dans le panier
let qte_produit_panier = {};

// pour stocke toutes les tableaux d'unite deja dans le panier 
let allunite_panier = {};

// quantite par materiel dans le panier pour gerer la quantite reste disponible 
var tableau_panier = {};
// changement d'unité


function vider() {
    $('#reference').val('');
    $('#designationmateriel').val('');
    $('#unite').html('');
    $('#qte_dipo').val('');
    $('#quantite').val('');
}

$(document).on('change', '#reference', function () {
    const reference = $(this).val();
    const id_pv = $("#pv_vente").val();
    if (reference != '') {
        $.ajax({
            method: 'post',
            url: base_url('Appro/recherche_produit'),
            data: { ref: reference },
            dataType: 'json'
        }).done(function (rechercher_appro) {
            if (rechercher_appro.success) {
                $.ajax({
                    method: 'post',
                    url: base_url('Dispatch/getStock_json'),
                    data: { idmateriel: rechercher_appro.materiel.idmateriel, id_pv: id_pv },
                    dataType: 'json',
                }).done(function (response) {

                    const materiel = rechercher_appro.materiel;
                    const unites_ = rechercher_appro.unites;
                    const quantite = response.quantite;

                    if (quantite > 0) {
                        let prix = 0

                        // materiel 
                        $('#reference').val(materiel.refmateriel);
                        $('#idmateriel').val(materiel.idmateriel);
                        $('#designationmateriel').val(materiel.designationmateriel);

                        // unite 
                        let content = '';
                        for (let i = 0; i < unites_.length; i++) {
                            const element = unites_[i];
                            if (element.denomination) {
                                content += `
										<option class='unites' value="${element.idunite}" data-id='${i}' data-prix='${element.prixProduit}'>${element.denomination}</option>
									`
                                $('#unite_container').removeClass('d-none');
                            }
                            else {
                                $('#unite_container').addClass('d-none');
                            }
                            // Pour afficher la premier prix
                            if (i == 0) {
                                prix = element.prixProduit;
                            }
                        }

                        real_unite = unites_;

                        console.log( unites_ );
                        


                        const the_qte = covertion(unites_, quantite);
                        quantite_dispo_tab = covertion(unites_, quantite);
                        let qte_dispo_val = '';
                        if (covertion(unites_, quantite).length > 0) {
                            qte_dispo_val = stock_texte(the_qte, 0);
                        }

                        console.log( the_qte );
                        


                        $('#qte_dipo').val(qte_dispo_val);
                        $('#quantite').focus();

                        $('#unite').html(content);
                    } else {
                        Myalert.erreur('Vous n\'avez pas encore approvisionné ce materiel dans ce point de vente.');
                        vider();
                    }
                }).fail(function (err) {
                    console.error('prix : '.err);
                })
            } else {
                Myalert.erreur('Cette référence n\'existe pas.');
                vider();
            }

        }).fail(function () {
            console.error('Erreur dans la recuperation du materiel');
        })
    }
    else {
        // vider
        vider();
    }
})

// AJOUT AU PANIER 
$(document.body).on("click", "#valider", function () {
    const id_pv = $("#pv_vente").val();
    const id_pv_destination = $("#pv_vente_destination").val();
    
    if (id_pv != id_pv_destination) {
        const reference = $("#reference").val();
        const idmateriel = $("#idmateriel").val();
        const quantite = $("#quantite").val();
        const unite_selectione = $('#unite').find('option:selected');

        // vérification du quantité 
        const identification = $(unite_selectione).data('id');
        
        if (quantite_dispo_tab.length > 0) {
            const qte_dispo = quantite_dispo_tab[quantite_dispo_tab.length - 1].quantite;
            let total_qte = parseInt(qteMinUnit(real_unite, quantite, identification));

            if (reference != '' && idmateriel != '' && quantite > 0) {
                if (qte_dispo >= total_qte) {
                    $('#qte_min').val(total_qte);
                    shwoSpinner(this, ['reference', 'idmateriel', 'quantite']);
                    $('#real_validation').click();
                }
                else {
                    // stock insufisant
                    Myalert.erreur('Le stock est insuffisant.');
                }
            } else {
                Myalert.erreur('Veuillez remplir tous les champs obligatoires.');
            }
        } else {
            Myalert.erreur('Veuillez d\'abord sélectionner un article valide avec du stock.');
        }
    } else {
        // point de vente identique 
        Myalert.erreur('Le dépôt source et le dépôt de destination sont identiques.');
    }
});
// AJOUT AU PANIER *



$(document).on('click', '.delete', function () {
    let idtransfert = $(this).data('idtransfert');
    let elem = $(this);
    Myalert.delete("Êtes-vous sûr de vouloir supprimer ?", function () {
        $.ajax({
            method: 'post',
            url: base_url('Transfert/delete'),
            dataType: 'json',
            data: { idtransfert: idtransfert }
        }).done(function (response) {
            if (response.success) {
                $(elem).closest('tr').remove();
                Myalert.deleted();
            }
            else {
                Myalert.erreur('Une erreur c\'est produite');
            }
        }).fail(function (err) {
            console.error('erreur dans la suppression :' + err);
        })
    });
})


$(document).on('click', '.recevoir', function () {
    const idtransfert = $(this).data('idtransfert');
    const elem = $(this);

    Myalert.delete('Êtes-vous sûr de vouloir recevoir ce transfert ?', function () {
        $.ajax({
            method: 'post',
            url: base_url('Transfert/receive'),
            dataType: 'json',
            data: { idtransfert: idtransfert }
        }).done(function (response) {
            if (response.success) {
                $('#' + idtransfert + '_td').html(`
                    reçu
                    <i class="fas fa-check"></i>
                                                `);
                $('#' + idtransfert + '_action').html(`<button class="btn btn-danger mr-2" type="button" disabled>
                                                            <i class="fa-solid fa-trash"></i>
                                                            
                                                        </button>
                                                        <button type="button" class="btn btn-primary recevoir" disabled>
                                                                <i class="fas fa-hand-holding-medical"></i>
                                                        </button>`);

                Myalert.added('Réception effectuée.')
            } else {
                Myalert.erreur('Une erreur s\'est produite.')
            }
        }).fail(function () {

        })
    });
})

// Nouveaux js ************************* 

