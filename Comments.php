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
    
    <title>Comments</title>
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
                        <a href="Blog.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">About us</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Contact us</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Features</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <form class="form-inline d-none d-sm-block" action="Blog.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control mr-2" name="Search" placeholder="Search here.." value="">
                            <button class="btn btn-primary" name="SearchButton">Go</button>
                        </div>
                    </form>
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
                    <h2><i class="fas fa-comments"></i> Manage Comments</h2>
                </div>
            </div>
        </div>
    </header>
    <!--Header End-->
    <!--Main Area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-lg-12">
            <?php 
                echo ErrorMessage();
                echo SuccessMessage();
            ?>
            <h2>Unapproved Comments</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date&Time</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Approve</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                <?php 
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments WHERE status='OFF' ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CommentId = $DataRows["id"];
                        $DateTimeOfComment = $DataRows["datetime"];
                        $CommenterName = $DataRows["name"];
                        $CommentContent = $DataRows["comment"];
                        $CommentPostId = $DataRows["post_id"];
                        $SrNo++;
                       
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo htmlentities($SrNo); ?></td>
                            <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                            <td><?php echo htmlentities($CommenterName); ?></td>
                            <td><?php echo htmlentities($CommentContent); ?></td>
                            <td>
                                <a href="ApproveComment.php?id=<?php echo htmlentities($CommentId); ?>"><span class="btn btn-success">Approve</span></a>
                            </td>
                            <td>
                                <a href="DeleteComment.php?id=<?php echo htmlentities($CommentId); ?>"><span class="btn btn-danger">Delete</span></a>
                            </td>
                            <td><a href="FullPost.php?id=<?php echo htmlentities($CommentPostId); ?>" target="_blank"><span class="btn btn-primary">Live Preview</span></a></td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>

                <h2>Approved Comments</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date&Time</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Revert</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                <?php 
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments WHERE status='ON' ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CommentId = $DataRows["id"];
                        $DateTimeOfComment = $DataRows["datetime"];
                        $CommenterName = $DataRows["name"];
                        $CommentContent = $DataRows["comment"];
                        $CommentPostId = $DataRows["post_id"];
                        $SrNo++;
                       
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo htmlentities($SrNo); ?></td>
                            <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                            <td><?php echo htmlentities($CommenterName); ?></td>
                            <td><?php echo htmlentities($CommentContent); ?></td>
                            <td>
                                <a href="DisApproveComment.php?id=<?php echo htmlentities($CommentId); ?>"><span class="btn btn-warning">Approve</span></a>
                            </td>
                            <td>
                                <a href="DeleteComment.php?id=<?php echo htmlentities($CommentId); ?>"><span class="btn btn-danger">Delete</span></a>
                            </td>
                            <td><a href="FullPost.php?id=<?php echo htmlentities($CommentPostId); ?>" target="_blank"><span class="btn btn-primary">Live Preview</span></a></td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
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