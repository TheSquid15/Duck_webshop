<?php
session_start();

require('../includes/header.php');
$item_of_the_day = new home_controller();

$item_of_the_day->item_of_the_day();

?>
<script>
    var countDownDate = new Date("<?php echo $item_of_the_day->deal_time; ?>").getTime();
</script>
<script src="../public/js/countdown.js"></script>
<?php
require('../includes/footer.php');