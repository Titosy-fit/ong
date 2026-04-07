function vider( ){
    $('#numero_om').val('') ; 
}
 
let reliquat = 0 ;
let avanceTotal = 0 ;
/**
 * affichage de l'image 
 * @param {*} input 
 */
function afficheImage(input) {
    Myalert.spinnerB();
    if (input.files && input.files[0]) {
        $('#loading').removeClass('d-none');
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#image").attr("src", e.target.result);
        };

        $("#btn_show_image").click();

        reader.readAsDataURL(input.files[0]);

        setTimeout(function () {
            $('#loading').addClass('d-none');
        }, 100)
    }
    Myalert.removeSpinnerB();
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
/**
 * Verification si les valeure dans les champs son compelt
 * @param {*} i_j 
 * @returns Bool
 */
function verification_champ(i_j = '') {
    let return_resp = true;
    if (i_j == '') {
        // si au moin un depense a ete tout rempli , on valide 
        let des = $('.desigantion_par_budget"] ');
        return_resp = false;
        for (let i = 0; i < des.length; i++) {
            const designation_atuel = des[i];
            const i_j = $(element).data('i_j');
            const des_val = $(designation_atuel).val();
            const montant_val = $('.montant_par_budget[data-i_j="' + i_j + '"] ').val();
            const justification_val = $('.justification_par_budget[data-i_j="' + i_j + '"] ').val();

            if (des_val != '' && montant_val != '' && justification_val != '') {
                return true;
            }
        }

    } else {
        let des = $('.desigantion_par_budget[data-i_j="' + i_j + '"] ').val();
        if (des == '') {
            $('#designation_ms_' + i_j).removeClass('d-none');
            return_resp = false;
        } else {
            $('#designation_ms_' + i_j).addClass('d-none');
        }
        let jus = $('.justification_par_budget[data-i_j="' + i_j + '"] ').val();
        if (jus == '') {
            $('#justification_ms_' + i_j).removeClass('d-none');
            return_resp = false;
        } else {
            $('#justification_ms_' + i_j).addClass('d-none');
        }
    }

    return return_resp;
}

/**
 * calcule de la total dépensé
 * @param {*} i 
 */
function calcule_total_depense(i = '') {
    let montant_champs = $('.montant_par_budget_' + i);
    let total_montant = 0;
    for (let k = 0; k < montant_champs.length; k++) {
        const element = montant_champs[k];
        let i_j = $(element).data('i_j');
        if (verification_champ(i_j)) {
            let montant = parseFloat($(element).val());
            total_montant += montant;
        }
    }
    $('#total_depenser_' + i).val('Total dépensé :  ' + total_montant.toLocaleString("fr-FR") + ' Ar');
    $('#total_depenser_val_' + i).val(total_montant);


    calcule_total_reliquat();
}


/**
 * calcule de totale reliquat
 */
function calcule_total_reliquat() {
    const input_depenser = $('.total_depense');
    let montant_total = 0;
    for (let i = 0; i < input_depenser.length; i++) {
        const element = input_depenser[i];
        montant_total += parseFloat($(element).val())
    }

    const total_budget = parseFloat($('#total_budget').val());


    $('#reliquat').val((total_budget - montant_total).toLocaleString("fr-FR") + ' Ar');
    $('#_depenser').val(montant_total.toLocaleString("fr-FR") + ' Ar');
    $('#reliquat_val').val((total_budget - montant_total));
    $('#_depenser_val').val(montant_total);
}

/**
 * remettre les montant vide a 0 ; 
 */
function set_montant_null_to_0() {
    const montant = $('.montant_par_budget');
    for (let i = 0; i < montant.length; i++) {
        const element = montant[i];
        if ($(element).val() == '') {
            $(element).val(0);
        }
    }
}

/**
 * contenue dans le depense par budget container 
 * @param {*} i 
 * @returns 
 */
function depense_content(i = 0, j = 0) {
    let content = `<div class="row">
                        <div class="col">
                            <label class="form-label">Motif de la dépense</label>
                            <input type="text" class="form-control desigantion_par_budget" name="designation[${i}][${j}]" data-i_j='${i}_${j}' value='' >
                            <p class="text-info d-none" id="designation_ms_${i}_${j}">Désignation obligatoire!</p>
                        </div>
                        <div class="col">
                            <label class="form-label">Montant dépensé : </label>
                            <input type="number" min='0'  id='${i == 0 ? ' focus' : ''}' class="form-control montant_par_budget montant_par_budget_${i}" name="montant[${i}][${j}]" data-i_j='${i}_${j}' value='0' required>
                        </div>
                       
                        <div class="col">
                            <label class="form-label">Pièce justificative : </label>
                            <input type="file" onchange="afficheImage(this)" class="form-control input_form-control justification_par_budget" name="justification[${i}][${j}]" id="choose_photo" data-i_j='${i}_${j}'>
                            <p class="text-info d-none" id="justification_ms_${i}_${j}">Justification obligatoire!</p>
                        </div>
                    </div>`
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
                    console.log(response)
                    const data = response.data;
                    const infoMission =response.data[0] ;
                    let sommeAvance = parseInt(infoMission.total_avance) ;
                    depenseTotal = infoMission.total_avance ;
                    $('#idmission').val(infoMission.idmission);
                    
                    $("#typeMission").val(infoMission.typeMission);
                    $("#totalAvance").val(sommeAvance.toLocaleString('fr-FR')+" Ar");
                    $('#objet').val(infoMission.objet_mission);
                    $('#nomagent').val(infoMission.nomUser + ' ' + infoMission.prenomUser);
                    $('#idagent').val(infoMission.idagent);
                    


                    let content = `<label class="form-label">Budget(s) : </label>
                                <input type="text" class="form-control d-none"  id="total_budget" value='${data.montant_avance}' readonly >
                    `;
                    for (let i = 0; i < data.length; i++) {
                        const element = data[i];
                        let montant_avance = parseInt(element.montant_avance);

                       
                        avance = montant_avance.toLocaleString('fr-FR');
                        content += `
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button container_entete p-0 text-darck" type="button" data-bs-toggle="collapse"  data-bs-target="#collapse${i}" aria-expanded="true" aria-controls="collapse${i}">
                                    <div class="entete_accord text-darck p-2 buget_designation">Budget : ${element.ligne_budget}  - ${element.designation_budget} | Montant de l'avance : ${avance+" Ar"}</div>
                                    
                                </button>
                            </h2>
                            <div id="collapse${i}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                <input type="number"  class="form-control d-none" name="budget[${i}]"  value='${element.idbudget}'>
                                    <div id='depense_par_budget_container_${i}'>
                                        ${depense_content(i)}
                                    </div>
                                    <div class='_boutton button_par_budget_container' id='button_par_budget_container_${i}'>
                                        <button class="btn btn-info ajouter_depense_par_budget" type="button" data-id='${i}' data-id_budget_inside='0'>
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <button class="btn btn-danger d-none delete_depense_par_budget" type="button" data-id='${i}' >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` ;
                    }

                    $('#budget_container').html(content);
                    $('#focus').focus();
                } else {
                    vider() ; 
                    if (response.type == 'liquide') {
                        Myalert.erreur('vous avez déjà fait une liquidation')
                    } else {
                        Myalert.erreur("N° OM ou N° ASM inconnu")
                    }
                }
            }
        });
    })
    /** ajouter une champs de depense dans le budget  */
    $(document).on('click', '.ajouter_depense_par_budget', function () {
        const id = $(this).data('id');
        const id_budget_inside = $(this).data('id_budget_inside')

        $(this).data('id_budget_inside', (id_budget_inside + 1))
        $('#depense_par_budget_container_' + id).append(depense_content((id), (id_budget_inside + 1)));
        $('.delete_depense_par_budget[data-id="' + id + '"]').removeClass('d-none');
    })
    $(document).on('click', '.delete_depense_par_budget', function () {
        const delete_ = $(this);
        $('#cancelDelete').click();
        const id = $(delete_).data('id');

        const elem_to_delete = $('#depense_par_budget_container_' + id + ' .row');
        console.log(elem_to_delete);

        $(elem_to_delete[elem_to_delete.length - 1]).remove();

        let = id_budget_inside = $('.ajouter_depense_par_budget[data-id="' + id + '"]').data('id_budget_inside');
        $('.ajouter_depense_par_budget[data-id="' + id + '"]').data('id_budget_inside', id_budget_inside - 1);

        if (elem_to_delete.length == 2) {
            $(delete_).addClass('d-none');
        }
        calcule_total_depense(id);
    })

    $(document).on('change keyup', '.montant_par_budget , .justification_par_budget ,.desigantion_par_budget  ', function () {
        /*const element = $(this);
        const i_j = $(element).data('i_j');
        let i = i_j.split('_')[0];
        calcule_total_depense(i);*/
        
       let totalMontant = 0;
        $('.montant_par_budget').each(function () {
            
            let val = parseFloat($(this).val()) || 0;
            if (!isNaN(parseFloat(val)) && isFinite(val)) {

                totalMontant += val;
                reliquat = depenseTotal-totalMontant ;
                $("#_depenser").val(totalMontant.toLocaleString('fr-FR')+" Ar") ;
                $("#_depenser_val").val(totalMontant) ;
                $("#reliquat").val(reliquat.toLocaleString('fr-FR')+" Ar") ;
                 
                $("#resteHidden").val(reliquat) ;

                $("#nonRetournerHidden").val(reliquat) ;
                $("#nonRetourner").val(reliquat.toLocaleString('fr-FR')+" Ar") ;
                
             }
            
        });
        
        console.log("Montant total:", totalMontant);
    })
    $(document.body).on('click', '#valider', function () {
        let nom = $('#nomagent').val();
        if (nom != '' && verification_champ) {
            set_montant_null_to_0();
            shwoSpinner('#valider', []);
            $('#T-valide').attr('type', 'submit');
            $('#T-valide').click();
        }
    })

    // $(document).on('click', '.delete', function () {
    //     let id = $(this).data('id');

    //     Myalert.delete();
    //     $(document.body).on('click', '#confirmeDelete', function () {
    //         console.log(id);
    //         $.ajax({
    //             method: 'POST',
    //             url: base_url('Depense/deleteit'),
    //             data: { id: id },
    //             dataType: 'json'
    //         }).done(function (data) {
    //             if (data.success) {
    //                 window.location.reload();
    //             }
    //         }).fail(function () {
    //             console.error('Erreur dans le suppression');
    //         })
    //     })
    // })


    // $(document.body).on('click', '.edit', function () {
    //     let montant = $(this).data('montant');
    //     let raison = $(this).data('raison');
    //     let id = $(this).data('id');

    //     $('#iddepensemodif').val(id);
    //     $('#montant_').val(montant);
    //     $('#raison_').val(raison);
    // })
    // $(document.body).on('click', '#modifier', function () {
    //     let montant = $('#montant_').val()
    //     let raison = $('#raison_').val()

    //     if (montant != '' && raison != '') {
    //         $(this).attr('type', 'submit');
    //         $(this).click();
    //     }
    // })



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
    // })
    $(document).on('click', '#image_ok', function () {
        $('#imageModal').remove();
        $('.modal-backdrop').remove();
    })

    $(document.body).on("click", ".detail", function () {
        const idliquidation = $(this).data("id");
        $.ajax({
            url: base_url("Liquidation/details"),
            type: "post",
            data: {
                idliquidation: idliquidation,
            },
        }).done(function (data) {
            $("#tab").html(data);
        });
    });

    $(document.body).on('click', ".supprimer", function () {
        let idliquidation = $(this).data('id');
        const elem = $(this);
        Myalert.delete();
        $('#confirmeDelete').on('click', function () {
            $('.close').click();
            $.ajax({
                method: 'post',
                url: base_url('Liquidation/delete'),
                data: { idliquidation: idliquidation },
                dataType: 'json',

            }).done(function (response) {
                if (response.success == true) {
                    $(elem).closest('tr').remove();
                    Myalert.deleted();
                }
            }).fail(function () {
                console.log('error');
            })
        })
    });
    $(document.body).on('input change', "#retourner", function () {
        let montantRetour = parseInt($(this).val()) ;
        if (!isNaN(parseFloat(montantRetour)) && isFinite(montantRetour)) {
            let reste = parseInt($("#resteHidden").val()) ; 
            let nonRetour = reste-montantRetour ;
            $("#nonRetourner").val(nonRetour.toLocaleString('fr-FR')+" Ar") ;
            $("#nonRetournerHidden").val(nonRetour) ;
        }
        else {
            $("#nonRetourner").val("0") ;
        }
    });
})



