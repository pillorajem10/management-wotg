// loading.js

function showLoader() {
    document.getElementById('loading-overlay').style.display = 'flex';
}

function hideLoader() {
    document.getElementById('loading-overlay').style.display = 'none';
}

// Show loader on window load
window.onload = function() {
    hideLoader(); // Hide loader once the page is fully loaded
};

// Show loader initially
document.addEventListener("DOMContentLoaded", function() {
    showLoader(); // Show loader while the page is loading
});
