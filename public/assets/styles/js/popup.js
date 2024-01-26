// popup.js

document.addEventListener('DOMContentLoaded', function () {
    function showAdminPopup(message) {
        var popup = document.createElement('div');
        popup.id = 'adminApprovalPopup';
        popup.className = 'adminApprovalPopup';
        popup.innerHTML = '<span class="close-btn" onclick="closePopup()">×</span>' + message;

        document.body.appendChild(popup);

        // Ajouter la classe pour déclencher l'animation
        setTimeout(function () {
            popup.classList.add('showPopup');
        }, 100); // Un léger délai peut être nécessaire pour l'initialisation de l'animation
    }


    window.closePopup = function () {
        var popup = document.getElementById('adminApprovalPopup');
        if (popup) {
            popup.classList.add('hidePopup');

            // Attendre la fin de l'animation avant de supprimer le popup du DOM
            setTimeout(function () {
                popup.remove();
            }, 800); // Assurez-vous que ce délai correspond à la durée de votre animation CSS
        }
    }


    if (window.showApprovalPopup) {
        showAdminPopup("Votre compte a été approuvé.");
    }

    if (window.showNonApprovedAdminPopup) {
        showAdminPopup("Votre compte administrateur est en attente d'approbation. Les fonctionnalités sont limitées.");
    }

}



);
