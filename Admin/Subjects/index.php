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
</style>
    </style>
</head>
<body>
    <div class="flex" >
        <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
            <img src="../../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

            <div class="space-y-2">
<<<<<<< HEAD
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
                <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
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
                <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                    <a class="flex items-center gap-2" href="calendar.html">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Groups
                    </a>
                </div>
=======
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
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
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
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="calendar.html">
                  <i data-lucide="users" class="w-4 h-4"></i>
                  Groups
                </a>
              </div>
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
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
                    <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400" >
                        <p class="text-2xl font-medium " >Subjects</p>
                        <div>
<<<<<<< HEAD
                            <form action="delete.php" method="POST" id="deleteForm">
                                <button class="bg-red-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer transition-opacity duration-300 opacity-0 pointer-events-none checkbox-button" id="delete-button" type="button">Delete</button>

                                <button  id="creationSubjet" class="bg-indigo-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer hover:bg-indigo-600" type="button" >+ New Subject</button>
                            </form>
=======
                          <form action="delete.php" method="POST">
                            <button class="bg-red-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer transition-opacity duration-300 opacity-0 pointer-events-none checkbox-button" id="delete-button" >Delete</button>
                          
                            <button  id="creationSubjet" class="bg-indigo-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer hover:bg-indigo-600" type="button" >+ New Subject</button>
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
                        </div>



                    </div>

                    <div class="flex text-zinc-500 py-2 border-zinc-200 border-b " >
                        <p class="w-1/3" >Subject</p>
                        <p class="w-1/3" >Created By</p>
                        <p class="w-1/5" >Created On</p>
                    </div>
                    <?php foreach ($subjects as $subject): ?>
    <?php
    $id = $subject['ID_Professeur'];
    $query2 = "SELECT CONCAT(Nom, ' ', Prenom) AS FullName FROM Professeur WHERE ID_Professeur = $id";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $professor = $stmt2->fetch(PDO::FETCH_ASSOC);
    $professorName = $professor['FullName'];
    ?>
    <div class="snap-y subject-card cursor-pointer"
         data-subject-title="<?php echo htmlspecialchars($subject['Titre']); ?>"
         data-professor-name="<?php echo htmlspecialchars($professorName); ?>"
         data-subject-description="<?php echo htmlspecialchars($subject['Description']); ?>">
        <div class="flex items-center text-sm font-medium border-zinc-200 border-b py-2">
            <div class="flex items-center w-1/3">
                <input type="checkbox" name="subject[]" value="<?=$subject['ID_Sujet']?>" class="mr-1 checkbox-button ">
                <p><?=$subject['Titre']?></p>
            </div>
            <p class="w-1/3"><?=$professorName?></p>
            <?php
            $date = new DateTime($subject["Date_Ajout"]);
            $formattedDate = $date->format('F jS, Y');
            ?>
            <p class="w-1/5"><?=$formattedDate?></p>
            <button type="button" class="border text-indigo-500 px-2 rounded-md border-zinc-400 cursor-pointer">
                Details
            </button>
        </div>
    </div>
<?php endforeach ; ?>
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
    <div id="new-subject-form" class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200">
    <div class="max-w-2xl w-full mx-4 bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95">
        <div class="relative p-8">
<<<<<<< HEAD
            <button id="close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200 cursor-pointer ">
=======
            <!-- Bouton de fermeture amélioré -->
            <button id="close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

<<<<<<< HEAD
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
=======
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Créer un nouveau sujet</h2>

            <form action="creer_sjt.php" method="POST" class="space-y-6">
                <!-- Titre du Sujet -->
                <div class="space-y-2">
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre du Sujet</label>
                    <input type="text" id="titre" name="titre" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-300" 
                           required>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description du Sujet</label>
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
                    <textarea id="description" name="description" rows="3"
                              class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-300"
                              required></textarea>
                </div>

<<<<<<< HEAD
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 cursor-pointer ">
                        Create Subject
=======
                <!-- Bouton de soumission -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                        Créer le Sujet
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<<<<<<< HEAD

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

<script>
=======
    <script>
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
        lucide.createIcons();
        document.addEventListener('DOMContentLoaded', function() {
        const buttonCreer = document.getElementById('creationSubjet'); // Le bouton + New Subject
        const form = document.getElementById('new-subject-form'); // Le formulaire
        const close = document.getElementById('close')

        buttonCreer.addEventListener('click', function() {
            // Toggle l'affichage du formulaire
            form.classList.toggle('hidden');

            close.addEventListener('click', function() {
            form.classList.add('hidden');
    });
            });
<<<<<<< HEAD

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
=======
        });
>>>>>>> aed5c8df0fb60a063918d5814350d433893b035a
    </script>
    <script src="JS/script.js" ></script>
    <script src="JS/subject-modal.js" ></script>

</body>
</html>
