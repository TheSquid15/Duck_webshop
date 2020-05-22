<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

cart_controller::login_guard();

$cart_controller = new cart_controller();


if(isset($_SESSION["cart"]["cart_item"])) {
    if (!empty($_SESSION["cart"]["cart_item"])) {
        $cart_controller->items["cart_item"] = $_SESSION["cart"]["cart_item"];
    }
}

if(!empty($_GET["action"])) {
    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    }

    switch($_GET["action"]) {
	    case "add":
	        $cart_controller->addToCart($id, $_POST["quantity"]);
            $_SESSION["cart"] = $cart_controller->items;
	        break;
	    case "remove":
	        $cart_controller->removeFromCart($id);
            $_SESSION["cart"] = $cart_controller->items;
            break;
	    case "clear":
		    unset($_SESSION["cart"]);
            break;
        case "cheackout":
            $cart_controller->checkout($_SESSION["cart"]["cart_item"]);
            break;
    }
}

require('../includes/header.php');
if(isset($_SESSION["cart"]["cart_item"])){
    $total = 0;
}
?>
<section class="container mt-4">
    <h2><b>Cart</b></h2>
    <div class="row">
        <div class="col-1"><b>ID:</b></div>
        <div class="col-3"><b>Product name:</b></div>
        <div class="col-3"><b>Quantity:</b></div>
        <div class="col-2"><b>Cost:</b></div>
        <div class="col-3"><b>Remove:</b></div>
    </div> <?php
    if(isset($_SESSION["cart"]["cart_item"])) {
    foreach($_SESSION["cart"]["cart_item"] as $product){
    ?>
    <div class="row product_row mb-2 align-items-center">
        <div class="col-1"><?php echo $product["productID"]?></div>
        <div class="col-3"><?php echo $product["name"]?></div>
        <div class="col-3"><?php echo $product["quantity"]?></div>
        <div class="col-2"><?php echo $product["price"]?></div>
        <div class="col-3"><a href="cart.php?action=remove&item_id=<?php echo $product["name"]?>"><button class="btn btn-primary">Remove</button></a></div>
    </div> <?php
    $total += ($product["quantity"]*$product["price"]);
    } ?>
    <div><b>Total: </b><?php echo $total?> bread slices</div> <?php
    }else { ?>
    <div class="row mb-2">
        <div class="col-12 m-3">
            <h3>Shopping cart is empty</h3>
        </div>
    </div>
    <?php
    }
    ?>
    <a href="cart.php?action=clear"><button class="btn btn-danger">Empty cart</button></a>
    <a href="cart.php?action=checkout"><button class="btn btn-primary float-right">Checkout</button></a>
</section>
<section class="container mt-3 mb-5 recommendation_section">
    <h2><b>Products you might like:</b></h2>
    <div class="row"> <?php
        $cart_controller->getRecommendations();
    ?></div>
</section>

<?php
require('../includes/footer.php');