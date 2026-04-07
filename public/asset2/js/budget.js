$('#designation').focus();
$(document).ready(function () {
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
                    $('#designation').focus() ; 
                    $('#activite').html(content);
                } else {
                    Myalert.erreur('Ce code de projet n\'existe pas.');
                }
            }).fail(function () {
                console.error('Erreur dans la verification du activite ');
            })
        }
    });


    $(document.body).on('click', '#valider', function () {
        const designation = $('#designation').val()
        const activite = $('#activite').val()

        $.ajax({
            method: 'post',
            url: base_url('Budget/verify'),
            data: {
                designation: designation,
                idactivite: activite
            },
            dataType: 'json'
        }).done(function (response) {

            if (response.success) {
                $('#t-valider').attr('type', 'submit');
                $('#t-valider').click();
                $('#ms-designation').addClass('d-none');
                shwoSpinner($('#valider'), ['designation', 'budget']);

            } else {
                $('#ms-designation').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du budget ');
        })
    })

    // modification 
    $(document).on('click', '.edit', function () {
        const idbudget = $(this).data('id');
        $.ajax({
            method: 'post',
            url: base_url('Budget/donner'),
            data: { idbudget: idbudget },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const data = response.data;
                const activites = response.activites;


                $('#id_modif').val(data.idbudget);
                $('#designation_modif').val(data.designation_budget);
                $('#budget_modif').val(data.budget);

                let content = ``;
                for (let i = 0; i < activites.length; i++) {
                    const element = activites[i];
                    if (element.idactivite == data.idactivite) {
                        content += `<option value="${element.idactivite}">${element.titre_activite}</option>`
                    }
                }
                for (let i = 0; i < activites.length; i++) {
                    const element = activites[i];
                    if (element.idactivite != data.idactivite) {
                        content += `<option value="${element.idactivite}">${element.titre_activite}</option>`
                    }
                }

                $('#activite_modif').html(content);

                $('#id_modif').val(data.idbudget);
            } else {
                Myalert.erreur();
            }

        }).fail(function () {
        })
    })


    $(document).on('click', '#modifier', function () {
        const designation = $('#designation_modif').val();
        const idactivite = $('#activite_modif').val();
        const idbudget = $('#id_modif').val();

        $.ajax({
            method: 'post',
            url: base_url('Budget/verify'),
            data: {
                designation: designation,
                idactivite: idactivite,
                idbudget: idbudget
            },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#modification').attr('type', 'submit');
                $('#modification').click();
                $('#ms-designation_modif').addClass('d-none');
            } else {
                $('#modification').attr('type', 'button');
                $('#ms-designation_modif').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du budget ');
        })
    })

    $(document).on('click', '.delete', function () {
        const idbudget = $(this).data('id');
        Myalert.delete();

        $('#confirmeDelete').click(function () {
            $.ajax({
                method: 'post',
                url: base_url('Budget/delete'),
                data: { idbudget: idbudget }
            }).done(function () {
                location.href = base_url('budget');
            }).fail(function () {
                console.error('erreur dans la suppression du budget');
            })
        })


    })
})