document.addEventListener('DOMContentLoaded', function() {
    // La variable isAdminNonApproved doit être définie dans votre template Twig
    // Exemple : <script>var isAdminNonApproved = {{ isAdminNonApproved ? 'true' : 'false' }};</script>

    // Fonction pour créer et afficher le popup
    function showAdminPopup(message) {
        var popup = document.createElement('div');
        popup.id = 'adminApprovalPopup';
        popup.className = 'adminApprovalPopup'; // Appliquer la classe CSS
        popup.innerHTML = '<span class="close-btn" onclick="closePopup()">×</span>' + message;

        document.body.appendChild(popup);
        
        // Afficher le popup après un court délai
        setTimeout(function() {
            popup.style.display = 'block';
        }, 1000);
    }

    // Fonction pour fermer le popup
    window.closePopup = function() {
        var popup = document.getElementById('adminApprovalPopup');
        if (popup) {
            popup.style.display = 'none';
        }
    }

    // Déterminer le message et afficher le popup
    if (typeof isAdminNonApproved !== 'undefined') {
        var message = isAdminNonApproved ? "Votre compte administrateur est en attente d'approbation. Les fonctionnalités sont limitées au réseau public et non à l'administration." : "Votre compte a été approuvé.";
        showAdminPopup(message);
    }
});
