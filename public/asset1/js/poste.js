
$('#designation').focus() ; 
$(document).ready(function () {
    $(document.body).on('click', '#valider', function () {
        const designation = $('#designation').val();
        //   teste du titre sur la base de donner    
        $.ajax({
            method: 'post',
            url: base_url('/verify'),
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
        const id = $(this).data('id');
        $.ajax({
            method: 'post',
            url: base_url('/donner'),
            data: { id: id },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const data = response.data;
                // const projets = response.projets;
                $('#designation_modif').val(data.designation_);
                $('#description_modif').val(data.description_);
                $('#id').val(data.id);
            } else {
                Myalert.erreur();
            }

        }).fail(function () {
        })
    })
    $(document).on('click', '#modifier', function () {
        const designation = $('#designation_modif').val();
        const id = $('#id').val();

        $.ajax({
            method: 'post',
            url: base_url('/verify'),
            data: {
                designation: designation,
                id: id
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
        const id = $(this).data('id');
        Myalert.delete();

        $('#confirmeDelete').click(function () {
            $.ajax({
                method: 'post',
                url: base_url('/delete'),
                data: { id: id }
            }).done(function () {
                location.href = base_url('');
            }).fail(function () {
                console.error('erreur dans la suppression du activite');
            })
        })


    })
})