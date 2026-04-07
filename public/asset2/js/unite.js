$( document ).ready( function (){
    $( document ).on('change' , '#reference' , function (){
        const reference = $( this ).val() ; 
        $.ajax({
            method: 'post' , 
            url : base_url('Unite/getProduit'),
            data : { reference : reference } , 
            dataType : 'json'
        }).done( function ( response ){
            console.log( response );
            
            if ( response.success ){
                $('#designationmateriel').val( response.data[0].designationmateriel )
                $('#reference').val( response.data[0].refmateriel )
            }else {
                Myalert.erreur('Veuillez vérifier la référence que vous avez saisie.') ; 
            }
        }).fail( function (){
            console.error("Erreur dans la recuperation du produit ");
        })
    })
})