<?php 
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<div class="container w-50">
    <h1>Post</h1>
    <div class="row" style="display:flex; flex-wrap: wrap;">
            <div id="posts_container" class="card border-0">
                <img class="card-img-top" src="<?= './app/assets/images/post_img/'.$post['photo_name']; ?>" alt="">
                <p class="card-title">
                    Posted by <strong><?= $post['username']; ?></strong>
                </p>
                <div class="card-body" id="comments_container">
                    <a id="comment_icon" href="<?= 'index.php?p=view_post&id='. $post->id_post; ?>">
                        <i style="cursor: pointer" class="far fa-comment fa-lg"></i>
                    </a>
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['id_user'])) { ?>
                        <span>
                        <i style="cursor: pointer" id="like_btn" id_post="<?= $post['id_post']; ?>" class="like_btn <?= $user_liked ? 'fas fa-heart fa-lg' : 'far fa-heart fa-lg'?>"></i>
                        </span>
                    <?php } ?>
                    <br>
                    <div style="display: inline-flex; vertical-align: top;">
                        <p style="padding-right: 5px;" id_post_show_likes="<?= $post['id_post']; ?>"><?= $post['likes_count'] ? $post['likes_count'] : 0; ?></p>
                        <p><?= $post['likes_count'] == '1' ? 'like' : 'likes'; ?></p>
                    </div>
                    <br>
                    <?php foreach ($comments as $comment): ?>
                    <div class="level-left">
                        <p><b><?= $comment['username']; ?>:</b>   <?= $comment['comment']; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <textarea class="form-control mb-3" id="comment_input" rows="4" cols="50" type="text" placeholder="Add a new comment"></textarea>
                <p class="control">
                    <a class="btn btn-warning" id="comment_btn" id_post="<?= $post['id_post']; ?>">
                        Comment
                    </a>
                </p>
            </div>
    </div>
</div>



<input type="hidden" name="token" id="token" value="<?= $token; ?>" />


<?php if (isset($_SESSION['username'])) { ?>
<script type="text/javascript">
    let currentUsername = "<?= $_SESSION['username']; ?>";
</script>
<script type="text/javascript" src="./app/assets/js/viewPost.js"></script>
<?php } ?>
