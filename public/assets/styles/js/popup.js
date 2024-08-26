document.addEventListener('DOMContentLoaded', function () {
    function showAdminPopup(message) {
        var popup = document.createElement('div');
        popup.id = 'adminApprovalPopup';
        popup.className = 'adminApprovalPopup';
        popup.innerHTML = '<span class="close-btn" onclick="closePopup()">×</span>' + message;

        document.body.appendChild(popup);

        setTimeout(function () {
            popup.classList.add('showPopup');
        }, 100);
    }

    window.closePopup = function () {
        var popup = document.getElementById('adminApprovalPopup');
        if (popup) {
            popup.classList.add('hidePopup');

            setTimeout(function () {
                popup.remove();
                fetch('/confirm-approval-popup');
            }, 800);
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
