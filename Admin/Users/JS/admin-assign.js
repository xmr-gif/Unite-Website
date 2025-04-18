document.addEventListener('DOMContentLoaded', function() {
    const assignAdminBtn = document.getElementById('openAssignAdmin');
    if (assignAdminBtn) {
        assignAdminBtn.addEventListener('click', loadProfessors);
    }

    document.addEventListener('click', handleAdminActions);
    setupModalClosures();

    async function loadProfessors() {
        try {
            const response = await fetch('get_professors.php');
            if (!response.ok) throw new Error('Network response was not ok');

            const professors = await response.json();
            renderProfessors(professors);
            document.getElementById('assignAdminModal').classList.remove('hidden');
        } catch (error) {
            console.error('Error loading professors:', error);
            alert('Error loading professors. Check console for details.');
        }
    }

    function renderProfessors(professors) {
        const list = document.getElementById('professorsList');
        list.innerHTML = professors.map(prof => `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                <div class="flex-1">
                    <div class="font-medium">${prof.FullName}</div>
                    <div class="text-sm text-gray-600">${prof.Email}</div>
                    <div class="text-sm ${prof.Est_Admin ? 'text-green-600' : 'text-red-600'}">
                        Admin Status: ${prof.Est_Admin ? 'Yes' : 'No'}
                    </div>
                </div>
                <button class="assign-admin-btn px-4 py-2 ${prof.Est_Admin ? 'bg-green-500' : 'bg-blue-500'}
                    text-white rounded-lg hover:opacity-90 transition-opacity"
                    data-id="${prof.ID_Professeur}">
                    ${prof.Est_Admin ? 'Revoke Admin' : 'Make Admin'}
                </button>
            </div>
        `).join('');
    }

    async function handleAdminActions(e) {
        if (e.target.classList.contains('assign-admin-btn')) {
            const button = e.target;
            try {
                const response = await fetch('update_admin.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ id: button.dataset.id })
                });

                const result = await response.json();

                if (result.success) {
                    button.textContent = result.new_status ? 'Revoke Admin' : 'Make Admin';
                    button.classList.toggle('bg-blue-500');
                    button.classList.toggle('bg-green-500');

                    const statusDiv = button.parentElement.querySelector('.text-sm');
                    statusDiv.textContent = `Admin Status: ${result.new_status ? 'Yes' : 'No'}`;
                    statusDiv.classList.toggle('text-red-600');
                    statusDiv.classList.toggle('text-green-600');
                }
            } catch (error) {
                console.error('Update failed:', error);
            }
        }
    }

    function setupModalClosures() {
        document.querySelectorAll('.close-assign-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('assignAdminModal').classList.add('hidden');
            });
        });

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('assignAdminModal');
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
});
