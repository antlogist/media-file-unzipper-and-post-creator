<?php
require_once plugin_dir_path( __FILE__ ) . 'Post.php';

class PostPdf extends Post {
  protected $postType;
  protected $customPdf;
  protected $pdfFileUrl;
  protected $pathToFile;
  protected $postId = 0;
  protected $imgUrl;
  protected $thumbnailId;
  protected $ref = "";
  protected $heldby = "";
  protected $year = "";

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
        $this->customPdf  => $this->pdfFileUrl,
        "will_ref" => $this->ref,
        "will_heldby" => $this->heldby,
        "will_year" => $this->year
      )
    );

    $this->createThumbnail();

    $this->postId = wp_insert_post($postData);

    set_post_thumbnail($this->postId, $this->thumbnailId);

  }

  private function transformTitle() {

    $title;

    if(strpos($this->postTitle, "PROB-")) {
      $arr = explode("PROB-", $this->postTitle);
      $title = $arr[0];
      $ref = $arr[1];

      $this->ref = "PROB-" . $ref;

      $this->year = preg_replace("/[^0-9]/", '', $arr[0]);

      return $title;
    }
    return $this->postTitle;
  }

  private function transformIntoUrl() {
    $imgUrl = str_replace('[', '', $this->postTitle);
    $imgUrl = str_replace(']', '', $imgUrl);
    $imgUrl = str_replace('(', '', $imgUrl);
    $imgUrl = str_replace(')', '', $imgUrl);
    $imgUrl = str_replace('.', '-', $imgUrl);
    $imgUrl = str_replace('  ', '-', $imgUrl);
    $imgUrl = str_replace(' ', '-', $imgUrl);
    $imgUrl = $imgUrl . '-pdf-724x1024.jpg';
    $this->imgUrl = $imgUrl;
    return $this->imgUrl;
  }

  private function createThumbnail() {

    $im = new imagick($this->pathToFile);
    $im->clear();
    $im->destroy();

    //Attachment information
    $attachment = array(
      'guid'           => $this->transformIntoUrl(),
      'post_mime_type' => 'image/jpeg',
      'post_title'     => $this->transformTitle(),
      'post_content'   => '',
      'post_status'    => 'inherit'
    );

    //Insert the attachment.
    $this->thumbnailId = wp_insert_attachment( $attachment, "../wp-content/uploads" . wp_upload_dir()['subdir'] . '/' . $this->imgUrl);


    //Generate attachment metadata
    $imageData = wp_generate_attachment_metadata($this->thumbnailId,  "../wp-content/uploads" . wp_upload_dir()['subdir'] . '/' . $this->imgUrl);

    //Update metadata for an attachment.
    wp_update_attachment_metadata( $this->thumbnailId, $imageData );

  }

  public function createPost() {
    $this->insertPost();
    return $this->postId;
  }

}
