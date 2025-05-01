<?php
session_start();

$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';
try {
    $pdo = new PDO ("mysql:host=$host;port=3307;dbname=$db",$user,$pass);
    //echo "Connexion reussie";
} catch (PDOException $e) {
    echo "La connexion n'est pas reussie ".$e->getMessage() ;
}

$query = "SELECT (SELECT COUNT(*) FROM etudiant)+(select COUNT(*) from professeur) AS total";
$stmt = $pdo->prepare($query);
$stmt->execute();
$totalUser = $stmt->fetch(PDO::FETCH_ASSOC);

$sqlsjt = "SELECT COUNT(*) as sujet FROM sujet";
$stmt = $pdo->prepare($sqlsjt);
$stmt->execute();
$sujet = $stmt->fetch();

// Nouveau : récupérer l'ID de l'étudiant connecté
$account_type = $_SESSION['account_type'];
$colomn = 'id_'.$account_type ;
$id = $_SESSION[$account_type . '_id'] ?? null;


echo 'ddddd' ;
$sql = "SELECT * FROM $account_type WHERE $colomn = (:id)"  ; 
$stmt2 = $pdo->prepare($sql); // Use $pdo
$stmt2->bindParam(':id', $id);
$stmt2->execute();
$user = $stmt2->fetchAll();
print_r($user);


print_r($_SESSION[$account_type . '_id'] );
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Team Task Manager</title>
  <meta name="description" content="Team Task Manager" />
  <meta name="author" content="Lovable" />

  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>

  <style>
    .task-card {
      transition: all 0.2s ease;
    }
    .task-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    #toast {
      transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
      opacity: 0;
      transform: translateY(-20px);
    }
    #toast.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>

<body class="bg-gray-50">
  <div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
      <i data-lucide="check-circle" class="h-5 w-5"></i>
      <span id="toast-message"></span>
    </div>
  </div>

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
      <img src="../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />
      <h1 class="text-xl font-bold text-center text-indigo-600 mb-6">Espace Prof</h1>
      <div class="space-y-2">
        <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
          <a class="flex items-center gap-2" href="#">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            Dashboard
          </a>
        </div>
        <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
          <span class="flex items-center gap-2">
            <i data-lucide="lightbulb" class="w-4 h-4"></i>
            Subjects
          </span>
        </div>
        <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
          <span class="flex items-center gap-2">
            <i data-lucide="book-open" class="w-4 h-4"></i>
            Choices
          </span>
        </div>
        <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
          <span class="flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Blogs
          </span>
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
            <a class="flex items-center gap-2" href="../logout/logout.php">
            Log Out
            </a>
          </span>
        </div>
      </div>
    </div>

    <div class="h-screen bg-gray-100 py-5 w-4/5 px-7">
      <div>
        
        <div class="flex justify-end w-full mb-5">
          <div class="border border-zinc-400 text-zinc-600 p-5 w-10 h-10 flex justify-center items-center rounded-xl cursor-pointer">
            +
          </div>
          <img src="../../images/PP.webp" alt="" class="w-10 h-10 rounded-full ml-3">
        </div>

        <div class="flex gap-3 justify-center mb-10">
          <div class="text-center flex font-medium bg-white w-1/4 py-7 rounded-3xl shadow-sm justify-around">
            <div>
              <p>Subjects</p>
              <p class="text-5xl"><?=$sujet['sujet']?></p>
            </div>
            <div class="bg-indigo-400 p-5 rounded-full w-15 h-15 flex justify-center items-center mt-2">
              <i class="ri-stack-fill text-4xl text-white"></i>
            </div>
          </div>
          <div class="text-center flex font-medium bg-white w-1/4 py-7 rounded-3xl shadow-sm justify-around px-2">
            <div>
              <p>Accepted Choices</p>
              <p class="text-5xl">10</p>
            </div>
            <div class="bg-indigo-400 p-5 rounded-full w-15 h-15 flex justify-center items-center mt-2">
              <i class="ri-check-line text-4xl text-white font-semibold"></i>
            </div>
          </div>
          <div class="text-center flex font-medium bg-white w-1/4 py-7 rounded-3xl shadow-sm justify-around px-2">
            <div>
              <p>Pending Choices</p>
              <p class="text-5xl">10</p>
            </div>
            <div class="bg-indigo-400 p-5 rounded-full w-15 h-15 flex justify-center items-center mt-2">
              <i class="ri-hourglass-fill text-4xl text-white"></i>
            </div>
          </div>
          <div class="text-center flex font-medium bg-white w-1/4 py-7 rounded-3xl shadow-sm justify-around">
            <div>
              <p>Users</p>
              <p class="text-5xl"><?=$totalUser['total']?></p>
            </div>
            <div class="bg-indigo-400 p-5 rounded-full w-15 h-15 flex justify-center items-center mt-2">
              <i class="ri-group-3-fill text-4xl text-white"></i>
            </div>
          </div>
        </div>
        <h2>Espace Etudiant</h2>
        <!-- Partie Groupe et Membres -->
        <?php
        if ($id) {
            $queryGroupe = "SELECT ID_Groupe FROM etudiant WHERE ID_Etudiant = :id";
            $stmt = $pdo->prepare($queryGroupe);
            $stmt->execute(['id' => $id]);
            $resultGroupe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultGroupe && $resultGroupe['ID_Groupe']) {
                $id_groupe = $resultGroupe['ID_Groupe'];

                $queryMembres = "SELECT Nom, Prenom FROM etudiant WHERE ID_Groupe = :id_groupe";
                $stmt = $pdo->prepare($queryMembres);
                $stmt->execute(['id_groupe' => $id_groupe]);
                $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo '<div class="bg-white p-6 rounded-2xl shadow-sm mt-10">';
                echo '<h2 class="text-2xl font-bold mb-5">Membres de votre groupe :</h2>';
                echo '<ul class="space-y-3">';
                foreach ($membres as $membre) {
                    echo '<li class="text-gray-700 font-medium">👤 ' . htmlspecialchars($membre['Prenom']) . ' ' . htmlspecialchars($membre['Nom']) . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<div class="bg-white p-6 rounded-2xl shadow-sm mt-10 text-center">';
                echo '<h2 class="text-xl font-semibold text-gray-600">Vous n\'êtes dans aucun groupe.</h2>';
                echo '</div>';
            }
        } else {
            echo '<div class="bg-white p-6 rounded-2xl shadow-sm mt-10 text-center">';
            echo '<h2 class="text-xl font-semibold text-gray-600">Erreur : utilisateur non connecté.</h2>';
            echo '</div>';
        }
        ?>
        <!-- Fin Groupe et Membres -->

      </div>
    </div>
  </div>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
