
$(document).ready(function () {
    $(document.body).on('click', '#valider', function () {
        const code = $('#code').val();
        //   teste du code sur la base de donner    
        $.ajax({
            method: 'post',
            url: base_url('Projet/verify'),
            data: { code: code },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#t-valider').attr('type', 'submit');
                $('#t-valider').click();
                $('#ms_code').addClass('d-none');
                shwoSpinner($('#valider'), ['code', 'titre', 'date_debut', 'region', 'region']);

            } else {
                $('#ms_code').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du projet ');
        })
    })

    // modification 
    $(document).on('click', '.edit', function () {
        const idprojet = $(this).data('id');
        $.ajax({
            method: 'post',
            url: base_url('Projet/donner'),
            data: { idprojet: idprojet },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                const data = response.data;
                $('#code_modif').val(data.codeprojet);
                $('#titre_modif').val(data.titreprojet);

                console.log(data.date_debut);


                const date_debut = new Date(data.date_debut);
                const formattedDate_debut = date_debut.toISOString().split('T')[0];

                const date_fin = new Date(data.date_fin);
                const formattedDate_fin = date_fin.toISOString().split('T')[0];

                $('#date_debut_modif').val(formattedDate_debut);
                $('#date_fin_modif').val(formattedDate_fin);
                $('#region_modif').val(data.regionprojet);
                $('#id_modif').val(data.idprojet);
            } else {
                Myalert.erreur();
            }

        }).fail(function () {
        })
    })
    $(document).on('click', '#modifier', function () {
        const codeprojet = $('#code_modif').val();
        const idprojet = $('#id_modif').val();

        $.ajax({
            method: 'post',
            url: base_url('Projet/verify'),
            data: {
                code: codeprojet,
                idprojet: idprojet
            },
            dataType: 'json'
        }).done(function (response) {
            if (response.success) {
                $('#modification').attr('type', 'submit');
                $('#modification').click();
            } else {
                $('#modification').attr('type', 'button');
                $('#ms_code_modif').removeClass('d-none');
            }
        }).fail(function () {
            console.error('Erreur dans la verification du projet ');
        })
    })

  $(document).on('click', '.delete', function () {
    const idprojet = $(this).data('id');
    const $btn = $(this);

    Myalert.delete("Voulez-vous vraiment supprimer ce projet ?", function () {
        $btn.prop('disabled', true)
            .html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: base_url('Projet/delete'),
            method: 'POST',
            data: { idprojet: idprojet },
            success: function () {
                Myalert.deleted();
                location.reload();
            },
            error: function () {
                Myalert.erreur("Erreur lors de la suppression");
            },
            complete: function () {
                $btn.prop('disabled', false)
                    .html('<i class="fa-solid fa-trash"></i>');
            }
        });
    });
});
    
     $(document).on('change', '#choix', function () {
        let val = $(this).val() ; 
        if(val=="importation") {
            $(".data-saisie").hide() ;
            $(".import").show() ;
        }
        else {
            $(".data-saisie").show() ;
            $(".import").hide() ;
        }
        


    })
})