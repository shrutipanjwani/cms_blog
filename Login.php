<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    if (isset($_SESSION["UserId"])) {
        Redirect_to("Dashboard.php");
    }

    if(isset($_POST["Submit"])){
        $UserName = $_POST["Username"];
        $Password = $_POST["Password"];
        if(empty($UserName) || empty($Password)){
            $_SESSION["ErrorMessage"] = "All fields must be filled out";
            Redirect_to("Login.php");
        } else {
            //code for checking username and password
            $Found_Account = Login_Attempt($UserName,$Password);
            if ($Found_Account) {
                $_SESSION["UserId"] = $Found_Account["id"];
                $_SESSION["UserName"] = $Found_Account["username"];
                $_SESSION["AdminName"] = $Found_Account["aname"];
                $_SESSION["SuccessMessage"]="Welcome ".$_SESSION["AdminName"];

                if (isset($_SESSION["TrackingURL"])) {
                    Redirect_to($_SESSION["TrackingURL"]);
                } else {
                    Redirect_to("Dashboard.php");
                }

            } else {
                $_SESSION["ErrorMessage"]= "Incorrect Username/Password";
                Redirect_to("Login.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <title>Login</title>
</head>
<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
             <a href="#" class="navbar-brand">SHRUTI</a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS" 
             aria-controls="navbarcollapseCMS"aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarcollapseCMS">
                
            </div>
        </div>
    </nav>
    <!--Navbar End-->

     <!--Header-->
     <header class="bg-dark text-white py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                       
                    </div>
                </div>
            </div>
        </header>
    <!--Header End-->

    <!--Main Area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-sm-3 col-sm-6" style="min-height: 580px">
                <br>
                <br>
                <br>
                <br>
                <?php 
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                <div class="card bg-secondary text-light">
                    <div class="card-header">
                        <h4>Welcome Back!</h4>
                    </div>
                    <div class="card-body bg-dark">
                        <form action="Login.php" method="post">
                            <div class="form-group">
                                <label for="username">
                                    <span class="FieldInfo">Username:</span>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="Username" id="username">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">
                                    <span class="FieldInfo">Password:</span>
                                </label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="Password" id="password">
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info btn-block" value="Login" name="Submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Main Area Ends-->
    
    <!--Footer-->
    <footer class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="lead text-center">Theme by Shruti Panjwani &copy;<span id="year"></span> All rights reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        $('#year').text(new Date().getFullYear());
    </script>
</body>
</html>