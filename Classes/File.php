<?php

class File {
  private static $dir ;
  private static $file;
  private $fileName;
  private $isAllowed = false;
  private $type;

  private static function init() {
    //Get upload directory with year and month
    self::$dir = "../../wp-content/uploads" . wp_upload_dir()['subdir'];

    //Get file path
    self::$file = self::$dir . '/' . basename($_FILES["fileToUpload"]["name"]);

  }

  static function unzipAndPost() {
    self::init();
    self::uploadZip();
  }

  private static function uploadZip() {
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], self::$file);
  }

}
