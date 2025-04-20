<?php
session_start();

// Check if request is valid
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['selected_subjects'])) {
    $_SESSION['error'] = 'Invalid request';
    header('Location: index.php');
    exit();
}

// Database connection
$host = 'localhost';
$db = 'unite_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database connection failed';
    header('Location: index.php');
    exit();
}

// Validate and sanitize input
$selectedSubjects = array_filter($_POST['selected_subjects'], function($subjectId) {
    return is_numeric($subjectId) && $subjectId > 0;
});

if (empty($selectedSubjects)) {
    $_SESSION['error'] = 'No valid subjects selected';
    header('Location: index.php');
    exit();
}

try {
    // Prepare SQL statement
    $placeholders = rtrim(str_repeat('?,', count($selectedSubjects)), ',');
    $query = "DELETE FROM sujet WHERE ID_Sujet IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($selectedSubjects);

    $_SESSION['success'] = 'Subjects deleted successfully';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error deleting subjects: ' . $e->getMessage();
}

header('Location: index.php');
exit();
