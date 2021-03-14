<?php 
    require_once('../includes/DB.php');
    require_once('../includes/Functions.php');
    require_once('../includes/Sessions.php');

    if(isset($_POST["Submit"])){
        $UserName = $_POST["Username"];
        $Name = $_POST["Name"];
        $Password = $_POST["Password"];
        $ConfirmPassword = $_POST["ConfirmPassword"];

        date_default_timezone_set("Asia/Kolkata");
        $CurrentTime = time();
        // $DateTime = strftime( "%Y-%m-%d %H:%M:%S" , $CurrentTime);
        $DateTime = strftime( "%B-%d-%y %H:%M:%S" , $CurrentTime);

        if(empty($UserName) || empty($Password) || empty($ConfirmPassword)){
            $_SESSION["ErrorMessage"] = "All fields must be filled out";
            Redirect_to("signup.php");
        } else if(strlen($Password) < 4){
            $_SESSION["ErrorMessage"] = "Password should be greater than 3 characters";
            Redirect_to("signup.php");
        } else if($Password !== $ConfirmPassword){
            $_SESSION["ErrorMessage"] = "Password & Confirm Password must match";
            Redirect_to("signup.php");
        } else if(CheckUserExistsOrNot($UserName)){
            $_SESSION["ErrorMessage"] = "Username Exists. Try another one!";
            Redirect_to("signup.php");
        } else {
            //Query to insert user in DB when everything is fine.
            $sql = "INSERT INTO users(datetime, username, name, password)";
            $sql .= "VALUES(:datetime,:userName,:name,:password)";
            $stmt = $ConnectingDB->prepare($sql);

            $stmt->bindValue(':datetime',$DateTime);
            $stmt->bindValue(':userName',$UserName);
            $stmt->bindValue(':password',$Password);
            $stmt->bindValue(':name',$Name);
            
            $Execute=$stmt->execute();

            if ($Execute) {
                $_SESSION["SuccessMessage"]="Welcome, ". $Name ." ";
                Redirect_to("blog.php?page=1");
            } else {
                $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
                Redirect_to("signup.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        <?php include "../css/style.css" ?>
        body{
            overflow: hidden;
        }
    </style>
</head>
<body>
    <section class="login-section">
        <div class="color"></div>
        <div class="color"></div>
        <div class="color"></div>
        <div class="box">
            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <?php 
                echo ErrorMessage();
                echo SuccessMessage();
            ?>
            <br>
            <div class="login-container">
                <div class="form">
                    <h2>Sign Up</h2>
                    <form action="signup.php" method="post">
                        <div class="inputBox">
                            <input type="text" placeholder="Username" name="Username">
                        </div>
                        <div class="inputBox">
                            <input type="text" placeholder="Name" name="Name">
                        </div>
                        <div class="inputBox">
                            <input type="password" placeholder="Password" name="Password">
                        </div>
                        <div class="inputBox">
                            <input type="password" placeholder="Confirm Password" name="ConfirmPassword">
                        </div>
                        <div class="inputBox">
                            <input type="submit" value="Register" name="Submit">
                        </div>
                        <p class="already">
                            Already have an account? <a href="signin.php">Sign In</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>