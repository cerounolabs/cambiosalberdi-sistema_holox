<?php
    session_start();

    unset($_SESSION['Sys00']);
    unset($_SESSION['Sys01']);
    unset($_SESSION['Sys02']);
    unset($_SESSION['Sys03']);
    
    session_destroy();

    header('Location: ../');
    exit;
?>