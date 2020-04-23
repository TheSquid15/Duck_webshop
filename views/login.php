<?php 
include('../includes/header.php');

$login_controller = new login_controller();

if(login_controller::is_logged_in()){
    header('Location: index.php');
} 

if (isset($_POST["submit"])) {
    $login_controller->user = $_POST["username"];
    $login_controller->password = $_POST["password"];
    $login_controller->prepare_login();
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
                    Username: <br>
                    <input type="text" name="username"><br><br>
                    Password: <br>
                    <input type="password" name="password"><br><br>
                    <input type="submit" name="submit">
                </div>
            </form>
        </div>
    </div>
</section>

<?php
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