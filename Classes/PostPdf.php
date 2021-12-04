<?php

require_once plugin_dir_path( __FILE__ ) . 'Post.php';

class PostPdf extends Post {
  protected $customPDF;

  function __construct($postType = 'will', $customPDF = "custom_pdf") {
    parent::__construct();
    $this->postType = $postType;
    $this->customPDF = $customPDF;
  }

  private function insertPost() {

  }

  public function createPost() {

  }

}
