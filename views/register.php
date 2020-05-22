<?php
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

$register = new login_controller();

if(isset($_POST["submit"])) {
    if(isset($_POST["username"]) && isset($_POST["lname"]) && isset($_POST["email"]) && isset($_POST["password"])) {
        if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $checkUsername = trim(htmlspecialchars($_POST["username"]));
            $check = $register->sql_query("SELECT * FROM `user` WHERE username = '$checkUsername' LIMIT 1");
            if($check->num_rows > 0) {
                $message = "Sorry, that username is already in use";
            }
            else{
                $checkEmail = trim(htmlspecialchars($_POST["email"]));
                $check = $register->sql_query("SELECT * FROM `user` WHERE email = '$checkEmail' LIMIT 1");
                if($check->num_rows > 0) {
                    $message = "Sorry, that email is already in use";
                }
                else {
                    if(isset($_POST["g-recaptcha-response"])) {
                        $captcha = $_POST["g-recaptcha-response"];
    
                        if(!$captcha) {
                            $message = "Please check reCaptcha.";
                        }
    
                        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldd2PEUAAAAANer8gaWGQcHdqeDOETCjkMPBnGV&response=". urlencode($captcha) ."&remoteip=". $_SERVER['REMOTE_ADDR']), true);
                        if($response['success'] == false){
                            $message = "reCaptcha check failed, please try again";
                        }
                        else {
                            $register->createUser($_POST["username"], $_POST["password"], $_POST["email"], $_POST["fname"], $_POST["lname"]);
                            header('Location: index.php');
                        }
    
                    }
                    else {
                        $message = "Please complete reCaptcha.";
                    }
                }
            }
        }
        else {
            $message = "Please input a valid email address.";
        }
    }
    else {
        $message = "Please fill out all required fields.";
    }
}

require('../includes/header.php');
if(isset($message)) { ?>
    <div class="alert-danger"><?php echo $message ?></div>
<?php 
}
?>

<section class="form-controller container-fluid">
    <div class="row align-items-center justify-content-center">
        <div class="col-4 justify-content-center"> 
            <form method="POST" action="" class="position-relative">
            <small class="position-absolute mr-2" style="right: 0;">*required</small>
                <div class="form-group pr-2 pl-2">
                    <div class="form-group d-flex justify-content-between m-0">
                        <div class="form-group w-100 pr-2">
                            <label for="fname">First name:</label>
                            <input class="w-100 form-control" type="text" id="fname" name="fname" placeholder="First name" value="<?php if(isset($_POST["submit"])){echo $_POST['fname'];}else{echo "";}?>">
                        </div>
                        <div class="form-group w-100">
                            <label for="lname">Last name:*</label>
                            <input class="w-100 form-control" type="text" id="lname" name="lname" placeholder="Last name" value="<?php if(isset($_POST["submit"])){echo $_POST['lname'];}else{echo "";}?>" required>
                        </div>
                    </div>
                    <label for="username">Username:*</label>
                    <input class="w-100 form-control" type="text" id="username" name="username" placeholder="Username" value="<?php if(isset($_POST["submit"])){echo $_POST['username'];}else{echo "";}?>" required><br>
                    <label for="email">Email:*</label>
                    <input class="w-100 form-control" type="text" id="email" name="email" placeholder="someone@somewhere.com" value="<?php if(isset($_POST["submit"])){echo $_POST['email'];}else{echo "";}?>" required><br>
                    <label for="password">Password:*</label>
                    <input class="w-100 form-control" type="password" id="password" name="password" placeholder="Password" required><br>
                    <div class="g-recaptcha mb-3" data-sitekey="6Ldd2PEUAAAAAC-UXFp2WnnAw6iQOd8xHUvz7wfG"></div>
                    <input type="submit" name="submit">
                </div>
            </form>
        </div>
    </div>
</section>
<?php
require('../includes/footer.php');