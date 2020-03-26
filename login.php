<?php 
include('includes/header.php');

$login_controller = new login_handler();

if(login_handler::is_logged_in()){
    header('index.php');
}

if(isset($_POST["submit"])){

    $username = trim(htmlspecialchars($_POST['user']));
    $password = trim(htmlspecialchars($_POST['pass']));
    $DBConn = new connectionController();
    $sql = "SELECT id, user, pass FROM users WHERE user = '{$username}' LIMIT 1";
    $userDBResult = $DBConn->conn->query($sql);
    $userDBResult = $DBConn->readResults;
         if (count($userDBResult)==1){
           if($login->logMeIn($userDBResult,$password)==1){
               if(isset($_SESSION['user_id'])){
                   header('index.php');
               }
           }
        }
}
else{
    if(isset($_GET['logout']) && $_GET['logout'] == 1){
        $messages->setMessage("You are now logged out");
    }
}
?>

<section class="form-controller">
    <form action="POST">
        Username: <br>
        <input type="text" name="username"><br><br>
        Password: <br>
        <input type="text" name="password"><br><br>
        <input type="submit" name="">
    </form>
</section>