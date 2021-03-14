<?php 
    require_once('includes/DB.php');
    require_once('includes/Functions.php');
    require_once('includes/Sessions.php');

    $SearchQueryParameter = $_GET['username'];
    global $ConnectingDB;
    $sql = "SELECT aname,aheadline,abio,aimage FROM admins WHERE username=:username";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':username', $SearchQueryParameter);
    $stmt->execute();
    $Result = $stmt->rowcount();
    if ($Result == 1) {
        while ($DataRows = $stmt->fetch()) {
            $ExistingName = $DataRows["aname"];
            $ExistingBio = $DataRows["abio"];
            $ExistingImage = $DataRows["aimage"];
            $ExistingHeadline = $DataRows["aheadline"];
        }
    } else {
        $_SESSION["ErrorMessage"] = "Bad Request!";
        Redirect_to('Blog.php');
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
    
    <title>Profiles</title>
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
                    <div class="col-md-6">
                        <h2><i class="fas fa-user mr-2"></i> <?php echo $ExistingName; ?></h2>
                        <h3><?php echo $ExistingHeadline; ?></h3>
                    </div>
                </div>
            </div>
        </header>
    <!--Header End-->
    <!--Main Area-->
    <section class="container py-2 mb-4">
        <div class="row">
            <?php 
                echo ErrorMessage();
                echo SuccessMessage();
            ?>
            <div class="col-md-3">
                <img src="images/<?php echo $ExistingImage; ?>" alt="" class="img-fuild mb-3 rounded-circle" width="160px">
            </div>
            <div class="col-md-9" style="min-height:500px">
                <div class="card">
                    <div class="card-body">
                        <p class="lead">
                            <?php echo $ExistingBio; ?>
                        </p>
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