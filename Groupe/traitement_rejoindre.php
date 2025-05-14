<?php
session_start();

$host = 'localhost';
$db = 'unite_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    echo "La connexion a échoué : " . $e->getMessage();
    exit;
}

// Récupérer l'ID de l'étudiant depuis la session
$account_type = $_SESSION['account_type'] ?? 'etudiant';
$id_column = 'id_' . $account_type;
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
    echo "Vous êtes déjà dans un groupe.";
    exit;
}

// Vérifier si l'ID du groupe est bien passé
if (isset($_POST['id_groupe'])) {
    $id_groupe = $_POST['id_groupe'];

    // Vérifier si le groupe existe
    $queryGroupeExist = "SELECT * FROM groupe WHERE ID_Groupe = :id_groupe";
    $stmtGroupeExist = $pdo->prepare($queryGroupeExist);
    $stmtGroupeExist->execute(['id_groupe' => $id_groupe]);
    $groupe = $stmtGroupeExist->fetch(PDO::FETCH_ASSOC);

    if ($groupe) {
        // Rejoindre le groupe en mettant à jour la table "etudiant"
        $queryRejoindre = "UPDATE etudiant SET ID_Groupe = :id_groupe WHERE ID_Etudiant = :id";
        $stmtRejoindre = $pdo->prepare($queryRejoindre);
        $stmtRejoindre->execute(['id_groupe' => $id_groupe, 'id' => $id]);

        echo "Vous avez rejoint le groupe avec succès.";
        header("Location: ../Groupe/index.php"); // Rediriger vers la page du groupe
        exit;
    } else {
        echo "Le groupe sélectionné n'existe pas.";
    }
} else {
    echo "Aucun groupe sélectionné.";
}
?>