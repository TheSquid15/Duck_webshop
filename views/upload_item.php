<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

upload_controller::login_guard();

if(!isset($_SESSION['admin']) && $_SESSION['admin'] !== true) {
    header('Location: index.php');
}

require('../includes/header.php');

$upload_controller = new upload_controller();
$sql = "SELECT * FROM category";
$result = $upload_controller->sql_query($sql);

if(isset($_POST["submit-new"])) {
    if($_FILES["image"]["name"]) {
        $imageName = $_FILES["image"]["name"];
        $file = $_FILES["image"]["tmp_name"];
        $imageType = getimagesize($file);

        if(($imageType[2] == 2) || ($imageType[2] == 0) || ($imageType[2] == 3)) {
            $size = filesize($_FILES["image"]["tmp_name"]);

            if($size <= MAX_SIZE*1024) {
                $prefix = uniqid();
                $newImageName = $prefix . "_" . $imageName;
                $newName = "../resources/" . $newImageName;
                $upload_controller->loadfile($file);
            }
            else {
                echo "<div class='alert-danger'>Filesize is too big</div>";
            }
        }
        else {
            echo "<div class='alert-danger'>Invalid file-type</div>";
        }

        $upload_controller->save($newName, IMAGETYPE_JPEG);

        $pstatement = $upload_controller->conn->prepare("INSERT INTO product VALUES (NULL, ?, ?, ?, ?)");
        $pstatement->bind_param("ssii", $productName, $productImg, $price, $category);

        $productName = $_POST["item_name"];
        $productImg = $newImageName;
        $price = $_POST["price"];
        $category = $_POST["category"];
        $pstatement->execute();

        echo "<div class='alert-success'>Product uploaded successfully</div>";
    }
}

if(isset($_POST["submit-edit"])){
    if($_FILES["image"]["name"]) {
        $imageName = $_FILES["image"]["name"];
        $file = $_FILES["image"]["tmp_name"];
        $imageType = getimagesize($file);
        
        $search_id = filter_var($_GET["item_id"], FILTER_SANITIZE_NUMBER_INT);
        $image_to_delete_sql = "SELECT productImage FROM product WHERE productID = $search_id";
        $image_info = $upload_controller->sql_query($image_to_delete_sql);
        $image = $image_info->fetch_assoc();
        unlink("../resources/" . $image["productImage"]);

        if(($imageType[2] == 2) || ($imageType[2] == 0) || ($imageType[2] == 3)) {
            $size = filesize($_FILES["image"]["tmp_name"]);

            if($size <= MAX_SIZE*1024) {
                $prefix = uniqid();
                $newImageName = $prefix . "_" . $imageName;
                $newName = "../resources/" . $newImageName;
                $upload_controller->loadfile($file);
            }
            else {
                echo "<div class='alert-danger'>Filesize is too big</div>";
            }
        }
        else {
            echo "<div class='alert-danger'>Invalid file-type</div>";
        }

        $upload_controller->save($newName, IMAGETYPE_JPEG);

        $product_update_statement = $upload_controller->conn->prepare("UPDATE product SET name = ?, productImage = ?, price = ?, categoryID = ? WHERE productID = ?");
        $product_update_statement->bind_param("ssiis", $productName, $productImg, $price, $category, $product_id);

        $productName = $_POST["item_name"];
        $productImg = $newImageName;
        $price = $_POST["price"];
        $category = $_POST["category"];
        $product_id = $search_id;
        $product_update_statement->execute();

        echo "<div class='alert-success'>Product successfully updated</div>";

    }
    else {
        $search_id = $_GET["item_id"];
        $product_update_statement = $upload_controller->conn->prepare("UPDATE product SET name = ?, price = ?, categoryID = ? WHERE productID = ?");
        $product_update_statement->bind_param("siii", $productName, $price, $category, $product_id);

        $productName = $_POST["item_name"];
        $price = $_POST["price"];
        $category = $_POST["category"];
        $product_id = $search_id;
        $product_update_statement->execute();

        echo "<div class='alert-success'>Product successfully updated</div>";
    }
}

if(isset($_GET["edit"]) && $_GET["edit"] == true){
    if(isset($_GET["item_id"])) {
        $san_edit_id = filter_var($_GET["item_id"], FILTER_SANITIZE_NUMBER_INT);
        $edit_item_sql = "SELECT * FROM product WHERE productID = '$san_edit_id'";
        $get_edit_item = $upload_controller->sql_query($edit_item_sql);
        if($get_edit_item->num_rows > 0) {
            $product = $get_edit_item->fetch_assoc(); ?>
            <section class="mt-4">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-sm-5">
                            <form action="" name="upload" method="POST" enctype="multipart/form-data">
                                <b><label for="item_name">Item name: </label></b>
                                <input class="form-control" type="text" name="item_name" value="<?php echo $product["name"]?>"><br>
                                <b><label for="price">Item price (in bread crumbs): </label></b>
                                <input class="form-control" type="number" name="price" min="0" value="<?php echo $product["price"]?>"><br>
                                <select class="form-control" name="category" id="">
                                    <?php 
                                        if($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()){ 
                                                if($product["categoryID"] == $row["categoryID"]) {
                                                    echo "<option value={$row['categoryID']} selected>{$row['categoryName']}</option>";
                                                }else{
                                                    echo "<option value={$row['categoryID']}>{$row['categoryName']}</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select><br>
                                <input class="form-control-file" type="file" name="image">
                                <input class="form-control mt-3" type="submit" name="submit-edit">
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        <?php
        }
        else {
            echo "<div class='alert-danger'>This item does not exist</div>";
        }
    }
}
else{
?>
<section class="mt-4">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-sm-5">
                <form action="" name="upload" method="POST" enctype="multipart/form-data">
                    <b><label for="item_name">Item name: </label></b>
                    <input class="form-control" type="text" name="item_name"><br>
                    <b><label for="price">Item price (in bread crumbs): </label></b>
                    <input class="form-control" type="number" name="price"><br>
                    <select class="form-control" name="category" id="">
                        <?php 
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()){
                                    echo "<option value={$row['categoryID']}>{$row['categoryName']}</option>";
                                }
                            }
                        ?>
                    </select><br>
                    <input class="form-control-file" type="file" name="image">
                    <input class="form-control mt-3" type="submit" name="submit-new">
                </form>
            </div>
        </div>
    </div>
</section>
<?php
}
require('../includes/footer.php');