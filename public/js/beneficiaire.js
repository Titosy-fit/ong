// $(document).ready(function () {
//     $(document.body).on('click', '#valider', function () {
//         shwoSpinner(this, ['address', 'num', 'nom', 'prenom', 'email', 'num_cin', 'date_cin']);

//         const numbene = $('#num').val();
//         const num_cin = $('#num_cin').val();

//         $.ajax({
//             type: "post",
//             url: base_url('Beneficiaire/verify'),
//             data: {
//                 telbene: numbene,
//                 numcinbene: num_cin
//             },
//             dataType: "json",
//             success: function (response) {
//                 if (response.success) {
//                     $('#t_validation').attr('type', 'submit');
//                     $('#t_validation').click();

//                     $('#ms_error').addClass('d-none') ; 
//                 } else {
//                     shwoSpinner('#valider', [], false);
//                     $('#ms_error').removeClass('d-none') ; 
//                 }
//             }
//         });


//     })

//     // Modification 
//     $(document).on('click', '.edit', function () {
//         const idbeneficiaire = $(this).data('id');
//         $.ajax({
//             type: "post",
//             url: base_url('Beneficiaire/donner'),
//             data: {
//                 idbeneficiaire: idbeneficiaire
//             },
//             dataType: "json",
//             success: function (response) {
//                 if (response.success) {
//                     const data = response.data;
//                     $('#idbeneficiaire').val(data.idbeneficiaire);
//                     $('#nom_modif').val(data.nombene);
//                     $('#prenom_modif').val(data.prenombene);
//                     $('#address_modif').val(data.adressebene);
//                     $('#num_modif').val(data.telbene);
//                     $('#email_modif').val(data.mailbene);
//                     $('#num_cin_modif').val(data.numcinbene);

//                     const date = new Date(data.datecinbene);
//                     const formattedDate = date.toISOString().split('T')[0];

//                     $('#date_cin_modif').val(formattedDate);
//                 } else {
//                     Myalert.erreur();
//                 }
//             }
//         });
//     })
//     $(document).on('click', '#modifier', function () {
//         const numbene = $('#num_modif').val();
//         const num_cin = $('#num_cin_modif').val();
//         const idbeneficiaire = $('#idbeneficiaire').val();


//         $('#ms_error_modif').addClass('d-none') ; 

//         $.ajax({
//             type: "post",
//             url: base_url('Beneficiaire/verify'),
//             data: {
//                 telbene: numbene,
//                 numcinbene: num_cin , 
//                 idbeneficiaire: idbeneficiaire , 
//             },
//             dataType: "json",
//             success: function (response) {
//                 if (response.success) {
//                     $('#modification').attr('type' , 'submit') ; 
//                     $('#modification').click() ; 
//                     $('#ms_error_modif').addClass('d-none') ; 
//                 } else {
//                     $('#modification').attr('type' , 'button') ; 
//                     $('#ms_error_modif').removeClass('d-none') ; 
//                 }
//             }
//         });
//     })


//     // delete 
//     $( document ).on('click' , '.delete' , function (){
//         const idbeneficiaire = $( this ).data('id') ;
        
//         Myalert.delete("Êtes-vous sûr de vouloir supprimer ?", function () {
//             $.ajax({
//                 type: "post",
//                 url: base_url('Beneficiaire/delete'),
//                 data: {
//                     idbeneficiaire : idbeneficiaire 
//                 },
//                 dataType: "json",
//                 success: function (response) {
//                     if ( response.success ){
//                         location.reload() ; 
//                     }else {
//                         Myalert.erreur() ; 
//                     }
//                 }
//             });
//         });

//     })
// })


