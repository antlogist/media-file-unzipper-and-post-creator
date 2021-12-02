<?php

/**
 * Plugin Name: Media File Unzipper And Post Creator
 * Description: WordPress plugin allows unzipping media files and creating posts.
 * Version: 1.0
 * Author: Anton Podlesnyy
 * Author URI: https://podlesnyy.ru
 */

add_action('admin_menu', 'add_menu_item');

function add_menu_item() {
  add_menu_page('Upload Wills', 'Upload Wills','manage_options','history_upload_wills','history_upload_wills','dashicons-media-archive', 10);
}

function history_upload_wills() { ?>
  <div class="wrap">
    <h1>Upload ZIP Archive with PDF files inside</h1>
    <form action="./admin.php?page=history_upload_wills" method="post" enctype="multipart/form-data" class="server-form">

      <p>
        <input type="file" name="fileToUpload" id="fileToUpload">
      </p>

      <p class="submit">
        <input type="submit" class="button button-primary" value="Upload and Create Posts" name="submit">
			</p>

    </form>
  </div>
<?php
  if(isset($_FILES['fileToUpload'])) {

    //Get upload directory with year and month
    $dir = "../wp-content/uploads" . wp_upload_dir()['subdir'];

    //Upload Zip File
    $file = $dir . '/' . basename($_FILES["fileToUpload"]["name"]);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file);
    $fileName 		= basename( $_FILES["fileToUpload"]["name"]);

    //Create instance of ZIP
    $zip = new ZipArchive;

    //Attempt to open the zip file.
    $resp = $zip->open($file);

    //Extract zip if it can be open
    if ($resp === true) {
      $zip->extractTo($dir);

      //Message about successful extract
      echo "<p>The zip file $fileName was successfully unzipped to  " . wp_upload_dir()['url'] . ".</p>";

      //Message with the number of media files in the zip file.
      echo "<p>There are ".$zip->numFiles." files in this zip file.</p>";

      //Loop through each media file to process it for the media library and creatr posts
      for($i=0; $i < $zip->numFiles; $i++) {

        //Get the URL of the media file.
        $fileName = wp_upload_dir()['url'] . '/' . $zip->getNameIndex($i);
        echo "<p>File name" . $fileName . "</p>";

        //Get the file type
        $fileType 	= wp_check_filetype( basename( $fileName ), null );
        echo "<p>File type" . $fileType . "</p>";

        //Check the file type
        $allowed 	= history_allowed_file_types($filetype['type']);

        if($allowed) {
          //File link
          echo "<div><a href='" . $fileName . "' target='_blank'>" . $fileName . "</a> Type: " . $fileType['type'] . "</div>";

          //Attachment information
          $attachment = array(
            'guid'           => $fileName,
            'post_mime_type' => $fileType['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', $zip->getNameIndex($i)),
            'post_content'   => '',
            'post_status'    => 'inherit'
          );

          //Insert the attachment.
          $attachId = wp_insert_attachment( $attachment, $dir . '/' . $zip->getNameIndex($i) );

          //Generate attachment metadata
          $attachData = wp_generate_attachment_metadata($attachId, $dir . '/' . $zip->getNameIndex($i));

          wp_update_attachment_metadata( $attachId, $attachData );

        } else {
          echo $zip->getNameIndex($i) . " could not be uploaded. Its file type of " . $fileType['type'] . "is not allowed";
        }

      }

    } else {
      echo "<h3>The zip file was NOT successfully unzipped.</h3>";
    }

    $zip->close();

  }
}


function history_allowed_file_types($fileType) {
  //Array of allowed file types by MIME
  $allowedFileTypes = array('application/pdf');
	if(in_array($fileType,$allowedFileTypes)) {
		return true;
	} else {
		return false;
	}
}

