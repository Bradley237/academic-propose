// MyShop - Main JS

// Confirm before delete actions
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this?');
}

// Auto-hide alerts after 4 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() { alert.remove(); }, 500);
        }, 4000);
    });
});
