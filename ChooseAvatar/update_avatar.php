<?php
session_start();

// Check if the form was submitted and the avatar number is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_avatar'])) {
    $avatar_number = $_POST['selected_avatar'];

    // Check if basic user info is in the session (from signup)
    if (isset($_SESSION['account_type']) && isset($_SESSION['Prenom']) && isset($_SESSION['Nom']) && isset($_SESSION['Email'])) {
        $account_type = $_SESSION['account_type'];
        $prenom = $_SESSION['Prenom'];
        $nom = $_SESSION['Nom'];
        $email = $_SESSION['Email'];
        $table = ($account_type === 'professor') ? 'Professeur' : 'Etudiant';
        $id_column = ($account_type === 'professor') ? 'ID_Professeur' : 'ID_Etudiant'; // Ensure this matches your database

        // Database connection details
        $host ='localhost';
        $db = 'unite_db';
        $user='root';
        $pass ='';
        try {
            $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Recommended for error handling
            //echo "Connexion reussite";

        } catch (PDOException $e) {
            echo "La connexion n'est pas reussie ".$e->getMessage() ;
            exit(); // Stop execution if connection fails
        }

        // First, save the basic user information if the ID isn't in the session yet
        if (!isset($_SESSION[$account_type . '_id'])) {
            $sql_insert = "INSERT INTO $table (Nom, Prenom, Email, Mdp) VALUES (:nom, :prenom, :email, :mdp)";
            $stmt_insert = $pdo->prepare($sql_insert); // Use $pdo
            $stmt_insert->bindParam(':nom', $nom);
            $stmt_insert->bindParam(':prenom', $prenom);
            $stmt_insert->bindParam(':email', $email);
            $stmt_insert->bindParam(':mdp', $hashed_password); // Use $hashed_password - IMPORTANT!
            $hashed_password = 'temporary'; // THIS IS STILL A PLACEHOLDER - YOU MUST GET THE HASHED PASSWORD FROM SIGNUP

            if ($stmt_insert->execute()) {
                // Get the last inserted ID
                $user_id = $pdo->lastInsertId(); // Use $pdo
                $_SESSION[$account_type . '_id'] = $user_id;
            } else {
                echo "Error saving basic user information.";
                print_r($stmt_insert->errorInfo()); // Debug database errors
                exit();
            }
        }

        // Now, update the avatar using the ID from the session
        if (isset($_SESSION[$account_type . '_id'])) {
            $user_id = $_SESSION[$account_type . '_id'];
            $avatar_column = 'Avatar';

            $sql_update = "UPDATE $table SET $avatar_column = :avatar WHERE $id_column = :user_id";
            $stmt_update = $pdo->prepare($sql_update); // Use $pdo
            $stmt_update->bindParam(':avatar', $avatar_number);
            $stmt_update->bindParam(':user_id', $user_id);

            if ($stmt_update->execute()) {
                // Avatar updated successfully, redirect to the dashboard
                header("Location: ../Home page/dashboard.php"); // Adjust path as needed
                exit();
            } else {
                echo "Error updating avatar.";
                print_r($stmt_update->errorInfo()); // Debug database errors
            }
        } else {
            echo "User ID not found in session after signup.";
        }

    } else {
        echo "Essential user information not found in session.";
    }
} else {
    echo "Invalid request.";
}
?>
