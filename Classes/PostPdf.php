<?php
require_once plugin_dir_path( __FILE__ ) . 'Post.php';

class PostPdf extends Post {
  protected $postType;
  protected $customPdf;
  protected $pdfFileUrl;
  protected $pathToFile;
  protected $postId = 0;

  function __construct($postTitle, $postContent, $postStatus, $postAuthor, $postType = 'will', $customPdf = 'custom_pdf', $pdfFileUrl = '', $pathToFile = '') {
    parent::__construct($postTitle, $postContent, $postStatus, $postAuthor);
    $this->postType = $postType;
    $this->customPdf = $customPdf;
    $this->pdfFileUrl = $pdfFileUrl;
    $this->pathToFile = $pathToFile;
  }

  private function insertPost() {
    $postData = array(
      'post_title'    => $this->transformTitle(),
      'post_type'     => $this->postType,
      'post_content'  => $this->postContent,
      'post_status'   => $this->postStatus,
      'post_author'   => $this->postAuthor,
      'meta_input'    => array(
        $this->customPdf  => $this->pdfFileUrl
      )
    );

    $this->createThumbnail();

    $this->postId = wp_insert_post($postData);

  }

  private function transformTitle() {

    $title;

    if(strpos($this->postTitle, "PROB-")) {
      $arr = explode("PROB-", $this->postTitle);
      $title = $arr[0];
      return $title;
    }
    return $this->postTitle;
  }

  private function createThumbnail() {

    $im = new imagick($this->pathToFile);
    $im->clear();
    $im->destroy();

  }

  public function createPost() {
    $this->insertPost();
    return $this->postId;
  }

}
