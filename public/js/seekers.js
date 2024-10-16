document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('openModal').onclick = function() {
        const checkboxes = document.querySelectorAll('.seeker-checkbox:checked');
        const emails = [];

        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            const seekerEmail = row.cells[3].textContent; // Assuming email is in the fourth cell
            emails.push(seekerEmail);
        });

        // Set the emails as a hidden input value in the form
        document.getElementById('contactForm').insertAdjacentHTML('beforeend', `<input type="hidden" name="emails" value="${emails.join(',')}">`);

        document.getElementById('nameModal').style.display = 'block';
    };

    document.getElementById('closeModal').onclick = function() {
        document.getElementById('nameModal').style.display = 'none';
    };

    // Close the modal if clicked outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('nameModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});
