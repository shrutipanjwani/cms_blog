<?php 
    require_once('../includes/DB.php');
    require_once('../includes/Functions.php');
    require_once('../includes/Sessions.php');

    $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
    ConfirmLogin();

    $SearchQueryParameter = $_GET['username'];
    global $ConnectingDB;
    $sql = "SELECT name,username,headline,bio,image FROM users WHERE username=:username";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':username', $SearchQueryParameter);
    $stmt->execute();
    $Result = $stmt->rowcount();
    if ($Result == 1) {
        while ($DataRows = $stmt->fetch()) {
            $ExistingName = $DataRows["name"];
            $ExistingUsername = $DataRows["username"];
            $ExistingImage = $DataRows["image"];
            $ExistingHeadline = $DataRows["headline"];
        }
    } else {
        $_SESSION["ErrorMessage"] = "Bad Request!";
        Redirect_to('blog.php?page=1');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <title>My Posts </title>
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

        <div class="section-container">
            <!--Header-->
            <header class="profile-header">
                <div class="">
                    <div class="row">
                        <div class="">
                            <h2><i class="fas fa-user"></i> @<?php echo $ExistingUsername; ?></h2>
                            <small><?php echo $ExistingHeadline; ?></small>
                        </div>
                    </div>
                </div>
            </header>
            <!--Header End-->
         
            <!--Main Area-->
            <section class="posts-section">
                <?php 
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                <div class="box-container" style="width: 100%">
                    <?php 
                        global $ConnectingDB;
                        $Username = $_GET["username"];
                        $sql = "SELECT * FROM posts WHERE username='$Username'";
                        $stmt = $ConnectingDB->query($sql);
                        while ($DataRows = $stmt->fetch()) {
                            $PostId = $DataRows["id"];
                            $DateTime = $DataRows["datetime"];
                            $PostTitle = $DataRows["title"];
                            $Category = $DataRows["category"];
                            $User = $DataRows["author"];
                            $Image = $DataRows["image"];
                            $PostDescription = $DataRows["post"];
                        ?>
                        
                        <div class="">
                            <a href="fullpost.php?id=<?php echo $PostId; ?>">
                                <div class="box">
                                    <div class="img-container">
                                        <img src="../uploads/<?php echo htmlentities($Image); ?>" alt="">
                                    </div>
                                    <div class="inside-box">
                                        <small><?php echo htmlentities($DateTime); ?> | By 
                                            <a href="myprofile.php?username=<?php echo htmlentities($ExistingUsername); ?>"><?php echo htmlentities($User); ?></a>
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
                        </div>
                    <?php } ?>
                </div>
            </section>
            <!--Main Area Ends-->

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
                                        $User = $DataRows["author"];
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
                            <form class="form-inline" action="blog.php?page=1" method="post">
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