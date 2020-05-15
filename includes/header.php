<?php
/* spl_autoload_register(function($class) {
    include "../model/" . $class . ".php";
}); */

/* include("../includes/const.php"); */

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Duck</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="icon" href="../resources/duckLogo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<!-- <header class="container-fluid navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="row align-items-center">
        <a href="index.php" class="homeLink navbar-brand"><img src="../resources/duckLogo.png" alt="" class="shop_logo">Duck Pun</a>
        <nav class="col">
            <ul class="main-menu">
                <a href="index.php"><li>Home</li></a>
                <a href="products.php"><li>Products</li></a>
                <a href=""><li>Contact</li></a>
            </ul>
            <ul class="login-menu">
                <?php if(!isset($_SESSION['loggedIn'])) { ?>
                <a href="login.php">
                    <li>Login</li>
                </a>
                <a href="register.php">
                    <li>Register</li>
                </a>
                <?php } ?>
                <?php if(isset($_SESSION['loggedIn'])) { ?>
                <li>Welcome, <?php $_SESSION['username']?></li>
                <a href="">
                    <li>Logout</li>
                </a>
                <?php } ?>
            </ul>
        </nav>
    </div>
</header> -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php"><img src="../resources/duckLogo.png" alt="" class="shop_logo">Quack-Tac</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
        <a class="nav-item nav-link active" href="products.php">Products</a>
        <a class="nav-item nav-link active" href="contact.php">Contact</a>
        <?php
        if(isset($_SESSION['admin'])) { 
            if($_SESSION['admin'] == true) {?>
        <a class="nav-item nav-link active" href="admin_dashboard.php">Admin Panel</a>
        <?php }
        }
        ?>
        </div>
    </div>
    <div class="navbar-nav">
        <?php 
        if(!isset($_SESSION["loggedIn"])) { ?>
            <a class="nav-item nav-link active" href="login.php">Login</a>
            <a class="nav-item nav-link active" href="register.php">Register</a>
        <?php 
        }
        else if($_SESSION["loggedIn"] == true) { ?>
            <a class="nav-item nav-link active">Welcome, <?php echo $_SESSION["username"]?></a>
            <a class="nav-item nav-link active" href="login.php?logout=1">Logout</a>
        <?php
        }
        ?>
    </div>
</nav>