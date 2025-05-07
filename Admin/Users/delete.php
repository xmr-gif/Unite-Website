<?php

if (isset($_POST['userss']) && is_array($_POST['userss'])) {
    print_r($_POST['userss']);
    $users = $_POST['userss'];

    $host = 'localhost';
    $db = 'unite_db';
    $user = 'root';
    $pass = '';
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($users as $userData) {
        list($role, $email) = explode('|', $userData); // Séparer rôle et email

        if ($role === 'etudiant') {
            $stmt = $pdo->prepare("DELETE FROM etudiant WHERE Email = ?");
        } elseif ($role === 'professeur') {
            $stmt = $pdo->prepare("DELETE FROM professeur WHERE Email = ?");
        }

        $stmt->execute([$email]);
    }

    header("Location: index.php");
    exit;}
?>
