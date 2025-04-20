<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'unite_db'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT
        ID_Professeur,
        CONCAT(Nom, ' ', Prenom) AS FullName,
        Email,
        Est_Admin
        FROM professeur");

    $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($professors)) {
        echo json_encode(['error' => 'No professors found']);
        exit;
    }

    echo json_encode($professors);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'details' => $e->getMessage()
    ]);
}
