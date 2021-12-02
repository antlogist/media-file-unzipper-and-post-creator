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
    <form action="/wp-admin/admin.php?page=history_upload_wills" method="post" enctype="multipart/form-data" class="server-form">

      <p>
        <input type="file" name="fileToUpload" id="fileToUpload">
      </p>

      <p class="submit">
        <input type="submit" class="button button-primary" value="Upload and Create Posts" name="submit">
			</p>

    </form>
  </div>
<?php }
