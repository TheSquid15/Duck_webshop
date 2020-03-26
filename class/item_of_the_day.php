<?php
    class item_of_the_day {

        private $DB_connect;
        public $deal_time;

        function __construct()
        {
            $this->DB_connect = new connectionController();
             
        }

        public function item_of_the_day() {

            $sql = "SELECT * FROM product_of_the_day JOIN product ON product_of_the_day.productID = product.productID";
            $result = $this->DB_connect->conn->query($sql);

            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div>
                        <img src="" alt="">
                        <div></div>
                        <div id="deal_time" class="time"></div>
                    </div>
                    <?php
                }
            }
        }
    }
?>