<?php
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

admin_controller::login_guard();

$dashboard = new admin_controller();

if(!isset($_SESSION['admin']) && $_SESSION['admin'] !== true) {
    header('Location: index.php');
}

if(isset($_GET['delete'])) {
    if($_SESSION["admin"]  == true) {
        if(isset($_GET['item_id'])){
            $item_to_delete = $_GET['item_id'];
            $dashboard->delete_item($item_to_delete);
        }
        elseif(isset($_GET['category_id'])){
            $item_to_delete = $_GET['category_id'];
            $dashboard->delete_category($item_to_delete);
        }
        elseif(isset($_GET['message_id'])){
            $item_to_delete = $_GET['message_id'];
            $dashboard->delete_message($item_to_delete);
        }
    }
    else {
        header('Location: index.php');
    }
    
}

if(isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Item succesfully deleted";
}

require('../includes/header.php');

if(isset($message)) {
    echo "<div class='alert-success sticky-top'>$message</div>";
}
?>
<div class="d-flex" id="admin_wrapper">
    <div class="sidemenu p-2">
        <h2>Menu</h2>
        <ul class="list-group">
            <a href="admin_dashboard.php"><li class="list-group-item">All</li></a>
            <a href="admin_dashboard.php?panel=products"><li class="list-group-item">Products</li></a>
            <a href="admin_dashboard.php?panel=category"><li class="list-group-item">Categories</li></a>
            <a href="admin_dashboard.php?panel=news"><li class="list-group-item">News</li></a>
            <a href="admin_dashboard.php?panel=message"><li class="list-group-item">Messages</li></a>
            <a href="admin_dashboard.php?panel=general_info"><li class="list-group-item">General Information</li></a>
            <a href="admin_dashboard.php?panel=address"><li class="list-group-item">Add Address</li></a>
            <a href="admin_dashboard.php?panel=orders"><li class="list-group-item">Orders</li></a>
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
            if($_GET['panel'] == "message") {
                include('../components/messages_panel.php');
            }
            if($_GET['panel'] == "general_info") {
                include('../components/general_information_panel.php');
            }
            if($_GET['panel'] == "address") {
                include('../components/address_panel.php');
            }
            if($_GET['panel'] == "news") {
                include('../components/news_panel.php');
            }
            if($_GET['panel'] == "orders") {
                include('../components/orders_panel.php');
            }
        }
        else {
            include('../components/all_panels.php');
    ?>


    <?php 
        } ?>
    </div>
</div>

<?php
require('../includes/footer.php');