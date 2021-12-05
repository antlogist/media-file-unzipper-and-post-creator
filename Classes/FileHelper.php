<?php

class FileHelper {
  static function isTitleUnique($title) {
    if(post_exists( $title )) {
      return false;
    }
    return true;
  }
}
