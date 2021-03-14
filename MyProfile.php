<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
    Confirm_Login();

    //Fetching the existing admin data start
    $AdminId = $_SESSION["UserId"];
    global $ConnectingDB;
    $sql = "SELECT * FROM admins WHERE id='$AdminId'";
    $stmt = $ConnectingDB->query($sql);
    while ($DataRows = $stmt->fetch()) {
        $ExistingName = $DataRows["aname"];
        $ExistingUsername = $DataRows["username"];
        $ExistingHeadline = $DataRows["aheadline"];
        $ExistingBio = $DataRows["abio"];
        $ExistingImage = $DataRows["aimage"];
    }
    //Fetching the existing admin data end

    if(isset($_POST["Submit"])){
        $AName = $_POST["Name"];
        $AHeadline = $_POST["Headline"];
        $ABio = $_POST["Bio"];
        $Image = $_FILES["Image"]["name"];
        $Target = "images/".basename($_FILES["Image"]["name"]);

       
        if(strlen($AHeadline) > 30){
            $_SESSION["ErrorMessage"] = "Headline should be less than 30 characters";
            Redirect_to("MyProfile.php");
        } else if(strlen($ABio) > 500){
            $_SESSION["ErrorMessage"] = "Bio should be less than 500 characters";
            Redirect_to("MyProfile.php");
        } else {
            //Query to update admin data DB when everything is fine.
            global $ConnectingDB;
            if (!empty($_FILES["Image"]["name"])) {
                $sql = "UPDATE admins
                    SET aname='$AName', aheadline='$AHeadline', aimage='$Image', abio='$ABio'
                    WHERE id='$AdminId'";
            } else {
                $sql = "UPDATE admins
                    SET aname='$AName', aheadline='$AHeadline', abio='$ABio'
                    WHERE id='$AdminId'";
            }
            
            $Execute = $ConnectingDB->query($sql);  
            move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);

            if ($Execute) {
                $_SESSION["SuccessMessage"]="Details Updated Successfully!";
                Redirect_to("MyProfile.php");
            } else {
                $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
                Redirect_to("MyProfile.php");
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
    
    <title>My Profile</title>
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
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link"><i class="fa fa-user"></i> My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="Posts.php" class="nav-link">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a href="Categories.php" class="nav-link">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a href="Admins.php" class="nav-link">Manage Admins</a>
                    </li>
                    <li class="nav-item">
                        <a href="Comments.php" class="nav-link">Comments</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php" class="nav-link">Live Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="Logout.php" class="nav-link">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!--Navbar End-->
    <!--Header-->
        <header class="bg-dark text-white py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2><i class="fas fa-user mr-2"></i> @<?php echo $ExistingUsername; ?></h2>
                        <small><?php echo $ExistingHeadline; ?></small>
                    </div>
                </div>
            </div>
        </header>
    <!--Header End-->
    <!--Main Area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <!-- Left Area -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h3><?php echo $ExistingName; ?></h3>
                    </div>
                    <div class="card-body">
                        <img src="images/<?php echo $ExistingImage; ?>" alt="" class="img-fluid mb-3 d-block">
                        <div>
                            <?php echo $ExistingBio; ?> 
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Area -->
            <div class="col-md-9">
                <?php 
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>

                <form class="" action="MyProfile.php" method="post" enctype="multipart/form-data">
                    <div class="card bg-dark text-light">
                        <div class="card-header bg-secondary text-light">
                            <h4>Edit Profile</h4>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" type="text" name="Name" value="" placeholder="Type your name">
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="text" value="" name="Headline" placeholder="Headline">
                                <small class="text-white">Add a professional headline like, 'Engineer' at XYZ or 'Architect'</small>
                                <span class="text-danger">Not more than 30 characters</span>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" placeholder="Bio" name="Bio" rows="8" cols="80"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="imageSelect">
                                    <span class="FieldInfo">Select Image</span>
                                </label>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="Image" id="imageSelect" value="">
                                    <label for="imageSelect" class="custom-file-label">Select Image</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block">
                                        <i class="fas fa-arrow-left"></i>
                                         &nbsp;Back to Dashboard
                                    </a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-success btn-block" type="submit" name="Submit">
                                        <i class="fas fa-check"></i>
                                        &nbsp;Publish
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

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