<?php
require("../includes/const.php");

include(MODEL);

class login_controller extends DB_model{

    public $DB_connect;
    public $user;
    public $password;

   
    public function log_in($user, $password) {
        if(!password_verify($password, $user['password'])) {
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

       /*  $prep = $this->conn->prepare("SELECT * FROM `user` WHERE username = ? LIMIT 1");
        $prep->bind_param("s", $preppedinput);

        $preppedinput = $san_user;
        $prep->execute();

        $result = $prep->get_result();
        $DBresult = $result->fetch_array();
 */
        $DBresult = $prep->fetch_assoc();

        if ($DBresult["username"] == $san_user){
            //run login method in this class
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

    public function createUser($username, $password, $email, $fname, $lname) {
        $statement = $this->conn->prepare("INSERT INTO user VALUES (NULL, ?, ?, ?, ?, ?)");
        $statement->bind_param("sssss", $user_username, $user_password, $user_email, $user_fname, $user_lname);
        $iterations = ["cost" => 10];

        $user_username = trim(htmlspecialchars($username));
        $sanatized_pass = trim(htmlspecialchars($password));
        $user_password = password_hash($sanatized_pass, PASSWORD_BCRYPT, $iterations);
        $user_email = $email;
        $user_fname = trim(htmlspecialchars($fname));
        $user_lname = trim(htmlspecialchars($lname));
        $statement->execute();

        $_SESSION["loggedIn"] = true;
        $_SESSION["username"] = trim(htmlspecialchars($username));
        $checkUser = trim(htmlspecialchars($username));
        $get_user_id = $this->sql_query("SELECT * FROM user WHERE username = '$checkUser'");
        $userID = $get_user_id->fetch_assoc();
        $_SESSION["user"] = $userID["userID"];

        $statement->close();
    }

}

?>