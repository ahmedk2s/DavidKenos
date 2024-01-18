// Fonction pour créer et afficher le popup
function createAdminApprovalPopup() {
    // Créer le conteneur du popup
    var popup = document.createElement('div');
    popup.id = 'adminApprovalPopup';
    popup.style.position = 'fixed';
    popup.style.right = '20px';
    popup.style.top = '20%';
    popup.style.width = '300px';
    popup.style.backgroundColor = '#1f2547';
    popup.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.2)';
    popup.style.padding = '20px';
    popup.style.zIndex = '1000';
    popup.style.display = 'none';
    popup.style.color = 'white';

    // Ajouter le contenu du popup
    popup.innerHTML = `
        <span class="close-btn" onclick="closePopup()" style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">×</span>
        Votre compte administrateur est en attente d'approbation. Les fonctionnalités sont limitées au réseau public et non à l'administration.
    `;

    // Ajouter le popup au body
    document.body.appendChild(popup);

    // Afficher le popup après un court délai
    setTimeout(function() {
        popup.style.display = 'block';
    }, 1000);
}

// Fonction pour fermer le popup
function closePopup() {
    var popup = document.getElementById('adminApprovalPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

// Vérifier si l'utilisateur est un admin non approuvé
if (typeof isAdminNonApproved !== 'undefined' && isAdminNonApproved) {
    createAdminApprovalPopup();
}







