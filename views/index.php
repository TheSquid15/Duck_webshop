<?php
session_start();

require('../includes/header.php');
$front_page = new home_controller();

$front_page->item_of_the_day();
?>
<section class="container home_news mt-4">
    <a href="news.php"><h1 class="text-center"><b>Recent news</b></h1></a> <?php
    $front_page->home_news();
    ?>
</section>
<section class="container mt-5">
<h1 class="text-center"><b>About Us:</b></h1>
<?php
    $front_page->about_us(); ?>
</section>
<script>
    var countDownDate = new Date("<?php echo $front_page->deal_time; ?>").getTime();
</script>
<script src="../public/js/countdown.js"></script>
<?php
require('../includes/footer.php');