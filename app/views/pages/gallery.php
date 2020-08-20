<?php
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<header class="jumbotron" id="welcome">
    <div class="container">
        <h1 class="text-center">
            Welcome to Camagru!
        </h1>
        <p class="text-center">
            GET CREATIVE!
        </p>
        <?php if ($title !== 'New post') { ?>
            <p class="text-center">
                <a class="btn btn-primary btn-large" href="index.php?p=post_webcam">New post  <i class="fas fa-plus"></i></a>
            </p>
        <?php } ?>
    </div>
</header>

<div class="container">
    <div id="posts_container" class="row text-center" style="display:flex; flex-wrap: wrap;">
        <?php foreach ($posts as $post): ?>
        <div  class="col-md-6 col-sm-12 mb-3">
            <div offset="<?= $offset += 1?>" class="card border-dark">
            <img src="<?= './app/assets/images/post_img/'.$post->photo_name; ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <p class="card-title">
                    Posted by <strong><?= $post->username; ?></strong>
                <p>
                <a href="<?= 'index.php?p=view_post&id='. $post->id_post; ?>">
                    <i style="cursor: pointer" class="far fa-comment fa-lg"></i>
                </a>
                <?php if (isset($_SESSION['username']) && isset($_SESSION['id_user'])) { ?>
                    <span class="">
                        <i style="cursor: pointer" id_post="<?= $post->id_post; ?>" class="like_btn <?= $post->user_liked ? 'fas fa-heart fa-lg' : 'far fa-heart fa-lg'?>"></i>
                    </span>
                <?php } ?>
                <div style="display: inline-flex; vertical-align: top;">
                    <p style="padding-right: 5px;" id_post_show_likes="<?= $post->id_post; ?>"><?= $post->likes_count ? $post->likes_count : 0; ?></p>
                    <p class=""><?= $post->likes_count == '1' ? 'like' : 'likes'; ?></p>
                </div>
                <br>
                <a class="btn btn-secondary btn-sm" href="<?= 'index.php?p=view_post&id='.$post->id_post; ?>">View all comments</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>


<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/gallery.js"></script>