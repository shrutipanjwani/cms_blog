<?php 
    require_once('../includes/DB.php');
    require_once('../includes/Functions.php');
    require_once('../includes/Sessions.php');

    $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
    ConfirmLogin();

    ///Fetching the existing user data start
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

    $SearchQueryParameter = $_GET["id"];

    if(isset($_POST["Submit"])){
        $Name = $_POST["CommenterName"];
        $Email = $_POST["CommenterEmail"];
        $Comment = $_POST["CommenterThoughts"];

        date_default_timezone_set("Asia/Kolkata");
        $CurrentTime = time();
        // $DateTime = strftime( "%Y-%m-%d %H:%M:%S" , $CurrentTime);
        $DateTime = strftime( "%B-%d-%y %H:%M:%S" , $CurrentTime);

        if(empty($Name)||empty($Email)||empty($Comment)){
            $_SESSION["ErrorMessage"] = "All fields must be filled out";
            Redirect_to("fullpost.php?id={$SearchQueryParameter}");
        } else if(strlen($Comment) > 500){
            $_SESSION["ErrorMessage"] = "Comment length should be less than 500 characters";
            Redirect_to("fullpost.php?id={$SearchQueryParameter}");
        } else {
            //Query to insert comment in DB when everything is fine.
            global $ConnectingDB;
            
            $sql = "INSERT INTO comments(datetime, name, email, comment, approvedby, status, post_id)";
            $sql .= "VALUES(:datetime, :name, :email, :comment, 'pending', 'OFF', :postIdFromURL)";
            $stmt = $ConnectingDB->prepare($sql);
            
            $stmt->bindValue(':datetime',$DateTime);
            $stmt->bindValue(':name',$Name);
            $stmt->bindValue(':email',$Email);
            $stmt->bindValue(':comment',$Comment);
            $stmt->bindValue(':postIdFromURL',$SearchQueryParameter);

            $Execute=$stmt->execute();

            if ($Execute) {
                $_SESSION["SuccessMessage"]="Comment Submitted Successfully!";
                Redirect_to("fullpost.php?id={$SearchQueryParameter}");
            } else {
                $_SESSION["ErrorMessage"]="Something went wrong. Try Again";
                Redirect_to("fullpost.php?id={$SearchQueryParameter}");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <title>Add new Blog</title>
    <style>
        <?php include "../css/style.css" ?>
        .body-inside-container{
            height: 10vh;
        }
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
                        <a href="../index.php?page=1" class="active navbar-list-a"><li class="navbar-list-item">
                            Home
                        </li></a>
                        <a href="addnewblog.php" class="navbar-list-a"><li class="navbar-list-item">
                            Write
                        </li></a> 
                        <a href="blog.php?page=1" class="navbar-list-a"><li class="navbar-list-item">
                            Read Blogs
                        </li></a>
                        <?php if(isset($_SESSION['UserId'])) { ?>
                            <li class="navbar-list-item dropdown">
                                <div class="img-contain">
                                    <img src="../images/<?php echo $ExistingImage; ?>" alt="">  
                                </div>
                                <ul>
                                    <li>
                                        <a href="myprofile.php?username=<?php echo $ExistingUsername; ?>" style="display: flex;">
                                            <div class="img-contain" style="margin-top: 3px;margin-right: 5px">
                                                <img src="../images/<?php echo $ExistingImage; ?>" alt="">  
                                            </div>
                                            <div>
                                                <span>&nbsp;<?php echo $ExistingName; ?></span>
                                                <small>@<?php echo $ExistingUsername; ?></small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="myposts.php?username=<?php echo htmlentities($ExistingUsername); ?>">My Posts</a>
                                    </li>
                                    <li>
                                        <a href="logout.php">Logout</a>
                                    </li>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <a href="signup.php"><li class="navbar-list-item list-btn">
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
        </div>
        
        <?php 
            echo ErrorMessage();
            echo SuccessMessage();
        ?>
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
                } else {
                    $PostIdFromURL = $_GET['id'];
                    if (!isset($PostIdFromURL)) {
                        $_SESSION["ErrorMessage"] = "Bad Request!";
                        Redirect_to("blog.php");
                    }
                    $sql = "SELECT * FROM posts WHERE id='$PostIdFromURL' ORDER BY id desc";
                    $stmt = $ConnectingDB->query($sql);
                    $Result = $stmt->rowcount();
                    if ($Result!=1) {
                        $_SESSION["ErrorMessage"] = "Bad Request!";
                        Redirect_to("blog.php?page=1");
                    }
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
                <div class="fullpost-container">
                    <img src="../uploads/<?php echo htmlentities($Image); ?>" class="card-img-top" style="max-height: 450px;max-width: 450px">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                        <small class="text-muted">
                            Category:<a href="blog.php?category=<?php echo htmlentities($Category); ?>">
                                        <span class="text-dark"><?php echo htmlentities($Category); ?></span>
                                    </a> 
                            & Written by 
                            <span class="text-dark">
                                    <a href="myprofile.php?username=<?php echo htmlentities($User); ?>"><?php echo htmlentities($User); ?>
                                </a>
                            </span> On <?php echo htmlentities($DateTime); ?>
                        </small>
                        <span style="float:right;" class="badge">Comments
                            <?php 
                                echo ApproveCommentsAccordingtoPost($PostId);
                            ?>
                        </span>
                        <br>
                        <br>
                        <hr>
                        <br>
                        <p class="card-text">
                            <?php echo nl2br($PostDescription); ?>
                        </p>
                    </div>
                </div>
        <?php } ?>
        <!--Fetching the existing comments-->
       
        <div class="bottom-container">    
            <span class="FieldInfo">
                <div class="side-line"></div> <div> &nbsp;&nbsp;<?php echo ApproveCommentsAccordingtoPost($PostId); ?> Comments</div> <div class="side-line"></div>
            </span>
            <br>
            <br>
            <?php 
                global $ConnectingDB;
                $sql = "SELECT * FROM comments WHERE post_id='$SearchQueryParameter' AND status='ON'";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                    $CommentDate = $DataRows['datetime'];
                    $CommenterName = $DataRows['name'];
                    $CommentContent = $DataRows['comment'];
            ?>
            <div>
                <div class="media">
                    <img src="../images/<?php echo $ExistingImage; ?>" alt="" width="80px" height="80px" style="object-fit:cover;border-radius: 50%;">
                    <div class="media-body">
                        <h6 class="lead"><?php echo $CommenterName; ?></h6>
                        <small><?php echo $CommentDate; ?></small>
                        <p><?php echo $CommentContent; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!--Fetching the existing comments end-->

            <!--Comment Part Start-->
            <div class="comment-writer">
                <form action="fullpost.php?id=<?php echo $SearchQueryParameter; ?>" class="" method="post">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="FieldInfo">
                                Share your thoughts about this post.
                            </h4>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input class="form-control" type="email" name="CommenterEmail" placeholder="Email" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea name="CommenterThoughts" cols="80" rows="6" class="form-control"></textarea>
                            </div>
                            <div class="">
                                <button type="submit" name="Submit" class="btn">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <div class="author-posts">
                <a href="blog.php?page=1"><button class="btn">Read All Author Posts</button></a>
            </div>
        </div>

        <!--Comment Part End-->          
        </div> <!--Box container end-->
        
        <div class="side-container">
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
                    <img src="../images/Web Development.jpg" alt="">
                    <br>
                    <br>
                    <div class="side-flex-div">
                        
                        <small>You can buy attention (advertising). You can beg for attention from the media (PR). 
                            You can bug people one at a time to get attention (sales). Or you can earn attention by 
                            creating something interesting and valuable and then publishing it online for free.
                        </small>
                        <br>
                        <br>
                        <a href="blog.php?page=1"><button class="btn">Explore</button></a>
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
                            <a href="blog.php?category=<?php echo $CategoryName; ?>">
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
                                <img src="../uploads/<?php echo htmlentities($Image); ?>" alt="">
                                </div>
                                <div class="media-body">
                                    <a href="fullpost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
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
                                    <img src="../uploads/<?php echo htmlentities($Image); ?>" alt="">
                                    </div>
                                    <div class="media-body">
                                        <a href="fullpost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
                                            <h6 class="lead"><?php echo htmlentities($Title); ?></h6>
                                        </a>
                                        <small><?php echo htmlentities($DateTime); ?> | By 
                                    <a href="myprofile.php?username=<?php echo htmlentities($ExistingUsername); ?>"><?php echo htmlentities($User); ?></a></small>
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
        })

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