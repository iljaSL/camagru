<?php
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<div class="container text-center">

    <h1 class="title">New post</h1>
    <p class="title is-size-6">You have a webcam? Take a selfie <a href="index.php?p=post_webcam">here</a>.</p>


<div class="card text-center">
  <div class="card-body">
  <form method="post" action="index.php?p=post_upload" enctype="multipart/form-data">
    <label for="img"><span class="text-primary">Upload an image</span></label><br />
    <label for="img" class="text-warning">(PNG only | max. 1 Mb)</label><br />
          <div class="file has-name">
            <label class="file-label">
              <input class="file-input btn btn-outline-dark" type="file" name="img" id="img">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Choose a file…
                </span>
              </span>
              <span id="filename" class="file-name">
              </span>
            </label>
          </div>
          <input id="button_upload" class="btn btn-primary" type="submit" name="submit" value="Upload" />
  </form>
  </div>
</div>

<div class="container-fluid">
    <div class="row text-center">
        <div class="card">
        <figure class="image is-800x600">
                <img id="uploaded_img" src="<?= isset($filename) ? './app/assets/images/user_img/'.$filename : './app/assets/images/site/placeholder_img.png'?>" alt="">
            </figure>
            <div id="overlay"></div>
            <div class="card-body">
                <button id="button_snap" class="btn btn-primary">Snap</button>
            </div>
        </div>
        <div class="col-16 col-sm-5 mb-1">
            <div id="sticker_container" class="card-columns">
                <?php foreach ($stickers as $sticker): ?>
                <div class="card">
                    <img class="card-img-top" src="<?= './app/assets/images/stickers/'.$sticker.'.png'; ?>" alt="Card image cap">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
</div>

<div id="thumbnails_container" class="container text-center">
    <p id="last_posts_title" class="title is-size-5" ><span class="text-center">Last posts</span></p>
    <div class="row">
        <?php foreach ($posts as $post):
        if ($post->id_user === $_SESSION['id_user']) {?>
            <div class="post-thumb col-12 col-sm-12">
                <div div_post="<?= $post->id_post?>" class="card">
                    <img class="card-img-top" src="<?= './app/assets/images/post_img/'.$post->photo_name; ?>" alt="">
                    <div class="card-body">
                        <div class="card-title">
                            <a id_post="<?= $post->id_post?>" class="btn btn-danger text-center">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } endforeach; ?>
    </div>
</div>

</div>

<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/postUpload.js"></script>


