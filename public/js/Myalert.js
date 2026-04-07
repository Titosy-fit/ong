/* class Myalert {
    constructor(parameters) {
        this.container = '.corps';
    }
    static updated(texte = 'Modification effectuée.') {
        let the_content = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-success">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-succes" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(the_content)
    }
    static deleted(texte = 'Suppression effectuée.') {
        let the_content = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-success">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-succes" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(the_content)
    }
    static added(texte = 'Enregistrement effectué.') {
        let the_content = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-success">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-succes" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(the_content)
    }


    static delete(texte = 'Êtes-vous sûr de vouloir supprimer ?') {
        var alert = `<div class="volet"></div>
				<div class="_alert">
					<div class="close" id="close">
						<i class="fa-solid fa-x"></i>
					</div>
					<div class="_icon-question">
						<i class="fa-regular fa-circle-question"></i>
					</div>
					<div class="_message">
						<p>${texte} </p>
					</div>
					<div class="_btn-delete">
						<button type="button" class="button-warning" id="confirmeDelete">Oui</button>
						<button type="button" class="button-succes" id="cancelDelete">Non</button>
					</div>
				</div>`
        $('.corps').append(alert);
    }

    static erreur(texte = 'Une erreur s`\'est produite.') {
        let alert = `
            <div class="volet"></div>
            <div class="_alert">
                <div class="close" id="close">
                    <i class="fa-solid fa-x"></i>
                </div>
                <div class="_icon-warning">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="_message">
                    <p>${texte} </p>
                </div>
                <div class="_btn">
                    <button type="button" class="button-war" id="button">OK</button>
                </div>
            </div>
        `
        $('.corps').append(alert);
    }

    static spinnerB() {
        let spinner = `
                 <div id="spiner_big_container">
                    <div class="spinner-border text-muted"></div>
                </div>
        `  ;
        $('.corps').append(spinner);
    }

    static removeSpinnerB() {
        $('#spiner_big_container').remove();
    }
}



 */

class Myalert {
	constructor(parameters) {
		this.container = ".corps";
	}

	static initToastContainer() {
		// Vérifier si le conteneur existe déjà avant de le créer
		if ($("#toast-container").length === 0) {
			$("body").append(`
                <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
            `);
		}
	}

	static showToast(texte, type = "success", duration = 3000) {
		this.initToastContainer();

		// Empêcher les doublons pour le même message dans un court laps de temps
		const toastKey = `${texte}_${type}`;
		const lastToast = $(`#toast-container`).data("last-toast");
		const now = Date.now();

		if (lastToast && lastToast.key === toastKey && now - lastToast.time < 500) {
			return; // Ignorer si le même toast a été affiché il y a moins de 500ms
		}

		$(`#toast-container`).data("last-toast", { key: toastKey, time: now });

		// Icônes selon le type
		const icons = {
			success: '<i class="fa-regular fa-circle-check"></i>',
			error: '<i class="fa-solid fa-circle-exclamation"></i>',
			warning: '<i class="fa-regular fa-circle-question"></i>',
			info: '<i class="fa-solid fa-info-circle"></i>',
		};

		// Classes CSS selon le type
		const toastClasses = {
			success: "toast-success",
			error: "toast-error",
			warning: "toast-warning",
			info: "toast-info",
		};

		const toastId =
			"toast-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9);
		const toast = `
            <div id="${toastId}" class="toast-notification ${toastClasses[type]}" style="
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-bottom: 10px;
                padding: 12px 20px;
                min-width: 250px;
                max-width: 350px;
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideInRight 0.3s ease;
                border-left: 4px solid;
                position: relative;
            ">
                <div class="toast-icon" style="font-size: 20px;">
                    ${icons[type]}
                </div>
                <div class="toast-message" style="flex: 1; color: #333;">
                    <p style="margin: 0; font-size: 14px;">${texte}</p>
                </div>
                <button class="toast-close" style="
                    background: none;
                    border: none;
                    cursor: pointer;
                    color: #999;
                    font-size: 14px;
                    padding: 0;
                "><i class="fa-solid fa-x"></i></button>
            </div>
        `;

		$("#toast-container").append(toast);

		// Gestionnaire de fermeture avec off() pour éviter les doublons
		$(`#${toastId} .toast-close`)
			.off("click")
			.on("click", function () {
				$(`#${toastId}`).fadeOut(300, function () {
					$(this).remove();
				});
			});

		// Auto-fermeture après la durée spécifiée
		if (duration > 0) {
			setTimeout(() => {
				if ($(`#${toastId}`).length) {
					$(`#${toastId}`).fadeOut(300, function () {
						$(this).remove();
					});
				}
			}, duration);
		}
	}

	static updated(texte = "Modification effectuée.") {
		this.showToast(texte, "success");
	}

	static deleted(texte = "Suppression effectuée.") {
		this.showToast(texte, "success");
	}

	static added(texte = "Enregistrement effectué.") {
		this.showToast(texte, "success");
	}

	static delete(
		texte = "Êtes-vous sûr de vouloir supprimer ?",
		onConfirm,
		onCancel,
	) {
		this.initToastContainer();

		const toastId =
			"toast-confirm-" +
			Date.now() +
			"-" +
			Math.random().toString(36).substr(2, 9);
		const toast = `
            <div id="${toastId}" class="toast-confirm" style="
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-bottom: 10px;
                padding: 15px 20px;
                min-width: 280px;
                max-width: 350px;
                border-left: 4px solid #ffc107;
                position: relative;
            ">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <div style="font-size: 20px; color: #ffc107;">
                        <i class="fa-regular fa-circle-question"></i>
                    </div>
                    <div style="flex: 1; color: #333;">
                        <p style="margin: 0; font-size: 14px;">${texte}</p>
                    </div>
                    <button class="toast-close" style="
                        background: none;
                        border: none;
                        cursor: pointer;
                        color: #999;
                        font-size: 14px;
                        padding: 0;
                    "><i class="fa-solid fa-x"></i></button>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button class="confirm-yes" style="
                        background: #dc3545;
                        color: white;
                        border: none;
                        padding: 6px 15px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 13px;
                    ">Oui</button>
                    <button class="confirm-no" style="
                        background: #28a745;
                        color: white;
                        border: none;
                        padding: 6px 15px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 13px;
                    ">Non</button>
                </div>
            </div>
        `;

		$("#toast-container").append(toast);

		// Fermeture avec off() pour éviter les doublons
		$(`#${toastId} .toast-close, #${toastId} .confirm-no`)
			.off("click")
			.on("click", function () {
				$(`#${toastId}`).fadeOut(300, function () {
					$(this).remove();
					if (onCancel) onCancel();
				});
			});

		$(`#${toastId} .confirm-yes`)
			.off("click")
			.on("click", function () {
				$(`#${toastId}`).fadeOut(300, function () {
					$(this).remove();
					if (onConfirm) onConfirm();
				});
			});
	}

	static erreur(texte = "Une erreur s'est produite.") {
		this.showToast(texte, "error", 5000);
	}

	static spinnerB() {
		// Supprimer le spinner existant avant d'en créer un nouveau
		this.removeSpinnerB();

		this.initToastContainer();
		const spinnerId = "spinner-toast";

		const spinner = `
            <div id="${spinnerId}" style="
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-bottom: 10px;
                padding: 15px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
            ">
                <div class="spinner-border text-muted" style="width: 20px; height: 20px;"></div>
                <div style="color: #333;">Chargement en cours...</div>
            </div>
        `;
		$("#toast-container").append(spinner);
	}

	static removeSpinnerB() {
		$("#spinner-toast").remove();
	}
}

// Ajouter les animations CSS une seule fois
if (!$("#toast-styles").length) {
	$("head").append(`
        <style id="toast-styles">
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .toast-success {
                border-left-color: #28a745;
            }
            .toast-success .toast-icon {
                color: #28a745;
            }
            
            .toast-error {
                border-left-color: #dc3545;
            }
            .toast-error .toast-icon {
                color: #dc3545;
            }
            
            .toast-warning {
                border-left-color: #ffc107;
            }
            .toast-warning .toast-icon {
                color: #ffc107;
            }
            
            .toast-info {
                border-left-color: #17a2b8;
            }
            .toast-info .toast-icon {
                color: #17a2b8;
            }
            
            .toast-notification:hover,
            .toast-confirm:hover {
                box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            }
        </style>
    `);
}
