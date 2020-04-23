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
    var countDownDate = new Date("<?php echo $items->deal_time; ?>").getTime();
</script>
<script src="../public/js/countdown.js">

</script>
