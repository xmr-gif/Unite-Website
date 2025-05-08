<?php
$host = 'localhost';
$db = 'unite_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $groupId = $_GET['group_id'];

    $query = "SELECT Prenom, Nom, Filiere_Precedente
              FROM Etudiant
              WHERE ID_Groupe = :groupId";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
    $stmt->execute();

    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($members);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
