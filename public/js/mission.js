$("#codeprojet").focus();
let currentBudgetPage = 1;
let currentIdactivite = "";
let currentIdbudgetArray = [];
let selectedBudgets = new Set(); // Stocke les IDs des budgets cochés

function saveSelectedBudgetsFromDisplay() {
	$(".budget_contain").each(function () {
		let id = $(this).find(".delete_budget").data("id");
		if (id) selectedBudgets.add(String(id));
	});
	let idbudgetVal = $("#idbudget").val();
	if (idbudgetVal && idbudgetVal !== "") {
		idbudgetVal.split(",").forEach((id) => {
			if (id) selectedBudgets.add(String(id));
		});
	}
}

// Fonction pour restaurer les checkboxes dans le modal
function restoreSelectedBudgetsInModal() {
	$(".choix_budget").each(function () {
		let id = String($(this).data("id"));
		if (selectedBudgets.has(id)) {
			$(this).prop("checked", true);
			$(this).attr("data-check", "true");
		} else {
			$(this).prop("checked", false);
			$(this).attr("data-check", "false");
		}
	});
}

// Initialiser selectedBudgets depuis l'input caché au chargement
function initSelectedBudgets() {
	let idbudgetVal = $("#idbudget").val();
	if (idbudgetVal && idbudgetVal !== "") {
		let ids = idbudgetVal.split(",");
		ids.forEach((id) => {
			if (id) selectedBudgets.add(String(id));
		});
	}
}

function select_budget(idactivite = "", idbudget = [], page = 1) {
	currentIdactivite = idactivite;
	currentIdbudgetArray = idbudget;
	currentBudgetPage = page;

	if (idbudget && idbudget.length > 0) {
		idbudget.forEach((id) => {
			if (id) selectedBudgets.add(String(id));
		});
	}

	let idbudgetVal = $("#idbudget").val();
	if (idbudgetVal && idbudgetVal !== "") {
		let ids = idbudgetVal.split(",");
		ids.forEach((id) => {
			if (id) selectedBudgets.add(String(id));
		});
	}

	let recherche_tab = `
        <table class="table table">
            <thead class="table-info">
                <tr>
                    <th>Choix</th>
                    <th>Ligne budgetaire</th>
                    <th>Intitulé</th>
                    <th>Montant alloué</th>
                    <th>Reliquat</th>
                </tr>
            </thead>
            <tbody id="budget-tbody">
                <tr><td colspan="5" class="text-center"><div class="spinner-border"></div></td</tr>
            </tbody>
        </table>
        <div id="budget-pagination" class="text-center mt-2"></div>
        <button class="btn btn-info btn-sm" id="budget_valide">VALIDER</button>
    `;

	$("#budget_modal").html(recherche_tab);
	loadBudgetPage(idactivite, idbudget, page);
}

function loadBudgetPage(idactivite, idbudget, page) {
	let idprojet = $("#projet").val();

	$.ajax({
		url: base_url("Budget/getProduitByactivite_json"),
		type: "post",
		data: {
			idactivite: idactivite,
			idprojet: idprojet,
			page: page,
			per_page: 10,
		},
		dataType: "json",
	})
		.done(function (response) {
			if (response.success && response.datas.length > 0) {
				const datas = response.datas;
				const total = response.total || datas.length;
				const totalPages = response.total_pages || Math.ceil(total / 10);

				let tbody = "";
				for (let i = 0; i < datas.length; i++) {
					const element = datas[i];
					let montantAlloue = parseInt(element.budget);
					let reste = parseInt(element.reste);
					let isChecked = selectedBudgets.has(String(element.idbudget));

					tbody += `
                    <tr>
                        <td class="text-center">
                            <input class='choix_budget form-check-input' 
                                   data-reste='${element.reste}' 
                                   data-ligne='${escapeHtml(element.ligne_budget)}' 
                                   data-check='${isChecked}' 
                                   type="checkbox" 
                                   name='budget' 
                                   data-id='${element.idbudget}' 
                                   data-designation='${escapeHtml(element.designation_budget)}' 
                                   data-montant='${element.budget}' 
                                   ${isChecked ? "checked" : ""}>
                        </td>
                        <td>${escapeHtml(element.ligne_budget)}</td>
                        <td>${escapeHtml(element.designation_budget)}</td>
                        <td>${montantAlloue.toLocaleString("fr-FR") + " Ar"}</td>
                        <td>${reste.toLocaleString("fr-FR") + " Ar"}</td>
                    </tr>`;
				}
				$("#budget-tbody").html(tbody);

				let paginationHtml = "";
				if (totalPages > 1) {
					paginationHtml =
						'<nav><ul class="pagination pagination-sm justify-content-center">';
					for (let i = 1; i <= totalPages; i++) {
						paginationHtml += `<li class="page-item ${i === page ? "active" : ""}">
                        <a class="page-link budget-page" href="#" data-page="${i}">${i}</a>
                    </li>`;
					}
					paginationHtml += "</ul></nav>";
				}
				$("#budget-pagination").html(paginationHtml);

				$(".budget-page")
					.off("click")
					.on("click", function (e) {
						e.preventDefault();
						loadBudgetPage(idactivite, idbudget, $(this).data("page"));
					});
			} else {
				$("#budget-tbody").html(
					'<tr><td colspan="5" class="text-secondary text-center">Aucun budget trouvé</td</tr>',
				);
				$("#budget-pagination").html("");
			}
		})
		.fail(function () {
			$("#budget-tbody").html(
				'<tr><td colspan="5" class="text-danger text-center">Erreur de chargement</td</tr>',
			);
			$("#budget-pagination").html("");
		});
}

function escapeHtml(str) {
	if (!str) return "";
	return str.replace(/[&<>]/g, function (m) {
		if (m === "&") return "&amp;";
		if (m === "<") return "&lt;";
		if (m === ">") return "&gt;";
		return m;
	});
}

function recherche_agent_content(recherche = "") {
	let content = "";
	let recherche_tab = `
        <form>
            <div class="input-group mt-3 mb-1">
                <input name="recherche" id='beneficiaire_search' type="text" class="form-control" placeholder="Recherche" value='${recherche}'>
                <a class="btn btn-info" id='recherche_beneficiaire'>
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
            </div>
            <p class="text-secondary d-none mb-0" id="msg-search">Aucun résultat...</p>
            <p class="text-danger d-none mb-0" id="msg-search_vide">Veuillez selectionner un beneficiaire</p>
        </form>
        <form>
        <table class="table table">
            <thead class="table-info">
                <tr>
                    <th>Choix</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse</th>
                    <th>Numéro Télephone</th>
                    <th>Email</th>
                </tr>
            </thead>
    `;
	$.ajax({
		url: base_url("User/search_json"),
		type: "post",
		data: { recherche: recherche, type: "Agent" },
		dataType: "json",
		async: false,
	}).done(function (response) {
		if (response.success) {
			const datas = response.datas;
			recherche = recherche_tab;
			recherche += `<tbody id='tableau_beneficiaire'>`;
			for (let i = 0; i < datas.length; i++) {
				const element = datas[i];
				recherche += `
                    <tr>
                        <td><input class='choix_beneficiaire' type="radio" name='beneficiaire' data-id='${element.idUser}' data-nom='${element.nomUser}' data-prenom='${element.prenomUser}'></td>
                        <td>${element.nomUser}</td>
                        <td>${element.prenomUser}</td>
                        <td>${element.adress}</td>
                        <td>${element.contact}</td>`;
				if (element.mailbene != "") {
					recherche += `<td>${element.mail}</td>`;
				} else {
					recherche += `<td>--</td>`;
				}
				recherche += `</tr>`;
			}
			recherche += `</tbody></table></form>
            <button data-nom="" data-prenom="" data-tel="" class="btn btn-info btn-sm" id="search_valide">VALIDER</button>`;
			content = recherche;
		} else {
			content = `<p class="text-secondary">Aucun agent trouvé</p>
            <button class="btn btn-info btn-sm" id="retourn">Retour</button>`;
		}
	});
	return content;
}

function check_budgete() {
	// Mettre à jour selectedBudgets avec les cases cochées
	$(".choix_budget:checked").each(function () {
		selectedBudgets.add(String($(this).data("id")));
	});

	let content = ``;
	let idbudgetArray = [];
	let somme = 0;

	let allIds = Array.from(selectedBudgets);

	if (allIds.length === 0) {
		$("#budget_container").html("");
		$("#idbudget").val("");
		return;
	}

	for (let idx = 0; idx < allIds.length; idx++) {
		let id = allIds[idx];
		let checkbox = $(`.choix_budget[data-id="${id}"]`);

		if (checkbox.length > 0) {
			let designation = checkbox.data("designation");
			let montant = parseInt(checkbox.data("montant"));
			let reste = parseInt(checkbox.attr("data-reste"));
			let ligne = checkbox.data("ligne");

			somme += montant;
			idbudgetArray.push(id);

			content += `
                <div class="budget_contain p-2 bg-light">
                    <div class="budget_designation">
                        <p class="">${ligne} - ${designation}</p>
                    </div>
                    <div class="budget_montant">
                        <p>Reliquat : ${reste.toLocaleString("fr-FR")} Ar</p>
                    </div>
                    <div class="budget_avance">
                        <input type="number" placeholder="Montant de l'avance" class="form-control all-avance" id="avance-${id}" name="avances[${id}]">
                        <input name="idbudget" id="idbudget" type="text" class="form-control input_form-control d-none" name='idbudget[${id}]' readonly>
                    </div>
                    <div class="btn_delete ">
                        <span class="text-danger delete_budget" data-id='${id}'><i class="fa-solid fa-trash"></i>Supprimer</span>
                    </div>
                </div>`;
		} else {
			idbudgetArray.push(id);
		}
	}

	$("#budget_container").html(content);
	$("#idbudget").val(idbudgetArray.join(","));

	let total = 0;
	$(".all-avance").each(function () {
		const val = parseFloat($(this).val());
		if (!isNaN(val) && val > 0) total += val;
	});
	$("#avance").val(total);
}

function updateTotalAvance() {
	let total = 0;
	$(".all-avance").each(function () {
		const val = parseFloat($(this).val());
		if (!isNaN(val) && val > 0) total += val;
	});
	$("#avance").val(total);
}

$(document).on("click", ".filtre-projet", function () {
	let idprojet = $(this).data("idprojet");
	$(".filtre-projet").removeClass("projet_active");
	$(this).addClass("projet_active");
	$.ajax({
		url: base_url("Mission/ajaxFiltreProjet"),
		type: "GET",
		data: { idprojet: idprojet },
		beforeSend: function () {
			$("#tbody-mission").html('<tr><td colspan="11">Chargement...</td</tr>');
		},
		success: function (html) {
			$("#tbody-mission").html(html);
		},
		error: function () {
			$("#tbody-mission").html(
				'<tr><td colspan="11">Erreur lors du chargement</td</tr>',
			);
		},
	});
});

$(document).ready(function () {
	initSelectedBudgets();

	$(document).on("click", ".close_budget", function () {
		$("#mybudgetmodal").modal("hide");
	});

	$(document).on("change", ".choix_budget", function () {
		const id = String($(this).data("id"));
		if ($(this).is(":checked")) {
			selectedBudgets.add(id);
			$(this).attr("data-check", true);
		} else {
			selectedBudgets.delete(id);
			$(this).attr("data-check", false);
		}
	});

	$(document.body).on("click", "#budget_valide", function () {
		// Mettre à jour selectedBudgets avec les cases cochées
		$(".choix_budget:checked").each(function () {
			selectedBudgets.add(String($(this).data("id")));
		});
		$(".choix_budget").each(function () {
			if (!$(this).is(":checked")) {
				selectedBudgets.delete(String($(this).data("id")));
			}
		});

		check_budgete();

		// Fermer le modal - méthode pour data-toggle="modal"
		$("#mybudgetmodal").modal("hide");
		$("body").removeClass("modal-open");
		$(".modal-backdrop").remove();

		// Alternative: cliquer sur le bouton de fermeture
		// $('.close_budget').click();
	});

	$(document).on("click", ".delete_budget", function () {
		let idbudget = String($(this).data("id"));
		selectedBudgets.delete(idbudget);
		let check = $(`.choix_budget[data-id="${idbudget}"]`);
		if (check.length > 0) {
			check.prop("checked", false);
			check.attr("data-check", false);
		}
		check_budgete();
	});

	$(document).on("click", "#select_budget", function () {
		const idactivite = $("#codeprojet").val();

		if (idactivite != "" && idactivite !== null) {
			$("#ms_projet").addClass("d-none");
			$("#budget_modal").html(
				'<div class="text-center p-5"><div class="spinner-border"></div></div>',
			);

			// Sauvegarder les budgets existants
			saveSelectedBudgetsFromDisplay();

			const idbudget = $("#idbudget").val();
			let tab_idbudget = Array.from(selectedBudgets);

			select_budget(idactivite, tab_idbudget);
			$("#active_buget_modal").click();
		} else {
			$("#ms_projet").removeClass("d-none");
			Myalert.erreur("Veuillez d'abord saisir un code projet");
		}
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
						$("#codeprojet").val(projet.codeprojet);
						$("#titreprojet").val(projet.titreprojet);
						$("#projet").val(projet.idprojet);
						// Réinitialiser les budgets quand on change de projet
						selectedBudgets.clear();
						$("#budget_container").html("");
						$("#idbudget").val("");
					} else {
						Myalert.erreur("Ce code de projet n'existe pas.");
					}
				})
				.fail(function () {
					console.error("Erreur dans la verification du mission ");
				});
		}
	});

	$(document.body).on("click", "#valider", function () {
		const asm = $("#asm").val();
		const om = $("#om").val();
		$.ajax({
			type: "post",
			url: base_url("Mission/verify"),
			data: { asm: asm, om: om },
			dataType: "json",
			success: function (response) {
				if (response.success) {
					$("#om_sm").addClass("d-none");
					$("#ams_sm").addClass("d-none");
					if (
						$("#titreprojet").val() != "" &&
						$("#objet").val() != "" &&
						$("#asm").val() != "" &&
						$("#om").val() != "" &&
						$("#date_debut").val() != "" &&
						$("#date_fin").val() != "" &&
						$("#idbudget").val() != null &&
						$("#idbudget").val() != "" &&
						$("#idagent").val() != "" // ← AJOUTEZ CETTE LIGNE
					) {
						shwoSpinner("#valider", []);
						$("#T-valider").attr("type", "submit");
						$("#T-valider").click();
					} else {
						if ($("#idbudget").val() == null || $("#idbudget").val() == "") {
							Myalert.erreur("Veuillez sélectionner les budgets utilisés");
						}
						if ($("#idagent").val() == "") {
							// ← AJOUTEZ CETTE CONDITION
							Myalert.erreur("Veuillez sélectionner un agent bénéficiaire");
						}
					}
				} else {
					if (response.type == "om") {
						$("#om_sm").removeClass("d-none");
					} else if (response.type == "asm") {
						$("#ams_sm").removeClass("d-none");
					} else {
						$("#om_sm").removeClass("d-none");
						$("#ams_sm").removeClass("d-none");
					}
				}
			},
		});
	});

	$(document).on("click", "#select_demandeur", function () {
		$("#demandeur_modal").html(recherche_agent_content());
	});

	$(document).on("change", ".choix_beneficiaire", function () {
		$("#search_valide").attr("data-id", $(this).data("id"));
	});

	$(document).on("click", "#search_valide", function () {
		const idagent = $(this).data("id");
		$("#idagent").val(idagent);
		if ($("#idagent").val() != "") {
			$("#T-valider").attr("type", "submit");
			$("#T-valider").click();
		} else {
			shwoSpinner("#valider", [], false);
			Myalert.erreur("Veuillez choisir un agent.");
		}
	});

	$(document).on("click", "#recherche_beneficiaire", function () {
		$("#demandeur_modal").html(
			recherche_agent_content($("#beneficiaire_search").val()),
		);
	});

	$(document).on("click", "#retourn", function () {
		$("#demandeur_modal").html(recherche_agent_content());
	});

	$(document).on("click", ".edit", function () {
		const idmission = $(this).data("id");
		$("#om_sm_modif").addClass("d-none");
		$("#ams_sm_modif").addClass("d-none");
		$.ajax({
			method: "post",
			url: base_url("Mission/donner"),
			data: { idmission: idmission },
			dataType: "json",
		})
			.done(function (response) {
				if (response.success) {
					const data = response.data;
					$("#idmission").val(data.idmission);
					$("#objet_modif").val(data.objet_mission);
					$("#lieu_modif").val(data.lieu_mission);
					$("#asm_modif").val(data.numero_asm);
					$("#om_modif").val(data.numero_om);
					const date_debut_mission = new Date(data.date_debut_mission);
					const formattedDate_debut = date_debut_mission
						.toISOString()
						.split("T")[0];
					const date_fin_mission = new Date(data.date_fin_mission);
					const formattedDate_fin = date_fin_mission
						.toISOString()
						.split("T")[0];
					$("#date_debut_modif").val(formattedDate_debut);
					$("#date_fin_modif").val(formattedDate_fin);
					$("#id_modif").val(data.idmission);
				} else {
					Myalert.erreur();
				}
			})
			.fail(function () {});
	});

	$(document).on("click", ".detail", function () {
		$("#details_modal_containt").html(LOADER);
		const id = $(this).data("id");
		$.ajax({
			type: "post",
			url: base_url("Mission/details"),
			data: { idmission: id },
			success: function (response) {
				$("#details_modal_containt").html(response);
			},
		});
	});

	$(document).on("click", ".delete", function () {
		const idmission = $(this).data("id");

		// Utiliser la fonction delete de Myalert avec callback
		Myalert.delete(
			"Êtes-vous sûr de vouloir supprimer cette mission ?",
			function () {
				// Confirmation Oui
				$.ajax({
					method: "post",
					url: base_url("Mission/delete"),
					data: { idmission: idmission },
					dataType: "json",
					success: function (response) {
						location.href = base_url("mission");
					},
					error: function () {
						console.error("erreur dans la suppression du mission");
						Myalert.erreur("Erreur lors de la suppression");
					},
				});
			},
			function () {
				// Annulation (optionnel)
				console.log("Suppression annulée");
			},
		);
	});

	$(document).on("click", "#modification", function () {
		let idmission = $("#idmission").val();
		let asm = $("#asm_modif").val();
		let om = $("#om_modif").val();
		$.ajax({
			method: "post",
			url: base_url("Mission/verify"),
			data: { asm: asm, om: om, idmission: idmission },
			dataType: "json",
			success: function (response) {
				if (response.success) {
					$("#om_sm_modif").addClass("d-none");
					$("#ams_sm_modif").addClass("d-none");
					$("#TM-valider").attr("type", "submit");
					$("#TM-valider").click();
				} else {
					$("#TM-valider").attr("type", "button");
					if (response.type == "om") {
						$("#om_sm_modif").removeClass("d-none");
					} else if (response.type == "asm") {
						$("#ams_sm_modif").removeClass("d-none");
					} else {
						$("#om_sm_modif").removeClass("d-none");
						$("#ams_sm_modif").removeClass("d-none");
					}
				}
			},
		});
	});
});

$(document).on("input change", ".all-avance", function () {
	let total = 0;
	$(".all-avance").each(function () {
		const val = parseFloat($(this).val().replace(",", "."));
		if (!isNaN(val)) total += val;
	});
	$("#avance").val(total);
});

// ===== FONCTIONS RECHERCHE AGENT INLINE =====
function searchAgentsInline(recherche) {
	// Recherche même avec 1 caractère
	if (recherche.length < 1) {
		$("#agent_results").hide();
		return;
	}

	$.ajax({
		url: base_url("User/search_json"),
		type: "post",
		data: { recherche: recherche, type: "Agent" },
		dataType: "json",
		beforeSend: function () {
			$("#agent_results")
				.html(
					'<div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div> Chargement...</div>',
				)
				.show();
		},
	})
		.done(function (response) {
			if (response.success && response.datas.length > 0) {
				let html = '<div style="max-height: 300px; overflow-y: auto;">';
				for (let i = 0; i < response.datas.length; i++) {
					const agent = response.datas[i];
					html += `
                <div class="agent-result-item" data-id="${agent.idUser}" data-nom="${agent.nomUser}" data-prenom="${agent.prenomUser}" data-contact="${agent.contact}" data-mail="${agent.mail || ""}">
                    <div class="agent-result-name">
                        <i class="fa-solid fa-user-circle"></i> <strong>${escapeHtml(agent.nomUser)}</strong> ${escapeHtml(agent.prenomUser)}
                    </div>
                    <div class="agent-result-contact">
                        <span><i class="fa-solid fa-phone"></i> ${agent.contact}</span>
                        ${agent.adress ? `<span><i class="fa-solid fa-location-dot"></i> ${escapeHtml(agent.adress)}</span>` : ""}
                        ${agent.mail ? `<span><i class="fa-solid fa-envelope"></i> ${escapeHtml(agent.mail)}</span>` : ""}
                    </div>
                </div>`;
				}
				html += "</div>";
				$("#agent_results").html(html).show();
			} else {
				$("#agent_results")
					.html(
						'<div class="text-center p-3 text-secondary"><i class="fa-solid fa-user-slash"></i> Aucun agent trouvé</div>',
					)
					.show();
			}
		})
		.fail(function () {
			$("#agent_results")
				.html(
					'<div class="text-center p-3 text-danger"><i class="fa-solid fa-circle-exclamation"></i> Erreur de chargement</div>',
				)
				.show();
		});
}

// Recherche au clic sur le bouton
$(document).on("click", "#btn_search_agent", function () {
	let recherche = $("#agent_search").val();
	if (recherche.length >= 1) {
		searchAgentsInline(recherche);
	} else {
		Myalert.erreur("Veuillez saisir au moins 1 caractère");
	}
});

// Recherche en tapant au clavier
$(document).on("keyup", "#agent_search", function () {
	let recherche = $(this).val().trim();
	if (recherche.length >= 1) {
		searchAgentsInline(recherche);
	} else if (recherche.length === 0) {
		$("#agent_results").hide().empty();
	}
});

// Sélectionner un agent
$(document).on("click", ".agent-result-item", function () {
	let id = $(this).data("id");
	let nom = $(this).data("nom");
	let prenom = $(this).data("prenom");
	let contact = $(this).data("contact");
	let mail = $(this).data("mail");

	$("#idagent").val(id);
	$("#selected_agent_name").html(`
        <strong>${escapeHtml(nom)} ${escapeHtml(prenom)}</strong><br>
        <small><i class="fa-solid fa-phone"></i> ${contact} ${mail ? '| <i class="fa-solid fa-envelope"></i> ' + escapeHtml(mail) : ""}</small>
    `);
	$("#selected_agent_info").removeClass("d-none");
	$("#agent_search").val("");
	$("#agent_results").hide();

	if (typeof Myalert !== "undefined" && Myalert.showToast) {
		Myalert.showToast(`${nom} ${prenom} sélectionné`, "success", 2000);
	}
});

// Effacer l'agent sélectionné
$(document).on("click", "#clear_agent", function () {
	$("#idagent").val("");
	$("#selected_agent_info").addClass("d-none");
	$("#agent_search").val("");
	$("#agent_results").hide();

	if (typeof Myalert !== "undefined" && Myalert.showToast) {
		Myalert.showToast("Agent désélectionné", "info", 1500);
	}
});
