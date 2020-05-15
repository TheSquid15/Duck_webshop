<?php
require("../includes/const.php");

include(MODEL);

class upload_controller extends DB_model {
    public static $msg;

    protected $image;
    protected $image_type;

    public function loadfile($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];

        if($this->image_type == IMAGETYPE_JPEG){
            $this->image = imagecreatefromjpeg($filename);
        }
        elseif($this->image_type == IMAGETYPE_PNG){
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function save($filename, $image_type = IMAGETYPE_JPEG) {
        if($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename);
        }
        elseif($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
    }

}