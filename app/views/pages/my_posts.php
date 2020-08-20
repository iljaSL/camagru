<?php 
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<div class="container">
        <div class="row text-center" style="display:flex; flex-wrap: wrap;">
        <?php foreach ($user_posts as $user_post): ?>
            <div  class="col-md-6 col-sm-12 mb-3">
                <div div_post="<?= $user_post->id_post?>" class="card">
                    <img src="<?= './app/assets/images/post_img/'.$user_post->photo_name; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <a id_post="<?= $user_post->id_post?>" class="delete delete_btn btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
</div>

<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/myPosts.js"></script>

