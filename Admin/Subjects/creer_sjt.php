<?php
    
    $host = 'localhost';
    $db = 'unite_db';
    $user = 'root';
    $pass = '';
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$db", $user, $pass);

    $sql = "INSERT INTO sujet ( `Titre`, `Description`) VALUES(?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['titre'],$_POST['description']]);

?>