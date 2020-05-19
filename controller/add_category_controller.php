<?php
require("../includes/const.php");

include(MODEL);

class add_category_controller extends DB_model{

    public function add_category($name) {
        $statement = $this->conn->prepare("INSERT INTO category VALUES (NULL, ?)");
        $statement->bind_param("s", $category_name);

        $category_name = $name;
        $statement->execute();

        $statement->close();
    }

}

?>