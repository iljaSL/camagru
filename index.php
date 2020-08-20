<?php
$title = 'home';
$valid_titles = array('signup', 'gallery', 'login', 'logout', 'post_webcam', 'post_upload', 'account', 'my_posts', 'view_post', 'confirmation', 'reset_password_email');
if (isset($_GET['p']) && in_array($_GET['p'], $valid_titles)) {
    $titles = array(
        'signup' => 'Signup',
        'gallery' => 'Gallery',
        'login' => 'Login',
        'logout' => 'Logout',
        'post_webcam' => 'New post',
        'post_upload' => 'New post',
        'account' => 'Account',
        'my_posts' => 'My posts',
        'view_post' => 'Post',
        'confirmation' => 'Account confirmation',
        'reset_password_email' => 'Reset my email'
    );
    $title = $titles[$_GET['p']];
    $css = 'app/assets/css/'.$_GET['p'].'.css';
} else {
    $title = 'Gallery';
}

require_once __DIR__.'/app/views/layouts/header.php';

require_once __DIR__.'/app/controllers/PostsController.php';
require_once __DIR__.'/app/controllers/UsersController.php';

if (isset($_GET['p'])) {
    if ($_GET['p'] === 'please_login')
        please_login();
    else if ($_GET['p'] === 'signup')
        view_signup();
    else if ($_GET['p'] === 'gallery')
        view_gallery();
    else if ($_GET['p'] === 'login')
        view_login();
    else if ($_GET['p'] === 'logout')
        view_logout();
    else if ($_GET['p'] === 'post_webcam')
        view_post_webcam();
    else if ($_GET['p'] === 'post_upload')
        view_post_upload();
    else if ($_GET['p'] === 'upload_img')
        upload_img();
    else if ($_GET['p'] === 'account')
        view_account();
    else if ($_GET['p'] === 'my_posts')
        view_my_posts();
    else if ($_GET['p'] === 'view_post')
        if (isset($_GET['id'])) {
            view_one_post($_GET['id']);
        } else {
            view_gallery();
        }
    else if ($_GET['p'] === 'confirmation')
        if (isset($_GET['email']) && isset($_GET['hash'])) {
            view_account_confirmation($_GET['email'], $_GET['hash']);
        } else {
            view_gallery();
        }
    else if ($_GET['p'] === 'reset_password_email')
        if (isset($_GET['email']) && isset($_GET['hash'])) {
            view_reset_password_email($_GET['email'], $_GET['hash']);
        } else {
            view_gallery();
        }
    else
        view_gallery();
} else {
    view_gallery();
}

require_once 'app/views/layouts/footer.php';