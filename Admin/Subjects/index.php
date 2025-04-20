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

 $query = "SELECT * FROM sujet";
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>
    <title>Subjects</title>
    <style>
      <style>
    .transition-opacity {
        transition-property: opacity;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    .duration-300 {
        transition-duration: 300ms;
    }
    /* Modal transition */
#addSubjectModal {
    transition: opacity 0.3s ease-in-out;
}

/* Success/Error message styling */
.success-message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem;
    background: #d1fae5;
    color: #065f46;
    border-radius: 0.5rem;
    z-index: 1000;
}

.error-message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem;
    background: #fee2e2;
    color: #991b1b;
    border-radius: 0.5rem;
    z-index: 1000;
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
                <a class="flex items-center gap-2">
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2">
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
                    <div class="border border-zinc-400 text-zinc-600 p-5 w-10 h-10 flex justify-center items-center rounded-xl cursor-pointer">
                        +
                    </div>
                    <img src="../../images/PP.webp" alt="" class="w-10 h-10 rounded-full ml-3">

                </div>


                <div class="bg-white px-9 py-4 rounded-3xl h-[72vh] " >
                <form method="POST" action="delete.php" id="deleteForm" >
                    <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400" >
                        <p class="text-2xl font-medium " >Subjects</p>
                        <div>
                            <button  type="submit" class="bg-red-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer transition-opacity duration-300 opacity-0 pointer-events-none checkbox-button" id="delete-button" >Delete</button>
                            <button type="button" class="bg-indigo-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer hover:bg-indigo-600 new-subject-btn">
                                + New Subject
                            </button>
                        </div>



                    </div>

                    <div class="flex text-zinc-500 py-2 border-zinc-200 border-b " >
                        <p class="w-1/3" >Subject</p>
                        <p class="w-1/3" >Created By</p>
                        <p class="w-1/5" >Created On</p>
                    </div>
                    <?php foreach ($subjects as $subject): ?>

                    <div class="snap-y" >
                        <div class="flex items-center text-sm font-medium border-zinc-200 border-b py-2">
                            <!-- Leader Column -->
                            <div class="flex items-center w-1/3">
                            <input type="checkbox" name="selected_subjects[]"
                                value="<?= htmlspecialchars($subject['ID_Sujet']) ?>"
                                class="mr-1 checkbox-button">
                                <p><?=$subject['Titre']?></p>
                            </div>

                            <?php
                            $id = $subject['ID_Professeur'];
                            $query2 = "SELECT CONCAT(Nom, ' ', Prenom) AS FullName FROM Professeur WHERE ID_Professeur = $id";
                            $stmt2 = $pdo->prepare($query2);
                            $stmt2->execute();
                            $professor = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $professorName = $professor['FullName'];

                            ?>

                            <!-- Subject Column -->
                            <p class="w-1/3"><?=$professorName?></p>

                            <!-- Status Column -->
                             <?php

                             $date = new DateTime($subject["Date_Ajout"]);
                             $formattedDate = $date->format('F jS, Y');


                             ?>

                            <!-- Date Column -->
                            <p class="w-1/5"><?=$formattedDate?></p>

                            <!-- Details Button -->
                            <button class="border text-zinc-500 px-2 rounded-md border-zinc-400 cursor-pointer">
                                Details
                            </button>
                        </div>
                        <?php endforeach ; ?>
                        </form>






                    </div>



                </div>

            </div>

        </div>
    </div>

    <!-- Add Subject Modal -->
<div id="addSubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Subject</h3>

            <form method="POST" action="add_subject.php" class="mt-4">
                <div class="mb-4 text-left">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" name="title" required
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-4 text-left">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea name="description" rows="4" required
                              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="items-center px-4 py-3">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Add Subject
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="ml-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Modal handling
    const modal = document.getElementById('addSubjectModal');
    const newSubjectBtn = document.querySelector('.new-subject-btn');

    function openModal(e) {
        e.preventDefault(); // Prevent any default button behavior
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Proper event listener with error handling
    if (newSubjectBtn) {
        newSubjectBtn.addEventListener('click', openModal);
    } else {
        console.error('New subject button not found!');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }

    // Prevent form submission from enter key in modal
    document.querySelector('#addSubjectModal form').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
</script>
<script src="script.js" >

</script>



</body>
</html>
