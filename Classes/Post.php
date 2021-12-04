<?php

class Post {
  protected $postTitle;
  protected $postContent;
  protected $postStatus;
  protected $postAuthor;

  public function __construct($postTitle = '', $postContent = '', $postStatus = 'publish', $postAuthor = 1) {
    $this->postTitle = $postTitle;
    $this->postContent = $postContent;
    $this->postStatus = $postStatus;
    $this->postAuthor = $postAuthor;
  }
}
