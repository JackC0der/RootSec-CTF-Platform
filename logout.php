<?php
    session_start();

    unset($_SESSION["session"]);

    header("Location:login.php");
?>