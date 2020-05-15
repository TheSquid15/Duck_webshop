<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

require('../includes/header.php');

$upload_controller = new upload_controller();
$sql = "SELECT * FROM category";
$result = $upload_controller->sql_query($sql);

if(isset($_POST["submit"])) {
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
        $productImg = $newName;
        $price = $_POST["price"];
        $category = $_POST["category"];
        $pstatement->execute();

        echo "<div class='alert-success'>Product uploaded successfully</div>";
    }
}
?>

<section>
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-sm-5">
                <form action="" name="upload" method="POST" enctype="multipart/form-data">
                    <b><label for="item_name">Item name: </label></b><input type="text" name="item_name"><br>
                    <b><label for="price">Item price (in bread crumbs): </label></b><input type="number" name="price"><br>
                    <select name="category" id="">
                        <?php 
                            if($result->num_rows > 0) {
                                $i = 1;
                                while($row = $result->fetch_assoc()){
                                    echo "<option value={$i}>{$row['categoryName']}</option>";
                                    $i++;
                                }
                            }
                        ?>
                    </select><br>
                    <input type="file" name="image">
                    <input type="submit" name="submit">
                </form>
            </div>
        </div>
    </div>
</section>