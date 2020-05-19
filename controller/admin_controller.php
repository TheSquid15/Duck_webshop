<?php
require("../includes/const.php");

require(MODEL);

class admin_controller extends DB_model {

    public function delete_item($delete_id) {
        $san_id = trim(htmlspecialchars($delete_id));
        $sql = "DELETE FROM product WHERE productID = $san_id";
        $imageSQL = "SELECT productImage FROM product WHERE productID = $san_id";
        $imageQuery = $this->sql_query($imageSQL);
        $imageToDelete = $imageQuery->fetch_assoc();

        unlink($imageToDelete["productImage"]);

        $this->sql_query($sql);
        header("Location: admin_dashboard.php?success=1");
    }

    public function delete_category($delete_id) {
        $san_id = trim(htmlspecialchars($delete_id));
        $sql = "DELETE FROM category WHERE categoryID = ?";
        $categorystmnt = $this->conn->prepare($sql);
        $categorystmnt->bind_param("i", $categoryToDelete);
        $categoryToDelete = $san_id;
        $categorystmnt->execute();

        header("Location: admin_dashboard.php?success=1");
    }

    public function delete_message($delete_id) {
        $san_id = trim(htmlspecialchars($delete_id));
        $sql = "DELETE FROM message WHERE messageID = ?";
        $messagestmnt = $this->conn->prepare($sql);
        $messagestmnt->bind_param("i", $messageToDelete);
        $messageToDelete = $san_id;
        $messagestmnt->execute();

        header("Location: admin_dashboard.php?success=1");
    }

    public function product_panel() {

        if(isset($_POST['pod_submit'])) {
            if(isset($_POST['item_of_the_day']) && isset($_POST['end_date']) && isset($_POST['percentage'])) {
                $pod_iod = $_POST['item_of_the_day'];
                $pod_end_date = $_POST['end_date'];
                $pod_percentage = $_POST['percentage'];

                $pod_query = "UPDATE product_of_the_day SET end_date = ?, percentage = ?, productID = ? WHERE 1";
                $pod_stmnt = $this->conn->prepare($pod_query);
                $pod_stmnt->bind_param("sii", $pod_stmnt_end_date, $pod_stmnt_percentage, $pod_stmnt_productID);

                $pod_stmnt_end_date = date("Y-m-d H:i:s", strtotime($pod_end_date));
                $pod_stmnt_percentage = $pod_percentage;
                $pod_stmnt_productID = $pod_iod;
                $pod_stmnt->execute();

                $pod_message_success = "Item of the day successfully updated";
            }
            else {
                $pod_message_fail = "Please fill out all the fields";
            }
        }

        if(isset($pod_message_fail)){
            echo "<div class='alert-danger'>$pod_message_fail</div>";
        }
        if(isset($pod_message_success)) {
            echo "<div class='alert-success'>$pod_message_success</div>";
        }

        $sql = "SELECT p.productID, p.name, p.productImage, p.price, p.categoryID, c.categoryName, pd.end_date, pd.percentage, pd.is_item_of_the_day
                FROM product p
                JOIN category c ON p.categoryID = c.categoryID
                LEFT JOIN product_of_the_day pd ON p.productID = pd.productID 
                ORDER BY name ASC";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <div class="row m-2 product_row" id="product_<?php echo $row['productID']?>">
                    <div class="col-1">
                        <img src="../resources/<?php echo $row['productImage']?>" alt="" width="100%">
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
                            <p class="new_price m-0 ml-4"><?php echo $new_price?></p><?php
                        }else { ?>
                        <p class="m-0"><?php echo $row['price']?></p>
                        <?php } ?>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-around">
                        <a href="edit_item.php?item=<?php echo $row['productID']?>"><button type="button" class="btn btn-primary">Edit</button></a>
                        <a href="admin_dashboard.php?delete=true&item_id=<?php echo $row['productID']?>"><button type="button" class="btn btn-danger">Delete</button></a>
                    </div>
                </div>
    <?php    }
         }
         $product_of_the_day_sql = "SELECT * FROM product_of_the_day pd JOIN product p ON pd.productID = p.productID";
         $pod_result = $this->sql_query($product_of_the_day_sql);
         $pod_row = $pod_result->fetch_assoc();

         $pod_select_sql = "SELECT p.productID, p.name, p.productImage, p.price, p.categoryID, c.categoryName, pd.end_date, pd.percentage, pd.is_item_of_the_day
                            FROM product p
                            JOIN category c ON p.categoryID = c.categoryID
                            LEFT JOIN product_of_the_day pd ON p.productID = pd.productID 
                            ORDER BY name ASC";
         $pod_select_result = $this->sql_query($pod_select_sql);
                 ?>
                 <h4 class="mt-4">Item of the day</h4>
                    <div class="row m-2 product_row">
                        <div class="col-12">
                            <form action="" method="post">
                                <form-group>
                                    <label for="item_of_the_day">Item of the day:</label>
                                    <select class="form-control mt-2" name="item_of_the_day" id="item_of_the_day"> <?php
                                        if($pod_select_result->num_rows > 0) {
                                            while($pod_select_row = $pod_select_result->fetch_assoc()) {
                                                if($pod_select_row['productID'] == $pod_row['productID']) {
                                                    echo "<option value={$pod_select_row['productID']} selected>{$pod_select_row['name']}</option>";
                                                }
                                                else{
                                                    echo "<option value={$pod_select_row['productID']} >{$pod_select_row['name']}</option>";
                                                }
                                            }
                                ?>  </select>
                                    <div class="form-group d-flex m-0 justify-content-between">
                                        <div class="form-group mr-2 mt-2 w-100">
                                            <label for="end_date">Deal expires:</label>
                                            <input class="form-control w-100" type="datetime-local" id="end_date" name="end_date">
                                        </div>
                                        <div class="form-group mt-2 w-100">
                                            <label for="percentage">% off:</label>
                                            <input class="form-control w-100" type="number" id="percentage" name="percentage" value="<?php echo $pod_row['percentage'] ?>">
                                        </div>
                                    </div>
                                </form-group>
                                <input class="form-control mt-3" type="submit" name="pod_submit" value="Submit">
                            </form>
                        </div>
                    </div>
                 <?php
         }
    }

    public function category_panel() {
        $sql = "SELECT * FROM category";

        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <div class="row m-2 product_row" id="category_<?php echo $row['categoryID']?>">
                    <div class="col-2">
                       <h4 class="m-0"><?php echo $row['categoryID']?></h4>
                    </div>
                    <div class="col-8 d-flex align-items-center">
                        <h4 class="m-0"><?php echo $row['categoryName']?></h4>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-end">
                        <a href="admin_dashboard.php?delete=true&category_id=<?php echo $row['categoryID']?>"><button type="button" class="btn btn-danger">Delete</button></a>
                    </div>
                </div>
            <?php }
        }
    }

    public function message_panel() {
        if(!isset($_GET['message_page'])){
            $page = 1;
        }
        else {
            $page = $_GET['message_page'];
        }
        $results_per_page = 5;
        $this_page_first_result = ($page - 1)*$results_per_page;
        $number_of_rows_sql = "SELECT * FROM message";
        $number_result = $this->sql_query($number_of_rows_sql);
        $number_of_rows = $number_result->num_rows;
        $sql = "SELECT * FROM message LIMIT $this_page_first_result, $results_per_page";
        $result = $this->sql_query($sql);
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <div class="row m-2 product_row align-items-center" id="message_<?php echo $row['messageID']?>">
                    <div class="col-3">
                        <h5 class="m-0"><?php echo $row['subject']?></h5>
                    </div>
                    <div class="col-5">
                        <p class="m-0"><?php echo substr($row['message'], 0, 100) . "..."?></p>
                    </div>
                    <div class="col-3">
                        <p class="m-0">From: <?php echo $row['email']?></p>
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-end">
                        <a href="admin_dashboard.php?delete=true&message_id=<?php echo $row['messageID']?>"><button type="button" class="btn btn-danger">Delete</button></a>
                    </div>
                </div>
            <?php }
            $number_of_pages = ceil($number_of_rows/$results_per_page); ?>
            <div class="col-12 m-0 d-flex align-items-center justify-content-center"><small class="">Pages</small></div>
            <div class="col-8 d-flex justify-content-around align-items-center m-auto"> 
                <a href="admin_dashboard.php?panel=message&message_page=<?php if($page > 1 ) {echo $page - 1;}else{ echo $page = 1; } ?>" class="back_forward"><button class="btn btn-primary">< back</button></a>
                <div>
                <?php
                for($page = 1; $page <= $number_of_pages; $page++) { ?>
                    <a href="admin_dashboard.php?panel=message&message_page=<?php echo $page ?>" class="m-1"><?php echo $page ?></a>
                <?php 
                } ?>
                </div>
                <a href="admin_dashboard.php?panel=message&message_page=<?php if($page < $number_of_pages ) {echo $page + 1;}else{ echo $page = $number_of_pages; } ?>" class="back_forward"><button class="btn btn-primary">next ></button></a>
            </div>
            <?php
        }
    }

    public function general_information_panel() {

        if(isset($_POST['submit-general-info'])) {
            if(!empty($_POST['slogan']) && !empty($_POST['about_us']) && !empty($_POST['email'])) {
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $san_slogan = trim(htmlspecialchars($_POST['slogan']));
                    $san_about_us = trim(htmlspecialchars($_POST['about_us']));
                    $san_email = trim(htmlspecialchars($_POST['email']));
                    $address = $_POST['address'];
                    $query = "UPDATE about_us SET slogan = ?, about_us_text = ?, email = ?, addressID = ? WHERE 1";

                    $infostmnt = $this->conn->prepare($query);
                    $infostmnt->bind_param("sssi", $c_slogan, $c_about, $c_email, $c_address);

                    $c_slogan = $san_slogan;
                    $c_about = $san_about_us;
                    $c_email = $san_email;
                    $c_address = $address;
                    $infostmnt->execute();

                    $message_success = "Information updated";
                }
                else {
                    $message = "Please make sure all the fields are filled out";
                }
            }
        }

        $general_info_sql = "SELECT * FROM about_us";
        $general_info_result = $this->sql_query($general_info_sql);

        if($general_info_result->num_rows > 0) {
        $info = $general_info_result->fetch_assoc();

        if(isset($message)) { ?>
            <div class="alert-danger"><?php echo $message ?></div>
        <?php
        }

        if(isset($message_success)) { ?>
            <div class="alert-success"><?php echo $message_success ?></div>
        <?php
        }
        ?>
        <div class="row m-2 product_row">
            <div class="col-12">
                <h4>About Us</h4>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="about_us">About Us:</label>
                        <textarea class="w-100 form-control mb-2" type="text" name="about_us" id="about_us" rows="10" required><?php echo $info['about_us_text']?></textarea>
                        <label for="slogan">Slogan:</label>
                        <input class="w-100 form-control mb-2" type="text" name="slogan" id="slogan" value="<?php echo $info['slogan'] ?>" required>
                        <label for="email">Email:</label>
                        <input class="w-100 form-control mb-2" type="text" name="email" id="email" value="<?php echo $info['email'] ?>" required>
                        <label for="address">Address:</label>
                        <select class="form-control" name="address" id="address"> <?php
                            $address_sql = "SELECT * FROM address JOIN postal_city ON address.postalcode = postal_city.postalcode WHERE userID = 1";
                            $result = $this->sql_query($address_sql);

                            if($result->num_rows > 0) {
                                $i = 1;
                                while($row = $result->fetch_assoc()){
                                    if($row['addressID'] == $info['addressID']) {
                                        echo "<option value={$row['addressID']} selected>{$row['street']} - {$row['postalcode']} {$row['city']}</option>";
                                    }
                                    else {
                                        echo "<option value={$row['addressID']}>{$row['street']} - {$row['postalcode']} {$row['city']}</option>";
                                    }
                                    $i++;
                                }
                            }
                        ?></select>
                        <input class="form-control mt-3" type="submit" name="submit-general-info" value="Update">
                    </div>
                </form>
            </div>
        </div>
        <?php
        }
    }

    public function address_panel() {
        if(isset($_POST['submit-address'])) {
            if(isset($_POST['address']) && isset($_POST['postalcode']) && isset($_POST['city']) && isset($_POST['country'])) {
                $san_postalcode = trim(htmlspecialchars($_POST['postalcode']));
                $san_city = trim(htmlspecialchars(ucfirst(strtolower($_POST['city']))));
                $pc_sql = "SELECT * FROM postal_city WHERE postalcode = $san_postalcode AND city = '$san_city'";
                $pc_check = $this->sql_query($pc_sql);
                if($pc_check->num_rows > 0){
                    $address_stmnt = $this->conn->prepare("INSERT INTO address VALUES (NULL, ?, ?, 1, ?, ?)");
                    $address_stmnt->bind_param("ssis", $a_street, $a_country, $a_postalcode, $a_city);
                    $a_street = trim(htmlspecialchars($_POST['address']));
                    $a_country = trim(htmlspecialchars($_POST['country']));
                    $a_postalcode = trim(htmlspecialchars($_POST['postalcode']));
                    $a_city = trim(htmlspecialchars(ucfirst(strtolower($_POST['city']))));
                    $address_stmnt->execute();

                    $message_success = "Address added successfully";
                    $address_stmnt->close();
                }
                else{
                    $pc_stmnt = $this->conn->prepare("INSERT INTO postal_city VALUES (?, ?)");
                    $pc_stmnt->bind_param("is", $pc_postalcode, $pc_city);
                    $pc_postalcode = $san_postalcode;
                    $pc_city = trim(htmlspecialchars(ucfirst(strtolower($_POST['city']))));
                    $pc_stmnt->execute();
                    $pc_stmnt->close();

                    $address_stmnt = $this->conn->prepare("INSERT INTO address VALUES (NULL, ?, ?, 1, ?, ?)");
                    $address_stmnt->bind_param("ssis", $a_street, $a_country, $a_postalcode, $a_city);
                    $a_street = trim(htmlspecialchars($_POST['address']));
                    $a_country = trim(htmlspecialchars($_POST['country']));
                    $a_postalcode = trim(htmlspecialchars($_POST['postalcode']));
                    $a_city = trim(htmlspecialchars(ucfirst(strtolower($_POST['city']))));
                    $address_stmnt->execute();

                    $message_success = "Address added successfully";
                    $address_stmnt->close();
                }

            }
            else {
                $message = "Please fill out all the fields";
            }
        }

        if(isset($message)){
            echo "<div class='alert-danger'>$message</div>";
        }
        if(isset($message_success)){
            echo "<div class='alert-success'>$message_success</div>";
        }
        ?>
        <div class="row m-2 product_row">
            <div class="col-12">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input class="w-100 form-control mb-2" type="text" name="address" id="address" placeholder="Some street 123, appartment 2">
                        <div class="form-group d-flex align-items-center justify-content-center">
                            <div class="form-group w-100 mr-3">
                                <label for="postalcode">Postal code:</label>
                                <input class="w-100 form-control mb-2" type="number" name="postalcode" id="postalcode" placeholder="Postalcode">
                            </div>
                            <div class="form-group w-100 mr-3">
                                <label for="city">City:</label>
                                <input class="w-100 form-control mb-2" type="text" name="city" id="city" placeholder="City">
                            </div>
                            <div class="form-group w-100">
                                <label for="country">Country:</label>
                                <input class="w-100 form-control mb-2" type="text" name="country" id="country" placeholder="Country">
                            </div>
                        </div>
                        <input class="form-control mt-3" type="submit" name="submit-address" value="Add">
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}