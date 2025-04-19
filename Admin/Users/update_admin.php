<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'unite_db';  
$user = 'root';
$pass = '';


$data = json_decode(file_get_contents('php://input'), true);
$professorId = $data['id'] ?? null;

if (!$professorId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request: Missing professor ID']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Toggle admin status
    $stmt = $pdo->prepare("UPDATE professeur
                          SET Est_Admin = 1 - Est_Admin
                          WHERE ID_Professeur = :id");

    $stmt->bindParam(':id', $professorId, PDO::PARAM_INT);
    $stmt->execute();

    // Get updated status
    $stmt = $pdo->prepare("SELECT Est_Admin FROM professeur WHERE ID_Professeur = :id");
    $stmt->bindParam(':id', $professorId, PDO::PARAM_INT);
    $stmt->execute();

    

    echo json_encode([
        'success' => true,
        'new_status' => $result['Est_Admin']
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'details' => $e->getMessage()
    ]);
}
