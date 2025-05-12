<?php
session_start();

// Check if the form was submitted and the avatar number is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_avatar'])) {
    $avatar_number = $_POST['selected_avatar'];

    // Check if essential session data exists
    if (
        isset($_SESSION['account_type'], $_SESSION['Prenom'], $_SESSION['Nom'],
        $_SESSION['Email'], $_SESSION[$_SESSION['account_type'] . '_id'])
    ) {
        $account_type = $_SESSION['account_type'];
        $user_id = $_SESSION[$account_type . '_id'];

        // Define table and column names (case-sensitive)
        $table = ($account_type === 'professeur') ? 'Professeur' : 'Etudiant';
        $id_column = ($account_type === 'professeur') ? 'ID_Professeur' : 'ID_Etudiant';
        $avatar_column = 'Avatar';

        try {
            // Database connection (use your actual credentials)
            $host = 'localhost';
            $db = 'unite_db';
            $user = 'root';
            $pass = '';
            $pdo = new PDO("mysql:host=$host;port=3307;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Update the avatar
            $sql = "UPDATE $table SET $avatar_column = :avatar WHERE $id_column = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':avatar', $avatar_number);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                // Redirect to dashboard
                $dashboard = ($account_type === 'professeur')
                    ? '../Dashboard/dashboardProf.php'
                    : '../Dashboard/dashboardEt.php';
                header("Location: $dashboard");
                exit();
            } else {
                echo "Error updating avatar: " . implode(", ", $stmt->errorInfo());
            }

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }

    } else {
        echo "Essential user information or User ID not found in session.";
        // Debug: Print session data
        echo "<pre>Session Data:\n";
        print_r($_SESSION);
        echo "</pre>";
    }
} else {
    echo "Invalid request.";
}
