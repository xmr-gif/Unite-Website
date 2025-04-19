<?php
 $host ='localhost';
 $db = 'unite_db';
 $user='root';
 $pass ='';
 try {
     $pdo = new PDO ("mysql:host=$host;port=3307;dbname=$db",$user,$pass);
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
                    <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400" >
                        <p class="text-2xl font-medium " >Subjects</p>
                        <div>
                          <form action="delete.php" method="POST">
                            <button class="bg-red-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer transition-opacity duration-300 opacity-0 pointer-events-none checkbox-button" id="delete-button" >Delete</button>
                          
                            <button class="bg-indigo-500 text-white text-sm px-2 py-1 rounded-md cursor-pointer hover:bg-indigo-600" type="button" >+ New Subject</button>
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
                                <input type="checkbox" name="subject[]" value="<?=$subject['ID_Sujet']?>" class="mr-1 checkbox-button ">
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
                            <button type="button" class="border text-zinc-500 px-2 rounded-md border-zinc-400 cursor-pointer">
                                Details
                            </button>
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
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Créer un nouveau sujet</h2>

    <form action="creer_sjt.php" method="POST">
        <!-- Titre du Sujet -->
        <div class="mb-4">
            <label for="titre" class="block text-sm font-medium text-gray-700">Titre du Sujet</label>
            <input type="text" id="titre" name="titre" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="date_ajout" class="block text-sm font-medium text-gray-700">Description du Sujet</label>
            <input type="text" id="description" name="description" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>


        <!-- Bouton de soumission -->
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Créer le Sujet</button>
        </div>
    </form>
</div>

    <script>
        lucide.createIcons();
    </script>
    <script src="script.js" ></script>

</body>
</html>
