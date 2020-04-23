<?php

require('../includes/header.php');

$item_of_the_day = new home_controller();

$item_of_the_day->item_of_the_day();

require('../includes/footer.php');