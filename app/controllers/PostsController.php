<?php

require_once __DIR__.'/../models/Post.php';
require_once __DIR__.'/../models/Comment.php';
require_once __DIR__.'/../models/Like.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/check_token.php';

// Controlling the views

function view_gallery() {
    $posts = Post::getLastFivePosts(isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0);
    $offset = 0;
    require_once __DIR__.'/../views/pages/gallery.php';
}

function view_one_post($id_post) {
    if (isset($_SESSION['username'])) {
        if ($post = Post::getOnePost($id_post)) {
            $user_liked = Post::isLikedBy($id_post, $_SESSION['id_user']) == true;
            $comments = Comment::getComments($id_post);
            require_once __DIR__.'/../views/pages/view_post.php';
        } else {
            header('Location: index.php');
        }
    } else {
        require_once __DIR__.'/../views/pages/please_login.php';
    }
}

function view_my_posts() {
    if (isset($_SESSION['username'])) {
        $user_posts = Post::getUserPosts($_SESSION['username']);
        require_once __DIR__.'/../views/pages/my_posts.php';
    } else {
        require_once __DIR__.'/../views/pages/please_login.php';
    }
}

function view_post_webcam() {
    if (isset($_SESSION['username'])) {
        $stickers = array('meme1', 'meme2', 'meme3', 'meme4', 'thug');
        $posts = Post::getUserPosts($_SESSION['username']);
        $posts = array_reverse(array_slice($posts, -6));
        require_once __DIR__.'/../views/pages/post_webcam.php';
    } else {
        require_once __DIR__.'/../views/pages/please_login.php';
    }
}

// Controlling CRUD

if (isset($_POST['action']) && $_POST['action'] === "get_next_five_posts"
    && isset($_POST['offset'])
    && isset($_POST['token'])) {
    session_start();
    $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    $posts = Post::getNextLastFivePosts($id_user, $_POST['offset']);
    $posts['connected'] = isset($_SESSION['username']) ? true : false;
    echo json_encode($posts);
}

if (isset($_POST['action']) && $_POST['action'] === "create_like"
    && isset($_POST['id_post'])
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        if (Post::getIdUserFromIdPost($_POST['id_post'])) {
            if (Like::alreadyLiked($_POST['id_post'], $_SESSION['id_user'])) {
                Like::deleteLike($_POST['id_post'], $_SESSION['id_user']);
                echo 'deleted';
            } else {
                Like::createLike($_POST['id_post'], $_SESSION['id_user']);
                echo 'created';
            }
        } else {
            http_response_code(400);
            echo "This picture does not exist";
        }
    }  else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'delete_post') {
    session_start();
    if (check_token()) {
        if (isset($_POST['id_post']) && isset($_SESSION['id_user'])) {
            if (Post::getIdUserFromIdPost($_POST['id_post'])['id_user'] === $_SESSION['id_user']) {
                Post::deleteLikesFromPost($_POST['id_post']);
                Post::deleteCommentsFromPost($_POST['id_post']);
                Post::deletePost($_POST['id_post'], $_SESSION['id_user']);
            } else {
                http_response_code(400);
                echo "You have not the permission to delete that";
            }
        }
    }  else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

if (isset($_POST['action']) && $_POST['action'] === "create_comment"
    && isset($_POST['comment'])
    && isset($_POST['id_post'])
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        if ($id_creator = Post::getIdUserFromIdPost($_POST['id_post'])) {
            if (empty($_POST['comment'])) {
                http_response_code(400);
                echo 'Comments can not be blank!';
                exit;
            }
            if (!preg_match("/^.{1,140}$/", $_POST['comment'])) {
                http_response_code(400);
                echo 'Max characters: 140';
                exit;
            }
            $creation_date = date("Y-m-d H:i:s");
            $comment_data = array(htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8'), $creation_date, $_SESSION['id_user'], $_POST['id_post']);
            $comment = Comment::insertComment($comment_data);
            $creator = User::sendEmailWhenComment($id_creator["id_user"]);
            if ($creator["email_when_comment"] === '1') {
                $subject = 'Someone commented you post.';
                $message = '

                Hi '.$creator['username'].',

                Someone just added a new comment on your post!
                Click on this link to check it out:

                http://127.0.0.1:8080/camagru/index.php?p=view_post&id='.$_POST['id_post'].'

                ';
                mail($creator['email'], $subject, $message);
            }
        } else {
            http_response_code(400);
            echo "This picture does not exist";
        }
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

if (isset($_POST['action']) && $_POST['action'] === "get_thumbnails"
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        $post = Post::getLastPostFromUser($_SESSION['id_user']);
        echo json_encode($post);
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}



function create_post($photo_path, $id_user) {
    $creation_date = date("Y-m-d H:i:s");
    $post_data = array($photo_path, $creation_date, $id_user);
    $post = Post::insertPost($post_data);
}







if (isset($_POST['action']) && $_POST['action'] === 'webcam_img_montage') {
    session_start();
    if (check_token()) {
        if( isset($_POST['img_data']) && isset($_POST['sticker_src']) && isset($_POST['placement_x']) && isset($_POST['placement_y']) ){
            echo $_POST['img_data'];
            $img = $_POST['img_data'];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $filedata = base64_decode($img);
            $filename = '../assets/images/post_img/img.png';
            file_put_contents($filename, $filedata);
            $img_src = "../assets/images/post_img/img.png";
            $sticker_src = "../assets/images/stickers/" . basename($_POST['sticker_src']);

            if (!file_exists($sticker_src)) {
                http_response_code(400);
                echo "This sticker does not exist";
                exit;
            }

            $img = imagecreatefrompng($img_src);
            $sticker = imagecreatefrompng($sticker_src);
            imagecolortransparent($sticker, imagecolorat($sticker, 0, 0));

            $sticker_x = imagesx($sticker);
            $sticker_y = imagesy($sticker);

            $placement_x = intval($_POST['placement_x']);
            $placement_y = intval($_POST['placement_y']);

            imagecopy($img, $sticker, $placement_x, $placement_y, 0, 0, $sticker_x, $sticker_y);

            $timestamp = time();
            $filename = $timestamp.'.png';
            $filepath = '../assets/images/post_img/'.$filename;
            imagepng($img, $filepath);

            create_post($filename, $_SESSION['id_user']);

            imagedestroy($img);
            imagedestroy($sticker);

        } else {
            print_r($_POST);;
        }
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }

}

function view_post_upload() {
    if (isset($_SESSION['username'])) {
        $stickers = array('meme1', 'meme2', 'meme3', 'meme4', 'thug');
        $posts = Post::getUserPosts($_SESSION['username']);
        $posts = array_reverse(array_slice($posts, -6));
        if (isset($_FILES['img'])) {
            $filename = upload_img();
        }
        require_once __DIR__.'/../views/pages/post_upload.php';
    } else {
        require_once __DIR__.'/../views/pages/please_login.php';
    }
}

function upload_img() {
    if (isset($_SESSION['username'])) {
        $maxsize = 1048576;
        if ($_FILES['img']['size'] > $maxsize) {
            echo '<div class="notification is-danger"><div class="container"><p>The file is too big (max. 1 Mb)</p></div></div>';
        } else {
            $valid_ext = array( 'png' );
            $file_ext = strtolower(  substr(  strrchr($_FILES['img']['name'], '.')  ,1)  );
            if (!in_array($file_ext, $valid_ext)) {
                echo '<div class="notification is-danger"><div class="container"><p>Wrong type of file (PNG only)</p></div></div>';
            } else {
                $timestamp = time();
                $filename = $timestamp.'.png';
                $path = __DIR__."/../assets/images/user_img/".$filename;
                $res = move_uploaded_file($_FILES['img']['tmp_name'], $path);

                if (mime_content_type($path) === 'image/png') {
                    function resize_imagepng($file, $w, $h) {
                        list($width, $height) = getimagesize($file);
                        $src = imagecreatefrompng($file);
                        $dst = imagecreatetruecolor($w, $h);
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
                        return $dst;
                    }
                    $img = resize_imagepng($path, 800, 600);
                    imagepng($img, $path);
                    return $filename;
                } else {
                    echo '<div class="notification text-danger"><div class="container"><p>Wrong type of file (PNG only)</p></div></div>';
                }
            }
        }
    } else {
        require_once __DIR__.'/../views/pages/please_login.php';
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'upload_img_montage') {
    session_start();
    if (check_token()) {
        if( isset($_POST['img_src'])
            && isset($_POST['sticker_src'])
            && isset($_POST['img_width'])
            && isset($_POST['img_height'])
            && isset($_POST['placement_x'])
            && isset($_POST['placement_y']) ) {

            if (!file_exists("../assets/images/user_img/" . basename($_POST['img_src']))
                && !file_exists("../assets/images/site/" . basename($_POST['img_src']))) {
                http_response_code(400);
                echo "This picture does not exist";
                exit;
            }

            $img_src = $_POST['img_src'];
            $sticker_src = "../assets/images/stickers/" . basename($_POST['sticker_src']);

            if (!file_exists($sticker_src)) {
                http_response_code(400);
                echo "This sticker does not exist";
                exit;
            }
            $img = imagecreatefrompng($img_src);

            $img_og_width = imagesx($img);
            $img_og_height = imagesy($img);

            $new_width = $_POST['img_width'];
            $new_height = $_POST['img_height'];
            $resized_img = imagecreatetruecolor($new_width, $new_height);

            imagecopyresampled($resized_img, $img, 0, 0, 0, 0, $new_width, $new_height, $img_og_width, $img_og_height);

            $sticker = imagecreatefrompng($sticker_src);
            imagecolortransparent($sticker, imagecolorat($sticker, 0, 0));

            $sticker_x = imagesx($sticker);
            $sticker_y = imagesy($sticker);

            $placement_x = intval($_POST['placement_x']);
            $placement_y = intval($_POST['placement_y']);

            imagecopy($resized_img, $sticker, $placement_x, $placement_y, 0, 0, $sticker_x, $sticker_y);

            $timestamp = time();
            $filename = $timestamp.'.png';
            $filepath = '../assets/images/post_img/'.$filename;
            imagepng($resized_img, $filepath);

            create_post($filename, $_SESSION['id_user']);

            imagedestroy($resized_img);
            imagedestroy($sticker);
        } else {
            echo '<div class="notification text-danger"><div class="container"><p>Something went wrong!</p></div></div>';
        }
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}
