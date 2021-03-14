<?php 
    require_once('../includes/Functions.php');
    require_once('../includes/Sessions.php');

    $_SESSION["UserId"] = null;
    $_SESSION["UserName"] = null;
    $_SESSION["UName"] = null;

    session_destroy();
    Redirect_to('../index.php?page=1');
?>