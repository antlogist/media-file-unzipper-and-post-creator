<?php

require_once plugin_dir_path( __FILE__ ) . 'Render.php';

class File {
  private static $dir ;
  private static $file;
  private static $fileName;
  private $isAllowed = false;
  private $type;

  private static function init() {
    //Get upload directory with year and month
    self::$dir = "../wp-content/uploads" . wp_upload_dir()['subdir'];

    //Get file path
    self::$file = self::$dir . '/' . basename($_FILES["fileToUpload"]["name"]);

    //Get filename
    self::$fileName = basename( $_FILES["fileToUpload"]["name"]);

  }

  static function unzipAndPost() {
    self::init();

    if(self::uploadZip()){
      self::extractZip();
    } else {

      $message[] = (object)[
        'type' => 'error',
        'text' => 'No file chosen...'
      ];

      return Render::message($message);
    }

  }

  private static function uploadZip() {
    return move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], self::$file);
  }

  private static function extractZip() {
    //Create instance of ZIP
    $zip = new ZipArchive;

    //Attempt to open the zip file.
    if(!$zip->open(self::$file)) {

      $message[] = (object)[
        'type' => 'error',
        'text' => 'The zip file was NOT successfully unzipped'
      ];

      $zip->close();
      Render::message($message);
    }

    //Extract zip if it can be open
    $zip->extractTo(self::$dir);

    $message = [];

    array_push($message, (object)[
      'type' => 'success',
      'text' => 'The zip file' . self::$fileName . '  was successfully unzipped to' . wp_upload_dir()['url']
    ]);

    array_push($message, (object)[
      'type' => 'info',
      'text' => 'There are ' . $zip->numFiles. ' files in this zip file.'
    ]);

    Render::message($message);

  }

}
