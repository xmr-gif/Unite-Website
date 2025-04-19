<?php
if (isset($_POST['subject']) && is_array($_POST['subject'])) {
    $ids = $_POST['subject']; // tableau d'ID_Sujet

    $host = 'localhost';
    $db = 'unite_db';
    $user = 'root';
    $pass = '';
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$db", $user, $pass);

    // Supprimer tous les sujets cochés
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("DELETE FROM sujet WHERE ID_Sujet IN ($placeholders)");
    $stmt->execute($ids);

    header("Location: index.php"); // Rediriger vers la page principale
    exit;
}
?>