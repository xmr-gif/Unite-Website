<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
    //echo "Connexion reussie";
} catch (PDOException $e) {
    echo "La connexion n'est pas reussie ".$e->getMessage();
}
// Recupeerer les groupes
$query = "SELECT * FROM groupe";
$stmt = $pdo->prepare($query);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
//if (isset($_POST['group_id'])) {
  //$groupId = $_POST['group_id'];
  // Récupérer les etudians
  $query2 = "SELECT Nom, Prenom FROM Etudiant WHERE ID_Groupe = :group_id";
  $stmtStudents = $pdo->prepare($query2);
  $stmtStudents->execute(['group_id' => $groupId]);
  $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

  //recup sjt
  $query3 = "SELECT Titre FROM sujet WHERE ID_Sujet = (SELECT ID_Sujet FROM groupe WHERE ID_Groupe = :group_id)";
  $stmtSujet = $pdo->prepare($query3);
  $stmtSujet->execute(['group_id' => $groupId]);
  $sujet = $stmtSujet->fetch(PDO::FETCH_ASSOC);

//}



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
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Users/index.php" >
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Subjects/index.php">
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Subjects
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" >
                  <i data-lucide="book-open" class="w-4 h-4"></i>
                  Choices
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Blogs/index.html">
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
                  <i data-lucide="log-out" class="w-4 h-4"></i>
                  Log Out
                </span>
              </div>
            </div>
          </div>

          <div class="w-3/4 py-5 px-6">
        <h2 class="text-xl font-bold mb-4">Groups List</h2>
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID Groupe</th>
                    <th class="border px-4 py-2">ID Sujet</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group) : ?>
                    <tr>

                        <td class="border px-4 py-2"><?= $group['ID_Groupe']; ?></td>
                        <td class="border px-4 py-2"><?= $group['ID_Sujet']; ?></td>
                        <td class="border px-4 py-2">
                            <!-- Ouvrir le modal en passant l'ID du groupe -->
                                <form method="POST"  id="groupForm">
                                <input type="hidden" name="group_id" value="<?= $group['ID_Groupe']?>">
                                <button type="submit">Details</button>
                              </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Group Details Modal -->
        <?php if (isset($_POST['group_id'])) : ?>
            <div id="groupModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-5 max-h-[80vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Group Details</h3>
                        <a href="#" class="close-modal text-gray-500 hover:text-gray-700">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </a>
                    </div>



                    <div class="flex items-center gap-2">
    <!-- Affichage des étudiants -->
    <span class="font-medium w-24">Students:</span>
    <div class="text-gray-600">
        <?php if (count($students) > 0) : ?>
            <ul>
                <?php foreach ($students as $student) : ?>
                    <li><?= $student['Nom'] . ' ' . $student['Prenom']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Il n'existe pas d'étudiants dans ce groupe.</p>
        <?php endif; ?>
    </div>
</div>

<div class="flex items-center gap-2 mt-4">
    <!-- Affichage du sujet -->
    <span class="font-medium w-24">Sujet:</span>
    <div class="text-gray-600">
        <?php if($sujet): ?>
            <ul>
                <li><?= $sujet['Titre']; ?></li>
            </ul>
        <?php else : ?>
            <p>Il n'existe pas de sujet dans ce groupe.</p>
        <?php endif; ?>
    </div>
</div>


                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Fermer le modal
    document.querySelector('.close-modal').addEventListener('click', () => {
        document.getElementById('groupModal').classList.add('hidden');
    });
</script>

<script>
    lucide.createIcons();
</script>
</body>
</html>
