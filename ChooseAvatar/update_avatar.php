<?php
session_start();

// Check if the form was submitted and the avatar number is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_avatar'])) {
    $avatar_number = $_POST['selected_avatar'];

        $account_type = $_SESSION['account_type'];
        $user_id = $_SESSION[$account_type . '_id'];
        $table = ($account_type === 'professor') ? 'Professeur' : 'Etudiant';
        $id_column = ($account_type === 'professor') ? 'ID_Professeur' : 'ID_Etudiant';
        $avatar_column = 'Avatar';

        // Database connection details
        $host ='localhost';
        $db = 'unite_db';
        $user='root';
        $pass ='';
        try {
            $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Update the avatar for the existing user
            $sql_update = "UPDATE $table SET $avatar_column = :avatar WHERE $id_column = :user_id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':avatar', $avatar_number);
            $stmt_update->bindParam(':user_id', $user_id);

            if ($stmt_update->execute()) {
                // Avatar updated successfully, redirect to the dashboard
                header("Location: ../Home page/dashboard.php");
                exit();
            } else {
                echo "Error updating avatar.";
                print_r($stmt_update->errorInfo());
            }

        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    } else {
        echo "Essential user information or User ID not found in session.";
    }
} else {
    echo "Invalid request.";
}
?>
