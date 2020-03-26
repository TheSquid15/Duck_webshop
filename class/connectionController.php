<?php
include("./includes/const.php");


class connectionController{
    public $conn;

    function __construct()
    {
        $this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

        if($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
