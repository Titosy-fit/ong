$('#codeprojet').focus();
function select_budget(idactivite = '', idbudget = []) {
    let content = '';
    let recherche_tab = `
					<table class="table table">
						<thead class="table-info">
							<tr>
								<th>Choix</th>
								<th>Désignation</th>
								<th>Budget</th>
							</tr>
						</thead>
		`;
    $.ajax({
        url: base_url('Budget/getProduitByactivite_json'),
        type: "post",
        data: { idactivite: idactivite },
        dataType: 'json',
        async: false
    }).done(function (response) {
        if (response.success) {
            const datas = response.datas

            recherche = recherche_tab;
            recherche += `	
						<tbody >
						` ;

            for (let i = 0; i < datas.length; i++) {
                const element = datas[i];

                recherche += `
							<tr >
								<td> 
									<input class='choix_budget form-check-input' data-check='${idbudget.includes(element.idbudget) ? true : false}' type="checkbox" name='budget' data-id='${element.idbudget}' data-designation='${element.designation_budget}' data-montant='${element.budget}' ${idbudget.includes(element.idbudget) ? 'checked' : ''}>
								</td>
								<td>${element.designation_budget}</td>
								<td>${element.budget}</td>`;
            }
            recherche += `	
						</tbody>
						</table>
						<button data-id='' class="btn btn-info btn-sm" data-bs-dismiss="modal" id="budget_valide">VALIDER</button>`  ;
            content = recherche;
        } else {
            content = `<p class="text-secondary">Aucun budget trouvé</p>
            <button  class="btn btn-info btn-sm " id="retourn_budget">Retour</button>`;
        }
    });
    return content;
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

function check_budgete() {
    let checks = $('.choix_budget');
    let content = ``;
    let idbudget = ``;
    let somme = 0;
    for (let i = 0; i < checks.length; i++) {
        const element = checks[i];
        if ($(element).attr('data-check') == 'true') {
            let id = $(element).data('id');
            let designation = $(element).data('designation');
            let montant = parseInt($(element).data('montant'));
            somme += montant;
            content += `
                    <div class="budget_contain p-2 bg-light">
                        <div class="budget_designation">
                            <p class="">${designation}</p>
                        </div>
                        <div class="budget_montant">
                            <p>${montant.toLocaleString("fr-FR")} Ar</p>
                        </div>
                        <div class="btn_delete ">
                            <span class="text-danger delete_budget" data-id='${id}'><i class="fa-solid fa-trash"></i></span>
                        </div>
                    </div>` ;
            idbudget += id + ',';
        }
    }
    content += `<input name="idbudget" id="idbudget" type="text" class="form-control input_form-control d-none" value='${idbudget}' readonly>`
    $('#budget_container').html(content);
    $('#avance').val(somme);
}

$(document).ready(function () {

    /**
     * change du choix de budget
     */
    $(document).on('change', '.choix_budget', function () {
        let isckeced = $(this).data('check');
        const elem = $(this);
        if (isckeced) {
            // decocher
            $(elem).attr('data-check', false)
        }
        else {
            // cocher
            $(elem).attr('data-check', true)
        }
    })

    /**
     * valider le choix du budget 
     */
    $(document.body).on('click', '#budget_valide', function () {
        check_budgete();
    })

    /**
     * effacer un budget selectionner
     */
    $(document).on('click', '.delete_budget', function () {
        let idbudget = $(this).data('id');
        let check = $('.choix_budget[data-id="' + idbudget + '"]');
        $(check).attr('data-check', false);
        check_budgete();
    })

    /**
     * selectionner les budgets
     */
    $(document).on('click', '#select_budget', function () {
        const idactivite = $('#activite').val();
        let content = `<p class="text-danger">Veuillez choisir le projet et l'activité.</p>`;
        if (idactivite !== null) {
            $('#ms_projet').addClass('d-none');
            $('#budget_modal').html(LOADER);
            const idbudget = $('#idbudget').val();
            if (idbudget != null) {
                let tab_idbudget = idbudget.split(',')
                content = select_budget(idactivite, tab_idbudget);
            }
            else {
                content = select_budget(idactivite);
            }


            $('#active_buget_modal').click();
        } else {
            $('#ms_projet').removeClass('d-none');
        }
        $('#budget_modal').html(content);
    })
    /**
     * change du code proget 
     */
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
                    $('#activite').html(content);
                } else {
                    Myalert.erreur('Ce code de projet n\'existe pas.');
                }
            }).fail(function () {
                console.error('Erreur dans la verification du mission ');
            })
        }
    })


    $(document.body).on('click', '#valider', function () {
        const asm = $('#asm').val();
        const om = $('#om').val();
        $.ajax({
            type: "post",
            url: base_url('Mission/verify'),
            data: {
                asm: asm,
                om: om
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#om_sm').addClass('d-none');
                    $('#ams_sm').addClass('d-none');

                    if ($('#titreprojet').val() != '' && $('#activite').val() != '' && $('#objet').val() != '' && $('#asm').val() != '' && $('#om').val() != '' && $('#date_debut').val() != '' && $('#date_fin').val() != '' && $('#idbudget').val() != null) {
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
                        if ($('#idbudget').val() == null) {
                            Myalert.erreur('Veuillez sélectionner les budgets utilisés');
                        }
                    }
                } else {
                    if (response.type == 'om') {
                        $('#om_sm').removeClass('d-none');
                    } else if (response.type == 'asm') {
                        $('#ams_sm').removeClass('d-none');
                    } else {
                        $('#om_sm').removeClass('d-none');
                        $('#ams_sm').removeClass('d-none');
                    }
                }
            }
        });
    })


    /**
     * choisire le demandeur de l'argent 
     */
    $(document).on('click', '#select_demandeur', function () {
        $('#demandeur_modal').html(recherche_agent_content());
    })
    $(document).on('change', '.choix_beneficiaire', function () {
        $('#search_valide').attr('data-id', $(this).data('id'));
    })
    $(document).on('click', '#search_valide', function () {
        const idagent = $(this).data('id');
        $('#idagent').val(idagent);
        if ($('#idagent').val() != '') {
            $('#T-valider').attr('type', 'submit');
            $('#T-valider').click();
        } else {
            shwoSpinner('#valider', [], false);
            Myalert.erreur('Veuillez choisir un agent.');
        }
    })
    $(document).on('click', '#recherche_beneficiaire', function () {
        $('#demandeur_modal').html(recherche_agent_content($('#beneficiaire_search').val()));
    })
    $(document).on('click', '#retourn', function () {
        $('#demandeur_modal').html(recherche_agent_content());
    })
    /**
     * choisire le demandeur de l'argent 
     */

    $(document).on('click', '.edit', function () {
        const idmission = $(this).data('id');
        $('#om_sm_modif').addClass('d-none');
        $('#ams_sm_modif').addClass('d-none');
        $.ajax({
            method: 'post',
            url: base_url('Mission/donner'),
            data: { idmission: idmission },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const data = response.data;
                $('#idmission').val(data.idmission);
                $('#objet_modif').val(data.objet_mission);
                $('#lieu_modif').val(data.lieu_mission);
                $('#asm_modif').val(data.numero_asm);
                $('#om_modif').val(data.numero_om);

                const date_debut_mission = new Date(data.date_debut_mission);
                const formattedDate_debut = date_debut_mission.toISOString().split('T')[0];

                const date_fin_mission = new Date(data.date_fin_mission);
                const formattedDate_fin = date_fin_mission.toISOString().split('T')[0];

                $('#date_debut_modif').val(formattedDate_debut);
                $('#date_fin_modif').val(formattedDate_fin);

                $('#id_modif').val(data.idmission);
            } else {
                Myalert.erreur();
            }

        }).fail(function () {
        })
    })

    /**
     * details mision
     */
    $(document).on('click', '.detail', function () {
        $('#details_modal_containt').html(LOADER);
        const id = $(this).data('id');
        $.ajax({
            type: "post",
            url: base_url('Mission/details'),
            data: {
                idmission: id
            },
            success: function (response) {
                $('#details_modal_containt').html(response);
            }
        });
    })


    /**
     * delete mission
    */
    $(document).on('click', '.delete', function () {
        const idmission = $(this).data('id');
        Myalert.delete();

        $('#confirmeDelete').click(function () {
            $.ajax({
                method: 'post',
                url: base_url('Mission/delete'),
                data: { idmission: idmission }
            }).done(function () {
                location.href = base_url('mission');
            }).fail(function () {
                console.error('erreur dans la suppression du mission');
            })
        })
    })

    $(document).on('click', '#modification', function () {
        let idmission = $('#idmission').val();
        let asm = $('#asm_modif').val();
        let om = $('#om_modif').val();
        $.ajax({
            method: "post",
            url: base_url('Mission/verify'),
            data: {
                asm: asm,
                om: om,
                idmission: idmission
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#om_sm_modif').addClass('d-none');
                    $('#ams_sm_modif').addClass('d-none');

                    $('#TM-valider').attr('type', 'submit');
                    $('#TM-valider').click();

                } else {
                    $('TM-valider').attr('type', 'button');
                    if (response.type == 'om') {
                        $('#om_sm_modif').removeClass('d-none');
                    } else if (response.type == 'asm') {
                        $('#ams_sm_modif').removeClass('d-none');
                    } else {
                        $('#om_sm_modif').removeClass('d-none');
                        $('#ams_sm_modif').removeClass('d-none');
                    }
                }
            }
        })
    })
})