<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';
try {
    $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
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



$sql = "SELECT * FROM $account_type WHERE $colomn = (:id)"  ;
$stmt2 = $pdo->prepare($sql); // Use $pdo
$stmt2->bindParam(':id', $id);
$stmt2->execute();
$user = $stmt2->fetchAll();






?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Team Timebox Calendar</title>
    <meta name="description" content="Team Timebox Calendar" />
    <meta name="author" content="Lovable" />

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
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
    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
      <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
        <i data-lucide="check-circle" class="h-5 w-5"></i>
        <span id="toast-message"></span>
      </div>
    </div>

    <!-- Main Layout -->
    <div class="flex min-h-screen">
      <!-- Sidebar -->
      <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
        <img src="../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

        <div class="space-y-2">
            <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="#">
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Subjects
                </a>
              </div>
            <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2" href="../Groupe/index.php" >
                  <i data-lucide="users" class="w-4 h-4"></i>
                  My Group
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <span class="flex items-center gap-2">
                  <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                  Tasks Manager
                </span>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="calendar.html" >
                  <i data-lucide="calendar-days" class="w-4 h-4"></i>
                  Calendar
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

<div class="flex-1 p-10">
  <?php
  if ($id) {
      $queryGroupe = "SELECT ID_Groupe FROM etudiant WHERE ID_Etudiant = :id";
      $stmtGroupe = $pdo->prepare($queryGroupe);
      $stmtGroupe->execute(['id' => $id]);
      $resultGroupe = $stmtGroupe->fetch(PDO::FETCH_ASSOC);

      if ($resultGroupe && $resultGroupe['ID_Groupe']) {
          $id_groupe = $resultGroupe['ID_Groupe'];

          $queryMembres = "SELECT Nom, Prenom FROM etudiant WHERE ID_Groupe = :id_groupe";
          $stmtMembres = $pdo->prepare($queryMembres);
          $stmtMembres->execute(['id_groupe' => $id_groupe]);
          $membres = $stmtMembres->fetchAll(PDO::FETCH_ASSOC);

          echo '<div class="bg-white p-6 rounded-2xl shadow-sm">';
          echo '<h2 class="text-2xl font-bold mb-5">Your Group Members :</h2>';
          echo '<ul class="space-y-3">';
          foreach ($membres as $membre) {
              echo '<li class="text-gray-700 font-medium">👤 ' . htmlspecialchars($membre['Prenom']) . ' ' . htmlspecialchars($membre['Nom']) . '</li>';
          }
          echo '</ul>';
          echo '</div>';
      } else {
          echo '<div class="bg-white p-6 rounded-2xl shadow-sm text-center">';
          echo '<h2 class="text-xl font-semibold text-gray-600">Vous n\'êtes dans aucun groupe.</h2>';
          echo '</div>';
      }
  } else {
      echo '<div class="bg-white p-6 rounded-2xl shadow-sm text-center">';
      echo '<h2 class="text-xl font-semibold text-gray-600">Erreur : utilisateur non connecté.</h2>';
      echo '</div>';
  }
  ?>
</div>
</body>
<script>
    lucide.createIcons();
</script>
</html>
