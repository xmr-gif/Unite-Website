<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';
try {
    $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
} catch (PDOException $e) {
    echo "La connexion n'est pas reussie ".$e->getMessage() ;
}

// Modified SQL query to get group information
$query = "SELECT
            g.ID_groupe AS GroupID,
            CONCAT(e.Nom, ' ', e.Prenom) AS GroupOwner,
            COUNT(et.ID_Etudiant) AS TotalMembers
          FROM Groupe g
          LEFT JOIN Etudiant e ON g.ID_groupe = e.ID_Groupe AND e.Est_Chef = 1
          LEFT JOIN Etudiant et ON g.ID_groupe = et.ID_Groupe
          GROUP BY g.ID_groupe, e.Nom, e.Prenom";

$stmt = $pdo->prepare($query);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>
    <title>Groups</title>
    <style>
         <style>
    .transition-opacity {
        transition-property: opacity;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    .duration-300 {
        transition-duration: 300ms;
    }
</style>
    </style>
</head>
<body>
    <div class="flex" >
        <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
            <img src="../../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

            <div class="space-y-2">
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="#" >
                  <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                  Dashboard
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Users/index.php" >
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="index.php">
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Subjects
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2">
                  <i data-lucide="book-open" class="w-4 h-4"></i>
                  Choices
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="index.html">
                  <i data-lucide="file-text" class="w-4 h-4"></i>
                  Blogs
                </a>
              </div>
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2" href="calendar.html">
                  <i data-lucide="users" class="w-4 h-4"></i>
                  Groups
                </a>
              </div>
            </div>

            <div class="absolute bottom-8 space-y-2 w-[calc(25%-48px)]">
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        Settings
                    </span>
                </div>
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <span class="flex items-center gap-2">
                        <form action="../../logout/lougout.php">
                        <button class="flex items-center gap-2 cursor-pointer" type="submit" >
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Log Out</button>
                        </form>
                    </span>
                </div>
            </div>
        </div>
        <div class="h-screen bg-gray-100 py-5 w-4/5 px-7 " >
            <div >
                <div class="flex justify-end w-full mb-5" >
                    <div class="border border-zinc-400 text-zinc-600 p-5 w-10 h-10 flex justify-center items-center rounded-xl cursor-pointer">
                        +
                    </div>
                    <img src="../../images/PP.webp" alt="" class="w-10 h-10 rounded-full ml-3">

                </div>


                <div class="bg-white px-9 py-4 rounded-3xl h-[72vh] " >
                    <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400" >
                        <p class="text-2xl font-medium " >Groups</p>
                        <div>

                            <form action="delete.php" method="POST" id="deleteForm">
                                <button class="bg-red-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer transition-opacity duration-300 opacity-0 pointer-events-none checkbox-button" id="delete-button" type="button">Delete</button>




                        </div>





                    </div>

                    <div class="flex text-zinc-500 py-2 border-zinc-200 border-b " >
                        <p class="w-1/3" >GroupID</p>
                        <p class="w-1/3" >Created By</p>
                        <p class="w-1/5" >Total Members</p>
                    </div>



   <?php foreach ($groups as $group): ?>
    <div class="snap-y subject-card cursor-pointer">
        <div class="flex items-center text-sm font-medium border-zinc-200 border-b py-2">
            <div class="flex items-center w-1/3">
                <input type="checkbox" name="group[]" value="<?= $group['GroupID'] ?>" class="mr-1 checkbox-button ">
                <p><?= $group['GroupID'] ?></p>
            </div>
            <p class="w-1/3"><?= $group['GroupOwner'] ?? 'No owner' ?></p>
            <p class="w-1/5"><?= $group['TotalMembers'] ?></p>
            <button type="button" class="border text-indigo-500 px-2 rounded-md border-zinc-400 cursor-pointer details-button" data-group-id="<?= $group['GroupID'] ?>">
                Details
            </button>
        </div>
    </div>
<?php endforeach; ?>
                                    </form>
                                    <?php
                                        if(isset($_POST['subject'])){
                                            printf($_POST['subject']);
                                        }
                                    ?>







                    </div>



                </div>

            </div>

        </div>
    </div>
    <div id="new-subject-form" class=" hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200">
    <div class="max-w-2xl w-full mx-4 bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95">
        <div class="relative p-8">
            <button id="close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200 cursor-pointer ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create a New Subject</h2>

            <form action="add_subject.php" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label for="titre" class="block text-sm font-medium text-gray-700">Title of the Subject</label>
                    <input type="text" id="titre" name="titre"
                           class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-300"
                           required>
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description of the Subject</label>
                    <textarea id="description" name="description" rows="3"
                              class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-300"
                              required></textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 cursor-pointer ">
                        Create Subject
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>


<div id="subjectDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-5 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Subject Details</h3>
            <button id="closeSubjectModal" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <span class="font-medium w-24">Title:</span>
                <span id="modalSubjectTitle" class="text-gray-600"></span>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-medium w-24">Professor:</span>
                <span id="modalProfessorName" class="text-gray-600"></span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-medium w-24">Description:</span>
                <span id="modalSubjectDescription" class="text-gray-600"></span>
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button id="closeSubjectModalButton" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Member Details Modal -->
<div id="memberDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Group Members</h3>
            <button id="closeMemberModal" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        <div class="space-y-4">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-zinc-500 border-b">
                        <th class="pb-2">Full Name</th>
                        <th class="pb-2">Gender</th>
                        <th class="pb-2">Previous Field</th>
                    </tr>
                </thead>
                <tbody id="memberList">
                    <!-- Member rows will be inserted here -->
                </tbody>
            </table>
            <p id="noMembers" class="text-zinc-400 text-center py-4 hidden">No members in this group</p>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Member details modal functionality
    const memberModal = document.getElementById('memberDetailsModal');
    const closeMemberModal = document.getElementById('closeMemberModal');
    const memberList = document.getElementById('memberList');
    const noMembers = document.getElementById('noMembers');

    // Details button click handler
    document.querySelectorAll('.details-button').forEach(button => {
        button.addEventListener('click', async function() {
            const groupId = this.dataset.groupId;

            try {
                // Show loading state
                memberList.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-zinc-400">Loading members...</td></tr>';
                memberModal.classList.remove('hidden');

                // Fetch members data
                const response = await fetch(`get_members.php?group_id=${groupId}`);
                if (!response.ok) throw new Error('Network response was not ok');

                const members = await response.json();

                // Clear previous entries
                memberList.innerHTML = '';

                if (members.length > 0) {
                    noMembers.classList.add('hidden');

                    // Populate member list
                    members.forEach(member => {
                        const row = document.createElement('tr');
                        row.className = 'py-2 border-b border-zinc-100 hover:bg-gray-50';
                        row.innerHTML = `
                            <td class="py-3 ${member.Est_Chef ? 'text-indigo-600 font-medium' : 'text-gray-700'}">
                                ${member.FullName}
                                ${member.Est_Chef ? '<span class="ml-2 text-xs text-Indigo-600">(Owner)</span>' : ''}
                            </td>
                            <td class="text-gray-600">${member.Sexe || '-'}</td>
                            <td class="text-gray-600">${member.Filiere_Precedente || '-'}</td>
                        `;
                        memberList.appendChild(row);
                    });
                } else {
                    noMembers.classList.remove('hidden');
                }

            } catch (error) {
                console.error('Error fetching members:', error);
                memberList.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-red-500">Error loading members</td></tr>';
            }
        });
    });

    // Close member modal
    closeMemberModal.addEventListener('click', () => {
        memberModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    memberModal.addEventListener('click', (e) => {
        if (e.target === memberModal) {
            memberModal.classList.add('hidden');
        }
    });

    // Lucide icons refresh
    lucide.createIcons();
});
</script>


    <script>
        lucide.createIcons();
        document.addEventListener('DOMContentLoaded', function() {
        const buttonCreer = document.getElementById('creationSubjet'); // Le bouton + New Subject
        const form = document.getElementById('new-subject-form'); // Le formulaire
        const close = document.getElementById('close')

        buttonCreer.addEventListener('click', function() {
            // Toggle l'affichage du formulaire
            form.classList.remove('hidden');

            close.addEventListener('click', function() {
            form.classList.add('hidden');
    });
            });



        const deleteButton = document.getElementById('delete-button');
        const deleteForm = document.getElementById('deleteForm');

        deleteButton.addEventListener('click', function(event) {
            if (confirm('Are you sure you want to delete the selected subject?')) {
                deleteForm.submit(); // If confirmed, submit the form
            } else {
                // If not confirmed, do nothing (form will not be submitted)
                event.preventDefault(); // Prevent any default action of the button
            }
        });
    });


    </script>
    <script src="JS/script.js" ></script>
    <script src="JS/subject-modal.js" ></script>

</body>
</html>
