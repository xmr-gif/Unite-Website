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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['subject'])) {
    $subject_ids = $_POST['subject'];

    try {
        // Create placeholders for IN clause
        $placeholders = str_repeat('?,', count($subject_ids) - 1) . '?';

        $stmt = $pdo->prepare("DELETE FROM sujet WHERE ID_Sujet IN ($placeholders)");
        $stmt->execute($subject_ids);

        // Redirect with success message
        header("Location: index.php?deleted=1");
        exit();
    } catch (PDOException $e) {
        die("Error deleting subjects: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
