<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
    Confirm_Login();

    if(isset($_POST["Submit"])){
        $PostTitle = $_POST["PostTitle"];
        $Category = $_POST["Category"];
        $Image = $_FILES["Image"]["name"];
        $Target = "uploads/".basename($_FILES["Image"]["name"]);
        $PostText = $_POST["PostDescription"];
        $Admin = $_SESSION['UserName'];

        date_default_timezone_set("Asia/Kolkata");
        $CurrentTime = time();
        // $DateTime = strftime( "%Y-%m-%d %H:%M:%S" , $CurrentTime);
        $DateTime = strftime( "%B-%d-%y %H:%M:%S" , $CurrentTime);

        if(empty($PostTitle)){
            $_SESSION["ErrorMessage"] = "Title can't be empty";
            Redirect_to("AddNewPost.php");
        } else if(strlen($PostTitle) < 5){
            $_SESSION["ErrorMessage"] = "Post title should be greater than 5 characters";
            Redirect_to("AddNewPost.php");
        } else if(strlen($PostText) > 9999){
            $_SESSION["ErrorMessage"] = "Post Description should be less than 10000 characters";
            Redirect_to("AddNewPost.php");
        } else {
            //Query to insert category in DB when everything is fine.
            global $ConnectingDB;
            $sql = "INSERT INTO posts(datetime,title,category,author,image,post)";
            $sql .= "VALUES(:datetime,:postTitle,:categoryName,:adminName,:imageName,:postDescription)";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':datetime',$DateTime);
            $stmt->bindValue(':postTitle',$PostTitle);
            $stmt->bindValue(':categoryName',$Category);
            $stmt->bindValue(':adminName',$Admin);
            $stmt->bindValue(':imageName',$Image);
            $stmt->bindValue(':postDescription',$PostText);
            $Execute=$stmt->execute();

            move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);

            if ($Execute) {
                $_SESSION["SuccessMessage"]="Post with id : ".$ConnectingDB->lastInsertId()." Added Successfully!";
                Redirect_to("AddNewPost.php");
            } else {
                $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
                Redirect_to("AddNewPost.php");
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
    
    <title>Categories</title>
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
                        <h2><i class="fas fa-plus"></i> Add new post</h2>
                    </div>
                </div>
            </div>
        </header>
    <!--Header End-->
    <!--Main Area-->
    <section class="container py-2 mb-4">
        <?php 
            echo ErrorMessage();
            echo SuccessMessage();
        ?>
        
                <form class="" action="AddNewPost.php" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light">
                        <div class="card-header">
                            <h1>Add new post</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">
                                    Post Title:
                                </label>
                                <input class="form-control" type="text" name="PostTitle" id="title" value="" placeholder="Type title here">
                            </div>
                            <div class="form-group">
                                <label for="CategoryTitle">
                                    Choose Category:
                                </label>
                                <select class="form-control" id="CategoryTitle" name="Category">
                                    <?php 
                                    //Fetching all categories from category table
                                        global $ConnectingDB; 
                                        $sql = "SELECT id,title FROM category";
                                        $stmt = $ConnectingDB->query($sql);
                                        while ($DataRows = $stmt->fetch()) {
                                            $Id = $DataRows["id"];
                                            $CategoryName = $DataRows["title"];
                                        
                                    ?>
                                    <option> <?php echo $CategoryName; ?></option>
                                    <?php } ?>
                                </select>
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
                            <div class="form-group">
                                <label for="Post">
                                    <span class="FieldInfo">Post:</span>
                                </label>
                                <textarea class="form-control" id="Post" name="PostDescription" rows="8" cols="80"></textarea>
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