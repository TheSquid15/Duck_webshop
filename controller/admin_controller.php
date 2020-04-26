<?php

require(MODEL);

class admin_controller extends DB_model {

    public function delete_item($delete_id) {
        $san_id = trim(htmlspecialchars($delete_id));
        $sql = "DELETE FROM product WHERE productID = $san_id";

        $this->sql_query($sql);
    }

    public function product_panel() {

        $sql = "SELECT * FROM product 
                JOIN category ON product.categoryID = category.categoryID
                LEFT JOIN product_of_the_day ON product.productID = product_of_the_day.productID
                ORDER BY name asc";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <div class="row m-2 product_row" id="product_<?php echo $row['productID']?>">
                    <div class="col-1">
                        <img src="<?php echo $row['productImage']?>" alt="" width="100%">
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <h4 class="m-0"><?php echo $row['name']?></h4>
                    </div>
                    <div class="col-3 d-flex align-items-center">
                        <p class="m-0"><?php echo $row['categoryName']?></p>
                    </div>
                    <div class="col-2 d-flex align-items-center">
                        <?php if($row['is_item_of_the_day'] == true) {
                            $new_price = ($row['price'] * (1 - ($row['percentage']/100)));
                            ?>
                            <p class="admin_old_price m-0"><?php echo $row['price'];?></p>
                            <p class="new_price m-0"><?php echo $new_price?></p><?php
                        }else { ?>
                        <p class="m-0"><?php echo $row['price']?></p>
                        <?php } ?>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-around">
                        <a href="edit_item.php"><button type="button" class="btn btn-primary">Edit</button></a>
                        <a href="admin_dashboard.php?delete=true&item_id=<?php echo $row['productID']?>"><button type="button" class="btn btn-danger">Delete</button></a>
                    </div>
                </div>
    <?php    }
         }
    }

    public function category_panel() {
        $sql = "SELECT * FROM category";

        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <div class="row m-2 product_row" id="product_<?php echo $row['categoryID']?>">
                    <div class="col-2">
                       <h4 class="m-0"><?php echo $row['categoryID']?></h4>
                    </div>
                    <div class="col-8 d-flex align-items-center">
                        <h4 class="m-0"><?php echo $row['categoryName']?></h4>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-around">
                        <a href="edit_product.php"><button type="button" class="btn btn-primary">Edit</button></a>
                        <a href="admin_dashboard.php?delete=true&category_id=<?php echo $row['categoryID']?>"><button type="button" class="btn btn-danger">Delete</button></a>
                    </div>
                </div>
            <?php }
        }
    }
}