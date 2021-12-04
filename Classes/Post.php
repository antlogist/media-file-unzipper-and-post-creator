<?php

class Post {
  protected $title;
  protected $postContent;
  protected $postStatus;
  protected $postAuthor;

  public function __construct($title = '', $postContent = '', $postStatus = 'publish', $postAuthor = 1) {
    $this->title = $title;
    $this->postContent = $postContent;
    $this->postStatus = $postStatus;
    $this->postAuthor = $postAuthor;
  }
}
