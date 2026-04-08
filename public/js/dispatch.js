
let mode = [];
let the_mode = 0;
let frais = 0;


let content_projet = 'En attente des projets...';
$.ajax({
	type: "post",
	url: base_url('Projet/projet_json'),
	dataType: "json",
	success: function (response) {
		let content = `Aucun projet trouvé.`
		if (response.success) {
			content = `<select name="projet" id="projet" class="form-select">`;
			content += `<option value="">Aucun</option>`
			for (let i = 0; i < response.data.length; i++) {
				const element = response.data[i];
				content += `<option value="${element.idprojet}">${element.codeprojet}</option>`
			}
			content += `</select>`
		}

		content_projet = content;
	}
});

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
let data_beneficiaire = [];
// qte de chaque materiel dans le panier
let qte_produit_panier = {};

// pour stocke toutes les tableaux d'unite deja dans le panier 
let allunite_panier = {};

// quantite par materiel dans le panier pour gerer la quantite reste disponible 
var tableau_panier = {};
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
qte_produit_panier = getLocaleStorage('qte_produit_panier', {});
allunite_panier = getLocaleStorage('allunite_panier', {});
tableau_panier = getLocaleStorage('tableau_panier', {});

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


$(document).on('change', '#pv_vente', function () {
	vider();
})

/**
 * ajoute dans le tableau tableau_panier 
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
	if (tableau_panier[reference] && tableau_panier[reference].length != 0) {
		texte = `${reference}_${tableau_panier[reference].length}`;
		// teste si l'utite est deja dans le panier ( si oui , on additionne )
		let teste_unite = false;
		// deja dans le panier 
		for (let i = 0; i < tableau_panier[reference].length; i++) {
			const element = tableau_panier[reference][i];
			element.qte_min = parseInt(element.qte_min) + parseInt(qte_min);

			qte_produit_panier[reference] = element.qte_min;

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
			qte_produit_panier[reference] = tableau_panier[reference][0].qte_min;
			let data = {
				count: texte,
				idmateriel: idmateriel,
				qte_min: tableau_panier[reference][0].qte_min,
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
			tableau_panier[reference].push(data);
		}

	}
	else {
		// pas encore dans le panier
		qte_produit_panier[reference] = parseInt(qte_min, 10);
		texte = `${reference}_0`;
		tableau_panier[reference] = [];
		tableau_panier[reference][0] = {
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
			tableau_panier[reference][0].unite_texte += 's';
		}
	}
	// localstorage 
	addLocaleStorage('tableau_panier', tableau_panier);
	// localsotorage 
	addLocaleStorage('qte_produit_panier', qte_produit_panier);

	console.log(qte_produit_panier);

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
	let content = '';

	$("#validerPanier").addClass("d-none");

	for (const reference in tableau_panier) {
		const par_ref = tableau_panier[reference];
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
	const id = $(this).data('id');
	const tr = $(this).closest('tr');
	Myalert.delete("Êtes-vous sûr de vouloir supprimer cet élément du panier ?", function () {
		let identification = id.split('_');
		const unite_identification = $(tr).data('unite_identification');
		const quantite = $(tr).data('quantite');
		const reference = $(tr).data('reference');

		// teste si il est bien dans le panier 
		if (tableau_panier[identification[0]]) {
			tableau_panier[identification[0]].splice(identification[1], 1);
			// pour l'affichage du qte disponible 

			if (allunite_panier[reference]) {
				let qte_deleted = qteMinUnit(allunite_panier[reference], quantite, unite_identification)

				console.log(tableau_panier[reference]);

				// elevé les quatite effacer 
				qte_produit_panier[reference] = parseInt(qte_produit_panier[reference]) - parseInt(qte_deleted);

				for (let i = 0; i < tableau_panier[reference].length; i++) {
					const element = tableau_panier[reference][i];
					element.qte_min = parseInt(element.qte_min) - parseInt(qte_deleted)
				}

			}
		}
		addLocaleStorage('tableau_panier', tableau_panier);
		addLocaleStorage('qte_produit_panier', qte_produit_panier);
		$('#tableau').html(append_tableau());
	});
})


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
				type_mat: 'projet'
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
							for (let i = 0; i < unite_bymats.length; i++) {
								const element = unite_bymats[i];
								if (element.denomination) {
									content += `
										<option class='unites' value="${element.idunite}" data-id='${i}' data-prix='${element.prixProduit}'>${element.denomination}</option>
									`
									$('#unite_container').removeClass('d-none');
								}
								else {
									$('#unite_container').addClass('d-none');
								}
							}

							real_unite = unite_bymats;
							allunite_panier[materiel.refmateriel] = unite_bymats;
							// localstorage 
							addLocaleStorage('allunite_panier', allunite_panier);


							// quantiter
							// pour avoir les quantite avec tous les unités
							let in_panier = 0;
							if (qte_produit_panier[materiel.refmateriel]) {
								in_panier = qte_produit_panier[materiel.refmateriel];
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
						Myalert.erreur('Ce materiel n\'a pas encore de prix.')
					}
				}).fail(function (err) {
					console.error('prix : '.err);
				})



				// const materiel = recherche_produit.materiel;
				// type_produit = materiel.type;


				// $('#idmateriel').val(materiel.idmateriel);
				// $('#reference').val(materiel.refmateriel);


				// $.ajax({
				// 	method: 'post',
				// 	url: base_url('Dispatch/getStock'),
				// 	data: { idmateriel: materiel.idmateriel, id_pv: id_pv },
				// 	dataType: 'json',
				// }).done(function (getStock) {
				// 	if (getStock.success) {
				// 		let quantite = getStock.quantite;
				// 		let in_the_panier = 0;
				// 		// PRENDRE LES QUANTITE DANS LE PANIER ICI 


				// 		if (tableau_panier[materiel.refmateriel] && tableau_panier[materiel.refmateriel].length > 0) {
				// 			in_the_panier = tableau_panier[materiel.refmateriel][0].quantite;
				// 		}

				// 		quantite_dispo = quantite - in_the_panier;
				// 		$('.with_qte').removeClass('d-none');
				// 		$('#qte_dipo').val(quantite_dispo);
				// 		$('#quantite').val(1);

				// 		$('#quantite').focus();
				// 	} else {
				// 		Myalert.erreur('Stock insuffisant. Veuillez faire l\'approvisionnement de ce materiel dans ce point de vente.');
				// 		vider();
				// 	}
				// }).fail(function (err) {
				// })

			} else {
				if (recherche_produit.type_err == 'type') {
					Myalert.erreur('Ce type de matériel ne peut pas être distribué.')
				} else {
					Myalert.erreur('Cette référence ( Numéro de série ) n\'existe pas.')
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

/**
 * Ajout dans le panier
 */
$(document.body).on("click", "#valider", function () {

	const id_pv = $("#pv_vente").val();
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
	$('#pv_vente').html('');
	let poindevente_content = `
	<option class="pv"  value="${id_pv}">${pv_texte}</option>
	`
	$('#pv_vente').html(poindevente_content);

	// Jouter le pv dans le localstorage  
	addLocaleStorage('pointventecontent', poindevente_content);

	// vérification du quantité 
	const identification = $(unite_selectione).data('id');
	const qte_dispo = quantite_dispo_tab[quantite_dispo_tab.length - 1].quantite;

	let total_qte = parseInt(qteMinUnit(real_unite, quantite, identification));


	// teste si le produit est deja dans le panier
	if (tableau_panier[reference] && tableau_panier[reference].length) {
		// additionner le quantiter dans le pannier et celle qui vien d'etre ajouter 
		total_qte = parseInt(total_qte) + parseInt(tableau_panier[reference][0].qte_min);
	}
	if (reference != '' && idmateriel != '' && designationmateriel != '' && quantite > 0 && reference != '') {
		if (qte_dispo >= total_qte) {
			// ajout dans le tableau_panier
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

	for (const reference in tableau_panier) {
		$("#validerPanier").removeClass("d-none");
		const par_ref = tableau_panier[reference];

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
			<label class="form-label">Beneficiaire :</label></br>
			<button id='ajout' class="btn btn-sm btn-primary mb-2">Ajouter</button>
			<button id="recherche"  class="btn btn-sm btn-info mb-2">Rechercher</button>
		</div>
	` ;
	}
	else {
		entete += `
		<div id='beneficiaire_set'>
			<label class="form-label">Beneficiaire :</label></br>
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

	entete += `
		<div'>
			<label class="form-label">Projet :</label></br>
			<div id='projet_container'>
				${content_projet}
			</div>
		</div>` ; 


	entete += `<input id="id_pointdevente" type="text" class="form-control d-none" readonly value='${$("#pv_vente").val()}' ></input>`;



	content = entete + content;
	// en tete de l'affichage 
	return content;
}


// $(document).on('keyup , change', '#frais', function () {
// 	frais = $(this).val();
// })

// beneficiaire DANS LE PANIER


/**
 * ajout du beneficiaire
 */
$(document).on('click', '#ajout', function () {
	$('#validation').html(ajout_beneficiaire_content());
	$('.modal-footer').addClass('d-none');
})
/**
 * ajout beneficiaire
 * @returns 
 */
function ajout_beneficiaire_content() {
	let ajout = `
	<form action="haja" id="registerbeneficiaire" method="post">
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
 * enregistrer le beneficiaire ajouter 
 */
$(document.body).on("submit", "#registerbeneficiaire", function (e) {
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
			url: base_url('Beneficiaire/verify'),
			data: {
				telbene: numero,
				numcinbene: num_cin
			},
		}).done(function (response) {

			if (response.success == true) {

				data_beneficiaire['nom'] = nom
				data_beneficiaire['prenom'] = prenom;
				data_beneficiaire['adresse'] = adresse
				data_beneficiaire['numero'] = numero
				data_beneficiaire['date_cin'] = date_cin
				data_beneficiaire['num_cin'] = num_cin
				data_beneficiaire['email'] = email;

				$('#validation').html(panier_modal_content(nom.toUpperCase() + ' ' + prenom, numero));
				$('.modal-footer').removeClass('d-none');
			} else {
				Myalert.delete('Ce bénéficiaire existe déjà. Voulez-vous utiliser l\'existant ?', function () {
					const info = response.data;
					let beneficiaire_nom = info.nombene.toUpperCase() + ' ' + info.prenombene;
					let id = info.idbeneficiaire;
					$('#validation').html(panier_modal_content(beneficiaire_nom, id));
					$('.modal-footer').removeClass('d-none');
				});
			}
		}).fail(function () {
			console.log('erreur sur l\'enregistrement du beneficiaire !');
		})
	} else if (isok == false) {
		Myalert.erreur('Veuiller remplir tout les champs');
	}
})


/**
 * rechercher un beneficiaire 
 */
$(document).on('click', '#recherche', function () {
	$('#validation').html(recherche_beneficiaire_content());
	$('.modal-footer').addClass('d-none');
})
/**
 * rechercher un beneficiaire 
 * @param {*} recherche 
 * @returns 
 */
function recherche_beneficiaire_content(recherche = '') {
	let content = '';
	let recherche_tab = `
					<form>
						<div class="input-group mt-3 mb-1">
							<input name="recherche" id= 'beneficiaire_search' type="text" class="form-control" placeholder="Recherche" value='${recherche}'>
							<a class="btn btn-info" id='recherche_beneficiaire'>
								<i class="fa-solid fa-magnifying-glass"></i>
							</a>
						</div>
						<p class="text-secondary d-none mb-0"id="msg-search">Aucun résultat...</p>
						<p class="text-danger d-none mb-0"id="msg-search_vide">Veuillez selectionner un beneficiaire</p>
						
					</form>
					<form>
					<table class="table table">

						<thead class="table-info">
							<tr >
								<th>Choix</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Adresse</th>
								<th>Numéro CIN</th>
								<th>Numéro Télephone</th>
								<th>Email</th>
							</tr>
						</thead>
		`;
	$.ajax({
		url: base_url('Beneficiaire/search_json'),
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
									<input class='choix_beneficiaire' type="radio" name='beneficiaire' data-tel='${element.idbeneficiaire}' data-nom = '${element.nombene}' data-prenom = '${element.prenombene}'>
								</td>
								<td>${element.nombene}</td>
								<td>${element.prenombene}</td>
								<td>${element.adressebene}</td>
								<td>${element.numcinbene}</td>
								<td>${element.telbene}</td>`;
				if (element.mailbene != '') {
					recherche += `<td>${element.mailbene}</td></tr>
						
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
			content = `<p class="text-secondary">Aucun bénéficiaire trouvé</p>`;
		}
	});
	return content;
}
/**
 * rechercher un beneficiaire 
 */
$(document).on('click', '#recherche_beneficiaire', function () {
	$('#validation').html(recherche_beneficiaire_content($('#beneficiaire_search').val()));
})
/**
 * choix beneficiaire 
 */
$(document).on('change', '.choix_beneficiaire', function () {
	$('#search_valide').attr('data-tel', $(this).data('tel'));
	$('#search_valide').attr('data-nom', $(this).data('nom'));
	$('#search_valide').attr('data-prenom', $(this).data('prenom'));
})

/**
 * annulation du beneficiaire
 */
$(document).on('click', '#annuler , #annuler_beneficiaire', function () {
	$('#validation').html(panier_modal_content());
	$('.modal-footer').removeClass('d-none');
})

/**
 * validation du recherche beneficiaire 
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

	let idbeneficiaire = $("#numbeneficiaire").val();
	// eregistrer le beneficiaire 

	if (data_beneficiaire.numero != undefined) {
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: base_url('Beneficiaire/register_json'),
			async: false,
			data: {
				nom: data_beneficiaire.nom,
				prenom: data_beneficiaire.prenom,
				adress: data_beneficiaire.adresse,
				numero: data_beneficiaire.numero,
				email: data_beneficiaire.email,
				num_cin: data_beneficiaire.num_cin,
				date_cin: data_beneficiaire.date_cin
			},
		}).done(function (response) {
			idbeneficiaire = response.idbeneficiaire
		})
	}
	const id_pointdevente = $("#id_pointdevente").val();
	let idprojet = '';
	idprojet = $("#projet").val();

	let numFacture = "";
	let idfacture = "";

	$.ajax({
		url: base_url("facturation"),
		type: "post",
		data: {
			idbeneficiaire: idbeneficiaire,
			id_pointdevente: id_pointdevente,
			idprojet: idprojet,
		},
		dataType: "json",
	}).done(function (facturation) {
		numFacture = facturation.facture;
		idfacture = facturation.idfacture;

		// **************** //
		let data = [];
		for (const reference in tableau_panier) {
			const par_ref = tableau_panier[reference];
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
		addLocaleStorage('tableau_panier', {});
		addLocaleStorage('allunite_panier', {});
		addLocaleStorage('qte_produit_panier', {});

		// envoyer les données 
		$.ajax({
			url: base_url("validate"),
			type: "post",
			data: { data: data },
		}).done(function (data) {
			tableau_panier = [];
			$('#tableau').html(append_tableau());
			Myalert.added() ; 
		}).fail(function () {
			console.error('Erreur sur la validation de la vente !!');
			window.location.reload();
		});
	});
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



// Nouveaux js ************************* 
