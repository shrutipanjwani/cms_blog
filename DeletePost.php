<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
    Confirm_Login();

    $SearchQueryParameter = $_GET["id"];

    global $ConnectingDB;
           
    $sql = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
    $stmt = $ConnectingDB->query($sql);
    while ($DataRows = $stmt->fetch()) {
        $TitleToBeDeleted = $DataRows["title"];
        $CategoryToBeDeleted = $DataRows["category"];
        $ImageToBeDeleted = $DataRows["image"];
        $PostToBeDeleted = $DataRows["post"];
    }

    if(isset($_POST["Submit"])){
            //Query to delete post in DB when everything is fine.
            global $ConnectingDB;
            $sql = "DELETE FROM posts WHERE id='$SearchQueryParameter'";
            
            $Execute = $ConnectingDB->query($sql); 
            move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);

            if ($Execute) {
                $Target_Path_To_Delete_Image =  "uploads/$ImageToBeDeleted";
                unlink($Target_Path_To_Delete_Image);
                $_SESSION["SuccessMessage"]="Post Deleted Successfully!";
                Redirect_to("Posts.php");
            } else {
                $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
                Redirect_to("Posts.php");
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
    
    <title>Delete Post</title>
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
                        <h2><i class="fas fa-times"></i> Delete Post</h2>
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
        
                <form class="" action="DeletePost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light">
                        <div class="card-header">
                            <h1>Delete post</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">
                                    Post Title:
                                </label>
                                <input disabled class="form-control" type="text" name="PostTitle" id="title" value="<?php echo $TitleToBeDeleted; ?>" placeholder="Type title here">
                            </div>
                            <div class="form-group">
                                <span class="FieldInfo">
                                    Existing Category:
                                </span>
                                <?php echo $CategoryToBeDeleted; ?>
                                <br>
                                
                            </div>
                            <div class="form-group mb-1">
                               
                                <span class="FieldInfo">
                                    Existing Image:
                                </span>
                                <img src="uploads/<?php echo $ImageToBeDeleted; ?>" width="170px" class="mb-2">
                                   
                            </div>
                            <div class="form-group">
                                <label for="Post">
                                    <span class="FieldInfo">Post:</span>
                                </label>
                                <textarea disabled class="form-control" id="Post" name="PostDescription" rows="8" cols="80">
                                    <?php echo $PostToBeDeleted; ?>
                                </textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block">
                                        <i class="fas fa-arrow-left"></i>
                                         &nbsp;Back to Dashboard
                                    </a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-danger btn-block" type="submit" name="Submit">
                                        <i class="fas fa-trash"></i>
                                        &nbsp;Delete
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