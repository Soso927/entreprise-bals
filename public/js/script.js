// ========== VALIDATION DU FORMULAIRE ==========
document.getElementById('devisForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Vérifier les champs obligatoires
    if (!validerFormulaire()) {
        return false;
    }

    // Si validation OK, envoyer l'email
    envoyerEmail();
});

// ========== FONCTION DE VALIDATION ==========
function validerFormulaire() {
    let erreurs = [];

    // Vérifier les informations de contact
    if (!document.getElementById('distributeur').value) {
        erreurs.push("Le nom du distributeur est obligatoire");
    }
    if (!document.getElementById('contact').value) {
        erreurs.push("Le nom du contact est obligatoire");
    }
    if (!document.getElementById('affaire').value) {
        erreurs.push("La référence de l'affaire est obligatoire");
    }
    if (!document.getElementById('telephone').value) {
        erreurs.push("Le numéro de téléphone est obligatoire");
    }
    if (!document.getElementById('email').value) {
        erreurs.push("L'email est obligatoire");
    }

    // Vérifier le type de coffret
    let typeCoffretChecked = document.querySelectorAll('input[name="typeCoffret"]:checked').length;
    if (typeCoffretChecked === 0) {
        erreurs.push("Veuillez sélectionner au moins un type de coffret");
    }

    // Vérifier les matériaux
    let materiauxChecked = document.querySelector('input[name="materiaux"]:checked');
    if (!materiauxChecked) {
        erreurs.push("Veuillez sélectionner un matériau");
    }

    // Vérifier l'indice de protection
    let ipChecked = document.querySelector('input[name="ip"]:checked');
    if (!ipChecked) {
        erreurs.push("Veuillez sélectionner un indice de protection (IP)");
    }

    // Afficher les erreurs s'il y en a
    if (erreurs.length > 0) {
        alert("⚠️ Veuillez corriger les erreurs suivantes :\n\n• " + erreurs.join("\n• "));
        return false;
    }

    return true;
}

// ========== FONCTION PRINCIPALE : GÉNÉRER LE CONTENU DE L'EMAIL ==========
function genererContenuEmail() {
    // 1. RÉCUPÉRER LES INFORMATIONS DE CONTACT
    let distributeur = document.getElementById('distributeur').value || "(non renseigné)";
    let contact = document.getElementById('contact').value || "(non renseigné)";
    let affaire = document.getElementById('affaire').value || "(non renseigné)";
    let telephone = document.getElementById('telephone').value || "(non renseigné)";
    let email = document.getElementById('email').value || "(non renseigné)";

    // 2. CONSTRUIRE L'EMAIL
    let contenu = "";
    contenu += "╔════════════════════════════════════════════════════════════╗\n";
    contenu += "║     DEMANDE DE DEVIS - COFFRET DE CHANTIER                 ║\n";
    contenu += "╚════════════════════════════════════════════════════════════╝\n\n";

    // Section Contact
    contenu += "┌─────────────────────────────────────────────────────────┐\n";
    contenu += "│   📋 INFORMATIONS DE CONTACT                             │\n";
    contenu += "└─────────────────────────────────────────────────────────┘\n";
    contenu += "Distributeur       : " + distributeur + "\n";
    contenu += "Contact            : " + contact + "\n";
    contenu += "Affaire/Référence  : " + affaire + "\n";
    contenu += "Téléphone          : " + telephone + "\n";
    contenu += "Email              : " + email + "\n";
    contenu += "\n";

    // Section Type de coffret
    let typesCoffret = [];
    document.querySelectorAll('input[name="typeCoffret"]:checked').forEach(function (checkbox) {
        typesCoffret.push(checkbox.value);
    });

    if (typesCoffret.length > 0) {
        contenu += "┌─────────────────────────────────────────────────────────┐\n";
        contenu += "│   🔧 TYPE DE COFFRET                                     │\n";
        contenu += "└─────────────────────────────────────────────────────────┘\n";
        typesCoffret.forEach(function (type) {
            contenu += "  ✓ " + type + "\n";
        });
        contenu += "\n";
    }

    // Section Matériaux
    let materiaux = document.querySelector('input[name="materiaux"]:checked');
    if (materiaux) {
        contenu += "┌─────────────────────────────────────────────────────────┐\n";
        contenu += "│   🛠️ MATÉRIAUX                                            │\n";
        contenu += "└─────────────────────────────────────────────────────────┘\n";
        contenu += "  ➤ " + materiaux.value + "\n\n";
    }

    // Section IP
    let ip = document.querySelector('input[name="ip"]:checked');
    if (ip) {
        contenu += "┌─────────────────────────────────────────────────────────┐\n";
        contenu += "│   🔒 INDICE DE PROTECTION                                │\n";
        contenu += "└─────────────────────────────────────────────────────────┘\n";
        contenu += "  ➤ " + ip.value + "\n\n";
    }

    // Section Protection
    let protTeteChecked = [];
    let protPrisesChecked = [];
    document.querySelectorAll('input[name="protectionTete"]:checked').forEach(function (checkbox) {
        protTeteChecked.push(checkbox.value);
    });
    document.querySelectorAll('input[name="protectionPrises"]:checked').forEach(function (checkbox) {
        protPrisesChecked.push(checkbox.value);
    });

    if (protTeteChecked.length > 0 || protPrisesChecked.length > 0) {
        contenu += "┌─────────────────────────────────────────────────────────┐\n";
        contenu += "│   ⚡ PROTECTION                                          │\n";
        contenu += "└─────────────────────────────────────────────────────────┘\n";
        if (protTeteChecked.length > 0) {
            contenu += "Protection de tête  : " + protTeteChecked.join(", ") + "\n";
        }
        if (protPrisesChecked.length > 0) {
            contenu += "Protection prises   : " + protPrisesChecked.join(", ") + "\n";
        }
        contenu += "\n";
    }

    // Section Prises - DÉTAILLÉE
    contenu += "┌─────────────────────────────────────────────────────────┐\n";
    contenu += "│   🔌 PRISES                                              │\n";
    contenu += "└─────────────────────────────────────────────────────────┘\n";

    let aucunePrise = true;

    // NF 10/16A
    let nfQte = document.getElementById('prise_nf_qte').value;
    if (nfQte && parseInt(nfQte) > 0) {
        contenu += "NF 10/16A (domestique)\n";
        contenu += "  → Quantité : " + nfQte + "\n";
        contenu += "  → Brochage : 2P+T\n";
        contenu += "  → Tension  : 230V\n\n";
        aucunePrise = false;
    }

    // CEI 16A
    let cei16Qte = document.getElementById('prise_16a_qte').value;
    let cei16Brochage = document.getElementById('prise_16a_brochage').value;
    let cei16Tension = document.getElementById('prise_16a_tension').value;
    if (cei16Qte && parseInt(cei16Qte) > 0) {
        contenu += "CEI 16A\n";
        contenu += "  → Quantité : " + cei16Qte + "\n";
        contenu += "  → Brochage : " + (cei16Brochage || "non spécifié") + "\n";
        contenu += "  → Tension  : " + (cei16Tension || "non spécifié") + "\n\n";
        aucunePrise = false;
    }

    // CEI 32A
    let cei32Qte = document.getElementById('prise_32a_qte').value;
    let cei32Brochage = document.getElementById('prise_32a_brochage').value;
    let cei32Tension = document.getElementById('prise_32a_tension').value;
    if (cei32Qte && parseInt(cei32Qte) > 0) {
        contenu += "CEI 32A\n";
        contenu += "  → Quantité : " + cei32Qte + "\n";
        contenu += "  → Brochage : " + (cei32Brochage || "non spécifié") + "\n";
        contenu += "  → Tension  : " + (cei32Tension || "non spécifié") + "\n\n";
        aucunePrise = false;
    }

    // CEI 63A
    let cei63Qte = document.getElementById('prise_63a_qte').value;
    let cei63Brochage = document.getElementById('prise_63a_brochage').value;
    let cei63Tension = document.getElementById('prise_63a_tension').value;
    if (cei63Qte && parseInt(cei63Qte) > 0) {
        contenu += "CEI 63A\n";
        contenu += "  → Quantité : " + cei63Qte + "\n";
        contenu += "  → Brochage : " + (cei63Brochage || "non spécifié") + "\n";
        contenu += "  → Tension  : " + (cei63Tension || "non spécifié") + "\n\n";
        aucunePrise = false;
    }

    // CEI 125A
    let cei125Qte = document.getElementById('prise_125a_qte').value;
    let cei125Brochage = document.getElementById('prise_125a_brochage').value;
    let cei125Tension = document.getElementById('prise_125a_tension').value;
    if (cei125Qte && parseInt(cei125Qte) > 0) {
        contenu += "CEI 125A\n";
        contenu += "  → Quantité : " + cei125Qte + "\n";
        contenu += "  → Brochage : " + (cei125Brochage || "non spécifié") + "\n";
        contenu += "  → Tension  : " + (cei125Tension || "non spécifié") + "\n\n";
        aucunePrise = false;
    }

    if (aucunePrise) {
        contenu += "  (aucune prise spécifiée)\n\n";
    }

    // Section Pièces jointes
    let fichiers = document.getElementById('pieceJointes').files;
    // Section Pièces jointes
    if (fichiersSelectionnes.length > 0) {
        contenu += "┌─────────────────────────────────────────────────────────┐\n";
        contenu += "│   📎 PIÈCES JOINTES                                      │\n";
        contenu += "└─────────────────────────────────────────────────────────┘\n";
        for (let i = 0; i < fichiersSelectionnes.length; i++) {
            contenu += "  • " + fichiersSelectionnes[i].name + " (" + formatTailleFichier(fichiersSelectionnes[i].size) + ")\n";
        }
        contenu += "\n";
    }

    // Section Observations
    let observations = document.getElementById('observations').value;
    if (observations) {
        contenu += "┌─────────────────────────────────────────────────────────┐\n";
        contenu += "│   💬 OBSERVATIONS                                        │\n";
        contenu += "└─────────────────────────────────────────────────────────┘\n";
        contenu += observations + "\n\n";
    }

    // Footer
    contenu += "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    contenu += "           BALS France - Coffrets sur mesure\n";
    contenu += "                 info@bals-france.fr\n";
    contenu += "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    return contenu;
}

// ========== FONCTION : FORMATER LA TAILLE DE FICHIER ==========
function formatTailleFichier(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// ========== VARIABLE GLOBALE POUR STOCKER LES FICHIERS ==========
let fichiersSelectionnes = [];

// ========== FONCTION : AFFICHER LES FICHIERS SÉLECTIONNÉS ==========
function afficherFichiers() {
    const input = document.getElementById('pieceJointes');
    const listeFichiers = document.getElementById('listeFichiers');

    // Ajouter les nouveaux fichiers à la liste existante
    for (let i = 0; i < input.files.length; i++) {
        fichiersSelectionnes.push(input.files[i]);
    }

    // Afficher la liste
    if (fichiersSelectionnes.length > 0) {
        let html = '<div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px;">';
        html += '<strong>Fichiers sélectionnés :</strong><br><br>';

        fichiersSelectionnes.forEach(function (fichier, index) {
            html += '<div style="display: flex; align-items: center; justify-content: space-between; padding: 8px; background-color: white; margin-bottom: 8px; border-radius: 5px; border: 1px solid #ddd;">';
            html += '<span>📄 ' + fichier.name + ' <small style="color: #666;">(' + formatTailleFichier(fichier.size) + ')</small></span>';
            html += '<button type="button" onclick="supprimerFichier(' + index + ')" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">❌ Supprimer</button>';
            html += '</div>';
        });

        html += '</div>';
        listeFichiers.innerHTML = html;
    } else {
        listeFichiers.innerHTML = '';
    }

    // Réinitialiser l'input pour permettre de rajouter des fichiers
    input.value = '';

    // Mettre à jour la prévisualisation
    updatePreview();
}

// ========== FONCTION : SUPPRIMER UN FICHIER ==========
function supprimerFichier(index) {
    // Supprimer le fichier de la liste
    fichiersSelectionnes.splice(index, 1);

    // Réafficher la liste
    afficherFichiers();

    // Mettre à jour la prévisualisation
    updatePreview();
}

// ========== FONCTION : METTRE À JOUR LA PRÉVISUALISATION ==========
function updatePreview() {
    let contenu = genererContenuEmail();
    document.getElementById('emailPreview').textContent = contenu;
}

// ========== MISE À JOUR DU VISUEL DYNAMIQUE ==========
function updateCoffretImage() {
    // Calcul du total des prises
    const nf = parseInt(document.getElementById('prise_nf_qte').value) || 0;
    const c16 = parseInt(document.getElementById('prise_16a_qte').value) || 0;
    const c32 = parseInt(document.getElementById('prise_32a_qte').value) || 0;
    const c63 = parseInt(document.getElementById('prise_63a_qte').value) || 0;
    const c125 = parseInt(document.getElementById('prise_125a_qte').value) || 0;

    const totalPrises = nf + c16 + c32 + c63 + c125;
    const imgElement = document.getElementById('coffretImage');
    const labelElement = document.getElementById('priseCountLabel');

    labelElement.textContent = totalPrises + " prise(s) sélectionnée(s)";

    // Logique de changement d'image (Exemple basé sur la quantité)
    if (totalPrises === 0) {
        imgElement.src = "images/coffret-vide.png";
    } else if (totalPrises <= 2) {
        imgElement.src = "images/coffret-petit-industrie.png";
    } else if (totalPrises <= 4) {
        imgElement.src = "images/coffret-moyen-industrie.png";
    } else {
        imgElement.src = "images/coffret-grand-industrie.png";
    }
}

// Modifiez votre fonction updatePreview existante pour inclure le visuel
function updatePreview() {
    updateCoffretImage(); // <--- Ajout ici
    let contenu = genererContenuEmail();
    document.getElementById('emailPreview').textContent = contenu;
}

// ========== FONCTION : COPIER LE TEXTE DANS LE PRESSE-PAPIERS ==========
function copierTexte() {
    let contenu = genererContenuEmail();

    // Créer un élément textarea temporaire
    let textarea = document.createElement('textarea');
    textarea.value = contenu;
    document.body.appendChild(textarea);

    // Sélectionner et copier le texte
    textarea.select();
    document.execCommand('copy');

    // Supprimer l'élément temporaire
    document.body.removeChild(textarea);

    // Message de confirmation
    alert('✅ Le contenu a été copié dans le presse-papiers !\n\nVous pouvez maintenant le coller dans votre email.');
}

// ========== FONCTION : OUVRIR LE CLIENT EMAIL ==========
function envoyerEmail() {
    let affaire = document.getElementById('affaire').value || "Sans référence";
    let contenu = genererContenuEmail();

    // Créer le lien mailto
    let sujet = "Demande de devis coffret - " + affaire;
    let lienEmail = "mailto:info@bals-france.fr";
    lienEmail += "?subject=" + encodeURIComponent(sujet);
    lienEmail += "&body=" + encodeURIComponent(contenu);
    
// Message d'information sur les pièces jointes
if (fichiersSelectionnes.length > 0) {
    alert("ℹ️ Votre client email va s'ouvrir.\n\n⚠️ IMPORTANT : Les pièces jointes ne peuvent pas être ajoutées automatiquement.\nVous devrez les joindre manuellement dans votre email.");
}

    // Ouvrir le client email
    window.location.href = lienEmail;
}

// Initialiser la prévisualisation au chargement de la page
window.onload = function () {
    updatePreview();
};