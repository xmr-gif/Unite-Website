<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unite_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$subjectId = $data['subject_id'] ?? null;
$groupId = $data['group_id'] ?? null;

// Validate input
if (!$subjectId || !$groupId) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters'
    ]);
    exit;
}

try {
    // Verify group exists and doesn't have a subject
    $stmt = $conn->prepare("SELECT ID_Sujet FROM Groupe WHERE ID_Groupe = ?");
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();

    if (!$group) {
        echo json_encode([
            'success' => false,
            'message' => 'Group not found'
        ]);
        exit;
    }

    if ($group['ID_Sujet'] !== null) {
        echo json_encode([
            'success' => false,
            'message' => 'Group already has a subject assigned'
        ]);
        exit;
    }

    // Verify subject is available
    $stmt = $conn->prepare("SELECT ID_Sujet FROM sujet WHERE ID_Sujet = ?");
    $stmt->bind_param("i", $subjectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Subject not found'
        ]);
        exit;
    }

    // Update group with selected subject
    $stmt = $conn->prepare("UPDATE Groupe SET ID_Sujet = ? WHERE ID_Groupe = ?");
    $stmt->bind_param("ii", $subjectId, $groupId);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Subject assigned successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to assign subject: ' . $conn->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    $conn->close();
}
?>
