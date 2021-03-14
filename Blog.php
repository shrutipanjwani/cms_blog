<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <title>Blog Page</title>
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
                        <a href="index.php" class="nav-link">Home</a>
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
        
            <div class="container">
                <div class="row mt-4">
                    <!--Main Area-->
                    <div class="col-sm-8">
                       <h1 class="lead">Posts</h1>
                       <?php
                        global $ConnectingDB;
                        if (isset($_GET["SearchButton"])) {
                            $Search = $_GET["Search"];
                            $sql = "SELECT * FROM posts 
                            WHERE datetime LIKE :search 
                            OR title LIKE :search 
                            OR category LIKE :search 
                            OR post LIKE :search";
                            $stmt = $ConnectingDB->prepare($sql);
                            $stmt->bindValue(':search','%'.$Search.'%');
                            $stmt->execute();
                        } else if(isset($_GET['page'])){
                            $Page = $_GET['page'];
                            if ($Page == 0 || $Page < 1) {
                                $ShowPostFrom = 0;
                            } else {
                                $ShowPostFrom = ($Page*5)-5;
                            }
                            $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,5";
                            $stmt = $ConnectingDB->query($sql);
                            //Query when category is active.
                        } elseif(isset($_GET["category"])){
                                $Category = $_GET['category'];
                                $sql = "SELECT * FROM posts WHERE category='$Category' ORDER BY id desc";
                                $stmt = $ConnectingDB->query($sql);
                        } else {
                            $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,3";
                            $stmt = $ConnectingDB->query($sql);
                        }
                        while ($DataRows = $stmt->fetch()) {
                            $PostId = $DataRows["id"];
                            $DateTime = $DataRows["datetime"];
                            $PostTitle = $DataRows["title"];
                            $Category = $DataRows["category"];
                            $Admin = $DataRows["author"];
                            $Image = $DataRows["image"];
                            $PostDescription = $DataRows["post"];
                        ?>
                        <div class="card">
                            <img src="uploads/<?php echo htmlentities($Image); ?>" class="img-fluid card-img-top" style="max-height: 450px;max-width: 450px">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                                <small class="text-muted">Category: <a href="Blog.php?category=<?php echo htmlentities($Category); ?>"><span class="text-dark"><?php echo htmlentities($Category); ?></span></a> & Written by <span class="text-dark"><a href="Profile.php?username=<?php echo htmlentities($Admin); ?>"><?php echo htmlentities($Admin); ?></a></span> On <?php echo htmlentities($DateTime); ?></small>
                                <span style="float:right" class="badge badge-dark text-light">Comments
                                    <?php 
                                        echo ApproveCommentsAccordingtoPost($PostId);
                                    ?>
                                </span>
                                <hr>
                                <p class="card-text">
                                    <?php 
                                    if (strlen($PostDescription) > 150) {
                                        $PostDescription = substr($PostDescription, 0, 150).'...';
                                    }
                                    echo htmlentities($PostDescription); ?>
                                </p>
                                <a href="FullPost.php?id=<?php echo $PostId; ?>" style="float:right;">
                                    <span class="btn btn-info">Read More >> </span>
                                </a>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination pagination-md">
                                <!-- Creating backward button -->
                                <?php 
                                        if (isset($Page)) {
                                            if ($Page>1) {
                                                
                                    ?>
                                    <li class="page-item">
                                        <a href="Blog.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
                                    </li>
                                <?php }  }?>
                                <?php 
                                    global $ConnectingDB;
                                    $sql = "SELECT COUNT(*) FROM posts";
                                    $stmt = $ConnectingDB->query($sql);
                                    $RowPagination = $stmt->fetch();
                                    $TotalPosts = array_shift($RowPagination);
                                    // echo $TotalPosts."<br>";

                                    $PostPagination = $TotalPosts/5;
                                    $PostPagination = ceil($PostPagination);
                                    // echo $PostPagination;
                                    for ($i=1; $i < $PostPagination; $i++) { 
                                        if (isset($Page)) {
                                            if ($i==$Page) { ?>
                                                <li class="page-item active">
                                                    <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                                </li>
                                        <?php } else { ?>
                                                <li class="page-item">
                                                    <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                                </li>
                                <?php }  }   } ?>

                                <!-- Creating forward button -->
                                <?php 
                                    if (isset($Page)&&!empty($Page)) {
                                        if ($Page+1<=$PostPagination) {
                                            
                                ?>
                                    <li class="page-item">
                                        <a href="Blog.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
                                    </li>
                                <?php }  }?>
                            </ul>
                        </nav>
                        <!-- Pagination end-->
                    </div>
                    <!--Main Area Ends-->

                    <!--Side Area-->
                    <div class="col-sm-4">
                       <div class="card mt-4">
                            <div class="card-body">
                                <img src="images/user.png" class="img-fluid mb-3 d-block" alt="">
                                <div class="text-center">
                                In literary theory, a text is any object that can be "read", whether this object is a work of literature, a street sign, an arrangement
                                of buildings on a city block, or styles of clothing. It is a coherent set of signs that transmits some kind of informative message.
                                </div>
                            </div>
                       </div>
                       <br>
                       <div class="card">
                            <div class="card-header bg-dark text-light">
                                <h2 class="lead">Sign up!</h2>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-success btn-block text-center text-white mb-4" type="button" name="button">Join the forum.</button>
                                <button class="btn btn-success btn-block text-center text-white mb-4" type="button" name="button">Login.</button>
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" name="" placeholder="Enter Your Email" value="">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribe Now!</button>
                                    </div>
                                </div>
                            </div>
                       </div>
                       <br>
                       <div class="card">
                           <div class="card-header bg-primary text-light">
                                <h2 class="lead">Categories</h2>
                            </div>
                            <div class="card-body">
                                <?php 
                                    global $ConnectingDB;
                                    $sql = "SELECT * FROM category ORDER BY id desc";
                                    $stmt = $ConnectingDB->query($sql);
                                    while ($DataRows = $stmt->fetch()) {
                                        $CategoryId = $DataRows["id"];
                                        $CategoryName = $DataRows["title"];
                                ?>
                                <a href="Blog.php?category=<?php echo $CategoryName; ?>"><span class="heading"><?php echo $CategoryName; ?></span></a><br>
                                <?php } ?>
                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h2 class="lead"> Recent Posts</h2>
                            </div>
                            <div class="card-body">
                                <?php 
                                    global $ConnectingDB;
                                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                                    $stmt = $ConnectingDB->query($sql);
                                    while ($DataRows = $stmt->fetch()) {
                                        $Id = $DataRows["id"];
                                        $Title = $DataRows["title"];
                                        $DateTime = $DataRows["datetime"];
                                        $Image = $DataRows["image"];
                                ?>  
                                <div class="media">
                                    <img src="uploads/<?php echo htmlentities($Image); ?>" alt="" class="d-block align-self-start img-fluid" width="90px">
                                    <div class="media-body ml-2">
                                        <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank"><h6 class="lead"><?php echo htmlentities($Title); ?></h6></a>
                                        <p class="small"><?php echo htmlentities($DateTime); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                     <!--Side Area Ends-->
                </div>
            </div>
    <!--Header End-->
    <!--Main Area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
               
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