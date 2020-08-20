<?php
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<section class="section">
  <div class="container">
    <h1 class="title">New post</h1>
    <p class="title is-size-6">Don't have a webcam? Upload an image <a href="index.php?p=post_upload">here</a>.</p>
  </div>
</section>

<div class="container-fluid">
    <div class="row text-center">
        <div class="card">
            <video id="video" playsinline autoplay>
            </video>
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



<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/postWebcam.js"></script>
