//************************* */ Nouveau js appro 

function vider() {
	$('#num_demande').val('');
}

function calacule_totale() {
	const prix = $('#prix').val();
	const quantite = $('#qte').val();
	if (prix && quantite) {
		const montant = parseInt(prix) * parseInt(quantite);
		$('#montant').val(montant.toLocaleString("fr-FR") + ' Ar');
	}
	else {
		$('#montant').val("0" + ' Ar');
	}
}
// $(document.body).on("change", "#reference", function () {
// 	const ref = $(this).val();
// 	$.ajax({
// 		url: base_url("Appro/recherche_produit"),
// 		type: "post",
// 		dataType: "json",
// 		data: {
// 			ref: ref,
// 		},
// 	}).done(function (data) {
// 		if (data.success == true) {
// 			const materiel = data.materiel
// 			$('#reference').val(materiel.refmateriel);
// 			$('#idmateriel').val(materiel.idmateriel);
// 			$('#designationmateriel').val(materiel.designationmateriel);
// 			$('#type_materiel').val(materiel.typemateriel);
// 			$('#fiche').val(materiel.fiche);

// 			$("#quantite").focus();

// 			// unite specify
// 			const unites = data.unites;
// 			if (unites.length > 0) {
// 				let content = '';
// 				for (let i = 0; i < unites.length; i++) {
// 					content += `<option value="` + unites[i].idunite + `">` + unites[i].denomination + `</option>`
// 				}
// 				$('#unite').html(content);
// 				$('#unite_container').removeClass('d-none');
// 			} else {
// 				$('#unite_container').addClass('d-none');
// 			}
// 		} else {
// 			vider();
// 			Myalert.erreur("Cette Réference n'existe pas.");
// 		}
// 	})
// });

$(document.body).on('click', '.delete', function () {
	Myalert.delete()
	const idremise = $(this).data('idremise');


	$('#confirmeDelete').on('click', function () {
		$.ajax({
			method: 'post',
			url: base_url("Remise/delete"),
			data: { idremise: idremise },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			}
		})
	})
})

$(document).on('click', '#valider', function () {
	shwoSpinner(this, ['focused', 'num_demande']);
});

$(document).on('change , keyup', '#quantite , #prix', function () {
	const quantite = $('#quantite').val();
	const prix = $('#prix').val();

	let montant = 0;

	if (quantite != '' && prix != '') {
		montant = parseInt(quantite) * parseInt(prix);
	}

	$('#montant_show').val(montant.toLocaleString("fr-FR") + ' Ar ');
	$('#montant').val(montant);
})


// Numero de la demande 
$(document).on('change', '#num_demande', function () {
	const Facture = $(this).val();
	if (Facture != '') {
		$.ajax({
			method: "POST",
			url: base_url('Demande/getByfact'),
			data: { facture: Facture },
			dataType: "json",
		}).done(function (response) {
			if (response.success) {
				const datas = response.datas;
				$('#iddemande').val(datas[0].idfacture)
				$('#num_demande').val(datas[0].Facture)
				let content = ``;
				for (let i = 0; i < datas.length; i++) {
					const materiel = datas[i];
					const uniteLabel = materiel.denomination ? materiel.denomination : 'unité(s)';
					
					content += `
						<div class="card mb-3 shadow-sm border-info" style="border-left: 5px solid #0dcaf0;">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col-md-3">
										<p class="mb-1 text-muted small"><strong>Matériel</strong></p>
										<div class="mb-0 text-dark" style="font-weight: 500;">${materiel.refmateriel}</div>
										<div class="small text-muted text-truncate" title="${materiel.designationmateriel}">${materiel.designationmateriel}</div>
										<input type="hidden" name="materiel[${i}]" value='${materiel.idmateriel}'>
										<input type="hidden" name="panier[${i}]" value='${materiel.idPanier}'>
										<input type="hidden" name="idunite[${i}]" value='${materiel.idunite}'>
									</div>
									<div class="col-md-2 text-center">
										<p class="mb-1 text-muted small">Initial</p>
										<span class="badge bg-secondary">${materiel.quantite} ${uniteLabel}</span>
									</div>
									<div class="col-md-2 text-center">
										<p class="mb-1 text-muted small">Déjà rendu</p>
										<span class="badge bg-light text-dark border">${materiel.qte_retournee_min} (unités base)</span>
									</div>
									<div class="col-md-2 text-center text-primary">
										<p class="mb-1 text-muted small">Reste à rendre</p>
										<strong class="h5 mb-0">${materiel.qte_restante_min}</strong>
									</div>
									<div class="col-md-3">
										<label class="form-label mb-1 text-muted small">Qte à rendre maintenant</label>
										<div class="input-group">
											<input type="number" min='0' max='${materiel.qte_restante_min}' 
												name="quantite[${i}]" 
												class="form-control return-input" 
												data-index="${i}"
												style="border-color: #0dcaf0; font-weight: bold;"
												value='${materiel.qte_restante_min}' 
												required >
											<span class="input-group-text bg-info text-white">${uniteLabel}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					`;
				}
				$('#remise_container').removeClass('d-none');
				$('#remise_container').html(content);
				
				// Focus sur le premier input
				$('.return-input').first().focus();
			} else {
				vider() ; 
				const msg = response.message ? response.message : 'Ce numero de demande n\'existe pas. ';
				Myalert.erreur(msg);
			}
		});
	}
})

$(document.body).on("click", ".detail", function () {
	const  idremise = $(this).data("idremise");
	$.ajax({
		url: base_url("Remise/getDetails"),
		type: "post",
		data: {
			idremise: idremise,
		},
	}).done(function (data) {
		$("#tab").html(data);
	});
});





