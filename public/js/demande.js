
let mode = [];
let the_mode = 0;
let frais = 0;

// let content_projet = 'En attente des projets...';
// $.ajax({
//     type: "post",
//     url: base_url('Projet/projet_json'),
//     dataType: "json",
//     success: function (response) {
//         let content = `Aucun projet trouvé.`
//         if (response.success) {
//             content = `<select name="projet" id="projet" class="form-select">`;
//             content += `<option value="">Aucun</option>`
//             for (let i = 0; i < response.data.length; i++) {
//                 const element = response.data[i];
//                 content += `<option value="${element.idprojet}">${element.codeprojet}</option>`
//             }
//             content += `</select>`
//         }

//         content_projet = content;
//     }
// });

$(document).on('click', ' .hidde_modale_stock', function (e) {
    $('#alertModal').remove();
})
$(document).on('click', '#alertModal', function (event) {
    $('#alertModal').remove();
})
$(document).on('click', '#alertModal .modal-content', function (event) {
    event.stopPropagation();
})
// alert modal

let quantite_dispo = 0;
let type_produit = '';

let quantite_dispo_tab = [];
let real_unite = [];
let data_user = [];
// qte de chaque materiel dans le panier
let qte_produit_panier_demande = {};

// pour stocke toutes les tableaux d'unite deja dans le panier 
let allunite_panier_demande = {};

// quantite par materiel dans le panier pour gerer la quantite reste disponible 
var tableau_panier_demande = {};
// changement d'unité


function addLocaleStorage(nom = 'panier', tableau) {
    let tableauJSON = JSON.stringify(tableau);
    localStorage.setItem(nom, tableauJSON);
}
function getLocaleStorage(nom = '', init) {
    let tableau = localStorage.getItem(nom);
    if (JSON.parse(tableau) != null) {
        tableau = JSON.parse(tableau);
    }
    else {
        tableau = init;
    }
    return tableau;
}

// reprendre les donner dans le localstorage 
qte_produit_panier_demande = getLocaleStorage('qte_produit_panier_demande', {});
allunite_panier_demande = getLocaleStorage('allunite_panier_demande', {});
tableau_panier_demande = getLocaleStorage('tableau_panier_demande', {});
// affichage des donne dans la localstorage 
const content = append_tableau();
$('#tableau').html(content);

/**
 * Vider toute les champs 
 */
function vider() {
    $('#reference').val('');
    $('#designationmateriel').val('');
    $('#qte_dipo').val('');
    $('#quantite').val('');
    $('#fiche').val('');
    $('#couleur').val('');
    $('#imei1').val('');
    $('#imei2').val('');

    quantite_dispo_tab = [];
    real_unite = []; $('.with_qte').addClass('d-none');
}

/**
 * change du reference
 */
$(document).on('change', '#reference', function () {
    const reference = $(this).val();
    const id_pv = $("#pv_vente").val();
    vider();
    if (reference != '') {
        $.ajax({
            method: 'post',
            url: base_url('Appro/recherche_produit'),
            data: {
                ref: reference,
                id_pv: id_pv,
                type_mat: 'bureau'
            },
            dataType: 'json'
        }).done(function (recherche_produit) {
            if (recherche_produit.success) {
                $.ajax({
                    method: 'post',
                    url: base_url('Dispatch/getStock_json'),
                    data: { idmateriel: recherche_produit.materiel.idmateriel, id_pv: id_pv },
                    dataType: 'json',
                }).done(function (response) {
                    if (response.success) {
                        const materiel = recherche_produit.materiel;
                        const unite_bymats = recherche_produit.unites;
                        const quantite = response.quantite;

                        if (quantite > 0) {
                            let prix = 0
                            // materiel 
                            $('#reference').val(materiel.refmateriel);
                            $('#idmateriel').val(materiel.idmateriel);
                            $('#designationmateriel').val(materiel.designationmateriel);
                            $('#designationmateriel').val(materiel.designationmateriel);
                            $('#fiche').val(materiel.fiche);

                            // unite 
                            let content = '';

                            $('#unite_container').addClass('d-none');

                            for (let i = 0; i < unite_bymats.length; i++) {
                                const element = unite_bymats[i];
                                content += `
										<option class='unites' value="${element.idunite}" data-id='${i}' data-prix='${element.prixProduit}'>${element.denomination}</option>
									`
                                $('#unite_container').removeClass('d-none');

                            }

                            real_unite = unite_bymats;
                            allunite_panier_demande[materiel.refmateriel] = unite_bymats;
                            // localstorage 
                            addLocaleStorage('allunite_panier_demande', allunite_panier_demande);


                            // quantiter
                            // pour avoir les quantite avec tous les unités
                            let in_panier = 0;
                            if (qte_produit_panier_demande[materiel.refmateriel]) {
                                in_panier = qte_produit_panier_demande[materiel.refmateriel];
                            }
                            const the_qte = covertion(unite_bymats, quantite - in_panier);
                            quantite_dispo_tab = covertion(unite_bymats, quantite);
                            let qte_dispo_val = '';
                            if (covertion(unite_bymats, quantite).length > 0) {
                                qte_dispo_val = stock_texte(the_qte, 0);
                            }


                            $('#qte_dipo').val(qte_dispo_val);
                            $('#quantite').focus();

                            $('#unite').html(content);
                        } else {
                            Myalert.erreur('Vous n\'avez pas encore approvisionné ce materiel dans ce point de vente.');
                            vider();
                        }
                    } else {
                        Myalert.erreur();
                    }
                }).fail(function (err) {
                    console.error('prix : '.err);
                })

            } else {
                if (recherche_produit.type_err == 'type') {
                    Myalert.erreur('Ce type de matériel ne peut pas être emprunté.')
                } else {
                    Myalert.erreur('Cette référence  n\'existe pas.')
                }
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

$(document).on('change', '#pv_vente', function () {
    vider();
})

/**
 * ajoute dans le tableau tableau_panier_demande 
 * @param {*} idmateriel 
 * @param {*} qte_min 
 * @param {*} quantite 
 * @param {*} reference 
 * @param {*} designationmateriel 
 * @param {*} description 
 * @param {*} unite_texte 
 * @param {*} identification 
 * @param {*} id_unite 
 */
function addToPanierTab(idmateriel, qte_min, quantite, reference, designationmateriel, description, unite_texte, identification, id_unite) {
    let texte = '';
    if (tableau_panier_demande[reference] && tableau_panier_demande[reference].length != 0) {
        texte = `${reference}_${tableau_panier_demande[reference].length}`;
        // teste si l'utite est deja dans le panier ( si oui , on additionne )
        let teste_unite = false;
        // deja dans le panier 
        for (let i = 0; i < tableau_panier_demande[reference].length; i++) {
            const element = tableau_panier_demande[reference][i];
            element.qte_min = parseInt(element.qte_min) + parseInt(qte_min);

            qte_produit_panier_demande[reference] = element.qte_min;

            if (element.identification == identification) {
                // on additionne
                teste_unite = true;
                if (parseInt(element.quantite) < 2 && unite_texte) {
                    element.unite_texte += 's';
                }
                element.quantite = parseInt(element.quantite) + parseInt(quantite);
            }
        }
        if (!teste_unite) {
            qte_produit_panier_demande[reference] = tableau_panier_demande[reference][0].qte_min;
            let data = {
                count: texte,
                idmateriel: idmateriel,
                qte_min: tableau_panier_demande[reference][0].qte_min,
                quantite: parseInt(quantite, 10),
                reference: reference,
                designationmateriel: designationmateriel,
                description: description,
                unite_texte: unite_texte,
                identification: identification,
                id_unite: id_unite,
            }
            if (parseInt(quantite, 10) > 1 && unite_texte) {
                data.unite_texte += 's';
            }
            tableau_panier_demande[reference].push(data);
        }

    }
    else {
        // pas encore dans le panier
        qte_produit_panier_demande[reference] = parseInt(qte_min, 10);
        texte = `${reference}_0`;
        tableau_panier_demande[reference] = [];
        tableau_panier_demande[reference][0] = {
            count: texte,
            idmateriel: idmateriel,
            qte_min: parseInt(qte_min, 10),
            quantite: parseInt(quantite, 10),
            reference: reference,
            designationmateriel: designationmateriel,
            description: description,
            unite_texte: unite_texte,
            identification: identification,
            id_unite: id_unite,
        }

        if (parseInt(quantite, 10) > 1 && unite_texte) {
            tableau_panier_demande[reference][0].unite_texte += 's';
        }
    }
    // localstorage 
    addLocaleStorage('tableau_panier_demande', tableau_panier_demande);
    // localsotorage 
    addLocaleStorage('qte_produit_panier_demande', qte_produit_panier_demande);

    console.log(qte_produit_panier_demande);

}


/**
 * affichage dans le tableau de panier 
 * @returns 
 */
function append_tableau() {

    let point_vente_content = getLocaleStorage('pointventecontent', null);
    if (point_vente_content != null) {
        $('#pv_vente').html(point_vente_content);
    }
    let projet_content = getLocaleStorage('projet_content', null);
    if (projet_content != null) {
        $('#projet').html(projet_content);
    }
    let content = '';
    $("#validerPanier").addClass("d-none");

    for (const reference in tableau_panier_demande) {
        const par_ref = tableau_panier_demande[reference];
        for (let i = 0; i < par_ref.length; i++) {
            $("#validerPanier").removeClass("d-none");
            const element = par_ref[i];

            content += `
				<tr id='${element.count}' data-idmateriel='${element.idmateriel}' data-reference='${reference}' data-designationmateriel='${element.designationmateriel}'  data-quantite='${element.quantite}' data-id_unite='${element.id_unite}' data-unite_texte='${element.unite_texte}'  data-unite_identification='${element.identification}'>
						<td>${element.reference}</td>
						<td>${element.designationmateriel}</td>
						<td>${element.description}</td>
						`;
            if (quantite > 1) {
                content += `
							<td>${element.quantite.toLocaleString("fr-FR")} ${element.unite_texte}s </td>
							`
            } else {
                content += `
							<td>${element.quantite.toLocaleString("fr-FR")} ${element.unite_texte} </td>
							`
            }
            content += `
						<td>
								<button  class="btn btn-danger delete" data-id='${element.count}'><i class="fa-solid fa-trash"></i></button>
						</td>
				</tr>
			`;
        }
    }
    return content;

}

/**
 * suppression dans le panier
 */
$(document).on('click', '.delete', function () {
    Myalert.delete();
    const id = $(this).data('id');
    const tr = $(this).closest('tr');
    $(document).on('click', '#confirmeDelete', function () {
        $('#cancelDelete').click();

        let identification = id.split('_');
        const unite_identification = $(tr).data('unite_identification');
        const quantite = $(tr).data('quantite');
        const reference = $(tr).data('reference');

        // teste si il est bien dans le panier 
        if (tableau_panier_demande[identification[0]]) {
            tableau_panier_demande[identification[0]].splice(identification[1], 1);
            // pour l'affichage du qte disponible 



            if (allunite_panier_demande[reference]) {
                let qte_deleted = qteMinUnit(allunite_panier_demande[reference], quantite, unite_identification)

                console.log(tableau_panier_demande[reference]);

                // elevé les quatite effacer 
                qte_produit_panier_demande[reference] = parseInt(qte_produit_panier_demande[reference]) - parseInt(qte_deleted);

                for (let i = 0; i < tableau_panier_demande[reference].length; i++) {
                    const element = tableau_panier_demande[reference][i];
                    element.qte_min = parseInt(element.qte_min) - parseInt(qte_deleted)
                }

            }
        }
        addLocaleStorage('tableau_panier_demande', tableau_panier_demande);
        addLocaleStorage('qte_produit_panier_demande', qte_produit_panier_demande);
        $('#tableau').html(append_tableau());
    })

})


    /

    /**
     * Ajout dans le panier
     */
    $(document.body).on("click", "#valider", function () {

        const id_pv = $("#pv_vente").val();
        const projet = $("#projet").val();
        const reference = $("#reference").val();
        const idmateriel = $("#idmateriel").val();
        const designationmateriel = $("#designationmateriel").val();
        const description = $("#fiche").val();
        const quantite = parseInt($("#quantite").val(), 10);
        const id_unite = $('#unite').val();
        const unite_selectione = $('#unite').find('option:selected');
        const unite_texte = $(unite_selectione).text();

        // fixed le pv
        const pv_active = $("#pv_vente").find('option:selected');
        const pv_texte = $(pv_active).text();
        const projet_active = $("#projet").find('option:selected');
        const projet_texte = $(projet_active).text();
        $('#pv_vente').html('');
        $('#projet').html('');
        let poindevente_content = `<option class="pv"  value="${id_pv}">${pv_texte}</option>`
        let projet_content = `<option class="pv"  value="${projet}">${projet_texte}</option>`
        $('#pv_vente').html(poindevente_content);
        $('#projet').html(projet_content);

        // Jouter le pv dans le localstorage  
        addLocaleStorage('pointventecontent', poindevente_content);
        addLocaleStorage('projet_content', projet_content);

        // vérification du quantité 
        const identification = $(unite_selectione).data('id');
        const qte_dispo = quantite_dispo_tab[quantite_dispo_tab.length - 1].quantite;

        let total_qte = parseInt(qteMinUnit(real_unite, quantite, identification));


        // teste si le produit est deja dans le panier
        if (tableau_panier_demande[reference] && tableau_panier_demande[reference].length) {
            // additionner le quantiter dans le pannier et celle qui vien d'etre ajouter 
            total_qte = parseInt(total_qte) + parseInt(tableau_panier_demande[reference][0].qte_min);
        }
        if (reference != '' && idmateriel != '' && designationmateriel != '' && quantite > 0 && reference != '') {
            if (qte_dispo >= total_qte) {
                // ajout dans le tableau_panier_demande
                addToPanierTab(idmateriel, qteMinUnit(real_unite, quantite, identification), quantite, reference, designationmateriel, description, unite_texte, identification, id_unite);

                const content = append_tableau();
                $('#tableau').html(content);
                vider();
            }
            else {
                // stock insufisant
                Myalert.erreur(' Le stock est insuffisant.');
            }
        }
    });
// AJOUT AU PANIER  


/**
 * validation du panier
 */
$(document).on('click', '#validerPanier', function () {
    $('#validation').html(panier_modal_content());
    $('.modal-footer').removeClass('d-none');

    setTimeout(function () {
        let teste = false;
        while (!teste) {
            $('#projet_container').html(content_projet);
            if (content_projet != 'En attente des projets...') {
                teste = true;
            }
        }
    }, 4000)
})
/**
 * Contenue du modal de validation
 * @param {*} nom_beneficiaire 
 * @param {*} tel_beneficiaire 
 * @returns 
 */
function panier_modal_content(nom_beneficiaire = '', tel_beneficiaire = '') {

    let content = `
	<div class="_tableau mt-4">
			<table class="table">
				<thead class="table-info">
					<tr>
						<th>Réference</th>
						<th>Désignation</th>
						<th>Déscription</th>
						<th>Quantité</th>
					</tr>
				</thead>
				<tbody >
	` ;

    for (const reference in tableau_panier_demande) {
        $("#validerPanier").removeClass("d-none");
        const par_ref = tableau_panier_demande[reference];

        if (par_ref.length > 0) {

            for (let i = 0; i < par_ref.length; i++) {
                const element = par_ref[i];
                content += `
				<tr >
						<td>${element.reference}</td>
						<td>${element.designationmateriel}</td>
						<td>${element.description}</td>
						<td>${element.quantite} ${element.unite_texte} </td>
				</tr>
			`;
            }
        }
    }
    content += `
				</tbody>
			</table>
		</div>
	` ;
    // en tete de l'affichage 

    // entete += `<div class="mb-2 ">
    //                     <label class="form-label">Frais de livraison :</label>
    //                     <input class="form-control input_form-control " type="number" min='0' id="frais" name="frais" value='${frais}' >
    //                 </div>` ;

    let entete = '';

    if (tel_beneficiaire == '' && nom_beneficiaire == '') {
        entete += `
		<div id='beneficiaire_vide' class=" mt-2">
			<label class="form-label">User :</label></br>
			
			<button id="recherche"  class="btn btn-sm btn-info mb-2">Rechercher</button>
		</div>
	` ;
    }
    else {
        entete += `
		<div id='beneficiaire_set'>
			<label class="form-label">Agent :</label></br>
			<div class="input-group mb-2">
				<input id="nom_beneficiaire" type="text" class="form-control" readonly value='${nom_beneficiaire}' >
				<button id='annuler_beneficiaire' class="btn btn-danger" type="submit">
				<i class="fa-solid fa-x"></i>
				</button>
				<input id="numbeneficiaire" type="hidden" class="form-control" value='${tel_beneficiaire}'>
			</div>
		</div>
		` ;
    }



    entete += `<input id="id_pointdevente" type="text" class="form-control d-none" readonly value='${$("#pv_vente").val()}' ></input>`;

    content = entete + content;
    // en tete de l'affichage 
    return content;
}


// $(document).on('keyup , change', '#frais', function () {
// 	frais = $(this).val();
// })

// user DANS LE PANIER


/**
 * ajout du user
 */
$(document).on('click', '#ajout', function () {
    $('#validation').html(ajout_beneficiaire_content());
    $('.modal-footer').addClass('d-none');
})
/**
 * ajout user
 * @returns 
 */
function ajout_beneficiaire_content() {
    let ajout = `
	<form action="haja" id="registeruser" method="post">
			<div class="mb-2">
				<p class="text-danger d-none" id="ms_error">Le numéro ou le numéro de CIN que vous avez entré existe déjà.</p>
				<label class="form-label ">Nom : </label>
				<input id="nom" name="nom" type="text" class="form-control input_form-control" required>
			</div>
			<div class="mb-2 not_public">
				<label class="form-label ">Prénom : </label>
				<input id="prenom" name="prenom" type="text" class="form-control input_form-control" required>
			</div>
			<div class="mb-2">
				<label class="form-label">Adresse : </label>
				<input name="adress" id="address" type="text" class="form-control input_form-control " required>
			</div>
			<div class="mb-2">
				<label class="form-label">Numéro Téléphone : </label>
				<input name="numero" id="num" type="text" class="form-control input_form-control" required>
			</div>
			<div class="mb-2">
				<label class="form-label">Email : </label>
				<input name="email" id="email" type="email" class="form-control input_form-control" required>
				<div class="text-danger fs-5 font-weight-bold">
					<?= form_error('email'); ?>
				</div>
			</div>
			<div class="mb-2">
				<label class="form-label">Numéro CIN : </label>
				<input name="num_cin" id="num_cin" type="text" class="form-control input_form-control" required>
			</div>

			<div class="mb-2">
				<label class="form-label">Date CIN : </label>
				<input name="date_cin" id="date_cin" type="date" class="form-control input_form-control" required>
			</div>
			<div class="mt-2">
				<button type="submit" class="btn btn-sm btn-info">Valider</button>
				<button type="button" class="btn btn-sm btn-secondary" id="annuler">Annuler l'ajout</button>
			</div>
		</form>` ;
    return ajout;
}
/**
 * enregistrer le user ajouter 
 */
$(document.body).on("submit", "#registeruser", function (e) {
    e.preventDefault();
    let isok = true;
    let nom = $('#nom').val();
    let prenom = $('#prenom').val();
    let adresse = $('#address').val();
    let numero = $('#num').val();
    let num_cin = $('#num_cin').val();
    let date_cin = $('#date_cin').val();
    let email = $('#email').val();
    if (isok == true) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: base_url('User/verify'),
            data: {
                contact: numero,
                numcinbene: num_cin
            },
        }).done(function (response) {

            if (response.success == true) {

                data_user['nom'] = nom
                data_user['prenom'] = prenom;
                data_user['adresse'] = adresse
                data_user['numero'] = numero
                data_user['date_cin'] = date_cin
                data_user['num_cin'] = num_cin
                data_user['email'] = email;

                $('#validation').html(panier_modal_content(nom.toUpperCase() + ' ' + prenom, numero));
                $('.modal-footer').removeClass('d-none');
            } else {
                Myalert.delete('Ce bénéficiaire existe déjà.Voulez-vous utiliser l\'existant ?');
                $('#confirmeDelete').on('click', function () {
                    const info = response.data;
                    let beneficiaire_nom = info.nomUser.toUpperCase() + ' ' + info.prenomUser;
                    let id = info.iduser;
                    $('#validation').html(panier_modal_content(beneficiaire_nom, id));
                    $('.modal-footer').removeClass('d-none');
                    $('#close').click();
                })
            }
        }).fail(function () {
            console.log('erreur sur l\'enregistrement du user !');
        })
    } else if (isok == false) {
        Myalert.erreur('Veuiller remplir tout les champs');
    }
})


/**
 * rechercher un user 
 */
$(document).on('click', '#recherche', function () {
    $('#validation').html(recherche_user_content());
    $('.modal-footer').addClass('d-none');
})
/**
 * rechercher un user 
 * @param {*} recherche 
 * @returns 
 */
function recherche_user_content(recherche = '') {
    let content = '';
    let recherche_tab = `
					<form>
						<div class="input-group mt-3 mb-1">
							<input name="recherche" id= 'beneficiaire_search' type="text" class="form-control" placeholder="Recherche" value='${recherche}'>
							<a class="btn btn-info" id='recherche_user'>
								<i class="fa-solid fa-magnifying-glass"></i>
							</a>
						</div>
						<p class="text-secondary d-none mb-0"id="msg-search">Aucun résultat...</p>
						<p class="text-danger d-none mb-0"id="msg-search_vide">Veuillez selectionner un user</p>
						
					</form>
					<form>
					<table class="table table">

						<thead class="table-info">
							<tr >
								<th>Choix</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Adresse</th>
								<th>Numéro Télephone</th>
								<th>Email</th>
							</tr>
						</thead>
		`;
    $.ajax({
        url: base_url('User/search_json'),
        type: "post",
        data: { recherche: recherche },
        dataType: 'json',
        async: false
    }).done(function (response) {
        if (response.success) {
            const datas = response.datas

            recherche = recherche_tab;
            recherche += `	
						<tbody id= 'tableau_beneficiaire'">
						` ;

            for (let i = 0; i < datas.length; i++) {
                const element = datas[i];

                recherche += `
							<tr >
								<td> 
									<input class='choix_user' type="radio" name='user' data-tel='${element.idUser}' data-nom = '${element.nomUser}' data-prenom = '${element.prenomUser}'>
								</td>
								<td>${element.nomUser}</td>
								<td>${element.prenomUser}</td>
								<td>${element.adress}</td>
								<td>${element.contact}</td>`;
                if (element.mail != '') {
                    recherche += `<td>${element.mail}</td></tr>
						
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
        } else {
            content = `<p class="text-secondary">Aucun agent trouvé</p>
            <button class="btn btn-info" id='retour_vers_modal'><i class="fas fa-reply"></i> Retour</button>
            `;
        }
    });
    return content;
}
/**
 * rechercher un user 
 */
$(document).on('click', '#recherche_user', function () {
    $('#validation').html(recherche_user_content($('#beneficiaire_search').val()));
})
/**
 * choix user 
 */
$(document).on('change', '.choix_user', function () {
    $('#search_valide').attr('data-tel', $(this).data('tel'));
    $('#search_valide').attr('data-nom', $(this).data('nom'));
    $('#search_valide').attr('data-prenom', $(this).data('prenom'));
})

/**
 * annulation du user
 */
$(document).on('click', '#annuler , #annuler_beneficiaire', function () {
    $('#validation').html(panier_modal_content());
    $('.modal-footer').removeClass('d-none');
})

/**
 * retourner vers le modal
 */
$(document).on('click', '#retour_vers_modal', function () {
    $('#validation').html(panier_modal_content());
    $('.modal-footer').removeClass('d-none');
})

/**
 * validation du recherche user 
 */
$(document).on('click', '#search_valide', function () {
    const id = $(this).data('tel');
    const nom = $(this).data('nom');
    const prenom = $(this).data('prenom');
    let contenue = '';
    contenue = nom.toUpperCase() + ' ' + prenom;
    $('#validation').html(panier_modal_content(contenue, id));
    $('.modal-footer').removeClass('d-none');
})

$(document.body).on("click", "#sendvalidation", function () {
    let iduser = $("#numbeneficiaire").val();

    if (iduser > 0) {
        // eregistrer le user 

        // if (data_user.numero != undefined) {
        //     $.ajax({
        //         type: 'post',
        //         dataType: 'json',
        //         url: base_url('User/register_json'),
        //         async: false,
        //         data: {
        //             nom: data_user.nom,
        //             prenom: data_user.prenom,
        //             adress: data_user.adresse,
        //             numero: data_user.numero,
        //             email: data_user.email,
        //             num_cin: data_user.num_cin,
        //             date_cin: data_user.date_cin
        //         },
        //     }).done(function (response) {
        //         iduser = response.iduser
        //     })
        // }
        const id_pointdevente = $("#id_pointdevente").val();
        const projet = $("#projet").val();

        let numFacture = "";
        let idfacture = "";

        $.ajax({
            url: base_url("Demande/facturation"),
            type: "post",
            data: {
                idUser: iduser,
                id_pointdevente: id_pointdevente,
                idprojet: projet
            },
            dataType: "json",
        }).done(function (facturation) {
            numFacture = facturation.facture;
            idfacture = facturation.idfacture;
            // **************** //

            let data = [];
            for (const reference in tableau_panier_demande) {
                const par_ref = tableau_panier_demande[reference];
                for (let i = 0; i < par_ref.length; i++) {
                    const element = par_ref[i];
                    let donner = {
                        'idfacture': idfacture,
                        'idmateriel': element.idmateriel,
                        'idPointVente': id_pointdevente,
                        'quantite': element.quantite,
                        'min_qte': element.qte_min,
                        'idunite': element.id_unite,
                    }
                    data.push(donner);
                }
            }
            // reinitialiser le localstorage
            addLocaleStorage('pointventecontent', null);
            addLocaleStorage('projet_content', null);
            addLocaleStorage('tableau_panier_demande', {});
            addLocaleStorage('allunite_panier_demande', {});
            addLocaleStorage('qte_produit_panier_demande', {});

            // envoyer les données 
            $.ajax({
                url: base_url("validate"),
                type: "post",
                data: { data: data },
            }).done(function (data) {
                tableau_panier_demande = [];
                $('#validerPanier').click();
                $('#validation').html('');
                $('#tableau').html(append_tableau());
                Myalert.added();
            }).fail(function () {
                console.error('Erreur sur la validation de la vente !!');
                window.location.reload();
            });
        });
    } else {

        Myalert.erreur('Veuillez choisir un agent')
    }
});
// EVOYER LE PANIER 
// format 
$(document).on('change', '#format', function () {
    $('#loaderFacture').removeClass('d-none');
    $('#pdfFrame').addClass('d-none');
    const format = $(this).val();

    let src = $('#pdfFrame').attr('src');
    let new_src = '';


    if (format == 'A4') {
        new_src = src.replace('tiquet', 'facture');
    } else {
        new_src = src.replace('facture', 'tiquet');
    }

    $('#pdfFrame').attr('src', new_src);

    setTimeout(function () {
        $('#loaderFacture').addClass('d-none');
        $('#pdfFrame').removeClass('d-none');
    }, 5000)
})

// ====================== AUTOCOMPLETE LIVE AGENT ======================
let agentSearchTimeout = null;

$(document).on('keyup', '#agent_search', function () {
    const query = $(this).val().trim();
    const $datalist = $('#agent_list');

    clearTimeout(agentSearchTimeout);

    if (query.length < 2) {
        $datalist.html('');
        $('#agent_selected').val('');
        $('#agent_info').html('');
        return;
    }

    agentSearchTimeout = setTimeout(() => {
        $.ajax({
            url: base_url('User/search_json'),
            type: "POST",
            data: { recherche: query },
            dataType: 'json'
        }).done(function (response) {
            $datalist.html('');

            if (response.success && response.datas.length > 0) {
                response.datas.forEach(user => {
                    const fullName = `${user.nomUser} ${user.prenomUser}`;
                    const displayText = `${fullName} | Tél: ${user.contact} | CIN: ${user.numero_cin || '—'}`;

                    const $option = $(`<option value="${fullName}" 
                        data-id="${user.idUser}" 
                        data-tel="${user.contact}" 
                        data-cin="${user.numero_cin || ''}" 
                        data-nom="${user.nomUser}" 
                        data-prenom="${user.prenomUser}">
                    `);
                    $datalist.append($option);
                });
            }
        });
    }, 350);
});

// Quand l'utilisateur sélectionne un agent dans la datalist
$(document).on('change', '#agent_search', function () {
    const selectedValue = $(this).val();
    const options = document.getElementById('agent_list').options;

    $('#agent_selected').val('');
    $('#agent_info').html('');

    for (let i = 0; i < options.length; i++) {
        if (options[i].value === selectedValue) {
            const id = options[i].getAttribute('data-id');
            const tel = options[i].getAttribute('data-tel');
            const cin = options[i].getAttribute('data-cin');
            const nom = options[i].getAttribute('data-nom');
            const prenom = options[i].getAttribute('data-prenom');

            $('#agent_selected').val(id);

            $('#agent_info').html(`
                <span class="text-success fw-bold">✅ ${nom} ${prenom}</span><br>
                <small>Téléphone : ${tel} | CIN : ${cin || '—'}</small>
            `);
            break;
        }
    }
});
// ====================== RECHERCHE AGENT INLINE (EXACTEMENT COMME MISSION) ======================
// ====================== ESCAPE HTML (nécessaire pour la sécurité) ======================
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function (match) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[match];
    });
}

// ====================== RECHERCHE AGENT INLINE (comme Mission) ======================
function searchAgentsInline(recherche) {
    if (recherche.length < 1) {
        $("#agent_results").hide().empty();
        return;
    }

    $.ajax({
        url: base_url("User/search_json"),
        type: "post",
        data: { recherche: recherche, type: "Agent" },
        dataType: "json",
        beforeSend: function () {
            $("#agent_results")
                .html('<div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div> Chargement...</div>')
                .show();
        }
    })
        .done(function (response) {
            if (response.success && response.datas.length > 0) {
                let html = '<div style="max-height: 300px; overflow-y: auto;">';
                response.datas.forEach(agent => {
                    html += `
                <div class="agent-result-item p-3 border-bottom" 
                     data-id="${agent.idUser}" 
                     data-nom="${escapeHtml(agent.nomUser)}" 
                     data-prenom="${escapeHtml(agent.prenomUser)}" 
                     data-contact="${agent.contact}" 
                     data-cin="${agent.numero_cin || ''}">
                    <div><i class="fa-solid fa-user-circle text-info"></i> 
                         <strong>${escapeHtml(agent.nomUser)}</strong> ${escapeHtml(agent.prenomUser)}
                    </div>
                    <small class="text-muted">
                        <i class="fa-solid fa-phone"></i> ${agent.contact}
                        ${agent.numero_cin ? ` | <i class="fa-solid fa-id-card"></i> ${agent.numero_cin}` : ''}
                    </small>
                </div>`;
                });
                html += "</div>";
                $("#agent_results").html(html).show();
            } else {
                $("#agent_results")
                    .html('<div class="text-center p-3 text-secondary"><i class="fa-solid fa-user-slash"></i> Aucun agent trouvé</div>')
                    .show();
            }
        })
        .fail(function () {
            $("#agent_results")
                .html('<div class="text-center p-3 text-danger">Erreur de connexion</div>')
                .show();
        });
}

// ====================== ÉVÉNEMENTS ======================
$(document).on("click", "#btn_search_agent", function () {
    let recherche = $("#agent_search").val().trim();
    if (recherche.length >= 1) searchAgentsInline(recherche);
    else Myalert.erreur("Veuillez saisir au moins 1 caractère");
});

$(document).on("keyup", "#agent_search", function () {
    let recherche = $(this).val().trim();
    if (recherche.length >= 1) searchAgentsInline(recherche);
    else $("#agent_results").hide().empty();
});

$(document).on("click", ".agent-result-item", function () {
    const id = $(this).data("id");
    const nom = $(this).data("nom");
    const prenom = $(this).data("prenom");
    const contact = $(this).data("contact");
    const cin = $(this).data("cin");

    $("#idagent").val(id);
    $("#selected_agent_name").html(`
        <strong>${nom} ${prenom}</strong><br>
        <small><i class="fa-solid fa-phone"></i> ${contact}
        ${cin ? ` | <i class="fa-solid fa-id-card"></i> ${cin}` : ''}</small>
    `);
    $("#selected_agent_info").removeClass("d-none");
    $("#agent_search").val("");
    $("#agent_results").hide();
});

$(document).on("click", "#clear_agent", function () {
    $("#idagent").val("");
    $("#selected_agent_info").addClass("d-none");
    $("#agent_search").val("");
    $("#agent_results").hide();
});
// Nouveaux js ************************* 
