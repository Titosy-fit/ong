$(document).ready(function () {

    // ====================== RECHERCHE BÉNÉFICIAIRE ======================
    function searchBeneficiaire(critere) {
        const searchResultDiv = $('#searchResult');
        const noUserFound = $('#noUserFound');
        const historiqueSection = $('#historiqueSection');

        if (!critere || critere.trim() === '') {
            searchResultDiv.hide();
            noUserFound.hide();
            return;
        }

        $.ajax({
            type: 'post',
            url: base_url('Beneficiaire/search_benef_json'),
            data: { recherche: critere },
            dataType: 'json',
            beforeSend: function () {
                searchResultDiv.html(
                    '<div class="text-center p-3"><div class="spinner-border spinner-border-sm" style="color: #820000;"></div> Recherche en cours...</div>'
                ).show();
            },
            success: function (response) {
                if (response.success && response.datas.length > 0) {
                    let html = '';
                    $.each(response.datas, function (i, b) {
                        html += `
                            <div class="benef-result-item" data-id="${b.idbeneficiaire}" data-nom="${escapeHtml(b.nom)}">
                                <i class="fas fa-user-circle" style="color: #820000; font-size: 20px;"></i>
                                <div>
                                    <strong style="color: #333;">${escapeHtml(b.nom)}</strong>
                                    <small style="color: #999; display: block;">ID: ${b.idbeneficiaire}</small>
                                </div>
                            </div>`;
                    });
                    searchResultDiv.html(html).show();
                    noUserFound.hide();
                } else {
                    searchResultDiv.hide();
                    noUserFound.show();
                }
            },
            error: function () {
                searchResultDiv.html(
                    '<div class="text-center p-3 text-danger"><i class="fas fa-exclamation-circle"></i> Erreur de connexion</div>'
                ).show();
            }
        });
    }

    // Événement au clic sur le bouton Rechercher
    $('#btnSearch').on('click', function () {
        searchBeneficiaire($('#searchBeneficiaire').val());
    });

    // Recherche avec touche Entrée
    $('#searchBeneficiaire').on('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchBeneficiaire($(this).val());
        }
    });

    // Recherche en temps réel (après 2 caractères)
    let searchTimer = null;
    $('#searchBeneficiaire').on('keyup', function () {
        const val = $(this).val().trim();
        clearTimeout(searchTimer);
        if (val.length >= 2) {
            searchTimer = setTimeout(function () {
                searchBeneficiaire(val);
            }, 300);
        } else if (val.length === 0) {
            $('#searchResult').hide();
            $('#noUserFound').hide();
        }
    });

    // ====================== SÉLECTION D'UN BÉNÉFICIAIRE ======================
    $(document).on('click', '.benef-result-item', function () {
        const id = $(this).data('id');
        const nom = $(this).data('nom');

        // Afficher l'info du bénéficiaire sélectionné
        $('#selectedBenefName').text(nom);
        $('#selectedBenefInfo').show();
        $('#searchResult').hide();
        $('#searchBeneficiaire').val('');
        $('#noUserFound').hide();

        // Charger l'historique
        chargerHistorique(id);
    });

    // Changer de bénéficiaire
    $('#clearBenef').on('click', function () {
        $('#selectedBenefInfo').hide();
        $('#historiqueSection').hide();
        $('#loaderHistorique').hide();
        $('#searchBeneficiaire').val('').focus();
    });

    // ====================== CHARGEMENT HISTORIQUE ======================
    function chargerHistorique(idbeneficiaire) {
        $('#loaderHistorique').show();
        $('#historiqueSection').hide();

        $.ajax({
            type: 'post',
            url: base_url('Beneficiaire/historique_json'),
            data: { idbeneficiaire: idbeneficiaire },
            dataType: 'json',
            success: function (response) {
                $('#loaderHistorique').hide();

                if (response.success) {
                    // Remplir les missions
                    remplirMissions(response.missions || []);
                    // Remplir les liquidations
                    remplirLiquidations(response.liquidations || []);
                    // Remplir les reliquats
                    remplirReliquats(response.reliquats || []);

                    // Mettre à jour les compteurs
                    $('#countMissions').text((response.missions || []).length);
                    $('#countLiquidations').text((response.liquidations || []).length);
                    $('#countReliquats').text((response.reliquats || []).length);

                    // Activer le premier onglet
                    activerOnglet('missions');

                    // Afficher la section
                    $('#historiqueSection').show();

                    // Scroll vers l'historique
                    setTimeout(function () {
                        $('html, body').animate({
                            scrollTop: $('#historiqueSection').offset().top - 80
                        }, 500);
                    }, 100);
                } else {
                    alert('Erreur lors du chargement de l\'historique.');
                }
            },
            error: function () {
                $('#loaderHistorique').hide();
                alert('Erreur de connexion au serveur.');
            }
        });
    }

    // ====================== REMPLIR LES TABLEAUX ======================
    function remplirMissions(missions) {
        const tbody = $('#missionsList');
        tbody.empty();

        if (missions.length === 0) {
            tbody.html(`<tr><td colspan="8" class="text-center" style="padding: 40px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                Aucune mission pour ce bénéficiaire.
            </td></tr>`);
            return;
        }

        $.each(missions, function (i, m) {
            const dateDebut = formatDate(m.date_debut_mission);
            const dateFin = formatDate(m.date_fin_mission);
            const avance = formatMontant(m.montant_avance);

            tbody.append(`
                <tr class="animated-row" style="animation-delay: ${i * 0.05}s">
                    <td><span style="background: #e8f0fe; color: #1a73e8; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">${escapeHtml(m.codeprojet || '--')}</span></td>
                    <td>${escapeHtml(m.objet_mission || '--')}</td>
                    <td>
                        <small class="d-block"><strong>ASM:</strong> ${escapeHtml(m.numero_asm || '--')}</small>
                        <small class="d-block"><strong>OM:</strong> ${escapeHtml(m.numero_om || '--')}</small>
                    </td>
                    <td>${escapeHtml((m.nomagent || '') + ' ' + (m.prenomagent || ''))}</td>
                    <td><i class="far fa-calendar-alt" style="color: #820000; margin-right: 4px;"></i>${dateDebut}</td>
                    <td><i class="far fa-calendar-alt" style="color: #820000; margin-right: 4px;"></i>${dateFin}</td>
                    <td>${escapeHtml(m.lieu_mission || '--')}</td>
                    <td><strong style="color: #2e7d32;">${avance} Ar</strong></td>
                </tr>
            `);
        });
    }

    function remplirLiquidations(liquidations) {
        const tbody = $('#liquidationsList');
        tbody.empty();

        if (liquidations.length === 0) {
            tbody.html(`<tr><td colspan="8" class="text-center" style="padding: 40px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                Aucune liquidation pour ce bénéficiaire.
            </td></tr>`);
            return;
        }

        $.each(liquidations, function (i, l) {
            const dateLiq = formatDate(l.date_liquidation);
            const depense = formatMontant(l.montant_depense);
            const reliquat = formatMontant(l.montant_reliquat);
            const retourne = formatMontant(l.montant_return);
            const avance = formatMontant(l.montant_avance);

            tbody.append(`
                <tr class="animated-row" style="animation-delay: ${i * 0.05}s">
                    <td><span style="background: #e8f0fe; color: #1a73e8; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">${escapeHtml(l.codeprojet || '--')}</span></td>
                    <td>${escapeHtml(l.objet_mission || '--')}</td>
                    <td>${escapeHtml(l.numero_om || '--')}</td>
                    <td><strong>${avance} Ar</strong></td>
                    <td><span style="color: #c62828;">${depense} Ar</span></td>
                    <td><span style="color: #e65100;">${reliquat} Ar</span></td>
                    <td><span style="color: #2e7d32;">${retourne} Ar</span></td>
                    <td><i class="far fa-calendar-alt" style="color: #c17900; margin-right: 4px;"></i>${dateLiq}</td>
                </tr>
            `);
        });
    }

    function remplirReliquats(reliquats) {
        const tbody = $('#reliquatsList');
        tbody.empty();

        if (reliquats.length === 0) {
            tbody.html(`<tr><td colspan="6" class="text-center" style="padding: 40px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                Aucun reliquat pour ce bénéficiaire.
            </td></tr>`);
            return;
        }

        $.each(reliquats, function (i, r) {
            const dateRetour = formatDate(r.dateReturn);
            const dateLiq = formatDate(r.date_liquidation);
            const montantReturn = formatMontant(r.montantReturn);
            const resteReturn = formatMontant(r.resteReturn);

            tbody.append(`
                <tr class="animated-row" style="animation-delay: ${i * 0.05}s">
                    <td>${escapeHtml(r.objet_mission || '--')}</td>
                    <td>${escapeHtml(r.numero_om || '--')}</td>
                    <td><strong style="color: #2e7d32;">${montantReturn} Ar</strong></td>
                    <td><span style="color: #c62828;">${resteReturn} Ar</span></td>
                    <td><i class="far fa-calendar-alt" style="color: #2e7d32; margin-right: 4px;"></i>${dateRetour}</td>
                    <td><i class="far fa-calendar-alt" style="color: #999; margin-right: 4px;"></i>${dateLiq}</td>
                </tr>
            `);
        });
    }

    // ====================== GESTION DES ONGLETS ======================
    $(document).on('click', '.tab-btn', function () {
        const tab = $(this).data('tab');
        activerOnglet(tab);
    });

    function activerOnglet(tab) {
        // Désactiver tous les onglets
        $('.tab-btn').css({
            'color': '#999',
            'border-bottom': '3px solid transparent'
        }).removeClass('active');

        // Cacher tous les contenus
        $('.tab-content-panel').hide();

        // Activer l'onglet cliqué
        $(`.tab-btn[data-tab="${tab}"]`).css({
            'color': '#820000',
            'border-bottom': '3px solid #820000'
        }).addClass('active');

        // Afficher le contenu correspondant
        $(`#tab-${tab}`).fadeIn(200);
    }

    // ====================== UTILITAIRES ======================
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function (m) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
            return map[m] || m;
        });
    }

    function formatDate(dateStr) {
        if (!dateStr) return '--';
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        } catch (e) {
            return dateStr;
        }
    }

    function formatMontant(val) {
        if (!val && val !== 0) return '0';
        const num = parseFloat(val);
        if (isNaN(num)) return '0';
        return num.toLocaleString('fr-FR');
    }

});
