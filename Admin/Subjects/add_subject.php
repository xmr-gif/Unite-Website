<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$db = 'unite_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $professor_id = 1; // Replace with actual logged-in professor ID

    try {
        $stmt = $pdo->prepare("INSERT INTO sujet (Titre, Description, ID_Professeur)
                       VALUES (:titre, :description, :prof_id)");
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':prof_id' => $professor_id
        ]);

        // Redirect with success message
        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Error adding subject: " . $e->getMessage());
    }
}
