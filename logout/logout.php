<?php
    session_start();
    session_destroy();
    header('Location: ../Home page/index.html');
    exit();
?>
