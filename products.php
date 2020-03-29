<?php
require('includes/header.php');
spl_autoload_register(function($class) {
    include "class/" . $class . ".php";
});
?>

<section class="product_of_the_day_container">
    <?php 
        $dealOfTheDay = new item_of_the_day();
    ?>
</section>
<section class="product_container">
    <?php
        $items = new display_items();
    ?>
</section>

<?php var_dump($dealOfTheDay->deal_time)?>

<script>
var countDownDate = new Date("<?php echo $dealOfTheDay->deal_time; ?>").getTime();

var x = setInterval(function() {

  var now = new Date().getTime();

  var distance = countDownDate - now;

  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  document.getElementById("deal_time").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  if (distance < 0) {
    clearInterval(x);
    document.getElementById("deal_time").innerHTML = "EXPIRED";
  }
}, 1000);
</script>