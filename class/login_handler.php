<?php 

class login_handler {

    private $DB_connect;

    function __construct()
    {
        $this->DB_connect = new connectionController();
    }

    public static function is_logged_in() {
        return isset($_SESSION['user_id']);
    }

}

?>