<?php
session_start();

// Check if user is logged in and is admin (add your authentication logic here)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request';
    header('Location: subjects.php');
    exit();
}

$host = 'localhost';
$db = 'unite_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database connection failed';
    header('Location: index.php');
    exit();
}

// Validate inputs
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$professorId = 1; // Replace with actual professor ID from session

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: index.php');
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO sujet (Titre, Description, Date_ajout, ID_Professeur)
                          VALUES (?, ?, NOW(), ?)");
    $stmt->execute([$title, $description, $professorId]);

    $_SESSION['success'] = 'Subject added successfully';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error adding subject: ' . $e->getMessage();
}

header('Location: index.php');
exit();
