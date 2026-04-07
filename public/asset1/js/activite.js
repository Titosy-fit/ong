
$('#codeprojet').focus() ; 
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
                    $('#titre').focus();
                } else {
                    Myalert.erreur('Ce code de projet n\'existe pas.');
                }
            }).fail(function () {
                console.error('Erreur dans la verification du activite ');
            })
        }
    })



    $(document.body).on('click', '#valider', function () {
        const titre = $('#titre').val();
        //   teste du titre sur la base de donner    
        $.ajax({
            method: 'post',
            url: base_url('Activite/verify'),
            data: { titre: titre },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#t-valider').attr('type', 'submit');
                $('#t-valider').click();
                $('#ms-titre').addClass('d-none');
                shwoSpinner($('#valider'), ['titre', 'date_debut_act', 'date_fin_act', 'projet']);

            } else {
                $('#ms-titre').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du activite ');
        })
    })

    // modification 
    $(document).on('click', '.edit', function () {
        const idactivite = $(this).data('id');
        $.ajax({
            method: 'post',
            url: base_url('Activite/donner'),
            data: { idactivite: idactivite },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const data = response.data;
                // const projets = response.projets;
                $('#titre_modif').val(data.titre_activite);

                const date_debut_act = new Date(data.date_debut_act);
                const formattedDate_debut = date_debut_act.toISOString().split('T')[0];

                const date_fin_act = new Date(data.date_fin_act);
                const formattedDate_fin = date_fin_act.toISOString().split('T')[0];

                $('#date_debut_modif').val(formattedDate_debut);
                $('#date_fin_modif').val(formattedDate_fin);

                // let content = ``;
                // for (let i = 0; i < projets.length; i++) {
                //     const element = projets[i];
                //     if (element.idprojet == data.idprojet) {
                //         content += `<option value="${element.idprojet}">${element.titreprojet}</option>`
                //     }
                // }
                // for (let i = 0; i < projets.length; i++) {
                //     const element = projets[i];
                //     if (element.idprojet != data.idprojet) {
                //         content += `<option value="${element.idprojet}">${element.titreprojet}</option>`
                //     }
                // }

                // $('#projet_modif').html(content);

                $('#id_modif').val(data.idactivite);
            } else {
                Myalert.erreur();
            }

        }).fail(function () {
        })
    })
    $(document).on('click', '#modifier', function () {
        const titre = $('#titre_modif').val();
        const idactivite = $('#id_modif').val();

        $.ajax({
            method: 'post',
            url: base_url('Activite/verify'),
            data: {
                titre: titre,
                idactivite: idactivite
            },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#modification').attr('type', 'submit');
                $('#modification').click();
                $('#ms_titre_modif').addClass('d-none');
            } else {
                $('#modification').attr('type', 'button');
                $('#ms_titre_modif').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du activite ');
        })
    })

    $(document).on('click', '.delete', function () {
        const idactivite = $(this).data('id');
        Myalert.delete();

        $('#confirmeDelete').click(function () {
            $.ajax({
                method: 'post',
                url: base_url('Activite/delete'),
                data: { idactivite: idactivite }
            }).done(function () {
                location.href = base_url('activite');
            }).fail(function () {
                console.error('erreur dans la suppression du activite');
            })
        })


    })
})