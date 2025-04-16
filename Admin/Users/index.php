<?php
 $host ='localhost';
 $db = 'unite_db';
 $user='root';
 $pass ='';
 try {
     $pdo = new PDO ("mysql:host=$host;port=3307;dbname=$db",$user,$pass);
     echo "Connexion reussite";
     
 } catch (PDOException $e) {
     echo "La connexion n'est pas reussie ".$e->getMessage() ;
 }
 $sql = "SELECT
                CONCAT(	Nom, ' ', Prenom) AS FullName,
                Email,
                'Professor' AS Role,
                RegistrationDate
            FROM professeur
            UNION ALL
            SELECT
                CONCAT(Nom, ' ', Prenom) AS FullName,
                Email,
                'Student' AS Role,
                RegistrationDate
            FROM etudiant
            ORDER BY RegistrationDate DESC";
 $state = $pdo->prepare($sql);
 $state->execute();
 $users = $state->fetchAll();
 
print_r($users);
print_r($users[1]["FullName"]);
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
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2">
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
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
                    <img src="PP.webp" alt="" class="w-10 h-10 rounded-full ml-3">

                </div>


                <div class="bg-white px-9 py-4 rounded-3xl h-[72vh] " >
                    <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400" >
                        <p class="text-2xl font-medium " >Users</p>
                        <div class=" rounded-md bg-gray-100 p-1 text-zinc-500 border-b-2 ">
                            <i class="ri-search-line"></i>
                            <input type="search" placeholder="Search" >
                        </div>

                    </div>

                    <div class="flex text-zinc-500 py-2 border-zinc-200 border-b " >
                        <p class="w-1/4" >Full Name</p>
                        <p class="w-1/4" >Email</p>
                        <p class="w-1/5" ><a href="">Role <i class="ri-filter-3-fill"></i></a></p>
                        <p class="w-1/5" ><a href="">Member Since <i class="ri-filter-3-fill"></i></a></p>
                    </div>
            <?php foreach ($users as $user): ?>
                    <div class="snap-y" >
                        <div class="flex items-center text-sm font-medium border-zinc-200 border-b py-2">
                            <!-- Leader Column -->
                            <div class="flex items-center w-1/4">
                                <input type="checkbox" class="mr-1">
                                <img src="PP.webp" alt="" class="w-8 h-8 rounded-md mr-1">
                                <p><?= $user["FullName"] ?></p>
                            </div>

                            <!-- Subject Column -->
                            <p class="w-1/4"><?= $user["Email"] ?></p>

                            <!-- Status Column -->
                            <div class="w-1/5">

                                    <p ><?= $user["Role"] ?></p>

                            </div>

                            <!-- Date Column -->
                            <p class="w-1/5">Feb 20th, 2025</p>

                            <!-- Details Button -->
                            <button class="border text-zinc-500 px-2 rounded-md border-zinc-400 cursor-pointer">
                                Details
                            </button>
                        </div>
              <?php endforeach ; ?>
                       



                </div>

            </div>

        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>

</body>
</html>
