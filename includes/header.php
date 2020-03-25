<?php
spl_autoload_register(function($class) {
    include "./class/" . $class . ".php";
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
    <link rel="stylesheet" href="./includes/style.css">
</head>
<body>
<header>
    <ul>
        <a href="index.php"><li>Home</li></a>
        <a href="products.php"><li>Products</li></a>
        <a href=""><li>Contact</li></a>
    </ul>
</header>