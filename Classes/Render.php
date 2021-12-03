<?php

class Render {
  static function form() {
    echo '
      <div class="wrap">
        <h1>Upload ZIP Archive with PDF files inside</h1>
        <form action="./admin.php?page=history_upload_wills" method="post" enctype="multipart/form-data" class="server-form">

          <p>
            <input type="file" name="fileToUpload" id="fileToUpload">
          </p>

          <p class="submit">
            <input type="submit" class="button button-primary" value="Upload and Create Posts" name="submit">
          </p>

        </form>
      </div>
    ';
  }
}
