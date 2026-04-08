$(document).on('click', '#valider', function () {
	shwoSpinner(this, ['adresse', 'denomination', 'contact']);
})

function Donneclient(elem) {
	let contact = elem.getAttribute('data-contact');
	let adress = elem.getAttribute('data-adress');
	let id = elem.getAttribute('data-id');
	let denomination_pv = elem.getAttribute('data-denomination_pv');
	let option = '';
	let titreprojet = elem.getAttribute('data-titreprojet');
	let codeprojet = elem.getAttribute('data-codeprojet');
	let projet = elem.getAttribute('data-idprojet');
	$('#denomination_modif').val( denomination_pv ) ; 
	$('#type_modif').html( option ) ; 
	$('#adresse_edit').val(adress);
	$('#contact_edit').val(contact);
	$('#idPv').val(id);
	$("#codeprojet-edit").val(codeprojet) ;
	$("#titreprojet-edit").val(titreprojet) ;
	$("#projet-edit").val(projet) ;
	
}


function showSuccessAlert() {
	$("#message-success").addClass("show");
	let t_out = setTimeout(() => {
		hideSuccessAlert();
		clearTimeout(t_out);
	}, 5000);
}
function hideSuccessAlert() {
	$("#message-success").removeClass("show");
}

function deleteIt(elem) {
	const id = elem.getAttribute("data-id");

	Myalert.delete("Cette action va supprimer toutes les données liées à ce point de vente. Êtes-vous sûr de vouloir continuer ?", function () {
		$.ajax({
			method: 'post',
			url: base_url('deletePv'),
			data: { id: id },
			dataType: 'json',
			success: function (response) {
				if (response.success == true) {
					window.location.reload()
				}
			}
		})
	});
}


$(document).ready(function () {
	$(document.body).on("click", "#modifier", function () {
		$.ajax({
			url: base_url("verifPv"),
			type: "post",
			dataType: "json",
			data: {
				adress: $("#adresse_edit").val(),
				contact: $("#contact_edit").val(),
				denomination: $("#denomination_modif").val(),
				id: $("#idPv").val(),
			},
		}).done(function (data) {
			if (data.success) {
				$("#modification").click();
			} else {
				if (data.adressExiste) {
					$("#adresse_edit").css("border", "1px solid red");
					$("#msg-adresse-modif").removeClass("d-none");
					$("#msg-num-modif").addClass("d-none");
					$("#msg-denomination-modif").addClass("d-none");
				} else {
					$("#adresse_edit").css("border", "");
				}

				if (data.contactExiste) {
					$("#contact_edit").css("border", "1px solid red");
					$("#msg-num-modif").removeClass("d-none");
					$("#msg-adresse-modif").addClass("d-none");
					$("#msg-denomination-modif").addClass("d-none");
				} else {
					$("#contact_edit").css("border", "");
				}

				if (data.denomination) {
					$("#denomination_modif").css("border", "1px solid red");
					$("#msg-denomination-modif").removeClass("d-none");
					$("#msg-adresse-modif").addClass("d-none");
					$("#msg-num-modif").addClass("d-none");
				} else {
					$("#denomination_modif").css("border", "");
				}

			}
		}).fail(function (jqXHR, textStatus, errorThrown) {
			console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
		});
	});


	// completion projet 
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
                    $('#codeprojet').val(projet.codeprojet);
                    $('#titreprojet').val(projet.titreprojet);
                    $('#projet').val(projet.idprojet);
                    let content = '';
                    for (let i = 0; i < activites.length; i++) {
                        const element = activites[i];
                        content += `<option value="${element.idactivite}">${element.titre_activite}</option>`;
                    }
                    $('#ligne').focus() ; 
                    $('#activite').html(content);
                } else {
                    Myalert.erreur('Ce code de projet n\'existe pas.');
                }
            }).fail(function () {
                console.error('Erreur dans la verification du activite ');
            })
        }
    });


    $(document).on('change', '#codeprojet-edit', function () {
		
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
                    $('#codeprojet-edit').val(projet.codeprojet);
                    $('#titreprojet-edit').val(projet.titreprojet);
                    $('#projet-edit').val(projet.idprojet);
                    let content = '';
                    for (let i = 0; i < activites.length; i++) {
                        const element = activites[i];
                        content += `<option value="${element.idactivite}">${element.titre_activite}</option>`;
                    }
                    $('#ligne').focus() ; 
                    $('#activite').html(content);
                } else {
                    Myalert.erreur('Ce code de projet n\'existe pas.');
                }
            }).fail(function () {
                console.error('Erreur dans la verification du activite ');
            })
        }
    });

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

});




