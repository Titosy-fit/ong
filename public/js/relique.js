$(document).ready(function(){

    $(document).on('change', '#numero_om', function () {
        const om = $(this).val();
      
        $.ajax({
            type: "post",
            url: base_url('Mission/getData_json2'),
            data: {
                om: om
            },
            dataType: "json",
            success: function (response) {

                if (response.success) {
                    
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
                    //alert(response.totalDepense) ;
                    let sommeDepense = parseInt(response.totalDepense) ;
                    $("#_depenser").val(sommeDepense.toLocaleString('fr-FR')+" Ar") ;
                    $("#_depenser_val").val(sommeDepense) ;

                    let sommeReturn = parseInt(response.totalRetourne) ;

                     $("#total_return").val(sommeReturn.toLocaleString('fr-FR')+" Ar") ;
                     $("#total_return2").val(sommeReturn) ;

                     $("#idMission").val(parseInt(response.idMission)) ; 
                     $("#idLiquidation").val(parseInt(response.idLiquidation)) ; 

                     let nonRetour = sommeAvance-sommeReturn-sommeDepense ;
                     $("#nonRetour").val(nonRetour.toLocaleString('fr-FR')+" Ar") ;
                     $("#nonRetourHidden").val(nonRetour) ;
                     //reste
                    //
                    if (nonRetour <= 0) {
            Myalert.erreur("Reliquat déjà entièrement payé");

            // On peut vider tous les champs pour plus de clarté
            $('#idmission, #typeMission, #totalAvance, #objet, #nomagent, #idagent, ' +
              '#_depenser, #_depenser_val, #total_return, #total_return2, ' +
              '#idMission, #idLiquidation, #nonRetour, #nonRetourHidden, ' +
              '#aRendre, #reste, #resteHidden').val('');

            $('#valider').prop('disabled', true);
            $('#aRendre').prop('disabled', true);

            return;   // ← on arrête ici, rien d'autre ne s'exécute
        }
                    $('#focus').focus();
                } else {
                    
                    if (response.type == 'liquide') {
                        Myalert.erreur("Vous n'avez pas encore effectué de liquidation")
                    } else {
                        Myalert.erreur("N° OM ou N° ASM inconnu")
                    }
                }
            }
        });
    })
//     $(document).on('change', '#numero_om', function () {
//     const om = $(this).val().trim();
//     if (!om) return;

//     $.ajax({
//         type: "POST",
//         url: base_url('Mission/getData_json2'),
//         data: { om: om },
//         dataType: "json",
//         success: function (response) {
//             if (!response.success) {
//                 if (response.type === 'liquide') {
//                     Myalert.erreur("Vous n'avez pas encore effectué de liquidation");
//                 } else {
//                     Myalert.erreur("N° OM ou N° ASM inconnu");
//                 }
//                 return;
//             }

//             // ────────────────────────────────────────────────
//             // Données de bases
//             // ────────────────────────────────────────────────
//             const infoMission       = response.data[0] || {};
//             const sommeAvance       = parseFloat(infoMission.total_avance) || 0;
//             const sommeDepense      = parseFloat(response.totalDepense)   || 0;

//             // ← Variables corrigées selon la nouvelle structure JSON
//             const totalRetourne     = parseFloat(response.totalRetourneViaRelique) || 0;
//             const reliquatRestant   = parseFloat(response.reliquatRestant) || 0;

//             // Remplissage des champs visibles
//             $('#idmission').val(infoMission.idmission || '');
//             $("#typeMission").val(infoMission.typeMission || '');
//             $("#totalAvance").val(sommeAvance.toLocaleString('fr-FR') + " Ar");
//             $('#objet').val(infoMission.objet_mission || '');
//             $('#nomagent').val((infoMission.nomUser || '') + ' ' + (infoMission.prenomUser || ''));
//             $('#idagent').val(infoMission.idagent || '');

//             $("#_depenser").val(sommeDepense.toLocaleString('fr-FR') + " Ar");
//             $("#_depenser_val").val(sommeDepense);

//             $("#total_return").val(totalRetourne.toLocaleString('fr-FR') + " Ar");
//             $("#total_return2").val(totalRetourne);

//             $("#idMission").val(response.idMission || '');
//             $("#idLiquidation").val(response.idLiquidation || '');

//             // ──── Valeur clé : reliquat encore à rembourser ────
//             $("#nonRetour").val(reliquatRestant.toLocaleString('fr-FR') + " Ar");
//             $("#nonRetourHidden").val(reliquatRestant);

//             // Si plus rien à rembourser
//             if (reliquatRestant <= 0) {
//                 Myalert.erreur("Reliquat déjà entièrement payé");

//                 // Vider les champs sensibles
//                 $('#idmission, #typeMission, #totalAvance, #objet, #nomagent, #idagent, ' +
//                   '#_depenser, #_depenser_val, #total_return, #total_return2, ' +
//                   '#idMission, #idLiquidation, #nonRetour, #nonRetourHidden, ' +
//                   '#aRendre, #reste, #resteHidden').val('');

//                 $('#valider').prop('disabled', true);
//                 $('#aRendre').prop('disabled', true);
//                 return;
//             }

//             // Réactiver les champs si besoin
//             $('#valider').prop('disabled', false);
//             $('#aRendre').prop('disabled', false);

//             $('#focus').focus();
//         },
//         error: function (xhr, status, error) {
//             console.error("Erreur AJAX getData_json2 :", status, error);
//             Myalert.erreur("Erreur de connexion au serveur");
//         }
//     });
// });
    $(document).on('input', '#aRendre', function () {
        // Récupérer les valeurs numériques (brutes)
        let reliquatRestant = parseFloat($('#nonRetourHidden').val()) || 0;
        let montantRendu    = parseFloat($(this).val()) || 0;

        // Calcul
        let reste = reliquatRestant - montantRendu;

        // Ne pas autoriser un reste négatif (optionnel mais recommandé)
        if (reste < 0) {
            reste = 0;
            $(this).val(reliquatRestant);   // remet le max possible
            Myalert.warning("Le montant rendu ne peut pas dépasser le reliquat restant");
        }

        // Mise à jour des deux champs "Reste"
        $('#reste').val(reste.toLocaleString('fr-FR') + " Ar");
        $('#resteHidden').val(reste);

        // Option : style visuel quand tout est remboursé
        if (reste === 0 && montantRendu > 0) {
            $('#reste').css({ 'color': '#28a745', 'font-weight': 'bold' }); // vert
        } else {
            $('#reste').css({ 'color': '', 'font-weight': 'normal' });
        }
    });
    $(document).on('click', '#valider', function () {

    // Récupérer les valeurs importantes (priorité aux hidden pour la précision)
    let idMission      = $('#idMission').val() || $('#idmission').val() || '';
    let idLiquidation  = $('#idLiquidation').val() || '';
    let montantReturn  = parseFloat($('#aRendreHidden').val()) || parseFloat($('#aRendre').val()) || 0;
    let resteReturn    = parseFloat($('#resteHidden').val()) || 0;
    let date           = $('input[name="date"]').val();
    let heure          = $('input[name="heure"]').val();

    // Vérifications rapides avant envoi
    if (!idMission || !idLiquidation) {
        Myalert.erreur("Mission ou liquidation non détectée");
        return;
    }

    if (montantReturn <= 0) {
        Myalert.erreur("Veuillez indiquer un montant à rendre");
        return;
    }

    if (montantReturn > parseFloat($('#nonRetourHidden').val() || 0)) {
        Myalert.erreur("Le montant rendu dépasse le reliquat restant");
        return;
    }

    // Préparer les données à envoyer
    let formData = {
        idMission:      idMission,
        idLiquidation:  idLiquidation,
        montantReturn:  montantReturn,
        resteReturn:    resteReturn,
        dateReturn:     date + ' ' + heure,   // format YYYY-MM-DD HH:MM
        // Optionnel : tu peux ajouter si besoin
        // idagent:     $('#idagent').val(),
        // numero_om:   $('#numero_om').val()
    };

    // Afficher le spinner et désactiver le bouton
    $('#valider').prop('disabled', true);
    $('#spinner_validation').removeClass('d-none');

    $.ajax({
        type: "POST",
        url: base_url('Relique/register'),   // ← À adapter selon ton contrôleur/méthode
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Myalert.added("Reliquat enregistré avec succès !");

                // Option : vider le formulaire ou recharger la page
                // $('#aRendre').val('');
                // $('#reste').val('0 Ar');
                // $('#resteHidden').val(0);
                // Ou recharger la liste des reliquats si tu en as une

                // Option : rediriger après 1-2 secondes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                Myalert.erreur(response.message || "Erreur lors de l'enregistrement");
            }
        },
        error: function () {
            Myalert.erreur("Erreur de connexion au serveur");
        },
        complete: function () {
            // Remettre le bouton et cacher spinner
            $('#valider').prop('disabled', false);
            $('#spinner_validation').addClass('d-none');
        }
    });
});
})