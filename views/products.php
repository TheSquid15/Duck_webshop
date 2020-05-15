<?php
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

$items = new item_page_controller();

require('../includes/header.php');
?>

<section class="product_of_the_day_container">
    <?php 
        $items->item_of_the_day();
    ?>
</section>
<section class="container pt-4">
    <div class="row">
        <?php
            $items->show_items();
        ?>
    </div>
</section>

<script>
    var countDownDate = new Date("<?php echo $items->deal_time; ?>").getTime();
</script>
<script src="../public/js/countdown.js">

</script>
