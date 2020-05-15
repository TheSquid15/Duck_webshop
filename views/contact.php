<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

if(!isset($_SESSION['admin']) && $_SESSION['admin'] == false) {
    header('Location: index.php');
}

require('../includes/header.php');
?>

<h2></h2>