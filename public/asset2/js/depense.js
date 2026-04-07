function afficheImage(input) {
    if (input.files && input.files[0]) {
        $('#loading').removeClass('d-none');
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#image").attr("src", e.target.result);
            $("#image").css({
                width: "100%",
                height: "100%",
                objectFit: 'contain',
                position: 'absolute'
            });
        };
        reader.readAsDataURL(input.files[0]);
        setTimeout(function () {
            $('#loading').addClass('d-none');
        }, 100)
    }
}

function recherche_agent_content(recherche = '') {
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
								<th>Numéro Télephone</th>
								<th>Email</th>
							</tr>
						</thead>
		`;
    $.ajax({
        url: base_url('User/search_json'),
        type: "post",
        data: { recherche: recherche, type: 'Agent' },
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
									<input class='choix_beneficiaire' type="radio" name='beneficiaire' data-id='${element.idUser}' data-nom = '${element.nomUser}' data-prenom = '${element.prenomUser}'>
								</td>
								<td>${element.nomUser}</td>
								<td>${element.prenomUser}</td>
								<td>${element.adress}</td>
								<td>${element.contact}</td>`;
                if (element.mailbene != '') {
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
						<button data-nom="" data-prenom="" data-tel="" class="btn btn-info btn-sm " id="search_valide">VALIDER</button>`  ;
            content = recherche;
        } else {
            content = `<p class="text-secondary">Aucun agent trouvé</p>
            <button  class="btn btn-info btn-sm " id="retourn">Retour</button>`;
        }
    });
    return content;
}

$(document).ready(function () {

    $(document).on('change', '#numero_om', function () {
        const om = $(this).val();
        $.ajax({
            type: "post",
            url: base_url('Mission/getData_json'),
            data: {
                om: om
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    $('#idmission').val(data.idmission);
                    $('#numero_om').val(data.numero_om);
                    $('#titre_activite').val(data.titre_activite);
                    $('#objet').val(data.objet_mission);
                    $('#nomagent').val(data.nomagent + ' ' + data.prenomagent);

                    
                } else {
                    Myalert.erreur('Le numéro d\'ordre de mission n\'existe pas.')
                }
            }
        });
    })




    $(document.body).on('click', '#valider', function () {
        if ($('#raison').val() != '' && $('#montant').val() != '' && $('#budget').val() != '' && $('#justification').val() != '') {
            shwoSpinner('#valider', []);

            $('#select_demandeur').click();

            $('#budget-ms').addClass('d-done');
            $('#justification_ms').addClass('d-done');
        }
        else {
            if ($('#budget').val() == '') {
                $('#budget-ms').removeClass('d-none');
            }
            if ($('#justification').val() == '') {
                $('#justification_ms').removeClass('d-none');
            }
        }
    })


    // /**
    //  * choisire le demandeur de l'argent 
    //  */
    // $(document).on('click', '#select_demandeur', function () {
    //     $('#demandeur_modal').html(recherche_agent_content());
    // })
    // $(document).on('change', '.choix_beneficiaire', function () {
    //     $('#search_valide').attr('data-id', $(this).data('id'));
    // })
    // $(document).on('click', '#search_valide', function () {
    //     const idagent = $(this).data('id');
    //     $('#idagent').val(idagent);

    //     if ($('#idagent').val() != '') {
    //         $('#T-valider').attr('type', 'submit');
    //         $('#T-valider').click();
    //     } else {
    //         shwoSpinner('#valider', [], false);
    //     }
    // })
    // $(document).on('click', '#recherche_beneficiaire', function () {
    //     $('#demandeur_modal').html(recherche_agent_content($('#beneficiaire_search').val()));
    // })
    // $( document ).on('click' , '#retourn' , function(){
    //     $('#demandeur_modal').html(recherche_agent_content());
    // })
    // /**
    //  * choisire le demandeur de l'argent 
    //  */

    $(document).on('click', '.delete', function () {
        let id = $(this).data('id');

        Myalert.delete();
        $(document.body).on('click', '#confirmeDelete', function () {
            console.log(id);
            $.ajax({
                method: 'POST',
                url: base_url('Depense/deleteit'),
                data: { id: id },
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    window.location.reload();
                }
            }).fail(function () {
                console.error('Erreur dans le suppression');
            })
        })
    })


    $(document.body).on('click', '.edit', function () {
        let montant = $(this).data('montant');
        let raison = $(this).data('raison');
        let id = $(this).data('id');

        $('#iddepensemodif').val(id);
        $('#montant_').val(montant);
        $('#raison_').val(raison);
    })
    $(document.body).on('click', '#modifier', function () {
        let montant = $('#montant_').val()
        let raison = $('#raison_').val()

        if (montant != '' && raison != '') {
            $(this).attr('type', 'submit');
            $(this).click();
        }
    })


    //     $(document).on('change', '#activite', function () {
    //         const idactivite = $(this).val();
    //         $('#budget').html(`<option value=""><span class="spinner-grow spinner-grow-sm"></span>
    //   En attente...</option>`);
    //         $.ajax({
    //             method: 'POST',
    //             url: base_url('Budget/getByactivite'),
    //             data: { idactivite: idactivite },
    //             dataType: 'json'
    //         }).done(function (response) {
    //             console.log(response);

    //             let content = ``;
    //             if (response.success) {
    //                 for (let i = 0; i < response.datas.length; i++) {
    //                     const element = response.datas[i];
    //                     content += `<option value="${element.idbudget}">${element.designation_budget}</option>`
    //                 }
    //             }
    //             else {
    //                 content += `<option value="">Aucun budget trouvé pour cette activité.</option>`
    //             }
    //             $('#budget').html(content);


    //         }).fail(function () {
    //             console.error('Erreur dans le recuperation des budgets');
    //         })
    //     })
})


