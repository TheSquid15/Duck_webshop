<?php 

include(MODEL);

class login_controller extends DB_model{

    public $DB_connect;
    public $user;
    public $password;

    function __construct()
    {
        $this->DB_connect = new DB_model();
    }

    public static function is_logged_in() {
        return isset($_SESSION['user_id']);
    }

    public static function login_guard() {
        if (!self::is_logged_in()) {
            header('login.php');
        }
    }

    /* public function sanatize_input($username, $password) {
        $this->user = trim(htmlspecialchars($username));
        $this->password = trim(htmlspecialchars($password));
    } */

    public function log_in($user, $password) {
        $DBuser = $user[0];
        if(!password_verify($password, $DBuser['password'])){
            return false;
        }
        else {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user'] = $DBuser['userID'];
            $_SESSION['username'] = $DBuser['username'];
            return true;
        }
    }

    public function logout() {

    }

}

?>