<?php
/* include("../includes/const.php");
 */

class DB_model {
    public $conn;
    public $DBresult;

    function __construct()
    {
        $this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        
        if($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function sql_query($query) {
        try {
        $result = $this->conn->query($query);
        return $result;
        }
        catch(Exception $e) {
            echo $e;
        }
    }

    function return_query_array($query) {
        $result = $this->sql_query($query);
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(!empty($results)) {
            return $results;
        }
    }

    public static function is_logged_in() {
        return isset($_SESSION['loggedIn']);
    }

    public static function login_guard() {
        if (!self::is_logged_in()) {
            header('Location: login.php');
        }
    }

}
