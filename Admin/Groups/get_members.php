<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$host = 'localhost';
$db = 'unite_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $groupId = $_GET['group_id'] ?? null;

    if (!$groupId) {
        throw new Exception('Group ID is required');
    }

    $query = "SELECT
            CONCAT(Nom, ' ', Prenom) AS FullName,
            Sexe,
            Filiere_Precedente,
            Est_Chef  
          FROM Etudiant
          WHERE ID_Groupe = :groupId
          ORDER BY Nom, Prenom";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
    $stmt->execute();

    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($members);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
