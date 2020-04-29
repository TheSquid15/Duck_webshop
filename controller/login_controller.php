<?php 

include(MODEL);

class login_controller extends DB_model{

    public $DB_connect;
    public $user;
    public $password;

    public static function is_logged_in() {
        return isset($_SESSION['loggedIn']);
    }

    public static function login_guard() {
        if (!self::is_logged_in()) {
            header('Location: login.php');
        }
    }

    /* public function sanatize_input($username, $password) {
        $this->user = trim(htmlspecialchars($username));
        $this->password = trim(htmlspecialchars($password));
    } */

    public function log_in($user, $password) {
        if($password !== $user['password']){
            return false;
        }
        else {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            if($user['userID'] == 1) {
                $_SESSION['admin'] = true;
            }
            return true;
        }
    }

    public function prepare_login() {
        $san_user = trim(htmlspecialchars($this->user));
        $san_password = trim(htmlspecialchars($this->password));
        $prep = $this->sql_query("SELECT * FROM `user` WHERE username = '$san_user' LIMIT 1");
        /* $prep = $login_control$rhiler->DB_connect->conn->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
        $prep->bind_param("s", $preppedUser);
        $preppedUser = $user;
        $prep->execute(); */
<<<<<<< HEAD
=======
        $DBresult = $prep->fetch_assoc();
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653

       /*  $prep = $this->conn->prepare("SELECT * FROM `user` WHERE username = ? LIMIT 1");
        $prep->bind_param("s", $preppedinput);

        $preppedinput = $san_user;
        $prep->execute();

        $result = $prep->get_result();
        $DBresult = $result->fetch_array();
 */
        $DBresult = $prep->fetch_assoc();

        if ($DBresult["username"] == $san_user){
            if($this->log_in($DBresult, $san_password) == 1){
                if(isset($_SESSION["loggedIn"]) && isset($_SESSION["user"]) && isset($_SESSION["username"])){
                    header("Location: index.php");
                }
                else {
                    echo "<div class='alert-danger'>Something went wrong, please try again</div>";
                }
            }
            else {
                echo "<div class='alert-danger'>Sorry, that username/password does not exist</div>";
            }
        }
        else {
            echo "<div class='alert-danger'>Sorry, that username/password does not exist</div>";
        }
    }

    public function logout() {

    }

}

?>