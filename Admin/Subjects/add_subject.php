<?php

    $host = 'localhost';
    $db = 'unite_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['titre']) && isset($_POST['description'])) {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);

            // Get the current date and time
            $dateAjout = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

            $sql = "INSERT INTO sujet (Titre, Description, Date_Ajout) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([$titre, $description, $dateAjout]);
                header("Location: index.php");
                exit();
            } catch (PDOException $e) {
                echo "Error inserting data: " . $e->getMessage();
            }
        } else {
            echo "Error: 'titre' or 'description' fields are missing in the form.";
        }
    } else {
        echo "This script expects a POST request.";
    }

?>
