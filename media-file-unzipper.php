<?php

/**
 * Plugin Name: Media File Unzipper And Post Creator
 * Description: WordPress plugin allows unzipping media files and creating posts.
 * Version: 2.0.0
 * Author: Anton Podlesnyy
 * Author URI: https://podlesnyy.ru
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if (!defined('MEDIA_FILE_UNZIPPER_DIRNAME')) {
  define('MEDIA_FILE_UNZIPPER_DIRNAME', plugin_basename(dirname(__FILE__)));
}

if (!defined('MEDIA_FILE_UNZIPPER')) {
  define('MEDIA_FILE_UNZIPPER', '2.0.0');
}

if (!class_exists('UnzipFile')) {

  require_once plugin_dir_path( __FILE__ ) . 'Classes/Render.php';
  require_once plugin_dir_path( __FILE__ ) . 'Classes/File.php';

  class UnzipFile {

    private $slug = 'add_pdf_zip';

    function __construct() {
      add_action('admin_menu', array(&$this, 'addMenuItem'));
    }

    public function addMenuItem() {
      add_menu_page('Upload PDF', 'Upload PDF','manage_options', $this->slug , array($this, 'pluginInit'),'dashicons-media-archive', 10);
    }

    public function pluginInit() {
      Render::form($this->slug);

      if(isset($_FILES['fileToUpload'])) {
        $this->unzipAndPost();
      }
    }

    private function unzipAndPost() {
      File::unzipAndPost();
    }

  }

  new UnzipFile();

}

