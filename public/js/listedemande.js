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