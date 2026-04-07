//************************* */ Nouveau js appro 

function vider() {
	$('#reference').val('');
	$('#designationmateriel').val('');
	$('#type_materiel').val('');
	$('#fiche').val('');
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

$(document).on('keyup , change', '#prix , #qte', function () {
	const prix = $('#prix').val();
	const quantite = $('#qte').val();

	if (prix && quantite) {
		calacule_totale();
	}
	else {
		$('#montant').val("0" + ' Ar');
	}
})

$(document.body).on("change", "#reference", function () {
	const ref = $(this).val();
	$.ajax({
		url: base_url("Appro/recherche_produit"),
		type: "post",
		dataType: "json",
		data: {
			ref: ref,
		},
	}).done(function (data) {
		if (data.success == true) {

			const materiel = data.materiel
			$('#reference').val(materiel.refmateriel);
			$('#idmateriel').val(materiel.idmateriel);
			$('#designationmateriel').val(materiel.designationmateriel);
			$('#type_materiel').val(materiel.typemateriel);
			$('#fiche').val(materiel.fiche);

			$("#prix").focus();

			// unite specify
			const unites = data.unites;
			if (unites.length > 0) {
				let content = '';
				for (let i = 0; i < unites.length; i++) {
					content += `<option value="` + unites[i].idunite + `">` + unites[i].denomination + `</option>`
				}
				$('#unite').html(content);
				$('#unite_container').removeClass('d-none');
			} else {
				$('#unite_container').addClass('d-none');
			}
		} else {
			vider();
			Myalert.erreur("Cette Réference n'existe pas.");
		}
	})
});

$(document.body).on('click', '.delete', function () {
	Myalert.delete()
	const idappro = $(this).data('idappro');


	$('#confirmeDelete').on('click', function () {
		$.ajax({
			method: 'post',
			url: base_url("deleteAppro"),
			data: { idappro: idappro },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			}
		})
	})
})


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

// verification dans la base de donne
function verify_numserie_base(numero) {
	let result = false;
	$.ajax({
		method: 'post',
		url: base_url('Appro/verifyNumserie'),
		data: { numero: numero },
		async: false,
		dataType: 'json',
	}).done(function (response) {
		result = response.success;
	}).fail(function (err) {
		console.error('erreur dans la verification du numero de series '.err);
	})
	return result;
}
function verify_imei_base(imei) {
	let result = false;
	$.ajax({
		method: 'post',
		url: base_url('Appro/verifyImei'),
		data: { imei: imei },
		async: false,
		dataType: 'json',
	}).done(function (response) {
		result = response.success;
	}).fail(function (err) {
		console.error('erreur dans la verification du numero de series '.err);
	})
	return result;
}

// verification dans les input ( standar )
function verify_input(numero, the_class, self) {
	let input = $('.'.the_class);
	let teste = true;
	for (let i = 0; i < input.length; i++) {
		const element = input[i];

		let elem_self = $(element).data('self');
		if (elem_self != self && $(element).val() == numero) {
			teste = false
		}
	}
	return teste;
}

function exist(elem) {
	$(elem).css({
		'border': '1px solid red'
	})
	$(elem).val('');
}
function ok(elem) {
	$(elem).css({
		'border': ''
	});
}


$(document).on('change', '#numSerie', function () {
	const numero = $(this).val();
	const self = $(this).data('self');
	if ($('#designationmateriel').val() != '' && numero != '') {
		if (verify_numserie_base(numero)) {
			ok(this);
			$('#numero_mss').addClass('d-none');
		} else {
			exist(this);
			$('#numero_mss').removeClass('d-none');
		}
	} else {
		$(this).val('');
		$('#numero_mss').addClass('d-none');
	}
})
$(document).on('change', '#imei1', function () {
	const imei = $(this).val();
	const self = $(this).data('self');
	if ($('#designationmateriel').val() != '' && imei != '') {
		if (verify_imei_base(imei) && $('#imei2').val() != imei) {
			ok(this);
			$('#imei1_mss').addClass('d-none');
		} else {
			exist(this);
			$('#imei1_mss').removeClass('d-none');
			$(this).focus();
		}
	} else {
		$(this).val('');
		$('#imei1_mss').addClass('d-none');
	}
})
$(document).on('change', '#imei2', function () {
	const imei = $(this).val();
	const self = $(this).data('self');
	if ($('#designationmateriel').val() != '' && imei != '') {
		if (verify_imei_base(imei) && $('#imei1').val() != imei) {
			ok(this);
			$('#imei2_mss').addClass('d-none');
		} else {
			exist(this);
			$('#imei2_mss').removeClass('d-none')
			$(this).focus();;
		}
	} else {
		$(this).val('');
		$('#imei2_mss').addClass('d-none');
	}
})


let window_width = window.innerWidth;

$(document).ready(function () {
	if (window_width <= 768) {
		$(".sidebar").addClass("hide");
	}
	$(window).on("resize", function () {
		if ($(this).innerWidth() <= 768) {
			$(".sidebar").addClass("hide");
		} else {
			$(".sidebar").removeClass("hide");
		}
	});
	const elemtooltips = document.querySelectorAll(".btn-tooltip");
	for (let elem of elemtooltips) {
		new bootstrap.Tooltip(elem);
	}


	$(".ui.dropdown").dropdown();
});




$(document).on('change', '#codeprojet', function () {
		
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
                    const activites = response.activites;
                    const depot = response.depot ;
                    $('#codeprojet').val(projet.codeprojet);
                    $('#titreprojet').val(projet.titreprojet);
                    $('#projet').val(projet.idprojet);
                   	let affiche = "" ;
                   	for (var i = 0; i < depot.length; i++) {
                   		affiche +="<option value='"+depot[i].idPointVente+"'>"+depot[i].denomination_pv+"</option>" ;
                   	}
                  	
                    $("#liste-pv").append(affiche) ;
                   
                    
                } else {
                    Myalert.erreur('Ce code de projet n\'existe pas.');
                }
            }).fail(function () {
                console.error('Erreur dans la verification du activite ');
            })
        }
  });

$(document).ready(function () {
    $("form").on("submit", function (e) {
        let depot = $("#liste-pv").val();
      	let projet = $("#projet").val() ;

      	if(projet==0) {
      		e.preventDefault() ;
      		Myalert.erreur('Veuillez choisir un projet') ;
      	}
        else if (depot === "0") {
            e.preventDefault(); // bloque le submit
            Myalert.erreur('Veuillez choisir un dépôt')
            $("#liste-pv").focus();
        }
        else {
        	if ($("#type").val() == 'autre') {
				shwoSpinner(this, ['designationmateriel', 'prix', 'quantite', 'montant']);
			} else {

				shwoSpinner(this, ['designationmateriel', 'prix', 'numSerie', 'imei1', 'imei2']);
			}
        }
    });
});

/*$(document).on('click', '#valider', function () {
	// laba
	const idfournisseur = $('#fournisseur').val();
	
});
*/