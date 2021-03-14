<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
    Confirm_Login();
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
    
    <title>Dashboard</title>
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
                    <div class="col-md-12 mb-2">
                        <h2><i class="fas fa-cog"></i> Dashboard</h2>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <a href="AddNewPost.php" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Add new post
                        </a>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <a href="Categories.php" class="btn btn-info btn-block">
                            <i class="fas fa-folder"></i> Add new category
                        </a>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <a href="Admins.php" class="btn btn-warning btn-block">
                            <i class="fas fa-user-plus"></i> Add new Admin
                        </a>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <a href="Comments.php" class="btn btn-success btn-block">
                            <i class="fas fa-check"></i> Approve Comments
                        </a>
                    </div>
                </div>
            </div>
        </header>
    <!--Header End-->
    <!--Main Area-->
    <section class="py-2 mb-4">
        <div class="row">
            <div class="col-lg-10  m-auto d-flex">
                <?php 
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                <!--Left side area-->
                <div class="col-lg-2">

                    <div class="card text-center bg-dark text-white mb-3">
                        <div class="card-body">
                            <h1 class="lead">Posts</h1>
                            <h4 class="display-5"><i class="fab fa-readme"></i> &nbsp;
                                <?php 
                                    TotalPosts();
                                ?>
                            </h4>
                        </div>
                    </div>

                    <div class="card text-center bg-dark text-white mb-3">
                        <div class="card-body">
                            <h1 class="lead">Categories</h1>
                            <h4 class="display-5"><i class="fas fa-folder"></i> &nbsp;
                                <?php 
                                TotalCategories();
                                ?>
                            </h4>
                        </div>
                    </div>

                    <div class="card text-center bg-dark text-white mb-3">
                        <div class="card-body">
                            <h1 class="lead">Admins</h1>
                            <h4 class="display-5"><i class="fas fa-users"></i> &nbsp;
                                <?php 
                                    TotalAdmins();
                                ?>
                            </h4>
                        </div>
                    </div>

                    <div class="card text-center bg-dark text-white mb-3">
                        <div class="card-body">
                            <h1 class="lead">Comments</h1>
                            <h4 class="display-5"><i class="fas fa-comments"></i> &nbsp;
                                <?php 
                                    TotalComments();
                                ?> 
                            </h4>
                        </div>
                    </div>

                </div>
                <!--Left side area end-->

                <!--Right side area start -->
                <div class="col-lg-10">
                    <h1>Total Posts</h1>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Date&Time</th>
                                <th>Author</th>
                                <th>Comments</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <?php 
                            $Sr = 0;
                            global $ConnectingDB;
                            $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                            $stmt = $ConnectingDB->query($sql);
                            while ($DataRows = $stmt->fetch()) {
                                $PostId = $DataRows["id"];
                                $DateTime = $DataRows["datetime"];
                                $Author = $DataRows["author"];
                                $Title = $DataRows["title"];
                                $Sr++;
                        ?>
                        <tbody>
                            <tr>
                                <td><?php echo $Sr; ?></td>
                                <td><?php echo $DateTime; ?></td>
                                <td><?php echo $Title; ?></td>
                                <td><?php echo $Author; ?></td>
                                <td>
                                    <?php 
                                        $Total = ApproveCommentsAccordingtoPost($PostId);
                                        if ($Total > 0) {
                                    ?>
                                        <span class="badge badge-success">
                                            <?php
                                                echo $Total;
                                            ?>
                                        </span>
                                    <?php } ?>

                                    <?php 
                                        $Total = DisApproveCommentsAccordingtoPost($PostId);
                                        if ($Total > 0) {
                                    ?>
                                    <span class="badge badge-danger">
                                    <?php
                                        echo $Total;
                                    ?>
                                    </span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="FullPost.php?id=<?php echo $PostId ?>"><span class="btn btn-info">Preview</span></a>
                                </td>
                            </tr>
                        </tbody>
                        <?php  } ?>
                    </table>
                </div>
                <!--Right side area end -->

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