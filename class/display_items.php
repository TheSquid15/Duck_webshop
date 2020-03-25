<?php
spl_autoload_register(function($class) {
    include "" . $class . ".php";
});

class display_items{
    
    function __construct(){
        $this->show_items();
    }

    public function show_items() {
        $conn = new connectionController();

        $sql = "SELECT * FROM product JOIN category ON product.categoryID = category.categoryID";
        $result = $conn->conn->query($sql);
        
        
        /* There's a problem here, works if I remove the if statement */
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                    <div class="item<?php echo $row['productID']?>">
                        <img src="<?php echo $row['productImage']?>" alt="">
                        <h2><?php echo $row['name']?></h2>
                        <h3><?php echo $row['price']?>kr.</h3>
                        <small>Category - <?php echo $row['categoryName']?></small>
                    </div>
                <?php
            }
        }
    }
}