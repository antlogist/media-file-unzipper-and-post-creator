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
  add_menu_page('Upload Media Zip', 'Upload Media Zip','manage_options','history_upload_media_zips','history_upload_media_zips','dashicons-media-archive', 10);
}
