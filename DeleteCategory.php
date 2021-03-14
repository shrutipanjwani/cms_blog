<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    if (isset($_GET["id"])) {
        $SearchQueryParameter = $_GET["id"];
        global $ConnectingDB;
       
        $sql = "DELETE FROM category WHERE id='$SearchQueryParameter'";
        $Execute = $ConnectingDB->query($sql);
        if ($Execute) {
            $_SESSION["SuccessMessage"]="Category Deleted Successfully!";
            Redirect_to("Categories.php");
        } else {
            $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
            Redirect_to("Categories.php");
        }
    }
?>