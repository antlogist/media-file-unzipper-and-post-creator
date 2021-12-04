<?php

require_once plugin_dir_path( __FILE__ ) . 'Post.php';

class PostPdf extends Post {
  protected $postType;
  protected $customPdf;
  protected $pdfFileUrl;
  protected $postId = 0;

  function __construct($postTitle, $postContent, $postStatus, $postAuthor, $postType = 'will', $customPdf = 'custom_pdf', $pdfFileUrl = '') {
    parent::__construct($postTitle, $postContent, $postStatus, $postAuthor);
    $this->postType = $postType;
    $this->customPdf = $customPdf;
    $this->pdfFileUrl = $pdfFileUrl;
  }

  private function insertPost() {
    $postData = array(
      'post_title'    => $this->postTitle,
      'post_type'     => $this->postType,
      'post_content'  => $this->postContent,
      'post_status'   => $this->postStatus,
      'post_author'   => $this->postAuthor,
      'meta_input'    => array(
      $this->customPdf  => $this->pdfFileUrl
      )
    );

    $this->postId = wp_insert_post($postData);

  }

  public function createPost() {
    $this->insertPost();
    return $this->postId;
  }

}