<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

$upload = new add_category_controller();

if(isset($_POST['submit'])) {
    if(!empty($_POST['category_name'])){
        $san_name = trim(htmlspecialchars($_POST['category_name']));
        $upload->add_category($san_name);
        $message_success = "Category succesfully added";
    }
    else {
        $message = "Please fill out the field";
    }
}

require('../includes/header.php');
if(isset($message)) { ?>
    <div class="alert-danger"><?php echo $message ?></div>
<?php
}
if(isset($message_success)){ ?>
    <div class="alert-success"><?php echo $message_success ?></div>
<?php
}
?>
<section class="mt-4">
    <div class="container">
        <h2>Add new category</h2>
        <div class="row mt-4 align-items-center justify-content-center">
            <div class="col-sm-5">
                <form action="" method="POST">
                    <label for="category_name">Category name:</label>
                    <input class="form-control w-100" type="text" name="category_name" required>
                    <input class="form-control mt-3" type="submit" name="submit">
                </form>
            </div>
        </div>
    </div>
</section>

<?php 
include('../includes/footer.php');
?>