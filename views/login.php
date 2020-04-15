<?php 
include('../includes/header.php');

$login_controller = new login_controller();

if(login_controller::is_logged_in()){
    header('index.php');
} 

if (isset($_POST["submit"])) {

    $user = trim(htmlspecialchars($_POST["username"]));
    $password = trim(htmlspecialchars($_POST["password"]));
    $sql = "SELECT * FROM user WHERE username = {'$user'} LIMIT 1";
    $prep = $login_controller->sql_query($sql);
    /* $prep = $login_controller->DB_connect->conn->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
    $prep->bind_param("s", $preppedUser);
    $preppedUser = $user;
    $prep->execute(); */
    $DBresult = $login_controller->DBresult;

    if (count($DBresult) == 1){
        if($login_controller->log_in($DBresult, $password) == 1){
            if(isset($_SESSION['loggedIn']) && isset($_SESSION['user']) && isset($_SESSION['username'])){
                header('index.php');
            }
        }
    }
    else {
        echo "Sorry, that username/password does not exist";
    }
}
else {
    if(isset($_GET['logout']) && $_GET['logout'] == 1) {
        echo "You've been logged out";
    }
}

?>

<section class="form-controller">
    <form method="POST" action="">
        Username: <br>
        <input type="text" name="username"><br><br>
        Password: <br>
        <input type="password" name="password"><br><br>
        <input type="submit" name="submit">
    </form>
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
var_dump($_SESSION);
