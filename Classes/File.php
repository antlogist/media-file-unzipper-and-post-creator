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
    echo self::$file;
    exit;
  }

  private function uploadZip() {
    // echo "../../wp-content/uploads" . wp_upload_dir()['subdir'];
  }
}
