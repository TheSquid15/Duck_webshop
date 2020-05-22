<?php 
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

$contact = new contact_controller();

if(isset($_POST["submit"])){
    if(isset($_POST["subject"]) && isset($_POST["message"]) && isset($_POST["email"])) {
        if(filter_var(trim(htmlspecialchars($_POST["email"])), FILTER_VALIDATE_EMAIL)) {
            if(isset($_POST["g-recaptcha-response"])){
                $captcha = $_POST["g-recaptcha-response"];

                if(!$captcha) {
                    $message = "Please check reCaptcha.";
                }
                
                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldd2PEUAAAAANer8gaWGQcHdqeDOETCjkMPBnGV&response=". urlencode($captcha) ."&remoteip=". $_SERVER['REMOTE_ADDR']), true);
                if($response['success'] == false) {
                    $message = "reCaptcha check failed, please try again";
                }
                else {
                    $contact->send_form($_POST["subject"], $_POST["message"], $_POST["email"]);
                    $success_message = "Your message has been sent!";
                }
            }
        }
        else {
            $message = "Invalid email address";
        }
    }
    else {
        $message = "Please fill out all the fields";
    }
}

require('../includes/header.php');
if(isset($message)) { ?>
    <div class="alert-danger"><?php echo $message ?></div>
<?php } 

if(isset($success_message)){
?>
    <div class="alert-success"><?php echo $success_message?></div>
<?php
}
?>

<section class="form-controller container-fluid">
    <div class="row align-items-center justify-content-center">
        <div class="col-4 justify-content-center"> 
            <h3 class="mb-3">Send message</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" class="w-100 form-control" id="subject" name="subject" placeholder="Subject" required><br>
                    <label for="message">Message:</label>
                    <textarea class="w-100 form-control" rows="6" type="text" id="message" name="message" placeholder="Message" required></textarea><br>
                    <label for="email">Email:</label>
                    <input class="w-100 form-control" type="text" id="email" name="email" placeholder="someone@somewhere.com" required><br>
                    <div class="g-recaptcha mb-3" data-sitekey="6Ldd2PEUAAAAAC-UXFp2WnnAw6iQOd8xHUvz7wfG"></div>
                    <input class="w-100 form-control" type="submit" name="submit">
                </div>
            </form>
        </div>
    </div>
</section>
<?php
require('../includes/footer.php');