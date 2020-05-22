<?php
require("../includes/const.php");

include(MODEL);

class home_controller extends DB_model {

    public function item_of_the_day() {

        $sql = "SELECT * FROM product_of_the_day JOIN product ON product_of_the_day.productID = product.productID";
        $result = $this->sql_query($sql);

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="item_of_the_day_controller home_deal">
                    <img src="../resources/<?php echo $row['productImage']?>" alt="">
                    <h2 class="product_title"><?php echo $row['name']?></h2>
                    <div class="percentageBox"><h3>-<?php echo $row['percentage']?>%</h3></div>
                    <div id="deal_time" class="time"></div>
                </div>
                <?php

                $this->deal_time = $row['end_date'];
            }
        }
        else {
            ?>
                <div class="item_of_the_day_controller">
                    <img src="" alt="">
                    <h3>New deals, every day!</h3>
                </div>
            <?php
        }
    }

    public function home_news() {
        $sql = "SELECT * FROM news ORDER BY time DESC LIMIT 3";
        $result = $this->sql_query($sql); ?>
        <div class="row d-flex justify-content-around">
        <?php
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
                <div class="card col-3 card_box mt-3">
                    <div style="background-image: url(../resources/<?php echo $row["image"]?>)" class="card-img-top news_card_top"></div>
                    <div class="card-body">
                        <a href="news.php?article=<?php echo $row["newsID"]?>"><h4 class="card-title"><?php echo $row["title"]?></h4></a>
                        <p class="card-text"><?php echo implode(' ', array_slice(explode(' ', str_replace("\n", "<br>", $row["message"])), 0, 25)) . "...";?></p>
                        <a href="news.php?article=<?php echo $row["newsID"]?>" class="btn btn-primary">Read more</a>
                    </div>
                </div> 
            <?php    
            } ?>
        </div> <?php
        }
        
        else { ?>
            <div class="row">
                <div class="col-12">
                    <h2>Sorry, there are currently no news</h2>
                </div>
            </div>
        <?php
        }
    }

    public function about_us() {
        $about_sql = "SELECT * FROM about_us JOIN address ON about_us.addressID = address.addressID";
        $opening_sql = "SELECT * FROM opening";
        $about_us_result = $this->sql_query($about_sql);
        $opening_result = $this->sql_query($opening_sql);
        $about = $about_us_result->fetch_assoc(); ?>
        <div class="row mt-4 mb-4">  
            <div class="col-12">
                <p class="text-justify"><?php echo $about["about_us_text"]?></p>
                <p class="text-center"><i>"<?php echo $about["slogan"]?>"</i> - <b>Quack Tac Team Slogan</b></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Open</th>
                            <th scope="col">Close</th>
                        </tr>
                    </thead>
                    <tbody> <?php
                        while($open_row = $opening_result->fetch_assoc()) {
                            if($open_row["closed"] == false) { ?>
                                <tr>
                                    <th scope="row"><?php echo $open_row["day"]?></th>
                                    <td><?php echo $open_row["opening"]?></td>
                                    <td><?php echo $open_row["closing"]?></td>
                                </tr>
                             <?php
                            }
                            else { ?>
                                <tr>
                                    <th scope="row"><?php echo $open_row["day"]?></th>
                                    <td><b>Closed</b></td>
                                    <td><b>Closed</b></td>
                                </tr>
                            <?php 
                            }
                        };
                  ?></tbody>
                </table>
            </div>
            <p class="w-100 text-center mt-5"><b>Have a question? Go <a href="contact.php">here</a> or send us an email: </b><a href="mailto:<?php echo $about["email"]?>"><?php echo $about["email"]?></a></p>
        </div>
        <?php
    }
}