<?php

class display_items{
    
    private $DB_connect;

    function __construct(){
        $this->DB_connect = new connectionController();
        $this->show_items();
    }

    public function show_items() {

        $sql = "SELECT * FROM product JOIN category ON product.categoryID = category.categoryID ORDER BY name asc";
        $result = $this->DB_connect->conn->query($sql);
        
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                    <div class="item" id="item_<?php echo $row['productID']?>">
                        <img src="<?php echo $row['productImage']?>" alt="">
                        <h2><?php echo $row['name']?></h2>
                        <h3><?php echo $row['price']?> slices of bread</h3>
                        <small>Category - <?php echo $row['categoryName']?></small>
                    </div>
                <?php
            }
        }
    }
}