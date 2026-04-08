//************************* */ Nouveau js appro

let selectedBudgets = new Set();
let currentBudgetPage = 1;
let currentIdprojet = "";
let globalBudgetInfos = {};

function initSelectedBudgets() {
	let idbudgetVal = $("#idbudget").val();
	if (idbudgetVal && idbudgetVal !== "") {
		idbudgetVal.split(",").forEach((id) => {
			if (id) selectedBudgets.add(String(id));
		});
	}
}

function saveSelectedBudgetsFromDisplay() {
	$(".budget_contain").each(function () {
		let btn = $(this).find(".delete_budget");
		let id = btn.data("id");
		if (id) {
			id = String(id);
			selectedBudgets.add(id);
			globalBudgetInfos[id] = {
				ligne: btn.attr("data-ligne") || btn.data("ligne"),
				designation: btn.attr("data-designation") || btn.data("designation"),
				reste: btn.attr("data-reste") || btn.data("reste")
			};
		}
	});
}

function restoreSelectedBudgetsInModal() {
	$(".choix_budget").each(function () {
		let id = String($(this).data("id"));
		$(this).prop("checked", selectedBudgets.has(id));
	});
}

function select_budget(idprojet, page = 1) {
	currentIdprojet = idprojet;
	currentBudgetPage = page;

	let recherche_tab = `
        <table class="table table-bordered">
            <thead class="table-info">
                <tr><th>Choix</th><th>Ligne budgétaire</th><th>Intitulé</th><th>Montant alloué</th><th>Reliquat</th></tr>
            </thead>
            <tbody id="budget-tbody"><tr><td colspan="5" class="text-center"><div class="spinner-border"></div></td></tr></tbody>
        </table>
        <div id="budget-pagination" class="text-center mt-2"></div>
        <button class="btn btn-info btn-sm mt-2" id="budget_valide">CHOISIR</button>
    `;
	$("#budget_modal").html(recherche_tab);
	loadBudgetPage(idprojet, page);
}

function loadBudgetPage(idprojet, page) {
	$.ajax({
		url: base_url("Appro/getBudgetsByProjet_json"),
		type: "post",
		data: { idprojet: idprojet, page: page, per_page: 10 },
		dataType: "json",
	}).done(function (response) {
		if (response.success && response.datas.length > 0) {
			let tbody = "";
			for (let i = 0; i < response.datas.length; i++) {
				const b = response.datas[i];
				let b_id = String(b.idbudget);
				globalBudgetInfos[b_id] = {
					ligne: b.ligne_budget,
					designation: b.designation_budget,
					reste: b.reste
				};
				let isChecked = selectedBudgets.has(b_id);
				tbody += `<tr>
                    <td class="text-center"><input class='choix_budget form-check-input' type="checkbox" data-id='${b.idbudget}' data-designation='${escapeHtml(b.designation_budget)}' data-ligne='${escapeHtml(b.ligne_budget)}' data-montant='${b.budget}' data-reste='${b.reste}' ${isChecked ? "checked" : ""}></td>
                    <td>${escapeHtml(b.ligne_budget)}</td>
                    <td>${escapeHtml(b.designation_budget)}</td>
                    <td>${parseInt(b.budget).toLocaleString("fr-FR")} Ar</td>
                    <td>${parseInt(b.reste).toLocaleString("fr-FR")} Ar</td>
                </tr>`;
			}
			$("#budget-tbody").html(tbody);

			// Restaurer l'état des checkboxes
			restoreSelectedBudgetsInModal();

			let paginationHtml = "";
			if (response.total_pages > 1) {
				paginationHtml =
					'<nav><ul class="pagination pagination-sm justify-content-center">';
				for (let i = 1; i <= response.total_pages; i++) {
					paginationHtml += `<li class="page-item ${i === page ? "active" : ""}"><a class="page-link budget-page" href="#" data-page="${i}">${i}</a></li>`;
				}
				paginationHtml += "</ul></nav>";
			}
			$("#budget-pagination").html(paginationHtml);

			$(".budget-page")
				.off("click")
				.on("click", function (e) {
					e.preventDefault();
					loadBudgetPage(idprojet, $(this).data("page"));
				});
		} else {
			$("#budget-tbody").html(
				'<tr><td colspan="5" class="text-center">Aucun budget trouvé</td</td>',
			);
		}
	});
}

function check_budgete() {
	$(".choix_budget:checked").each(function () {
		selectedBudgets.add(String($(this).data("id")));
	});

	let content = "";
	let idbudgetArray = [];

	if (selectedBudgets.size === 0) {
		$("#budget_container").html(
			'<div class="text-muted text-center p-2">Aucun budget sélectionné</div>',
		);
		$("#idbudget").val("");
		return;
	}

	for (let id of selectedBudgets) {
		let ligne = "";
		let designation = "";
		let reste = 0;
		let details = globalBudgetInfos[id];
		
		if (details) {
			ligne = details.ligne;
			designation = details.designation;
			reste = details.reste;
		} else {
			let checkbox = $(`.choix_budget[data-id="${id}"]`);
			if (checkbox.length > 0) {
				ligne = checkbox.attr("data-ligne") || checkbox.data("ligne");
				designation = checkbox.attr("data-designation") || checkbox.data("designation");
				reste = checkbox.attr("data-reste") || checkbox.data("reste");
				globalBudgetInfos[id] = { ligne, designation, reste };
			}
		}

		if (ligne !== "" || designation !== "") {
			content += `<div class="budget_contain d-flex justify-content-between align-items-center p-2 mb-1 bg-white border rounded">
                <div><strong>${escapeHtml(ligne)}</strong> - ${escapeHtml(designation)}<br><small class="text-muted">Reliquat: ${parseInt(reste || 0).toLocaleString("fr-FR")} Ar</small></div>
                <div><span class="text-danger delete_budget btn btn-sm" data-id='${id}' data-ligne='${escapeHtml(ligne)}' data-designation='${escapeHtml(designation)}' data-reste='${reste || 0}'><i class="fa-solid fa-trash"></i></span></div>
            </div>`;
		}
		idbudgetArray.push(id);
	}

	$("#budget_container").html(content);
	$("#idbudget").val(idbudgetArray.join(","));
}

function vider() {
	$("#reference").val("");
	$("#designationmateriel").val("");
	$("#type_materiel").val("");
	$("#fiche").val("");
}

function calacule_totale() {
	const prix = $("#prix").val();
	const quantite = $("#qte").val();
	if (prix && quantite) {
		const montant = parseInt(prix) * parseInt(quantite);
		$("#montant").val(montant.toLocaleString("fr-FR") + " Ar");
	} else {
		$("#montant").val("0" + " Ar");
	}
}

$(document).on("keyup , change", "#prix , #qte", function () {
	const prix = $("#prix").val();
	const quantite = $("#qte").val();
	if (prix && quantite) {
		calacule_totale();
	} else {
		$("#montant").val("0" + " Ar");
	}
});

$(document.body).on("change", "#reference", function () {
	const ref = $(this).val();
	$.ajax({
		url: base_url("Appro/recherche_produit"),
		type: "post",
		dataType: "json",
		data: {
			ref: ref,
		},
	}).done(function (data) {
		if (data.success == true) {
			const materiel = data.materiel;
			$("#reference").val(materiel.refmateriel);
			$("#idmateriel").val(materiel.idmateriel);
			$("#designationmateriel").val(materiel.designationmateriel);
			$("#type_materiel").val(materiel.typemateriel);
			$("#fiche").val(materiel.fiche);
			$("#prix").focus();
			const unites = data.unites;
			if (unites.length > 0) {
				let content = "";
				for (let i = 0; i < unites.length; i++) {
					content +=
						`<option value="` +
						unites[i].idunite +
						`">` +
						unites[i].denomination +
						`</option>`;
				}
				$("#unite").html(content);
				$("#unite_container").removeClass("d-none");
			} else {
				$("#unite_container").addClass("d-none");
			}
		} else {
			vider();
			Myalert.erreur("Cette Réference n'existe pas.");
		}
	});
});

$(document.body).on("click", ".delete", function () {
	Myalert.delete();
	const idappro = $(this).data("idappro");
	$("#confirmeDelete").on("click", function () {
		$.ajax({
			method: "post",
			url: base_url("deleteAppro"),
			data: { idappro: idappro },
			dataType: "json",
			success: function (response) {
				if (response.success == true) {
					window.location.reload();
				}
			},
		});
	});
});

$(document).on("change , keyup", "#quantite , #prix", function () {
	const quantite = $("#quantite").val();
	const prix = $("#prix").val();
	let montant = 0;
	if (quantite != "" && prix != "") {
		montant = parseInt(quantite) * parseInt(prix);
	}
	$("#montant_show").val(montant.toLocaleString("fr-FR") + " Ar ");
	$("#montant").val(montant);
});

function verify_numserie_base(numero) {
	let result = false;
	$.ajax({
		method: "post",
		url: base_url("Appro/verifyNumserie"),
		data: { numero: numero },
		async: false,
		dataType: "json",
	})
		.done(function (response) {
			result = response.success;
		})
		.fail(function (err) {
			console.error("erreur dans la verification du numero de series ", err);
		});
	return result;
}

function verify_imei_base(imei) {
	let result = false;
	$.ajax({
		method: "post",
		url: base_url("Appro/verifyImei"),
		data: { imei: imei },
		async: false,
		dataType: "json",
	})
		.done(function (response) {
			result = response.success;
		})
		.fail(function (err) {
			console.error("erreur dans la verification du numero de series ", err);
		});
	return result;
}

function verify_input(numero, the_class, self) {
	let input = $("." + the_class);
	let teste = true;
	for (let i = 0; i < input.length; i++) {
		const element = input[i];
		let elem_self = $(element).data("self");
		if (elem_self != self && $(element).val() == numero) {
			teste = false;
		}
	}
	return teste;
}

function exist(elem) {
	$(elem).css({
		border: "1px solid red",
	});
	$(elem).val("");
}

function ok(elem) {
	$(elem).css({
		border: "",
	});
}

$(document).on("change", "#numSerie", function () {
	const numero = $(this).val();
	const self = $(this).data("self");
	if ($("#designationmateriel").val() != "" && numero != "") {
		if (verify_numserie_base(numero)) {
			ok(this);
			$("#numero_mss").addClass("d-none");
		} else {
			exist(this);
			$("#numero_mss").removeClass("d-none");
		}
	} else {
		$(this).val("");
		$("#numero_mss").addClass("d-none");
	}
});

$(document).on("change", "#imei1", function () {
	const imei = $(this).val();
	const self = $(this).data("self");
	if ($("#designationmateriel").val() != "" && imei != "") {
		if (verify_imei_base(imei) && $("#imei2").val() != imei) {
			ok(this);
			$("#imei1_mss").addClass("d-none");
		} else {
			exist(this);
			$("#imei1_mss").removeClass("d-none");
			$(this).focus();
		}
	} else {
		$(this).val("");
		$("#imei1_mss").addClass("d-none");
	}
});

$(document).on("change", "#imei2", function () {
	const imei = $(this).val();
	const self = $(this).data("self");
	if ($("#designationmateriel").val() != "" && imei != "") {
		if (verify_imei_base(imei) && $("#imei1").val() != imei) {
			ok(this);
			$("#imei2_mss").addClass("d-none");
		} else {
			exist(this);
			$("#imei2_mss").removeClass("d-none");
			$(this).focus();
		}
	} else {
		$(this).val("");
		$("#imei2_mss").addClass("d-none");
	}
});

let window_width = window.innerWidth;

$(document).ready(function () {
	if (window_width <= 768) {
		$(".sidebar").addClass("hide");
	}
	$(window).on("resize", function () {
		if ($(this).innerWidth() <= 768) {
			$(".sidebar").addClass("hide");
		} else {
			$(".sidebar").removeClass("hide");
		}
	});
	const elemtooltips = document.querySelectorAll(".btn-tooltip");
	for (let elem of elemtooltips) {
		new bootstrap.Tooltip(elem);
	}
	$(".ui.dropdown").dropdown();
});

$(document).on("change", "#codeprojet", function () {
	const codeprojet = $(this).val();
	if (codeprojet != "") {
		$.ajax({
			method: "post",
			url: base_url("Projet/bycode_json"),
			data: { codeprojet: codeprojet },
			dataType: "json",
		})
			.done(function (response) {
				if (response.success) {
					const projet = response.data;
					const depot = response.depot;
					$("#codeprojet").val(projet.codeprojet);
					$("#titreprojet").val(projet.titreprojet);
					$("#projet").val(projet.idprojet);
					let affiche = '<option value="0">-- Choisissez un dépôt --</option>';
					for (var i = 0; i < depot.length; i++) {
						affiche +=
							"<option value='" +
							depot[i].idPointVente +
							"'>" +
							depot[i].denomination_pv +
							"</option>";
					}
					$("#liste-pv").html(affiche);
				} else {
					Myalert.erreur("Ce code de projet n'existe pas.");
				}
			})
			.fail(function () {
				console.error("Erreur dans la verification du activite ");
			});
	}
});

$(document).ready(function () {
	$("form").on("submit", function (e) {
		let depot = $("#liste-pv").val();
		let projet = $("#projet").val();
		if (projet == 0) {
			e.preventDefault();
			Myalert.erreur("Veuillez choisir un projet");
		} else if (depot === "0") {
			e.preventDefault();
			Myalert.erreur("Veuillez choisir un dépôt");
			$("#liste-pv").focus();
		} else {
			if ($("#type").val() == "autre") {
				shwoSpinner(this, [
					"designationmateriel",
					"prix",
					"quantite",
					"montant",
				]);
			} else {
				shwoSpinner(this, [
					"designationmateriel",
					"prix",
					"numSerie",
					"imei1",
					"imei2",
				]);
			}
		}
	});
});

// ============ BUDGET SELECTION EVENTS ============

$(document).ready(function () {
	initSelectedBudgets();

	// Bouton sélection budget
	$(document).on("click", "#select_budget", function () {
		let idprojet = $("#projet").val();
		if (idprojet && idprojet != 0 && idprojet != "") {
			saveSelectedBudgetsFromDisplay();
			select_budget(idprojet);
			// Au lieu de $("#active_buget_modal").click();
			$("#mybudgetmodal").modal("show");
		} else {
			Myalert.erreur("Veuillez d'abord saisir un code projet valide");
		}
	});
	// Validation du modal (bouton CHOISIR)
	$(document).on("click", "#budget_valide", function () {
		console.log("CHOISIR clique");
		$(".choix_budget:checked").each(function () {
			selectedBudgets.add(String($(this).data("id")));
		});
		$(".choix_budget").each(function () {
			if (!$(this).is(":checked")) {
				selectedBudgets.delete(String($(this).data("id")));
			}
		});
		check_budgete();
		$("#mybudgetmodal").modal("hide");
		$("body").removeClass("modal-open");
		$(".modal-backdrop").remove();
	});

	// Suppression d'un budget
	$(document).on("click", ".delete_budget", function () {
		let idbudget = String($(this).data("id"));
		selectedBudgets.delete(idbudget);
		let check = $(`.choix_budget[data-id="${idbudget}"]`);
		if (check.length) check.prop("checked", false);
		check_budgete();
	});

	// Fermeture modal
	$(document).on("click", ".close_budget", function () {
		$("#mybudgetmodal").modal("hide");
	});
});

function escapeHtml(str) {
	if (str === null || str === undefined) return "";
	return String(str).replace(/[&<>]/g, function (m) {
		if (m === "&") return "&amp;";
		if (m === "<") return "&lt;";
		if (m === ">") return "&gt;";
		return m;
	});
}
