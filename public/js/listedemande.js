$(document.body).on('click', ".supprimer", function () {
	let idfacture = $(this).data('idfacture');
	const elem = $(this);
	Myalert.delete("Êtes-vous sûr de vouloir supprimer ?", function () {
		$.ajax({
			method: 'post',
			url: base_url('liste-demande/deleteListe'),
			data: { idfacture: idfacture },
			dataType: 'json',

		}).done(function (response) {
			if (response.success == true) {
				$(elem).closest('tr').remove();
				Myalert.deleted();
			}
		}).fail(function () {
			console.log('error');
		})
	});
});

$(document).on('click', '.imprim', function () {
	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	$('#affichefacture').click();
	let facture = $(this).data("facture");
	let url = base_url('Dispatch/facture/' + facture);

	$('#pdfFrame').attr('src', url);

	setTimeout(function () {
		Myalert.removeSpinnerB();
	}, 100);

	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');
	setTimeout(function () {
		$('#loaderFacture').addClass('d-none');
		$('#pdfFrame').removeClass('d-none');
	}, 5000)
})
$(document).on('change', '#format', function () {
	$('#loaderFacture').removeClass('d-none');
	$('#pdfFrame').addClass('d-none');

	const format = $(this).val();

	let src = $('#pdfFrame').attr('src');
	let new_src = '';
	$('#pdfFrame').attr('src', '');

	if (format == 'A4') {
		new_src = src.replace('tiquet', 'facture');
	} else {
		new_src = src.replace('facture', 'tiquet');
	}
	$('#pdfFrame').attr('src', new_src);

	setTimeout(function () {
		$('#loaderFacture').addClass('d-none');
		$('#pdfFrame').removeClass('d-none');
	}, 5000)
})
$(document.body).on("click", ".detail", function () {
	var idfacture = $(this).data("idfacture");
	$.ajax({
		url: base_url("liste-demande/getDetails"),
		type: "post",
		data: {
			idfacture: idfacture,
		},
	}).done(function (data) {
		$("#tab").html(data);
	});
});
// ====================== IMPORT FICHIER ======================
$(document).ready(function () {
	let selectedFile = null;

	// Ouverture du modal + sélection du fichier
	$(document).on('change', '#importFile', function () {
		const file = this.files[0];
		if (!file) return;

		selectedFile = file;
		const previewContainer = $('#importPreview');
		previewContainer.removeClass('d-none').html(`
            <p><strong>Fichier :</strong> ${file.name} <small>(${(file.size / 1024).toFixed(2)} KB)</small></p>
        `);

		// Si c'est une photo → prévisualisation comme dans ton code liquidation
		if (file.type.startsWith('image/')) {
			const reader = new FileReader();
			reader.onload = function (e) {
				previewContainer.append(`
                    <img src="${e.target.result}" class="img-fluid mt-2" style="max-height: 300px; border: 1px solid #ddd;">
                `);
			};
			reader.readAsDataURL(file);
		}
	});

	// Bouton "Importer et Enregistrer"
	$(document).on('click', '#btnImportSave', function () {
		if (!selectedFile) {
			Myalert.erreur("Veuillez sélectionner un fichier");
			return;
		}

		Myalert.spinnerB();

		const formData = new FormData($('#importForm')[0]);

		$.ajax({
			url: $('#importForm').attr('action'),
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				Myalert.removeSpinnerB();
				if (response.success) {
					Myalert.added("Données importées avec succès !");
					$('#importDataModal').modal('hide');
					location.reload(); // Tu peux supprimer cette ligne si tu veux juste afficher les données sans recharger
				} else {
					Myalert.erreur(response.message || "Erreur lors de l'importation");
				}
			},
			error: function () {
				Myalert.removeSpinnerB();
				Myalert.erreur("Erreur de connexion au serveur");
			}
		});
	});
});
// ====================== SAISIE MANUELLE (AVEC OU SANS FICHIER) ======================
let demande_valide = false;
let idfacture_demande = null;
let agent_info_demande = null;

// Vérifier le numéro de demande
$(document).on('click', '#btn_verify_demande', function () {
	const num_demande = $('#manual_num_demande').val().trim().toUpperCase();

	if (num_demande === '') {
		$('#demande_verification_result').html(
			'<span class="text-danger"><i class="fa-solid fa-times-circle"></i> Veuillez saisir un numéro de demande</span>'
		);
		$('#btnSaveManual').prop('disabled', true);
		demande_valide = false;
		return;
	}

	$.ajax({
		url: base_url('liste-demande/verifier_demande'),
		type: 'POST',
		data: { num_demande: num_demande },
		dataType: 'json',
		beforeSend: function () {
			$('#demande_verification_result').html(
				'<span class="text-info"><i class="fa-solid fa-spinner fa-spin"></i> Vérification...</span>'
			);
		}
	}).done(function (response) {
		if (response.success) {
			let agentHtml = '';
			if (response.agent && response.agent.nom_complet) {
				agentHtml = '<br><small>Agent: ' + response.agent.nom_complet + '</small>';
			}

			$('#demande_verification_result').html(
				'<span class="text-success"><i class="fa-solid fa-check-circle"></i> Demande valide' + agentHtml + '</span>'
			);

			idfacture_demande = response.idfacture;
			agent_info_demande = response.agent;
			demande_valide = true;
			$('#btnSaveManual').prop('disabled', false);

			// Pré-remplir avec les infos de l'agent si les champs sont vides
			if (agent_info_demande) {
				if (!$('#manual_nom').val() && agent_info_demande.nom_complet) {
					$('#manual_nom').val(agent_info_demande.nom_complet);
				}
				if (!$('#manual_cin').val() && agent_info_demande.cin) {
					$('#manual_cin').val(agent_info_demande.cin);
				}
				if (!$('#manual_fokontany').val() && agent_info_demande.fokontany) {
					$('#manual_fokontany').val(agent_info_demande.fokontany);
				}
				if (!$('#manual_commune').val() && agent_info_demande.commune) {
					$('#manual_commune').val(agent_info_demande.commune);
				}
			}
		} else {
			$('#demande_verification_result').html(
				'<span class="text-danger"><i class="fa-solid fa-times-circle"></i> ' + response.message + '</span>'
			);
			demande_valide = false;
			$('#btnSaveManual').prop('disabled', true);
		}
	}).fail(function () {
		$('#demande_verification_result').html(
			'<span class="text-danger"><i class="fa-solid fa-times-circle"></i> Erreur de connexion</span>'
		);
		demande_valide = false;
		$('#btnSaveManual').prop('disabled', true);
	});
});

// Vérification automatique quand on perd le focus
$(document).on('blur', '#manual_num_demande', function () {
	const val = $(this).val().trim();
	if (val.length >= 5 && !demande_valide) {
		$('#btn_verify_demande').click();
	}
});

// Enregistrer la saisie manuelle (AVEC OU SANS FICHIER)
$(document).on('click', '#btnSaveManual', function () {
	if (!demande_valide) {
		Myalert.erreur('Veuillez d\'abord vérifier le numéro de demande');
		return;
	}

	const num_demande = $('#manual_num_demande').val().trim();
	const entana = $('#manual_entana').val().trim();
	const designation = $('#manual_designation').val().trim();
	const fichierInput = document.getElementById('manual_fichier');
	const fichier = fichierInput ? fichierInput.files[0] : null;

	// Validation : soit article, soit fichier (ou les deux)
	if (entana === '' && designation === '' && !fichier) {
		Myalert.erreur('Veuillez saisir au moins un article OU joindre un fichier');
		return;
	}

	Myalert.spinnerB();

	// Utiliser FormData pour supporter l'upload de fichier
	const formData = new FormData();
	formData.append('num_demande', num_demande);
	formData.append('entana', entana);
	formData.append('designation', designation);
	formData.append('isany', $('#manual_isany').val());
	formData.append('date_reception', $('#manual_date').val());
	formData.append('nom', $('#manual_nom').val().trim());
	formData.append('cin', $('#manual_cin').val().trim());
	formData.append('fokontany', $('#manual_fokontany').val().trim());
	formData.append('commune', $('#manual_commune').val().trim());

	if (fichier) {
		formData.append('fichier', fichier);
	}

	$.ajax({
		url: base_url('liste-demande/save_saisie_manuelle'),
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		dataType: 'json'
	}).done(function (response) {
		Myalert.removeSpinnerB();

		if (response.success) {
			let message = 'Saisie enregistrée avec succès !';
			if (response.type === 'manuel_fichier') {
				message = 'Article et fichier enregistrés avec succès !';
			} else if (response.type === 'fichier') {
				message = 'Fichier importé et enregistré avec succès !';
			}

			Myalert.added(message);

			// Réinitialiser le formulaire
			$('#manual_entana, #manual_designation, #manual_nom, #manual_cin, #manual_fokontany, #manual_commune').val('');
			$('#manual_isany').val('1');
			$('#manual_date').val('<?= date("Y-m-d") ?>');
			$('#manual_fichier').val('');

			// Garder le numéro de demande vérifié
			// $('#manual_num_demande').val(num_demande); // Déjà présent

		} else {
			Myalert.erreur(response.message || 'Erreur lors de l\'enregistrement');
		}
	}).fail(function (xhr, status, error) {
		Myalert.removeSpinnerB();
		console.error('Erreur:', error);
		Myalert.erreur('Erreur de connexion au serveur');
	});
});

// Prévisualisation du fichier sélectionné (optionnel)
$(document).on('change', '#manual_fichier', function () {
	const file = this.files[0];
	if (file) {
		const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
		console.log(`Fichier sélectionné: ${file.name} (${sizeInMB} MB)`);

		// Vérification de la taille
		if (file.size > 10 * 1024 * 1024) {
			Myalert.erreur('Le fichier dépasse 10MB');
			$(this).val('');
		}
	}
});