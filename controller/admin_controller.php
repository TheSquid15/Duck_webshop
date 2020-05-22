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
                        <a href="upload_item.php?edit=true&item_id=<?php echo $row['productID']?>"><button type="button" class="btn btn-primary">Edit</button></a>
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
            //if no page is specified, set it to 1
            $page = 1;
        }
        else {
            //make the page be the same as the GET variable
            $page = $_GET['message_page'];
        }
        //max items per page
        $results_per_page = 5;
        //define the starting point of the query
        $this_page_first_result = ($page - 1)*$results_per_page;
        $number_of_rows_sql = "SELECT * FROM message";
        $number_result = $this->sql_query($number_of_rows_sql);
        //get total number of items
        $number_of_rows = $number_result->num_rows;
        //sql query with an offset, starting with 0-5
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
        if(isset($_POST["opening-hours-submit"])) {
            $update_times_sql = "UPDATE opening SET opening = ?, closing = ?, closed = ? WHERE dayID = ?";
            $update_time_stmnt = $this->conn->prepare($update_times_sql);
            $update_time_stmnt->bind_param("ssis", $opening_time, $closing_time, $closed, $id_of_day);

            $opening_time = trim(htmlspecialchars($_POST["Sunday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Sunday-close"]));
            $closed = $_POST["Sunday-closed"];
            $id_of_day = 1;
            $update_time_stmnt->execute();

            $opening_time = trim(htmlspecialchars($_POST["Monday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Monday-close"]));
            $closed = $_POST["Monday-closed"];
            $id_of_day = 2;
            $update_time_stmnt->execute();

            $opening_time = trim(htmlspecialchars($_POST["Tuesday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Tuesday-close"]));
            $closed = $_POST["Tuesday-closed"];
            $id_of_day = 3;
            $update_time_stmnt->execute();

            $opening_time = trim(htmlspecialchars($_POST["Wednesday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Wednesday-close"]));
            $closed = $_POST["Wednesday-closed"];
            $id_of_day = 4;
            $update_time_stmnt->execute();

            $opening_time = trim(htmlspecialchars($_POST["Thursday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Thursday-close"]));
            $closed = (int)$_POST["Thursday-closed"];
            $id_of_day = 5;
            $update_time_stmnt->execute();

            $opening_time = trim(htmlspecialchars($_POST["Friday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Friday-close"]));
            $closed = $_POST["Friday-closed"];
            $id_of_day = 6;
            $update_time_stmnt->execute();

            $opening_time = trim(htmlspecialchars($_POST["Saturday-open"]));
            $closing_time = trim(htmlspecialchars($_POST["Saturday-close"]));
            $closed = $_POST["Saturday-closed"];
            $id_of_day = 7;
            $update_time_stmnt->execute();

            $update_time_stmnt->close();
            $message_success_opening = "Times updated succesfully";
        }

        if(isset($message_success_opening)) { ?>
            <div class="alert-success mt-4"><?php echo $message_success_opening ?></div>
        <?php
        }
        ?>
        <div class="row m-2 mt-4 product_row">
            <div class="col-12">
                <h4 class="mt-2">Opening Hours</h4>
                <div class="d-flex">
                    <b class="w-100 mr-2"><p class="w-100">Opening time:</p></b>
                    <b class="w-100 mr-2"><p class="w-100">Closing time:</p></b>
                    <b class="w-25 mr-2"><p class="w-25">Closed/Open:</p></b>       
                </div>
                <form action="" method="POST">
                 <?php
                    $days_sql = "SELECT * FROM opening ORDER BY dayID";
                    $days = $this->sql_query($days_sql);

                    while($day = $days->fetch_assoc()) { ?>
                    <p class="m-0 mb-2"><b><?php echo $day["day"]?>:</b></p>
                    <div class="form-group d-flex mb-2">
                        <input class="w-100 form-control mr-2" type="time" name="<?php echo $day["day"]?>-open" id="<?php echo $day["day"]?>-open" min="00:00" max="23:59" value="<?php echo $day["opening"]?>" required>
                        <input class="w-100 form-control mr-2" type="time" name="<?php echo $day["day"]?>-close" id="<?php echo $day["day"]?>-close" min="00:00" max="23:59" value="<?php echo $day["closing"]?>" required>
                        <select class="w-25 form-control mr-2" name="<?php echo $day["day"]?>-closed" id="<?php echo $day["day"]?>-closed" min="00:00" max="23:59" required>  
                            <option value="0" <?php if($day["closed"] == false) {echo "selected";}?>>Open</option>
                            <option value="1" <?php if($day["closed"] == true) {echo "selected";}?>>Closed</option>
                        </select>
                    </div>
                    <?php
                    }
                ?>
                    <input class="form-control mt-4 mb-3" type="submit" name="opening-hours-submit" value="Update opening hours">
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

    private function loadfile($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];

        if($this->image_type == IMAGETYPE_JPEG){
            $this->image = imagecreatefromjpeg($filename);
        }
        elseif($this->image_type == IMAGETYPE_PNG){
            $this->image = imagecreatefrompng($filename);
        }
    }

    private function save($filename, $image_type = IMAGETYPE_JPEG) {
        if($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename);
        }
        elseif($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
    }

    public function news_panel() {
        if(isset($_POST["edit-news"])) {
            if(isset($_POST["title"]) && isset($_POST["text"])) {
                //if a file is uploaded
                if($_FILES["image"]["name"]){
                    $sanatized_article_id = filter_var($_GET["article_id"], FILTER_SANITIZE_NUMBER_INT);
                    //get the old picture from database
                    $fetch_article_image_sql = "SELECT image FROM news WHERE newsID = $sanatized_article_id";
                    $get_article_image = $this->sql_query($fetch_article_image_sql);
                    $image_to_delete = $get_article_image->fetch_assoc();
                    //delete the original picture
                    unlink("../resources/$image_to_delete[image]");

                    $imageName = $_FILES["image"]["name"];
                    $file = $_FILES["image"]["tmp_name"];
                    $imageType = getimagesize($file);

                    //check if the file is any of these image file constants
                    if(($imageType[2] == 2) || ($imageType[2] == 0) || ($imageType[2] == 3)) {
                        $size = filesize($_FILES["image"]["tmp_name"]);

                        if($size <= MAX_SIZE*1024) {
                            $prefix = uniqid();
                            $newImageName = $prefix . "_news_" . $imageName;
                            $newName = "../resources/" . $newImageName;
                            $this->loadfile($file);
                        }
                        else {
                            $message = "Filesize is too big";
                        }
                    } 
                    else {
                        $message = "Invalid file-type";
                    }

                    $this->save($newName, IMAGETYPE_JPEG);

                    $news_statement = $this->conn->prepare("UPDATE news SET title = ?, message = ?, image = ? WHERE newsID = ?");
                    $news_statement->bind_param("sssi", $news_title, $news_message, $news_image, $article_id);

                    $news_title = trim(htmlspecialchars($_POST["title"]));
                    $news_message = trim(htmlspecialchars($_POST["text"]));
                    $news_image = $newImageName;
                    $article_id = $sanatized_article_id;
                    $news_statement->execute();

                    $message_success = "News article successfully edited";
                }
                else {
                    $sanatized_article_id = filter_var($_GET["article_id"], FILTER_SANITIZE_NUMBER_INT);

                    $news_statement = $this->conn->prepare("UPDATE news SET title = ?, message = ? WHERE newsID = ?");
                    $news_statement->bind_param("ssi", $news_title, $news_message, $article_id);

                    $news_title = trim(htmlspecialchars($_POST["title"]));
                    $news_message = trim(htmlspecialchars($_POST["text"]));
                    $article_id = $sanatized_article_id;
                    $news_statement->execute();

                    $message_success = "News article successfully edited";
                }
            }
            else {
                $message = "Please fill out all of the fields";
            }
        }

        if(isset($_POST["submit-news"])) {
            if(isset($_POST["title"]) && isset($_POST["text"])) {
                if($_FILES["image"]["name"]){
                    $imageName = $_FILES["image"]["name"];
                    $file = $_FILES["image"]["tmp_name"];
                    $imageType = getimagesize($file);

                    if(($imageType[2] == 2) || ($imageType[2] == 0) || ($imageType[2] == 3)) {
                        $size = filesize($_FILES["image"]["tmp_name"]);

                        if($size <= MAX_SIZE*1024) {
                            $prefix = uniqid();
                            $newImageName = $prefix . "_news_" . $imageName;
                            $newName = "../resources/" . $newImageName;
                            $this->loadfile($file);
                        }
                        else {
                            $message = "Filesize is too big";
                        }
                    } 
                    else {
                        $message = "Invalid file-type";
                    }

                    $this->save($newName, IMAGETYPE_JPEG);

                    $news_statement = $this->conn->prepare("INSERT INTO news VALUES (NULL, ?, ?, ?, DEFAULT)");
                    $news_statement->bind_param("sss", $news_title, $news_message, $news_image);

                    $news_title = trim(htmlspecialchars($_POST["title"]));
                    $news_message = trim(htmlspecialchars($_POST["text"]));
                    $news_image = $newImageName;
                    $news_statement->execute();

                    $message_success = "News article uploaded";
                }
                else {
                    $message = "Please upload a picture";
                }
            }
            else {
                $message = "Please fill out all of the fields";
            }
        }

        if(isset($message)) { ?>
            <div class="alert-danger"><?php echo $message ?></div>
        <?php
        }
        if(isset($message_success)) { ?>
            <div class="alert-success"><?php echo $message_success?></div>
        <?php
        }

        if(isset($_GET["edit"])) {
            if(isset($_GET["article_id"])) {
                $sanatized_article_id = filter_var($_GET["article_id"], FILTER_SANITIZE_NUMBER_INT);
                $sql_for_article = "SELECT * FROM news WHERE newsID = $sanatized_article_id";
                $fetch_article = $this->sql_query($sql_for_article);
                if($fetch_article->num_rows > 0) {
                    $article = $fetch_article->fetch_assoc(); ?>
                    <div class="row product_row m-3">
                        <div class="col-12">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input class="form-control mb-3" type="text" name="title" id="title" value="<?php echo $article["title"]?>" required>
                                    <label for="text">Text:</label>
                                    <textarea class="form-control mb-3" name="text" id="text" rows="10" required><?php echo $article["message"]?></textarea>
                                    <label for="image">Image file:</label>
                                    <input class="form-control-file mb-3" type="file" name="image" id="image">
                                    <input class="form-control" type="submit" name="edit-news" value="Post">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php
                }
                else {
                    $message = "This id does not match any of the records";
                }
            }
        }
        
        else{
        ?>
        <div class="row product_row m-3">
            <div class="col-12">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input class="form-control mb-3" type="text" name="title" id="title" required>
                        <label for="text">Text:</label>
                        <textarea class="form-control mb-3" name="text" id="text" rows="10" required></textarea>
                        <label for="image">Image file:</label>
                        <input class="form-control-file mb-3" type="file" name="image" id="image">
                        <input class="form-control" type="submit" name="submit-news" value="Post">
                    </div>
                </form>
            </div>
        </div>
        <?php
        }
    }

    public function order_panel() {
        if(!isset($_GET['order_page'])){
            $page = 1;
        }
        else {
            $page = $_GET['order_page'];
        }
        $results_per_page = 3;
        $this_page_first_result = ($page - 1)*$results_per_page;
        $fetching_order_sql = "SELECT * FROM orders JOIN user ON user.userID = orders.userID";
        $orders_pages_query = $this->sql_query($fetching_order_sql);
        $number_of_rows = $orders_pages_query->num_rows;
        $sql = "SELECT * FROM orders JOIN user ON user.userID = orders.userID JOIN address ON address.userID = user.userID LIMIT $this_page_first_result, $results_per_page";
        $orders_result = $this->sql_query($sql);
        $number_of_pages = ceil($number_of_rows/$results_per_page);

        if($orders_result->num_rows > 0) {
            while($order = $orders_result->fetch_assoc()) {
                $orders_sql = "SELECT * FROM order_overview WHERE orderID = '$order[orderID]'";
                $order_info = $this->sql_query($orders_sql);
        ?>
        <div class="w-100">
            <p class="m-0 mb-2"><?php echo "Order ID: $order[orderID]"?></p>
        </div>
        <div class="row product_row m-3 p-3">
            <div class="col-12 d-flex"> 
                <div class="w-100 p-3 m-2 d-flex flex-column items_overview rounded">
                    <p><b>Products: </b></p><?php
                $total_price = 0;
                while($order_item = $order_info->fetch_assoc()) { ?>
                    <div>
                        <p class="m-0"><?php echo $order_item["quantity"]?> x <?php echo $order_item["name"]?> - <?php echo $order_item["price"] * $order_item["quantity"]?></p>
                    </div>
                <?php
                    $total_price += $order_item["price"];
                }
                ?>
                    <div>
                        <p class="m-0 mt-3"><b>Total:</b> <?php echo $total_price?> Slices of bread</p>
                    </div>
                </div>
                <div class="w-100 p-3 m-2 items_overview rounded d-flex flex-column">
                    <p><b>Customer info: </b></p>
                    <p class="m-0"><b>Username:</b> <?php echo $order["username"]?></p>
                    <p class="m-0"><b>Email:</b> <?php echo $order["email"]?></p>
                    <p class="m-0"><b>Name:</b> <?php echo $order["fname"] . " " . $order["lname"]?></p>
                    <p class="m-0"><b>Shipping address:</b> <?php echo $order["street"] . " " . $order["postalcode"] . " " . $order["city"] . " - " . $order["country"]?></p>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-end mr-4 ml-4">
                <button class="btn btn-primary mr-2">Generate invoice</button>
                <button class="btn btn-danger">Delete</button>
            </div>
        </div>
        <?php
            }
        ?>
        <div class="col-12 m-0 d-flex align-items-center justify-content-center"><small class="">Pages</small></div>
        <div class="col-8 d-flex justify-content-around align-items-center m-auto"> 
            <a href="admin_dashboard.php?panel=orders&order_page=<?php if($page > 1 ) {echo $page - 1;}else{ echo $page = 1; } ?>" class="back_forward"><button class="btn btn-primary">< back</button></a>
            <div>
            <?php
            for($page = 1; $page <= $number_of_pages; $page++) { ?>
                <a href="admin_dashboard.php?panel=orders&order_page=<?php echo $page ?>" class="m-1"><?php echo $page ?></a>
            <?php 
            } ?>
            </div>
            <a href="admin_dashboard.php?panel=orders&order_page=<?php if($page < $number_of_pages ) {echo $page + 1;}else{ echo $page = $number_of_pages; } ?>" class="back_forward"><button class="btn btn-primary">next ></button></a>
        </div>
        <?php
        }
        else { ?>
            <div class="row product_row m-3 p-2">No orders</div>
        <?php
        }
    }
}