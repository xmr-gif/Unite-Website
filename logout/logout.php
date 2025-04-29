<?php
    session_start();
    session_destroy();
    header('Location: ../SignupPage/index.php');
    exit();
?>