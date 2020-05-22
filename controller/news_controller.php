<?php
require("../includes/const.php");

include(MODEL);

class news_controller extends DB_model {
    public $news_article;
    public $news_array;

    public function get_news() {
        $sql = "SELECT * FROM news ORDER BY time DESC";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                ?>
                   <div class="row justify-content-center mb-5">
                        <div class="col-2">
                            <img class="news-image" src="../resources/<?php echo $row["image"]?>" alt="">
                        </div>
                        <div class="col-6">
                            <a href="news.php?article=<?php echo $row["newsID"]?>"><h2><?php echo $row["title"]?></h2></a>
                            <small><?php echo $row["time"]?></small>
                            <p><?php echo implode(' ', array_slice(explode(' ', str_replace("\n", "<br>", $row["message"])), 0, 25)) . "...";?><a href="news.php?article=<?php echo $row["newsID"]?>"> Read more</a></p>
                            <?php 
                            if(isset($_SESSION["admin"])) {
                                if($_SESSION["admin"] == true) {
                            ?>
                            <div>
                                <a href="admin_dashboard.php?panel=news&edit=true&article_id=<?php echo $row["newsID"]?>"><button type="button" class="btn btn-primary">Edit</button></a>
                                <a href="news.php?delete=true&delete_id=<?php echo $row["newsID"]?>"><button type="button" class="btn btn-danger">Delete</button></a>
                            </div>
                            <?php
                                }
                            }                            
                            ?>
                        </div>
                   </div> 
                <?php
            }
        }
    }

    public function show_article($article) {
        $sql = "SELECT * FROM news WHERE newsID = '$article'";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc(); ?>
            <a href="news.php"><button class="btn btn-primary mb-4">< Back</button></a>
            <?php
                if(isset($_SESSION["loggedIn"])) {
                    if($_SESSION["admin"] == true) { ?>
                        <a class="float-right" href="admin_dashboard.php?panel=news&edit=true&article_id=<?php echo $row["newsID"]?>"><button class="btn btn-primary mb-4">Edit Post</button></a>
                    <?php
                    }
                }
            ?>
                <div class="row news-header">
                    <div class="col-12 news-header d-flex justify-content-center align-items-center" style="background-image: url('../resources/<?php echo $row['image']?>');">
                        <h1><?php echo $row["title"] ?></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-2">
                        <p>Uploaded on: <?php echo $row["time"]?></p>
                    </div>
                    <div class="col-12">
                        <h1 class="news-title"><?php echo $row["title"]?></h1>
                        <p class="text-justify"><?php echo str_replace("\n", "<br>", $row["message"])?></p>
                    </div>
                </div>
            <?php
        }
        else {
            echo "<div class='alert-danger'>This article does not exist</div>";
        }
    }

    public function delete_article($article_id) {
        $san_id = trim(htmlspecialchars($article_id));

        $news_check = "SELECT * FROM news";
        $news_DB = $this->sql_query($news_check);
        if($news_DB->num_rows > 0) {
            while($row = $news_DB->fetch_assoc()) {
                if($row["newsID"] == $san_id){
                    unlink("../resources/" . $row["image"]);

                    $sql = "DELETE FROM news WHERE newsID = ?";
                    $remove_news_stmnt = $this->conn->prepare($sql);
                    $remove_news_stmnt->bind_param("i", $delete_article_id);

                    $delete_article_id = $san_id;
                    $remove_news_stmnt->execute();
                }
            }
        }
    }
}