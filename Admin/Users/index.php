<?php
 $host ='localhost';
 $db = 'unite_db';
 $user='root';
 $pass ='';
 try {
     $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
     //echo "Connexion reussite";

 } catch (PDOException $e) {
     echo "La connexion n'est pas reussie ".$e->getMessage() ;
 }
 $sql = "SELECT
            CONCAT(Nom, ' ', Prenom) AS FullName,
            Email,
            'Professor' AS Role,
            DateRegistration,
            NULL AS Filiere_precedente,  -- Student fields
            NULL AS Dans_Un_Groupe,
            NULL AS Sexe,
            NULL AS Est_Chef,
            Est_Admin                   -- Professor-specific field
        FROM professeur
        UNION ALL
        SELECT
            CONCAT(Nom, ' ', Prenom) AS FullName,
            Email,
            'Student' AS Role,
            DateRegistration,
            Filiere_precedente,         -- Student fields
            Dans_Un_Groupe,
            Sexe,
            Est_Chef,
            NULL AS Est_Admin           -- Professor field
        FROM etudiant
        ORDER BY DateRegistration DESC";
 $state = $pdo->prepare($sql);
 $state->execute();
 $users = $state->fetchAll();
 /*
print_r($users);
print_r($users[1]["FullName"]);*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>
    <title>Users</title>
    <style>
#userModal {
    transition: opacity 0.3s ease-in-out;
    backdrop-filter: blur(2px);
}
#successNotification {
    transition: opacity 0.3s ease-in-out;
}
</style>
<style>
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* If you want to allow email wrapping instead of truncation */
    .email-cell {
        word-break: break-word;
        white-space: normal;
    }
</style>
</head>
<body>
    <div class="flex" >
        <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
            <img src="../../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

            <div class="space-y-2">
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Dashboard/index.php" >
                  <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                  Dashboard
                </a>
              </div>
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2" href="" >
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Subjects/index.php" >
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Subjects
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Choices/index.html" >
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
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
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
                  <i data-lucide="log-out" class="w-4 h-4"></i>
                  Log Out
                </span>
              </div>
            </div>
          </div>
        <div class="h-screen bg-gray-100 py-5 w-4/5 px-7 " >
            <div >
                <div class="flex justify-end w-full mb-5" >
                    <button id="openAssignAdmin" class="border border-zinc-400 text-zinc-600 p-5 w-10 h-10 flex justify-center items-center rounded-xl cursor-pointer">
                        +
                    </button>
                    <img src="PP.webp" alt="" class="w-10 h-10 rounded-full ml-3">

                </div>


                <div class="bg-white px-9 py-4 rounded-3xl h-[72vh] flex flex-col" >
                    <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400" >
                        <p class="text-2xl font-medium " >Users</p>
                        <form action="delete.php" method="POST" id="deleteForm">
                            <button class="border border-red-500 text-red-500 px-3 py-1 rounded-md" id="delete-button" type="submit">Delete Selected</button>
                        </form>

                        <div class=" rounded-md bg-gray-100 p-1 text-zinc-500 border-b-2 ">
                            <i class="ri-search-line"></i>
                            <input type="search" id="searchInput" placeholder="Search" >
                        </div>

                    </div>

                    <div class="flex text-zinc-500 py-2 border-zinc-200 border-b " >
                        <p class="w-1/4" >Full Name</p>
                        <p class="w-1/4" >Email</p>
                        <p class="w-1/5" ><a href="">Role <i class="ri-filter-3-fill"></i></a></p>
                        <p class="w-1/5" ><a href="">Member Since <i class="ri-filter-3-fill"></i></a></p>
                    </div>
                    <div class="flex-1 overflow-y-auto">
            <?php foreach ($users as $user): ?>
                    <div class="snap-y" >
                        <div class="flex items-center text-sm font-medium border-zinc-200 border-b py-2">
                            <!-- Full name -->
                            <div class="flex items-center w-1/4">
                            <input form="deleteForm" type="checkbox" name="userss[]" value="<?=$user["Role"]=='Student'?'etudiant':'professeur'?>|<?=$user['Email']?>" class="mr-1">
                                <img src="PP.webp" alt="" class="w-8 h-8 rounded-md mr-1">
                                <p class="full-name " ><?= $user["FullName"] ?></p>
                            </div>

                            <!-- Email -->
                            <p class="w-1/4 email-cell"><?= $user["Email"] ?></p>

                            <!-- Role -->
                            <div class="w-1/5">

                                    <p ><?= $user["Role"] ?></p>

                            </div>
                            <?php
                                $date = new DateTime($user["DateRegistration"]);
                                $formattedDate = $date->format('F jS, Y');
                            ?>

                            <!-- member since -->
                            <p class="w-1/5"><?=$formattedDate?></p>

                            <!-- Details Button -->

                            <button
                                class="details-btn border text-zinc-500 px-2 rounded-md border-zinc-400 cursor-pointer"
                                data-fullname="<?= htmlspecialchars($user['FullName'], ENT_QUOTES) ?>"
                                data-email="<?= htmlspecialchars($user['Email'], ENT_QUOTES) ?>"
                                data-role="<?= htmlspecialchars($user['Role'], ENT_QUOTES) ?>"
                                data-date="<?= htmlspecialchars($formattedDate, ENT_QUOTES) ?>"
                                data-filiere="<?= htmlspecialchars($user['Filiere_precedente'] ?? 'Undefined', ENT_QUOTES) ?>"
                                data-dans-un-groupe="<?= htmlspecialchars($user['Dans_Un_Groupe'] ?? 'Undefined', ENT_QUOTES) ?>"
                                data-sexe="<?= htmlspecialchars($user['Sexe'] ?? 'Undefined', ENT_QUOTES) ?>"
                                data-est-chef="<?= htmlspecialchars($user['Est_Chef'] ?? 'Undefined', ENT_QUOTES) ?>"
                                data-est-admin="<?= htmlspecialchars($user['Est_Admin'] ?? 'Undefined', ENT_QUOTES) ?>"
                            >
                                Details
                            </button>

                        </div>

              <?php endforeach ; ?>
              </div>




                </div>

            </div>

        </div>
    </div>

<!-- User Details Modal -->
<div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-5 max-h-[80vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-medium">User Details</h3>
      <button class="close-modal text-gray-500 hover:text-gray-700">
        <i data-lucide="x" class="h-5 w-5"></i>
      </button>
    </div>

    <div class="space-y-3">
      <!-- Common Fields -->
      <div class="flex items-center gap-2">
        <span class="font-medium w-24">Full Name:</span>
        <span id="modalFullName" class="text-gray-600"></span>
      </div>

      <div class="flex items-center gap-2">
        <span class="font-medium w-24">Email:</span>
        <span id="modalEmail" class="text-gray-600"></span>
      </div>

      <div class="flex items-center gap-2">
        <span class="font-medium w-24">Role:</span>
        <span id="modalRole" class="text-gray-600"></span>
      </div>

      <div class="flex items-center gap-2">
        <span class="font-medium w-24">Member Since:</span>
        <span id="modalDate" class="text-gray-600"></span>
      </div>

      <!-- Student-specific Fields -->
      <div id="studentFields" class="hidden space-y-3 pt-3 border-t border-gray-200">
    <div class="flex items-center gap-2">
        <span class="font-medium w-24">Previous Field:</span>
        <span id="modalFiliere" class="text-gray-600"></span>
    </div>
    <div class="flex items-center gap-2">
        <span class="font-medium w-24">In Group:</span>
        <span id="modalDansUnGroupe" class="text-gray-600"></span>
    </div>
    <div class="flex items-center gap-2">
        <span class="font-medium w-24">Gender:</span>
        <span id="modalSexe" class="text-gray-600"></span>
    </div>
    <div class="flex items-center gap-2">
        <span class="font-medium w-24">Is Leader:</span>
        <span id="modalEstChef" class="text-gray-600"></span>
    </div>
</div>

      <!-- Professor-specific Fields -->
      <div id="adminFields" class="hidden space-y-3 pt-3 border-t border-gray-200">
        <div class="flex items-center gap-2">
          <span class="font-medium w-24">Is Admin:</span>
          <span id="modalEstAdmin" class="text-gray-600"></span>
        </div>
      </div>
    </div>

    <div class="mt-4 flex justify-end">
      <button class="close-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
        Close
      </button>
    </div>
  </div>
</div>
<!-- Assign Admin Modal -->
<div id="assignAdminModal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-5 max-h-[80vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-medium">Assign Admin Status</h3>
      <button class="close-assign-modal text-gray-500 hover:text-gray-700">
        <i data-lucide="x" class="h-5 w-5"></i>
      </button>
    </div>

    <div id="professorsList" class="space-y-3">
      <!-- Professors will be loaded here -->
    </div>

    <div class="mt-4 flex justify-end">
      <button class="close-assign-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
        Close
      </button>
    </div>
  </div>
</div>
<!-- Add this anywhere in your user.php body -->
<div id="successNotification" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300">
    Admin status updated successfully!
</div>

    <script>lucide.createIcons();</script>
    <script src="js/user-modal.js"></script>
    <script src="js/admin-assign.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');

    // Sélectionner toutes les lignes d'utilisateurs
    const userRows = document.querySelectorAll('.flex.items-center.text-sm.font-medium.border-zinc-200.border-b.py-2');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();

        userRows.forEach(row => {
            // Récupérer le fullname
            const fullName = row.querySelector('.full-name')?.textContent.toLowerCase() || '';

            // Affiche o masque
            row.style.display = fullName.includes(query) ? '' : 'none';
        });
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
</script>


</body>
</html>
