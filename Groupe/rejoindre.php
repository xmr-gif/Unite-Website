<?php
session_start();

$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';

try {
    $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
} catch (PDOException $e) {
    echo "La connexion a échoué : " . $e->getMessage();
    exit;
}

$account_type = $_SESSION['account_type'] ?? 'etudiant';
$colomn = 'id_'.$account_type;
$id = $_SESSION[$account_type . '_id'] ?? null;

if (!$id) {
    echo "Utilisateur non connecté.";
    exit;
}

// Vérifier si l'utilisateur est déjà dans un groupe
$queryGroupe = "SELECT ID_Groupe FROM etudiant WHERE ID_Etudiant = :id";
$stmtGroupe = $pdo->prepare($queryGroupe);
$stmtGroupe->execute(['id' => $id]);
$resultGroupe = $stmtGroupe->fetch(PDO::FETCH_ASSOC);

if ($resultGroupe && $resultGroupe['ID_Groupe']) {
    echo "<div class='text-center mt-10 text-gray-700 text-xl'>";
    echo "Vous êtes déjà dans un groupe. <br>";
    echo "<a href='../Groupe/index.php' class='text-indigo-600 underline'>Voir votre groupe</a>";
    echo "</div>";
    exit;
}

// Récupérer tous les groupes existants
$sqlGroupes = "SELECT * FROM groupe";
$stmt = $pdo->query($sqlGroupes);
$groupes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rejoindre un groupe</title>


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
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
      <img src="../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo Étudiant" />
      <h1 class="text-xl font-bold text-center text-indigo-600 mb-6">Espace Étudiant</h1>
      <div class="space-y-2">
        <div class="px-4 py-2 hover:bg-slate-100 rounded-md cursor-pointer text-zinc-600">
          <a class="flex items-center gap-2" href="../Student/dashboardEt.php">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            Tableau de bord
          </a>
        </div>
        <div class="px-4 py-2 hover:bg-slate-100 rounded-md cursor-pointer text-zinc-600">
          <a class="flex items-center gap-2" href="../Subject/index.php">
            <i data-lucide="book-open" class="w-4 h-4"></i>
            Mes Sujets
          </a>
        </div>
        <div class="px-4 py-2 bg-indigo-100 text-indigo-700 font-semibold rounded-md">
          <a class="flex items-center gap-2" href="../Groupe/index.php">
            <i data-lucide="users" class="w-4 h-4"></i>
            Mon Groupe
          </a>
        </div>
        <div class="px-4 py-2 hover:bg-slate-100 rounded-md cursor-pointer text-zinc-600">
          <a class="flex items-center gap-2" href="#">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Mes Choix
          </a>
        </div>
      </div>

      <div class="absolute bottom-8 space-y-2 w-[calc(25%-48px)]">
        <div class="px-4 py-2 hover:bg-slate-100 rounded-md cursor-pointer text-zinc-600">
          <a class="flex items-center gap-2" href="#">
            <i data-lucide="settings" class="w-4 h-4"></i>
            Paramètres
          </a>
        </div>
        <div class="px-4 py-2 hover:bg-slate-100 rounded-md cursor-pointer text-zinc-600">
          <a class="flex items-center gap-2" href="../logout/logout.php">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Déconnexion
          </a>
        </div>
      </div>
    </div>
    
    <div class="flex-1 p-10">
      <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow">
        <h2 class="text-2xl font-bold mb-6 text-center text-indigo-700">Rejoindre un groupe existant</h2>
        
        <?php if (count($groupes) > 0): ?>
          <ul class="space-y-4">
            <?php foreach ($groupes as $groupe): ?>
              <li class="flex justify-between items-center border p-4 rounded-lg">
                <div>
                  <p class="text-lg font-semibold text-gray-700">Groupe #<?= htmlspecialchars($groupe['ID_Groupe']) ?></p>
                  <?php if (!empty($groupe['Nom_Groupe'])): ?>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($groupe['Nom_Groupe']) ?></p>
                  <?php endif; ?>
                </div>
                <form action="traitement_rejoindre.php" method="POST">
                  <input type="hidden" name="id_groupe" value="<?= $groupe['ID_Groupe'] ?>">
                  <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Rejoindre</button>
                </form>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-center text-gray-600">Aucun groupe disponible pour le moment.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script>
    lucide.createIcons();
  </script>

</body>
</html>
