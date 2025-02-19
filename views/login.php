<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

$login_controller = new login_controller();

if(login_controller::is_logged_in()){
    header('Location: index.php');
} 

require('../includes/header.php');

if (isset($_POST["submit"])) {
    $login_controller->user = $_POST["username"];
    $login_controller->password = $_POST["password"];
    $login_controller->prepare_login();
    var_dump($login_controller->user);
    var_dump($login_controller->password);

   }
   else {
    if(isset($_GET['logout']) && $_GET['logout'] == 1) {
        echo "<div class='alert-success'>You've been logged out</div>";
        session_destroy(); 
    }
}
?>

<section class="form-controller container-fluid">
    <div class="row align-items-center justify-content-center">
        <div class="col-4 justify-content-center"> 
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="w-100 form-control" type="text" id="username" name="username" placeholder="Username"><br>
                    <label for="password">Password:</label>
                    <input class="w-100 form-control" type="password" id="password" name="password" placeholder="Password"><br>
                    <input class="w-100 form-control" type="submit" name="submit">
                </div>
            </form>
        </div>
    </div>
</section>

<?php
require('../includes/footer.php');
/* if(isset($_POST['submit'])) {

    $login_controller->sanatize_input($_POST['username'], $_POST['password']);
    $sql = "SELECT userID, username, password FROM user WHERE username = '{$login_controller->user}' LIMIT 1";

    $fetchedDBuser = $login_controller->DB_connect->conn->query($sql);
    $row = $fetchedDBuser->fetch_assoc();

        if(count($row) == 1){
            $login_controller->log_in($fetchedDBuser, $login_controller->password);
            if($login_controller->log_in($fetchedDBuser, $login_controller->password) == true) {
                if(isset($_SESSION['user'])) {
                    header('index.php');
                }
            }
        }
}
 */