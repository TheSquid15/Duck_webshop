<?php
session_start();

spl_autoload_register(function($class) {
    include "../controller/" . $class . ".php";
});

$news_con = new news_controller();

if(isset($_SESSION["admin"]) && $_SESSION["admin"] == true) {
    if(isset($_GET["delete"])) {
        if($_GET["delete"] == true && isset($_GET["delete_id"])) {
            $news_con->delete_article($_GET["delete_id"]);
            $message_success = "Article deleted successfully";
        }
    }
}


require("../includes/header.php");

if(isset($message_success)) {
    echo "<div class='alert-success'>$message_success</div>";
}

//if an article ID is passed through the url
if(isset($_GET["article"])) {
    $article = filter_var($_GET["article"], FILTER_SANITIZE_NUMBER_INT); ?>
    <section class="container mt-4">
        <?php $news_con->show_article($article); ?>
    </section>
    <?php
}
else { ?>
    <section class="form-controller container">
        <h1 class="mb-4">Most recent news</h1>
        <?php $news_con->get_news()?>
    </section>
<?php
}

require('../includes/footer.php');