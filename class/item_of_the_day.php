<?php

    class item_of_the_day extends DB_model {

        private $DB_connect;
        public $deal_time;

        function __construct()
        {
            $this->DB_connect = new DB_model();
            $this->item_of_the_day();
        }

        public function item_of_the_day() {

            $sql = "SELECT * FROM product_of_the_day JOIN product ON product_of_the_day.productID = product.productID";
            $result = $this->DB_connect->conn->query($sql);

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
?>