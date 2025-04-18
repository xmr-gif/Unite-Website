document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // User Details Modal Handling
    document.addEventListener('click', function(e) {
        const detailsBtn = e.target.closest('.details-btn');
        if (detailsBtn) showUserDetails(detailsBtn);
    });

    function showUserDetails(button) {
        const userData = button.dataset;
        const modal = document.getElementById('userModal');
        const studentFields = document.getElementById('studentFields');
        const adminFields = document.getElementById('adminFields');

        // Set common fields
        document.getElementById('modalFullName').textContent = userData.fullname || 'Undefined';
        document.getElementById('modalEmail').textContent = userData.email || 'Undefined';
        document.getElementById('modalRole').textContent = userData.role || 'Undefined';
        document.getElementById('modalDate').textContent = userData.date || 'Undefined';

        // Handle role-specific fields
        if (userData.role === 'Student') {
            studentFields.classList.remove('hidden');
            adminFields.classList.add('hidden');
            document.getElementById('modalFiliere').textContent = userData.filiere || 'Undefined';
            document.getElementById('modalDansUnGroupe').textContent =
                userData.dansUnGroupe === '1' ? 'Yes' : (userData.dansUnGroupe === '0' ? 'No' : 'Undefined');
            document.getElementById('modalSexe').textContent = userData.sexe || 'Undefined';
            document.getElementById('modalEstChef').textContent =
                userData.estChef === '1' ? 'Yes' : (userData.estChef === '0' ? 'No' : 'Undefined');
        } else {
            studentFields.classList.add('hidden');
            adminFields.classList.remove('hidden');
            document.getElementById('modalEstAdmin').textContent =
                userData.estAdmin === '1' ? 'Yes' : (userData.estAdmin === '0' ? 'No' : 'Undefined');
        }
        modal.classList.remove('hidden');
    }

    // Close modal handlers
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('userModal').classList.add('hidden');
        });
    });

    window.addEventListener('click', (e) => {
        const modal = document.getElementById('userModal');
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
