<?php
require("../includes/const.php");

include(MODEL);

class cart_controller extends DB_model {

    public $items;
    public $temp_items;

    public static function is_logged_in() {
        return isset($_SESSION['loggedIn']);
    }

    public static function login_guard() {
        if (!self::is_logged_in()) {
            header('Location: login.php');
        }
    }

    public function getRecommendations() { 

        $sql = "SELECT p.productID, p.name, p.productImage, p.price, p.categoryID, c.categoryName, 
                pd.end_date, pd.percentage, pd.is_item_of_the_day
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                LEFT JOIN product_of_the_day pd ON p.productID = pd.productID 
                ORDER BY RAND() LIMIT 3";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
        <div class="col-4 item">
            <img src="../resources/<?php echo $row["productImage"]?>" alt="">
            <h2><?php echo $row["name"]?></h2>
            <?php if($row['is_item_of_the_day'] == true) {
                $price_reduct = ($row['price'] * (1 - ($row['percentage']/100))); ?>
                <h3 class="old_price"><?php echo $row["price"]?></h3>
                <h3 class="new_price"><b>ONLY</b> <?php echo $price_reduct?> slices of bread</h3>
                <?php
            }else{ ?>
                <h3 class="current_price"><?php echo $row["price"]?> slices of bread</h3>
            <?php   
            }
            ?>
            <form action="cart.php?action=add&item_id=<?php echo $row["productID"]?>" method="POST">
            <input class="form-control w-25 mb-2" type="number" name="quantity" min="0" value="1" />
            <input class="btn btn-primary" type="submit" name="submit" value="Add to cart">
            </form>
            <small>Category - <?php echo $row["categoryName"]?></small>
        </div>
    <?php
            }
        }
    }

    public function addToCart($id, $quantity) {
        if(!empty($quantity)) {
            $sanatized_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $sanatized_quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
            $get_products_sql = "SELECT * FROM products_full_information WHERE productID = $sanatized_id";
            $products = $this->return_query_array($get_products_sql);
            $this->temp_items = array($products[0]["name"] => array(
                "name" => $products[0]["name"],
                "productID" => $products[0]["productID"],
                "quantity" => $_POST["quantity"],
                "price" => $products[0]["price"]
            ));

            if(!empty($this->items["cart_item"])) {
                if(in_array($products[0]["name"], array_keys($this->items["cart_item"]))) {
                    foreach($this->items["cart_item"] as $item => $attr) {
                        if($products[0]["name"] == $item) {
                            if(empty($this->items["cart_item"][$item]["quantity"])){
                                $this->items["cart_item"][$item]["quantity"] = 0;
                            }
                            $this->items["cart_item"][$item]["quantity"] += $quantity;
                        }
                    }
                }
                else { 
                    $this->items["cart_item"] = array_merge($this->items["cart_item"], $this->temp_items);
                }
            }
            else {
                $this->items["cart_item"] = $this->temp_items;
            }
        }
    }

    public function removeFromCart($id){
        if(!empty($this->items["cart_item"])){
            foreach($this->items["cart_item"] as $item => $attr) {
                if($id == $item) {
                    unset($this->items["cart_item"][$item]);
                }
                if(empty($this->items["cart_item"])){
                    unset($this->items["cart_item"]);
                }
            }
        }   
    }

    public function checkout($array) {
        foreach($array as $item){
            $item_information = $item["productID"];
            $order = $this->conn->prepare("INSERT INTO orders VALUES (NULL, DEFAULT, ?)");
            $order->bind_param("i", $user_id);

            $user_id = $_SESSION["user"];
            $order->execute();
        }
    }

}