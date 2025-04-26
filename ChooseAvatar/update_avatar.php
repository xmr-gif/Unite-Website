<?php
session_start();

// Check if the form was submitted and the avatar number is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_avatar'])) {
    $avatar_number = $_POST['selected_avatar'];

    // Check if basic user info is in the session (from signup)
    if (isset($_SESSION['account_type']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['email'])) {
        $account_type = $_SESSION['account_type'];
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        $email = $_SESSION['email'];
        $table = ($account_type === 'professor') ? 'professors' : 'students';
        $id_column = ($account_type === 'professor') ? 'D_Professeur' : 'D_Etudiant'; // Adjust column names

        // Database connection details (replace with your actual credentials)
        $servername = "your_servername";
        $username = "your_username";
        $password = "your_password";
        $dbname = "your_dbname";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // First, save the basic user information if the ID isn't in the session yet
            if (!isset($_SESSION[$account_type . '_id'])) {
                $sql_insert = "INSERT INTO $table (Nom, Prenom, Email, Password) VALUES (:lastname, :firstname, :email, :password)"; // You'll likely need to handle password hashing on signup
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(':lastname', $lastname);
                $stmt_insert->bindParam(':firstname', $firstname);
                $stmt_insert->bindParam(':email', $email);
                $stmt_insert->bindParam(':password', $dummy_password); // Replace with actual password from signup (consider hashing earlier)
                $dummy_password = 'temporary'; // Placeholder - you MUST handle the actual password securely

                if ($stmt_insert->execute()) {
                    // Get the last inserted ID
                    $user_id = $conn->lastInsertId();
                    $_SESSION[$account_type . '_id'] = $user_id; // Store the new ID in the session
                } else {
                    echo "Error saving basic user information.";
                    exit();
                }
            }

            // Now, update the avatar using the ID from the session
            if (isset($_SESSION[$account_type . '_id'])) {
                $user_id = $_SESSION[$account_type . '_id'];
                $avatar_column = 'Avatar'; // Assuming 'Avatar' is the column name

                $sql_update = "UPDATE $table SET $avatar_column = :avatar WHERE $id_column = :user_id";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindParam(':avatar', $avatar_number);
                $stmt_update->bindParam(':user_id', $user_id);

                if ($stmt_update->execute()) {
                    // Avatar updated successfully, redirect to the dashboard
                    header("Location: ../Home page/dashboard.php"); // Adjust path as needed
                    exit();
                } else {
                    echo "Error updating avatar.";
                }
            } else {
                echo "User ID not found in session after signup.";
            }

        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $conn = null; // Close connection
    } else {
        echo "Essential user information not found in session.";
    }
} else {
    echo "Invalid request.";
}
?>
