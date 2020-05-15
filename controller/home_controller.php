<?php
require("../includes/const.php");

include(MODEL);

class home_controller extends DB_model {

    public function item_of_the_day() {

        $sql = "SELECT * FROM product_of_the_day JOIN product ON product_of_the_day.productID = product.productID";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="item_of_the_day_controller home_deal">
                    <img src="<?php echo $row['productImage']?>" alt="">
                    <h2 class="product_title"><?php echo $row['name']?></h2>
                    <div class="percentageBox"><h3>-<?php echo $row['percentage']?>%</h3></div>
                </div>
                <?php
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