<?php 
require('../includes/header.php');

$upload_controller = new upload_controller();
$sql = "SELECT * FROM category";
$result = $upload_controller->sql_query($sql);

var_dump($result);

?>

<section>
    <form action="" name="upload" method="POST" enctype="multipart/form-data">
        <b><label for="item_name">Item name: </label></b><input type="text" name="item_name"><br>
        <b><label for="price">Item price (in bread crumbs): </label></b><input type="number" name="price"><br>
        <select name="category" id="">
            <?php 
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        echo "<option value={$row['categoryName']}>{$row['categoryName']}</option>";
                    }
                }
            ?>
        </select><br>
        <input type="file" name="image">
    </form>
</section>