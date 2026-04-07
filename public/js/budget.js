$('#ligne').focus();
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
                    $('#ligne').focus() ; 
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
       
        const ligne = $('#ligne').val()

        $.ajax({
            method: 'post',
            url: base_url('Budget/verify'),
            data: {
                 ligne: ligne
            },
            dataType: 'json'
        }).done(function (response) {

            if (response.success) {
                $('#t-valider').attr('type', 'submit');
                $('#t-valider').click();
                $('#ms-ligne').addClass('d-none');
                shwoSpinner($('#valider'), ['designation', 'budget']);

            } else {
                $('#ms-ligne').removeClass('d-none');
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
                $("#ligne_modif").val(data.ligne_budget);
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
        const ligne = $("#ligne_modif").val() ;
        const idactivite = $('#activite_modif').val();
        const idbudget = $('#id_modif').val();
        let budget = $('#budget_modif').val();
        $.ajax({
            method: 'post',
            url: base_url('Budget/edit'),
            data: {
                designation: designation,
                ligne: ligne,
                idbudget: idbudget,
                budget:budget
                
            },
            dataType: 'json'
        }).done(function (response) {
           
            if (response.success) {
                
                 $('#des_ligne_modif').addClass('d-none');
                 
                 location.reload() ;

            } else {

                if(response.message=="error-ligne") {
                   
                    $('#des_ligne_modif').removeClass('d-none');
                }
                
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
   $(document).on('click', '.view-more', function () {
    const idbudget = $(this).data('id');
     function formatDate(dateStr) {
        if (!dateStr) return '—';
        var parts = dateStr.split(' ')[0].split('-');
        if (parts.length === 3) {
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }
        return dateStr; // fallback
    }
    console.log("═══════════════════════════════════════════════════════════════");
    console.log("=== OUVERTURE MODAL DÉTAILS BUDGÉTAIRE ===");
    console.log("→ ID du budget cliqué :", idbudget);
    console.log("═══════════════════════════════════════════════════════════════");

    // Réinitialisation systématique
    console.log("→ Réinitialisation des champs span (mise à '—')");
    $('#viewMoreModal span[id^="detail_"], #viewMoreModal span[id^="agent_"]').text('—');

    $.ajax({
        url: base_url('Budget/donner_details'),
        method: 'POST',
        data: { idbudget: idbudget },
        dataType: 'json',
        beforeSend: function () {
            console.log("→ [AJAX START] Envoi requête vers donner_details");
            console.log("  Paramètres envoyés :", { idbudget: idbudget });
        },
        success: function (response) {
            console.log("→ [AJAX SUCCESS] Réponse reçue du serveur");
            console.log("  → Structure complète de la réponse :", response);

            if (!response.success) {
                console.warn("→ Le serveur a répondu avec success: false");
                console.warn("→ Message :", response.message || "Aucun message d'erreur");
                Myalert.erreur(response.message || "Impossible de charger les détails");
                return;
            }

            const d = response.data;
            console.log("→ Données extraites (response.data) :", d);
            console.log("→ Clés disponibles dans data :", Object.keys(d));
            console.log("→ Missions :", d.missions ? d.missions.length + " trouvée(s)" : "aucune");
            console.log("→ Liquidations :", d.liquidations ? d.liquidations.length + " trouvée(s)" : "aucune");

            // ────────────────────────────────────────────────
            // COLONNE PROJET
            // ────────────────────────────────────────────────
            console.group("→ Colonne PROJET");
            $('#detail_codeprojet').text(d.codeprojet || '—');
            console.log("  codeprojet :", d.codeprojet || '—');
            $('#detail_createur').text('Admin'); // À adapter si tu renvoies le vrai nom
            console.log("  createur (hardcodé) : Admin");
            console.groupEnd();

            // ────────────────────────────────────────────────
            // COLONNE BUDGET
            // ────────────────────────────────────────────────
            console.group("→ Colonne BUDGET");
            $('#detail_ligne').text(d.ligne_budget || '—');
            console.log("  ligne_budget :", d.ligne_budget || '—');
            $('#detail_designation').text(d.designation_budget || '—');
            console.log("  designation_budget :", d.designation_budget || '—');
            const budgetAffiche = d.budget ? Number(d.budget).toLocaleString('fr-FR') : '—';
            $('#detail_budget').text(budgetAffiche);
            console.log("  budget affiché :", budgetAffiche);
            console.groupEnd();

            // ────────────────────────────────────────────────
            // COLONNE MISSION
            // ────────────────────────────────────────────────
            console.group("→ Colonne MISSION");
            if (d.missions && Array.isArray(d.missions) && d.missions.length > 0) {
                const premiereMission = d.missions[0];
                console.log("  Première mission :", premiereMission);

                const avanceTxt = Number(premiereMission.montant_avance || 0).toLocaleString('fr-FR') + ' Ar';
                $('#detail_avance').text(avanceTxt);
                console.log("  avance :", avanceTxt);

                $('#detail_objet').text(premiereMission.objet_mission || '—');
                console.log("  objet_mission :", premiereMission.objet_mission || '—');

                $('#detail_lieu').text(premiereMission.lieu_mission || '—');
                console.log("  lieu_mission :", premiereMission.lieu_mission || '—');

                if (d.missions.length > 1) {
                    console.log("  → Plusieurs missions :", d.missions.length);
                    $('#detail_objet').after(
                        '<br><small class="text-muted">(+' + (d.missions.length - 1) + ' autre(s))</small>'
                    );
                }

                let agents = d.missions
                    .map(m => m.agent || '—')
                    .filter((v, i, a) => a.indexOf(v) === i)
                    .join(', ');
                $('#agent_1').text(agents || 'Non assigné');
                console.log("  Agents affichés :", agents || 'Non assigné');
            } else {
                console.log("  → Aucune mission associée");
                $('#detail_avance').text('—');
                $('#detail_objet').text('—');
                $('#detail_lieu').text('—');
                $('#agent_1').text('Non assigné');
            }
            console.groupEnd();

            // ────────────────────────────────────────────────
            // COLONNE LIQUIDATION – version simplifiée (comme mission)
            // ────────────────────────────────────────────────
            console.group("→ Colonne LIQUIDATION (mode mission)");
            const liquidations = d.liquidations || [];

            console.log("  Nombre de liquidations :", liquidations.length);

            if (liquidations.length === 0) {
                console.log("  → Aucune liquidation → champs à '—'");
                $('#detail_total_depense').text('—');
                $('#detail_total_reliquat').text('—');
                $('#detail_date_liquidation').text('—');
                // On efface un éventuel message précédent
                $('#viewMoreModal td:last-child .alert-info').remove();
            } else {
                // On prend la première liquidation (la plus récente)
                const liq = liquidations[0];
                console.log("  Première liquidation :", liq);

                // Récupération intelligente des valeurs (plusieurs noms possibles)
                const depenseVal = liq.montant_depense ?? liq.total_depense ?? liq.montant_depense_total ?? 0;
                const depenseTxt = Number(depenseVal).toLocaleString('fr-FR') + ' Ar';
                $('#detail_total_depense').text(depenseTxt);
                console.log("  → total dépensé :", depenseTxt);

                const reliquatVal = liq.montant_reliquat ?? liq.reliquat ?? liq.reste ?? 0;
                const reliquatTxt = Number(reliquatVal).toLocaleString('fr-FR') + ' Ar';
                $('#detail_total_reliquat').text(reliquatTxt);
                console.log("  → reliquat :", reliquatTxt);

                // Affichage de la date avec vérification
                let dateLiquidation = '—';
                if (liq.date_liquidation) {
                    console.log("  → date_liquidation brute :", liq.date_liquidation);
                    dateLiquidation = formatDate(liq.date_liquidation);
                    console.log("  → après formatDate :", dateLiquidation);
                } else {
                    console.log("  → pas de date_liquidation");
                }
                $('#detail_date_liquidation').text(dateLiquidation);
                console.log("  → date liquidation affichée :", dateLiquidation);

                // Supprime l'ancien message s'il existe
                $('#viewMoreModal td:last-child .alert-info').remove();

                // S'il y a plus d'une liquidation, on ajoute un petit indicateur
                if (liquidations.length > 1) {
                    console.log("  → Plusieurs liquidations :", liquidations.length);
                    const $cell = $('#viewMoreModal td:last-child'); // cellule de la colonne liquidation
                    $cell.append(
                        '<div class="alert alert-info mt-2 mb-0 py-1 px-2" style="font-size:0.8rem;">' +
                        '<i class="fa-solid fa-info-circle"></i> ' +
                        liquidations.length + ' liquidations au total (seule la plus récente est affichée)' +
                        '</div>'
                    );
                }
            }
            console.groupEnd();

            console.log("═══════════════════════════════════════════════════════════════");
            console.log("→ MODAL MISE À JOUR TERMINÉE");
            console.log("═══════════════════════════════════════════════════════════════");
        },
        error: function (xhr, status, error) {
            console.error("═══════════════════════════════════════════════════════════════");
            console.error("→ [AJAX ERROR] Échec de la requête");
            console.error("  → status :", status);
            console.error("  → error :", error);
            console.error("  → réponse brute :", xhr.responseText);
            console.error("═══════════════════════════════════════════════════════════════");
            Myalert.erreur("Erreur serveur lors du chargement des détails");
        }
    });
});
})