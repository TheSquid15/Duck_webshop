<?php
require("../includes/const.php");

include(MODEL);

class item_page_controller extends DB_model {
    
    private $DB_connect;

    public function show_items() {

        $sql = "SELECT p.productID, p.name, p.productImage, p.price, p.categoryID, c.categoryName, pd.end_date, pd.percentage, pd.is_item_of_the_day
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                LEFT JOIN product_of_the_day pd ON p.productID = pd.productID 
                ORDER BY name ASC";
        $result = $this->sql_query($sql);
        
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                    <div class="item col-md-4" id="item_<?php echo $row['productID']?>">
                        <img src="<?php echo $row['productImage']?>" alt="">
                        <h2><?php echo $row['name']?></h2>
                        <?php if($row['is_item_of_the_day'] == true){
                            $price_reduct = ($row['price'] * (1 - ($row['percentage']/100)));
                            ?>
                        <h3 class="old_price"><?php echo $row['price']?></h3>    
                        <h3 class="new_price"><?php echo $price_reduct?> slices of bread</h3>
                        <?php }
                        else {
                            ?>
                        <h3 class="current_price"><?php echo $row['price']?></h3>
                            <?php
                        }
                        ?>
                        <small>Category - <?php echo $row['categoryName']?></small>
                    </div>
                <?php
            }
        }
    }

    public function item_of_the_day() {

        $sql = "SELECT * FROM product_of_the_day JOIN product ON product_of_the_day.productID = product.productID";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="item_of_the_day_controller">
                    <img src="<?php echo $row['productImage']?>" alt="">
                    <h2 class="product_title"><?php echo $row['name']?></h2>
                    <div class="percentageBox"><h3>-<?php echo $row['percentage']?>%</h3></div>
                    <div id="deal_time" class="time"></div>
                </div>
                <?php

                $this->deal_time = $row['end_date'];
            }
        }
        else {
            ?>
                <div class="item_of_the_day_controller">
                    <img src="" alt="">
                    <h3>New deals, every day!</h3>
                </div>
            <?php
        }
    }
}