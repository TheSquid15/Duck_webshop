<?php
require('../includes/header.php');

$items = new item_page_controller();
?>

<section class="product_of_the_day_container">
    <?php 
        $items->item_of_the_day();
    ?>
</section>
<section class="product_container">
    <?php
        $items->show_items();
    ?>
</section>

<script>
    var countDownDate = new Date("<?php echo $dealOfTheDay->deal_time; ?>").getTime();
</script>
<script src="<?php echo ROOT?>public/js/countdown.js"></script>