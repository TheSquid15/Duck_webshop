<?php
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

if(!isset($_SESSION['admin']) && $_SESSION['admin'] !== true) {
    header('Location: index.php');
}

$dashboard = new admin_controller();

if(isset($_GET['delete']) && isset($_GET['item_id'])) {
    if($_SESSION["admin"]  == true) {
        $item_to_delete = $_GET['item_id'];
        $dashboard->delete_item($item_to_delete);
    }
    else {
        header('Location: index.php');
    }
    
}

require('../includes/header.php');
?>
<div class="d-flex" id="admin_wrapper">
    <div class="sidemenu p-2">
        <h2>Menu</h2>
        <ul class="list-group">
            <a href="admin_dashboard.php"><li class="list-group-item">All</li></a>
            <a href="admin_dashboard.php?panel=products"><li class="list-group-item">Products</li></a>
            <a href="admin_dashboard.php?panel=category"><li class="list-group-item">Categories</li></a>
        </ul>
    </div>
    <div class="container p-4"> <?php 
        if(isset($_GET['panel'])) {
            if($_GET['panel'] == "products") {
                include('../components/product_panel.php');
            }
            if($_GET['panel'] == "category") {
                include('../components/category_panel.php');
            }
        }
        else {
            include('../components/all_panels.php');
    ?>


    <?php 
        } ?>
    </div>
</div>