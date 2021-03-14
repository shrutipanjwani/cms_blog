<?php 
    require_once("DB.php");

    function Redirect_to($NewLocation){
        header("Location:".$NewLocation);
        exit;
    }

    function CheckAdminExistsOrNot($Username)
    {
        global $ConnectingDB;
        $sql = "SELECT username FROM admins WHERE username=:userName";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':userName', $Username);
        $stmt->execute();
        $Result = $stmt->rowcount();
        if ($Result == 1) {
            return true;
        } else {
            return false;
        }
    }

    function CheckUserExistsOrNot($Username)
    {
        global $ConnectingDB;
        $sql = "SELECT username FROM users WHERE username=:userName";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':userName', $Username);
        $stmt->execute();
        $Result = $stmt->rowcount();
        if ($Result == 1) {
            return true;
        } else {
            return false;
        }
    }

    function Login_Attempt($UserName,$Password){
        global $ConnectingDB;
        $sql = "SELECT * FROM admins WHERE username=:userName AND password=:password LIMIT 1";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':userName',$UserName);
        $stmt->bindValue(':password',$Password);
        $stmt->execute();
        $Result = $stmt->rowcount();
        if ($Result == 1) { 
            return $Found_Account = $stmt->fetch();
        } else {
            return null;
        }
    }

    function LoginAttempt($UserName,$Password){
        global $ConnectingDB;
        $sql = "SELECT * FROM users WHERE username=:userName AND password=:password LIMIT 1";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':userName',$UserName);
        $stmt->bindValue(':password',$Password);
        $stmt->execute();
        $Result = $stmt->rowcount();
        if ($Result == 1) { 
            return $Found_Account = $stmt->fetch();
        } else {
            return null;
        }
    }

    function Confirm_Login()
    {
       if (isset($_SESSION["UserId"])) {
           return true;
       } else {
           $_SESSION["ErrorMessage"] = "Login Required";
           Redirect_to("Login.php");
       }
    }

    function ConfirmLogin()
    {
       if (isset($_SESSION["UserId"])) {
           return true;
       } else {
           $_SESSION["ErrorMessage"] = "Login Required";
           Redirect_to("../userpages/signin.php");
       }
    }

    function TotalPosts(){
        global $ConnectingDB;
        $sql = "SELECT COUNT(*) FROM posts";
        $stmt = $ConnectingDB->query($sql);
        $TotalRows = $stmt->fetch();
        $TotalPosts = array_shift($TotalRows);
        echo $TotalPosts;
    }

    function TotalCategories(){
        global $ConnectingDB;
        $sql = "SELECT COUNT(*) FROM category";
        $stmt = $ConnectingDB->query($sql);
        $TotalRows = $stmt->fetch();
        $TotalCategories = array_shift($TotalRows);
        echo $TotalCategories;
    }

    function TotalAdmins(){
        global $ConnectingDB;
        $sql = "SELECT COUNT(*) FROM admins";
        $stmt = $ConnectingDB->query($sql);
        $TotalRows = $stmt->fetch();
        $TotalAdmins = array_shift($TotalRows);
        echo $TotalAdmins;
    }

    function TotalComments(){
        global $ConnectingDB;
        $sql = "SELECT COUNT(*) FROM comments";
        $stmt = $ConnectingDB->query($sql);
        $TotalRows = $stmt->fetch();
        $TotalComments = array_shift($TotalRows);
        echo $TotalComments;
    }

    function ApproveCommentsAccordingtoPost($PostId){
        global $ConnectingDB;
        $sqlApprove = "SELECT COUNT(*) FROM comments WHERE post_id='$PostId' AND status='ON'";
        $stmtApprove = $ConnectingDB->query($sqlApprove);
        $RowsTotal = $stmtApprove->fetch();
        $Total = array_shift($RowsTotal);
        return $Total;
    }

    function DisApproveCommentsAccordingtoPost($PostId){
        global $ConnectingDB;
        $sqlDisApprove = "SELECT COUNT(*) FROM comments WHERE post_id='$PostId' AND status='ON'";
        $stmtDisApprove = $ConnectingDB->query($sqlDisApprove);
        $RowsTotal = $stmtDisApprove->fetch();
        $Total = array_shift($RowsTotal);
        return $Total;
    }
?>