
$('#designation').focus() ; 
$(document).ready(function () {
    $(document.body).on('click', '#valider', function () {
        const designation = $('#designation').val();
        //   teste du titre sur la base de donner    
        $.ajax({
            method: 'post',
            url: base_url('Poste/verify'),
            data: { designation: designation },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#t-valider').attr('type', 'submit');
                $('#t-valider').click();
                $('#ms-designation').addClass('d-none');
                shwoSpinner($('#valider'), ['designation', 'description']);
            } else {
                $('#ms-designation').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du activite ');
        })
    })

    // modification 
    $(document).on('click', '.edit', function () {
        const idposte = $(this).data('id');
        $.ajax({
            method: 'post',
            url: base_url('Poste/donner'),
            data: { idposte: idposte },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const data = response.data;
                // const projets = response.projets;
                $('#designation_modif').val(data.designation_poste);
                $('#description_modif').val(data.description_poste);
                $('#idposte').val(data.idposte);

                // if ( data.designation_poste.toUpperCase() == 'COMPTABLE' || data.designation_poste.toUpperCase() == 'AGENT' ){
                //     $('#designation_modif').attr('readonly' , '') ; 
                // }else {
                //     $('#designation_modif').removeAttr('readonly') ; 
                // }
            } else {
                Myalert.erreur();
            }

        }).fail(function () {
        })
    })
    $(document).on('click', '#modifier', function () {
        const designation = $('#designation_modif').val();
        const idposte = $('#idposte').val();

        $.ajax({
            method: 'post',
            url: base_url('Poste/verify'),
            data: {
                designation: designation,
                idposte: idposte
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
            console.error('Erreur dans la verification du activite ');
        })
    })

    $(document).on('click', '.delete', function () {
        const idposte = $(this).data('id');
        Myalert.delete();

        $('#confirmeDelete').click(function () {
            $.ajax({
                method: 'post',
                url: base_url('Poste/delete'),
                data: { idposte: idposte }
            }).done(function () {
                location.href = base_url('poste');
            }).fail(function () {
                console.error('erreur dans la suppression du activite');
            })
        })


    })
})