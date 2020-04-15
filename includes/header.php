<?php
/* spl_autoload_register(function($class) {
    include "../model/" . $class . ".php";
}); */

include("../includes/const.php");

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Duck</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="icon" href="../resources/duckLogo.png">
</head>
<body>
<header>
    <a href="index.php"><img src="../resources/duckLogo.png" alt="" class="shop_logo"></a>
    <nav>
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
</header>