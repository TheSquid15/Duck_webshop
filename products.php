<?php
require('includes/header.php');
spl_autoload_register(function($class) {
    include "class/" . $class . ".php";
});
?>

<section class="product_of_the_day_container">
<!-- Put product of the day later -->
</section>
<section class="product_container">
    <?php
        $items = new display_items();
    ?>
</section>