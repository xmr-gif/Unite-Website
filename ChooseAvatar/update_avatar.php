<?php
session_start();

// Check if the form was submitted and the avatar number is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_avatar'])) {
    $avatar_number = $_POST['selected_avatar'];

<<<<<<< HEAD
    // Check if essential session data exists
    if (
        isset($_SESSION['account_type'], $_SESSION['Prenom'], $_SESSION['Nom'],
        $_SESSION['Email'], $_SESSION[$_SESSION['account_type'] . '_id'])
    ) {
=======
    // Check if essential user information AND the user ID are in the session
    if (isset($_SESSION['account_type']) && isset($_SESSION['Prenom']) && isset($_SESSION['Nom']) && isset($_SESSION['Email']) ) {
>>>>>>> cc2df2073e7bd32afc1c191f43b665d0994e3d87
        $account_type = $_SESSION['account_type'];
        $user_id = $_SESSION[$account_type . '_id'];

        // Define table and column names (case-sensitive)
        $table = ($account_type === 'professeur') ? 'Professeur' : 'Etudiant';
        $id_column = ($account_type === 'professeur') ? 'ID_Professeur' : 'ID_Etudiant';
        $avatar_column = 'Avatar';

        try {
<<<<<<< HEAD
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
=======
            $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Recommended for error handling
            //echo "Connexion reussite";
>>>>>>> cc2df2073e7bd32afc1c191f43b665d0994e3d87

        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }

<<<<<<< HEAD
=======
        // First, save the basic user information if the ID isn't in the session yet
        if (!isset($_SESSION[$account_type . '_id'])) {
            $sql_insert = "INSERT INTO $table (Nom, Prenom, Email, Mdp) VALUES (:nom, :prenom, :email, :mdp)";
            $stmt_insert = $pdo->prepare($sql_insert); // Use $pdo
            $stmt_insert->bindParam(':nom', $nom);
            $stmt_insert->bindParam(':prenom', $prenom);
            $stmt_insert->bindParam(':email', $email);
            $stmt_insert->bindParam(':mdp', $mdp); // Use $hashed_password - IMPORTANT!
            

            if ($stmt_insert->execute()) {
                // Get the last inserted ID
                $user_id = $pdo->lastInsertId(); // Use $pdo
                $_SESSION[$account_type . '_id'] = $user_id;
                print_r($_SESSION[$account_type . '_id']);
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

            // Update the avatar for the existing user
            $sql_update = "UPDATE $table SET $avatar_column = :avatar WHERE $id_column = :user_id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':avatar', $avatar_number);
            $stmt_update->bindParam(':user_id', $user_id);

            if ($stmt_update->execute()) {
                // Avatar updated successfully, redirect to the dashboard
                if ($_SESSION['account_type'] === 'etudiant') {
                    header("Location: ../Student/dashboardEt.php"); // Dashboard étudiant
                } elseif ($_SESSION['account_type'] === 'professeur') {
                    header("Location: ../Professor/dashboardProf.php"); // Dashboard professeur
                }
                exit();
            } else {
                echo "Error updating avatar.";
                print_r($stmt_update->errorInfo());
            }

        } 

>>>>>>> cc2df2073e7bd32afc1c191f43b665d0994e3d87
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
