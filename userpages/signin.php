<?php 
    require_once('../includes/DB.php');
    require_once('../includes/Functions.php');
    require_once('../includes/Sessions.php');

    if (isset($_SESSION["UserId"])) {
        Redirect_to("blog.php?page=1");
    }

    if(isset($_POST["Submit"])){
        $UserName = $_POST["Username"];
        $Password = $_POST["Password"];
        if(empty($UserName) || empty($Password)){
            $_SESSION["ErrorMessage"] = "All fields must be filled out";
            Redirect_to("signin.php");
        } else {
            //code for checking username and password
            $Found_Account = LoginAttempt($UserName,$Password);
            if ($Found_Account) {
                $_SESSION["UserId"] = $Found_Account["id"];
                $_SESSION["UserName"] = $Found_Account["username"];
                $_SESSION["UName"] = $Found_Account["name"];
                $_SESSION["SuccessMessage"]="Welcome ".$_SESSION["UName"];

                if (isset($_SESSION["TrackingURL"])) {
                    Redirect_to($_SESSION["TrackingURL"]);
                } else {
                    Redirect_to("blog.php?page=1");
                }

            } else {
                $_SESSION["ErrorMessage"]= "Incorrect Username/Password";
                Redirect_to("signin.php");
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
    <title>Signin</title>
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
                    <h2>Sign In</h2>
                    <p>Welcome Back!</p>
                    <form action="signin.php" method="post">
                        <div class="inputBox">
                            <input type="text" placeholder="Username" name="Username">
                        </div>
                        <div class="inputBox">
                            <input type="password" placeholder="Password" name="Password">
                        </div>
                        <div class="inputBox">
                            <input type="submit" value="Login" name="Submit">
                        </div>
                        <p class="already">
                            Don't have an account? <a href="signup.php">Sign Up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>