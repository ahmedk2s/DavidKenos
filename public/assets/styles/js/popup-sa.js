// Fonction pour créer et afficher le popup d'attente de validation des administrateurs
function createAdminWaitingPopup() {
    var popup = document.createElement('div');
    popup.id = 'adminWaitingApprovalPopup';
    popup.classList.add('admin-popup');
    popup.innerHTML = `
        <span class="close-btn" onclick="closeAdminPopup()">×</span>
        Un ou plusieurs administrateurs sont en attente de validation.
    `;

    document.body.appendChild(popup);
}

// Fonction pour fermer le popup
function closeAdminPopup() {
    var popup = document.getElementById('adminWaitingApprovalPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

// Récupérer la valeur de l'attribut data-is-admin-waiting
var isAdminWaitingForApproval = document.querySelector('[data-is-admin-waiting]').getAttribute('data-is-admin-waiting') === 'true';

// Vérifier si des administrateurs sont en attente de validation
document.addEventListener('DOMContentLoaded', function() {
    var isSuperAdmin = document.querySelector('[data-is-super-admin]').getAttribute('data-is-super-admin') === 'true';
    if (isAdminWaitingForApproval && isSuperAdmin) {
        createAdminWaitingPopup();
    }
});
