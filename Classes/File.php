<?php

require_once plugin_dir_path( __FILE__ ) . 'Render.php';

class File {
  private static $dir ;
  private static $file;
  private static $fileName;
  private static $numFiles;
  private static $zip;
  private static $allowedFileTypes = ['application/pdf'];

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
    if($zip->open(self::$file) !== true) {

      $message[] = (object)[
        'type' => 'error',
        'text' => 'The zip file was NOT successfully unzipped'
      ];

      $zip->close();
      Render::message($message);
      exit;
    }

    self::$numFiles = $zip->numFiles;

    //Extract zip if it can be open
    $zip->extractTo(self::$dir);

    self::$zip = $zip;

    $message = [];

    array_push($message, (object)[
      'type' => 'success',
      'text' => 'The zip file' . self::$fileName . '  was successfully unzipped to ' . wp_upload_dir()['url']
    ]);

    array_push($message, (object)[
      'type' => 'info',
      'text' => 'There are ' . self::$numFiles. ' files in this zip file.'
    ]);

    Render::message($message);

    self::addToLibAndPost();
  }

  private static function addToLibAndPost() {
    for($i=0; $i < self::$numFiles; $i++) {

      //Get the URL of the media file.
      $fileUrl = wp_upload_dir()['url'] . '/' . self::$zip->getNameIndex($i);

      $message = [];
      $message[] = (object)[
        'type' => 'info',
        'text' => 'File url: ' . $fileUrl
      ];
      Render::message($message);

      //Get the file type
      $fileType 	= wp_check_filetype( basename( $fileUrl ), null );

      $message = [];
      $message[] = (object)[
        'type' => 'info',
        'text' => 'File type: ' . $fileType['type']
      ];
      Render::message($message);

      //Check the type
      if(in_array($fileType['type'], self::$allowedFileTypes)) {

        $message = [];
        $message[] = (object)[
          'type' => 'success',
          'text' => '<a href="' . $fileUrl . '" target="_blank"> '. $fileUrl . '</a> File type: ' . $fileType['type']
        ];
        Render::message($message);


        //Attachment information
        $attachment = array(
          'guid'           => $fileUrl,
          'post_mime_type' => $fileType['type'],
          'post_title'     => preg_replace('/\.[^.]+$/', '', self::$zip->getNameIndex($i)),
          'post_content'   => '',
          'post_status'    => 'inherit'
        );

        //Insert the attachment.
        $attachId = wp_insert_attachment( $attachment, self::$dir . '/' . self::$zip->getNameIndex($i) );

        //Generate attachment metadata
        $attachData = wp_generate_attachment_metadata($attachId, self::$dir . '/' . self::$zip->getNameIndex($i));

        //Update metadata for an attachment.
        wp_update_attachment_metadata( $attachId, $attachData );

      } else {

        $message = [];
        $message[] = (object)[
          'type' => 'error',
          'text' => self::$zip->getNameIndex($i) . ' could not be uploaded. Its file type of  ' . $fileType['type'] . ' is not allowed'
        ];
        Render::message($message);

      }

    }
  }

}
