<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    if(isset($_SESSION['UserId'])) {
        //Fetching the existing user data start
        $UserId = $_SESSION["UserId"];
        global $ConnectingDB;
        $sql = "SELECT * FROM users WHERE id='$UserId'";
        $stmt = $ConnectingDB->query($sql);
        while ($DataRows = $stmt->fetch()) {
            $ExistingName = $DataRows["name"];
            $ExistingUsername = $DataRows["username"];
            $ExistingImage = $DataRows["image"];
        }
        //Fetching the existing user data end
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <title>Home</title>
    <style>
        <?php include "css/style.css" ?>
    </style>
</head>
<body>
    <section class="body-container">
        <div class="body-inside-container">
            <header>
                <nav class="navbar">
                    <ul class="navbar-list">
                        <a href="index.php?page=1"><li class="navbar-list-item" style="font-weight: bold;">
                            BlogSpace
                        </li></a>
                    </ul>
                    <ul class="navbar-list" id="navbar-list">
                        <a href="index.php?page=1" class="active navbar-list-a"><li class="navbar-list-item">
                            Home
                        </li></a>
                        <a href="userpages/addnewblog.php" class="navbar-list-a"><li class="navbar-list-item">
                            Write
                        </li></a> 
                        <a href="userpages/blog.php?page=1" class="navbar-list-a"><li class="navbar-list-item">
                            Read Blogs
                        </li></a>
                        <?php if(isset($_SESSION['UserId'])) { ?>
                            <li class="navbar-list-item dropdown">
                                <div class="img-contain">
                                    <img src="images/<?php echo $ExistingImage; ?>" alt="">  
                                </div>
                                <ul>
                                    <li>
                                        <a href="userpages/myprofile.php?username=<?php echo $ExistingUsername; ?>" style="display: flex;">
                                            <div class="img-contain" style="margin-top: 3px;margin-right: 5px">
                                                <img src="images/<?php echo $ExistingImage; ?>" alt="">  
                                            </div>
                                            <div>
                                                <span>&nbsp;<?php echo $ExistingName; ?></span>
                                                <small>@<?php echo $ExistingUsername; ?></small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="userpages/myposts.php?username=<?php echo htmlentities($ExistingUsername); ?>">My Posts</a>
                                    </li>
                                    <li>
                                        <a href="userpages/logout.php">Logout</a>
                                    </li>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <a href="userpages/signup.php"><li class="navbar-list-item list-btn">
                                Get Started
                            </li></a>
                        <?php } ?>
                    </ul>
                    <div class="burger">
                        <div class="line-1"></div>
                        <div class="line-2"></div>
                        <div class="line-3"></div>
                    </div>
                </nav>
            </header>
            <div class="head">
                <h1 class="heading">Small steps wins</h1>
            </div>
            <div class="header-img">
                <img src="images/banner2.png" alt="">
            </div>
        </div>

        <div class="main-container">
            <div class="box-container">
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
                        $ShowPostFrom = ($Page*8)-8;
                    }
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,8";
                    $stmt = $ConnectingDB->query($sql);
                    //Query when category is active.
                    } elseif(isset($_GET["category"])){
                        $Category = $_GET['category'];
                        $sql = "SELECT * FROM posts WHERE category='$Category' ORDER BY id desc";
                        $stmt = $ConnectingDB->query($sql);
                    } else {
                        $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,4";
                        $stmt = $ConnectingDB->query($sql);
                    }
                    while ($DataRows = $stmt->fetch()) {
                        $PostId = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $PostTitle = $DataRows["title"];
                        $Category = $DataRows["category"];
                        $User = $DataRows["author"];
                        $Image = $DataRows["image"];
                        $PostDescription = $DataRows["post"];
                    ?>
                    <a href="userpages/fullpost.php?id=<?php echo $PostId; ?>">
                        <div class="box">
                            <div class="img-container">
                                <img src="uploads/<?php echo htmlentities($Image); ?>" alt="">
                            </div>
                            <div class="inside-box">
                                <small><?php echo htmlentities($DateTime); ?> | By 
                                    <a href="userpages/myprofile.php?username=<?php echo htmlentities($User); ?>"><?php echo htmlentities($User); ?></a>
                                </small>
                                
                                <h5><?php echo htmlentities($PostTitle); ?></h5>
                                <br>
                                <div class="separator-line"></div>
                                <div class="para">
                                    <?php 
                                    if (strlen($PostDescription) > 150) {
                                        $PostDescription = substr($PostDescription, 0, 150).'...';
                                    }
                                    echo htmlentities($PostDescription); ?>
                                </div>
                                
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div> <!--Box container end-->
            <div class="side-container">
                <ul class="navbar-nav ml-auto">
                    <form class="form-inline d-none d-sm-block" action="index.php?page=1" method="post">
                        <div class="search-form-group">
                            <input type="text" class="search-form-control" name="Search" placeholder="Enter your keywords" value="">
                            <button class="btn" name="SearchButton"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </ul>
                <br>
                <div class="inside-side-container">
                    <div class="side-flex">
                        <div>
                            <h5>Read Blogs</h5>
                        </div>
                        <div class="side-line"></div>
                        <br>
                        <br>
                    </div>
                    <img src="images/Web Development.jpg" alt="">
                    <br>
                    <br>
                    <div class="side-flex-div">
                        
                        <small>You can buy attention (advertising). You can beg for attention from the media (PR). 
                            You can bug people one at a time to get attention (sales). Or you can earn attention by 
                            creating something interesting and valuable and then publishing it online for free.
                        </small>
                        <br>
                        <br>
                        <a href="userpages/blog.php?page=1"><button class="btn">Explore</button></a>
                    </div>
                </div>
                
                <div class="inside-side-container">
                    <div class="side-flex">
                        <div>
                            <h5>Follow us</h5>
                        </div>
                        <div class="side-line"></div>
                        <br>
                        <br>
                    </div>
                   
                    <div class="side-flex-div social-icons">
                        <ul class="social-icons-list">
                            <a href="#"><li><i class="fab fa-facebook"></i></li></a>
                            <a href="#"><li><i class="fab fa-twitter"></i></li></a>
                            <a href="#"><li><i class="fab fa-instagram"></i></li></a>
                            <a href="#"><li><i class="fab fa-linkedin"></i></li></a>
                            <a href="#"><li><i class="fab fa-google-plus"></i></li></a>
                        </ul>
                    </div>
                </div>

                <div class="inside-side-container">
                    <div class="side-flex">
                        <div>
                            <h5>Categories</h5>
                        </div>
                        <div class="side-line"></div>
                        <br>
                        <br>
                    </div>
                   
                    <div class="side-flex-div categories">
                        <ul class="categories-list">
                        <?php 
                            global $ConnectingDB;
                            $sql = "SELECT * FROM category ORDER BY id desc";
                            $stmt = $ConnectingDB->query($sql);
                            while ($DataRows = $stmt->fetch()) {
                                $CategoryId = $DataRows["id"];
                                $CategoryName = $DataRows["title"];
                            ?>
                            <a href="userpages/blog.php?category=<?php echo $CategoryName; ?>">
                                <li><?php echo $CategoryName; ?></li>
                            </a>
                            <br>
                        <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="inside-side-container">
                    <div class="side-flex">
                        <div>
                            <h5>Popular Posts</h5>
                        </div>
                        <div class="side-line"></div>
                        <br>
                        <br>
                    </div>
                   
                    <div class="side-flex-div posts">
                        <ul class="posts-list">
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
                                <div>
                                <img src="uploads/<?php echo htmlentities($Image); ?>" alt="">
                                </div>
                                <div class="media-body">
                                    <a href="userpages/fullpost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
                                        <h6 class="lead"><?php echo htmlentities($Title); ?></h6>
                                    </a>
                                    <small><?php echo htmlentities($DateTime); ?></small>
                                </div>
                            </div>
                            <br>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                
                <div class="inside-side-container">
                    <div class="side-flex">
                        <div>
                            <h5>Newsletter</h5>
                        </div>
                        <div class="side-line"></div>
                        <br>
                        <br>
                    </div>
                   
                    <div class="side-flex-div news">
                        <ul class="news-list">
                            <form class="form-inline" action="index.php?page=1" method="post">
                                <div class="news-box">
                                    <input type="email" class="search-form-control" placeholder="Enter your email" value="">
                                    <span class="butn"><i class="fa fa-envelope"></i></span>
                                </div>
                                <button class="btn">Join the forum</button>
                            </form>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <!-- Pagination -->
        <nav class="pagination-nav">
            <ul class="pagination">
            <!-- Creating backward button -->
            <?php 
                if (isset($Page)) {
                    if ($Page>1) {
                                        
            ?>
            <li class="page-item">
                <a href="index.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
            </li>
            <?php }  }?>
            <?php 
                global $ConnectingDB;
                $sql = "SELECT COUNT(*) FROM posts";
                $stmt = $ConnectingDB->query($sql);
                $RowPagination = $stmt->fetch();
                $TotalPosts = array_shift($RowPagination);
                // echo $TotalPosts."<br>";
                $PostPagination = $TotalPosts/8;
                $PostPagination = ceil($PostPagination);
                // echo $PostPagination;
                for ($i=1; $i < $PostPagination; $i++) { 
                    if (isset($Page)) {
                        if ($i==$Page) { ?>
                            <li class="page-item active">
                                <a href="index.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                            </li>
                    <?php } else { ?>
                            <li class="page-item">
                                <a href="index.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                            </li>
                    <?php }  }  } ?>
                    <!-- Creating forward button -->
                <?php 
                if (isset($Page)&&!empty($Page)) {
                    if ($Page+1<=$PostPagination) {              
                ?>
                <li class="page-item">
                    <a href="index.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
                </li>
                <?php }  }?>
            </ul>
        </nav>
        <!-- Pagination end-->
        
        <!-- <section class="slide-texter">
            <div class="slide-text">
                <h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</h2>
            </div>
            <div class="slide-text">
                <h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</h2>
            </div>
        </section> -->

        <footer>
            <div class="footer">
                <div class="column">
                    <ul>
                        <li class="title">
                            Read blogs and gain knowledge
                        </li>
                    </ul>
                </div>

                <div class="column">
                    <ul>
                        <li class="blogtitle" style="font-weight: bold;">
                            BlogSpace
                        </li>
                    </ul>
                </div>

                <div class="column">
                    <ul class="d-flex">
                        <li class="d-flex-li title">Follow us</li>
                        <ul class="d-flex d-flex2">
                            <a href="#"><li><i class="fab fa-facebook"></i></li></a>
                            <a href="#"><li><i class="fab fa-twitter"></i></li></a>
                            <a href="#"><li><i class="fab fa-instagram"></i></li></a>
                            <a href="#"><li><i class="fab fa-linkedin"></i></li></a>
                            <a href="#"><li><i class="fab fa-google-plus"></i></li></a>
                        </ul>
                    </ul>
                </div>
            </div>
            <hr>
            <br>
            <div class="footer">
                <div class="column">
                    <ul>
                        <li class="title">
                            About
                        </li>
                        <li>
                            <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            sed do eiusmod tempor incididunt ut.sed do eiusmod tempor incididunt.
                            </small>
                        </li>
                    </ul>
                </div>

                <div class="column">
                    <ul>
                        <li class="title">
                        <br>
                            Latest Blog Posts
                        </li>
                        <li>
                            <ul class="posts-list">
                                <?php 
                                    global $ConnectingDB;
                                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,3";
                                    $stmt = $ConnectingDB->query($sql);
                                    while ($DataRows = $stmt->fetch()) {
                                        $Id = $DataRows["id"];
                                        $Title = $DataRows["title"];
                                        $DateTime = $DataRows["datetime"];
                                        $Image = $DataRows["image"];
                                ?>  
                                <div class="media">
                                    <div>
                                    <img src="uploads/<?php echo htmlentities($Image); ?>" alt="">
                                    </div>
                                    <div class="media-body">
                                        <a href="userpages/fullpost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
                                            <h6 class="lead"><?php echo htmlentities($Title); ?></h6>
                                        </a>
                                        <small><?php echo htmlentities($DateTime); ?> | By 
                                    <a href="userpages/myprofile.php?username=<?php echo htmlentities($ExistingUsername); ?>"><?php echo htmlentities($User); ?></a></small>
                                    </div>
                                </div>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="column">
                    <ul>
                        <li class="title">
                            Subscribe Newsletter
                        </li>
                        <li>
                            <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna.
                            </small>
                            <form class="form-inline" action="index.php?page=1" method="post">
                                <div class="news-box">
                                    <input type="email" class="search-form-control" placeholder="Enter your email" value="">
                                    <span class="butn"><i class="fa fa-envelope"></i></span>
                                </div>
                                <button class="footer-btn">Join the forum</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <br>
            <div class="bottom-footer">
                <small>Copyright &copy; Blogs 2021</small>
            </div>
        </footer>
        
    </section>
    <script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('.img-contain').click(function(){
                $('.dropdown ul').toggleClass('active');
            })
        });

        //Navbar 

        const navSlide = () => {
            const burger = document.querySelector('.burger');
            const nav = document.querySelector('#navbar-list');
            const navLinks = document.querySelectorAll('#navbar-list li');

            burger.addEventListener('click', () => {
                //Toggle Nav
                nav.classList.toggle('nav-active');

                //Animate Links
                navLinks.forEach((link, index) => {
                    if (link.style.animation) {
                        link.style.animation = '';
                    } else {
                        link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.5}s`;
                    }
                });
                //Burger Animation
                burger.classList.toggle('toggle');
            });
        }

        navSlide();

    </script>
   
</body>
</html>