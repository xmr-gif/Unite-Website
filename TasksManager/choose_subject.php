<?php
session_start();

// Check if the user is logged in as Etudiant
if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'Etudiant') {
    echo json_encode(['success' => false, 'message' => 'You must be logged in as a student']);
    exit();
}

// Check if subject ID was provided
if (!isset($_POST['subject_id']) || empty($_POST['subject_id'])) {
    echo json_encode(['success' => false, 'message' => 'No subject selected']);
    exit();
}

$etudiant_id = $_SESSION['Etudiant_id'];
$subject_id = $_POST['subject_id'];

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit();
}

// Verify student eligibility
$sql_student = "SELECT Dans_Un_Groupe, Est_Chef, ID_Groupe FROM Etudiant WHERE ID_Etudiant = ?";
$stmt = $conn->prepare($sql_student);
$stmt->bind_param("i", $etudiant_id);
$stmt->execute();
$result_student = $stmt->get_result();

if ($result_student->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Student not found']);
    $stmt->close();
    $conn->close();
    exit();
}

$student_info = $result_student->fetch_assoc();
$stmt->close();

// Check if student is in a group and is a leader
if ($student_info['Dans_Un_Groupe'] != 1) {
    echo json_encode(['success' => false, 'message' => 'You must be in a group to choose a subject']);
    $conn->close();
    exit();
}

if ($student_info['Est_Chef'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Only group leaders can choose subjects']);
    $conn->close();
    exit();
}

$group_id = $student_info['ID_Groupe'];

if (!$group_id) {
    echo json_encode(['success' => false, 'message' => 'Group information not found']);
    $conn->close();
    exit();
}

// Check if the group already has a subject
$sql_group = "SELECT ID_Sujet FROM Groupe WHERE ID_Groupe = ?";
$stmt = $conn->prepare($sql_group);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result_group = $stmt->get_result();

if ($result_group->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Group not found']);
    $stmt->close();
    $conn->close();
    exit();
}

$group_data = $result_group->fetch_assoc();
$stmt->close();

if (!is_null($group_data['ID_Sujet'])) {
    echo json_encode(['success' => false, 'message' => 'Your group already has a subject assigned']);
    $conn->close();
    exit();
}

// Check if subject exists and is available
$sql_subject = "SELECT ID_Sujet FROM sujet WHERE ID_Sujet = ?
                AND ID_Sujet NOT IN (SELECT ID_Sujet FROM Groupe WHERE ID_Sujet IS NOT NULL)";
$stmt = $conn->prepare($sql_subject);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result_subject = $stmt->get_result();

if ($result_subject->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Subject not found or already assigned to another group']);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Update group with the chosen subject
$sql_update = "UPDATE Groupe SET ID_Sujet = ? WHERE ID_Groupe = ?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("ii", $subject_id, $group_id);
$result = $stmt->execute();

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Subject successfully assigned to your group']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to assign subject']);
}

$stmt->close();
$conn->close();
?>
