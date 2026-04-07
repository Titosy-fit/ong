// $(document).on('click', '#valider', function () {
// 	shwoSpinner(this, ['nom', 'prenom', 'adresse', 'email', 'numero']);
// })

// function deleteIt(elem) {
// 	const id = elem.getAttribute("data-id");

// 	Myalert.delete("Cette action va supprimer toutes les données insérées par cet utilisateur. Êtes-vous sûr de vouloir continuer ?")

// 	$('#confirmeDelete').on('click', function () {
// 		$.ajax({
// 			url: base_url("deleteUser"),
// 			type: "POST",
// 			data: { id: id },
// 			dataType: "json",
// 			success: function (response) {
// 				if (response.success == true) {
// 					window.location.reload()
// 				}
// 			}
// 		});
// 	})
// }

// function reinitialize(elem) {
// 	const id = elem.getAttribute("data-id");

// 	Myalert.delete('Etes-vous sur de réinitialiser se profil ?');

// 	$('#confirmeDelete').on('click', function () {
// 		$.ajax({
// 			url: base_url("mdpUser"),
// 			type: "POST",
// 			data: { id: id },
// 			dataType: "json",
// 			success: function (response) {
// 				if (response.success == true) {
// 					window.location.reload()
// 				}
// 			},
// 		});
// 	})
// }

// function DonnerUser(elem) {
// 	const form = $(this);
// 	$.ajax({
// 		url: base_url("DonnerUser"),
// 		type: "post",
// 		data: {
// 			id: elem.getAttribute("data-id"),
// 		},
// 		dataType: "json",

// 	}).done(function (response) {

// 		if (response.success) {

// 			const data = response.data[0];
// 			const pv = response.pv;
// 			const postes = response.postes;
// 			$("#id-User").val(data.idUser);
// 			$("#nom_modif").val(data.nomUser);
// 			$("#prenom_modif").val(data.prenomUser);
// 			$("#numero_modif").val(data.contact);
// 			$("#adresse_modif").val(data.adress);
// 			$("#email_modif").val(data.mail);

// 			let poste_modif = '' ; 

// 			for (let i = 0; i < postes.length; i++) {
// 				const element = postes[i];
// 				if  ( data.idposte == element.idposte ){
// 					poste_modif +=`<option value="${element.idposte}">${element.designation_poste}</option>` ;
// 				}
// 			}
			
// 			for (let i = 0; i < postes.length; i++) {
// 				const element = postes[i];
// 				if  ( data.idposte != element.idposte ){
// 					poste_modif +=`<option value="${element.idposte}">${element.designation_poste}</option>` ;
// 				}
// 			}

// 			$("#poste_modif").html( poste_modif );
// 			let content = ``;

// 			for (let i = 0; i < pv.length; i++) {
// 				const pointvente = pv[i];
// 				if (pointvente.idPointVente == data.idPointVente) {
// 					content = `
// 					<option class="pv" value="${pointvente.idPointVente}">
// 						${pointvente.denomination_pv}
// 					</option>
// 					` ;
// 				}
// 			}
// 			for (let i = 0; i < pv.length; i++) {
// 				const pointvente = pv[i];
// 				if (pointvente.idPointVente != data.idPointVente) {
// 					content += `
// 					<option class="pv" value="${pointvente.idPointVente}">
// 						${pointvente.denomination_pv}
// 					</option>
// 					` ;
// 				}
// 			}

// 			$('#pvModif').html(content);
// 		} else {
// 			Myalert.erreur();
// 		}

// 	})
// 		.fail(function (errorMessage) {
// 			console.log(errorMessage);
// 		});
// }

// $(document.body).on("click", "#modifier", function () {
// 	$.ajax({
// 		url: base_url("verifUser"),
// 		type: "post",
// 		dataType: "json",
// 		data: {
// 			numero: $("#numero_modif").val(),
// 			email: $("#email_modif").val(),
// 			id: $("#id-User").val(),
// 		},
// 	}).done(function (data) {
// 		if (data.success) {
// 			$("#modification").click();
// 		} else {
// 			if (data.numExiste) {
// 				$("#numero_modif").css("border", "1px solid red");
// 				$("#msg-num-modif").removeClass("d-none");
// 				$("#msg-mail-modif").addClass("d-none");
// 			} else {
// 				$("#numero_modif").css("border", "");
// 			}

// 			if (data.mailExiste) {
// 				$("#email_modif").css("border", "1px solid red");
// 				$("#msg-mail-modif").removeClass("d-none");
// 				$("#msg-num-modif").addClass("d-none");
// 			} else {
// 				$("#email_modif").css("border", "");
// 			}

// 		}
// 	}).fail(function (jqXHR, textStatus, errorThrown) {
// 		console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
// 	});
// });

// let window_width = window.innerWidth;

// $(document).ready(function () {
// 	if (window_width <= 768) {
// 		$(".sidebar").addClass("hide");
// 	}
// 	$(window).on("resize", function () {
// 		if ($(this).innerWidth() <= 768) {
// 			$(".sidebar").addClass("hide");
// 		} else {
// 			$(".sidebar").removeClass("hide");
// 		}
// 	});
// 	const elemtooltips = document.querySelectorAll(".btn-tooltip");
// 	for (let elem of elemtooltips) {
// 		new bootstrap.Tooltip(elem);
// 	}

// });


$(document).on('click', '#valider', function () {
    shwoSpinner(this, ['nom', 'prenom', 'adresse', 'email', 'numero']);
    Myalert.added();
})

function deleteIt(elem) {
    const id = elem.getAttribute("data-id");

    Myalert.delete("Cette action va supprimer toutes les données insérées par cet utilisateur. Êtes-vous sûr de vouloir continuer ?")

    $('#confirmeDelete').on('click', function () {
        $.ajax({
            url: base_url("deleteUser"),
            type: "POST",
            data: { id: id },
            dataType: "json",
            success: function (response) {
                if (response.success == true) {
                    Myalert.deleted();
                    window.location.reload()
                }
            }
        });
    })
}

function reinitialize(elem) {
    const id = elem.getAttribute("data-id");

    Myalert.delete('Etes-vous sur de réinitialiser se profil ?');

    $('#confirmeDelete').on('click', function () {
        $.ajax({
            url: base_url("mdpUser"),
            type: "POST",
            data: { id: id },
            dataType: "json",
            success: function (response) {
                if (response.success == true) {
                    window.location.reload()
                }
            },
        });
    })
}

// function DonnerUser(elem) {
//     const form = $(this);
//     $.ajax({
//         url: base_url("DonnerUser"),
//         type: "post",
//         data: {
//             id: elem.getAttribute("data-id"),
//         },
//         dataType: "json",

//     }).done(function (response) {

//         if (response.success) {

//             const data = response.data[0];
//             const pv = response.pv;
//             const postes = response.postes;
//             $("#id-User").val(data.idUser);
//             $("#nom_modif").val(data.nomUser);
//             $("#prenom_modif").val(data.prenomUser);
//             $("#numero_modif").val(data.contact);
//             $("#adresse_modif").val(data.adress);
//             $("#email_modif").val(data.mail);
            
//             // Mettre à jour les cases à cocher des rôles pour la modification
//             if (data.roles) {
//                 let rolesArray = [];
//                 if (Array.isArray(data.roles)) {
//                     rolesArray = data.roles;
//                 } else if (typeof data.roles === 'string') {
//                     rolesArray = data.roles.split(',').map(role => role.trim());
//                 }
                
//                 // Mettre à jour les cases à cocher
//                 $('.role-checkbox-modif').each(function() {
//                     const roleValue = $(this).val();
//                     if (rolesArray.includes(roleValue)) {
//                         $(this).prop('checked', true);
//                     } else {
//                         $(this).prop('checked', false);
//                     }
//                 });
                
//                 // Mettre à jour le champ caché
//                 updateHiddenRoleModif();
//             } else {
//                 // Décocher toutes les cases si aucun rôle
//                 $('.role-checkbox-modif').prop('checked', false);
//                 $("#hidden_role_modif").val("");
//             }

//             let poste_modif = '' ; 

//             for (let i = 0; i < postes.length; i++) {
//                 const element = postes[i];
//                 if  ( data.idposte == element.idposte ){
//                     poste_modif +=`<option value="${element.idposte}">${element.designation_poste}</option>` ;
//                 }
//             }
            
//             for (let i = 0; i < postes.length; i++) {
//                 const element = postes[i];
//                 if  ( data.idposte != element.idposte ){
//                     poste_modif +=`<option value="${element.idposte}">${element.designation_poste}</option>` ;
//                 }
//             }

//             $("#poste_modif").html( poste_modif );
//             let content = ``;

//             for (let i = 0; i < pv.length; i++) {
//                 const pointvente = pv[i];
//                 if (pointvente.idPointVente == data.idPointVente) {
//                     content = `
//                     <option class="pv" value="${pointvente.idPointVente}">
//                         ${pointvente.denomination_pv}
//                     </option>
//                     ` ;
//                 }
//             }
//             for (let i = 0; i < pv.length; i++) {
//                 const pointvente = pv[i];
//                 if (pointvente.idPointVente != data.idPointVente) {
//                     content += `
//                     <option class="pv" value="${pointvente.idPointVente}">
//                         ${pointvente.denomination_pv}
//                     </option>
//                     ` ;
//                 }
//             }

//             $('#pvModif').html(content);
//         } else {
//             Myalert.erreur();
//         }

//     })
//         .fail(function (errorMessage) {
//             console.log(errorMessage);
//         });
// }


function DonnerUser(elem) {
    const id = elem.getAttribute("data-id");

    $.ajax({
        url: base_url("DonnerUser"),
        type: "POST",
        data: { id: id },
        dataType: "json",
    })
    .done(function (response) {
        if (response.success) {
            const data    = response.data;       // ← maintenant c'est l'objet direct (pas data[0])
            const projets = response.projets;    // ← liste des projets (remplace pv)
            const postes  = response.postes;

            // Champs texte
            $("#id-User").val(data.idUser || '');
            $("#nom_modif").val(data.nomUser || '');
            $("#prenom_modif").val(data.prenomUser || '');
            $("#numero_modif").val(data.contact || '');
            $("#adresse_modif").val(data.adress || '');
            $("#email_modif").val(data.mail || '');

            // Rôles (checkboxes) → inchangé
            if (data.roles) {
                let rolesArray = [];
                if (Array.isArray(data.roles)) {
                    rolesArray = data.roles;
                } else if (typeof data.roles === 'string') {
                    rolesArray = data.roles.split(',').map(role => role.trim());
                }

                $('.role-checkbox-modif').each(function() {
                    const roleValue = $(this).val();
                    $(this).prop('checked', rolesArray.includes(roleValue));
                });

                updateHiddenRoleModif();
            } else {
                $('.role-checkbox-modif').prop('checked', false);
                $("#hidden_role_modif").val("");
            }

            // Poste → simplifié (une seule boucle)
            let posteHtml = '';
            postes.forEach(poste => {
                const selected = (poste.idposte == data.idposte) ? ' selected' : '';
                posteHtml += `<option value="${poste.idposte}"${selected}>${poste.designation_poste}</option>`;
            });
            $("#poste_modif").html(posteHtml);

            // Projet → remplace l'ancien code pv
            let projetHtml = '<option value="">-- Sélectionner un projet --</option>';

            if (projets && projets.length > 0) {
                projets.forEach(projet => {
                    const selected = (projet.idprojet == data.idprojet) ? ' selected' : '';
                    let label = projet.codeprojet || '—';
                    if (projet.titreprojet) {
                        label += ` — ${projet.titreprojet}`;
                    }
                    projetHtml += `<option value="${projet.idprojet}"${selected}>${label}</option>`;
                });
            } else {
                projetHtml += '<option value="" disabled>Aucun projet disponible</option>';
            }

            $("#projetModif").html(projetHtml);
        } else {
            Myalert.erreur("Impossible de charger les informations de l'utilisateur.");
        }
    })
    .fail(function (errorMessage) {
        console.log("Erreur AJAX DonnerUser :", errorMessage);
        Myalert.erreur("Erreur de connexion au serveur.");
    });
}
// Fonction pour mettre à jour le champ caché des rôles (AJOUT)
function updateHiddenRole() {
    const selectedRoles = [];
    $('.role-checkbox:checked').each(function() {
        selectedRoles.push($(this).val());
    });
    
    if (selectedRoles.length > 0) {
        $("#hidden_role").val(selectedRoles.join(','));
    } else {
        $("#hidden_role").val("");
    }
}

// Fonction pour mettre à jour le champ caché des rôles (MODIFICATION)
function updateHiddenRoleModif() {
    const selectedRoles = [];
    $('.role-checkbox-modif:checked').each(function() {
        selectedRoles.push($(this).val());
    });
    
    if (selectedRoles.length > 0) {
        $("#hidden_role_modif").val(selectedRoles.join(','));
    } else {
        $("#hidden_role_modif").val("");
    }
}

// Écouter les changements sur les cases à cocher des rôles (AJOUT)
$(document).on('change', '.role-checkbox', function() {
    updateHiddenRole();
});

// Écouter les changements sur les cases à cocher des rôles (MODIFICATION)
$(document).on('change', '.role-checkbox-modif', function() {
    updateHiddenRoleModif();
});

$(document.body).on("click", "#modifier", function () {
    // Mettre à jour les rôles avant de vérifier
    updateHiddenRoleModif();
    
    $.ajax({
        url: base_url("verifUser"),
        type: "post",
        dataType: "json",
        data: {
            numero: $("#numero_modif").val(),
            email: $("#email_modif").val(),
            id: $("#id-User").val(),
        },
    }).done(function (data) {
        if (data.success) {
            $("#modification").click();
        } else {
            if (data.numExiste) {
                $("#numero_modif").css("border", "1px solid red");
                $("#msg-num-modif").removeClass("d-none");
                $("#msg-mail-modif").addClass("d-none");
            } else {
                $("#numero_modif").css("border", "");
            }

            if (data.mailExiste) {
                $("#email_modif").css("border", "1px solid red");
                $("#msg-mail-modif").removeClass("d-none");
                $("#msg-num-modif").addClass("d-none");
            } else {
                $("#email_modif").css("border", "");
            }

        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
    });
});

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

    // Initialiser les rôles au chargement
    updateHiddenRole();
    updateHiddenRoleModif();
    
    // Mettre à jour les rôles avant soumission du formulaire d'ajout
    $('form[action="<?= base_url(\'registerUser\') ?>"]').on('submit', function() {
        updateHiddenRole();
    });
    
    // Mettre à jour les rôles avant soumission du formulaire de modification
    $('#modifClient').on('submit', function() {
        updateHiddenRoleModif();
    });
    

});