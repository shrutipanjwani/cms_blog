<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    if (isset($_GET['id'])) {
        $SearchQueryParameter = $_GET['id'];
        global $ConnectingDB;
        $Admin = $_SESSION['AdminName'];
        $sql = "UPDATE comments SET status='OFF', approvedby='$Admin' WHERE id='$SearchQueryParameter'";
        $Execute = $ConnectingDB->query($sql);
        if ($Execute) {
            $_SESSION["SuccessMessage"]="Comment Disapproved Successfully!";
            Redirect_to("Comments.php");
        } else {
            $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
            Redirect_to("Comments.php");
        }
    }
?>