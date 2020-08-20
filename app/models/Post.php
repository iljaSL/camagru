<?php

require_once __DIR__.'/Database.php';

class Post {

    public static function insertPost($post) {
        $req = Database::getPDO()->prepare("  INSERT INTO posts (photo_name, creation_date, id_user) 
                                VALUES (:photo_name, :creation_date, :id_user)");
        $req->execute(array(
            "photo_name" => $post[0],
            "creation_date" => $post[1],
            "id_user" => $post[2]
        ));
    }

    public static function deleteLikesFromPost($id_post) {
        $req = Database::getPDO()->prepare("  DELETE FROM likes
                                WHERE id_post = :id_post");
        $req->execute(array( "id_post" => $id_post ));
    }

    public static function deleteCommentsFromPost($id_post) {
        $req = Database::getPDO()->prepare("  DELETE FROM comments
                                WHERE id_post = :id_post");
        $req->execute(array( "id_post" => $id_post ));
    }

    public static function deletePost($id_post, $id_user) {
        $req = Database::getPDO()->prepare("  DELETE FROM posts
                                WHERE id_post = :id_post");
        $req->execute(array( "id_post" => $id_post ));
    }

    public static function getAllPosts($id_user) {
        $req = Database::getPDO()->prepare('  SELECT posts.id_post, posts.photo_name, posts.id_user, likes_count, users.username, likes.id_user AS user_liked
                                        FROM posts
                                        LEFT JOIN (
                                            SELECT id_post, COUNT(*) AS likes_count
                                            FROM likes
                                            GROUP BY id_post
                                        ) likes_count ON likes_count.id_post = posts.id_post
                                        JOIN users ON posts.id_user = users.id_user
                                        LEFT JOIN likes ON posts.id_post = likes.id_post
                                        AND likes.id_user = :id_user');
        $req->execute(array( "id_user" => $id_user ));
        $data = $req->fetchAll(PDO::FETCH_CLASS, 'Post');
        return $data;
    }

    public static function getLastFivePosts($id_user) {
        $req = Database::getPDO()->prepare('SELECT posts.id_post,
        posts.photo_name,
        posts.id_user,
        likes_count,
        users.username,
        likes.id_user AS user_liked,
        (SELECT comments.comment
        FROM comments
        WHERE comments.id_post = posts.id_post
        ORDER BY comments.id_post DESC
        LIMIT 1) AS comment,
        (SELECT users.username FROM users
        JOIN comments ON comments.id_user = users.id_user
        WHERE comments.id_post = posts.id_post
        ORDER BY comments.id_post DESC LIMIT 1) AS commenter
        FROM posts
        LEFT JOIN (
            SELECT id_post, COUNT(*) AS likes_count
            FROM likes
            GROUP BY id_post
        ) likes_count ON likes_count.id_post = posts.id_post
        JOIN users ON posts.id_user = users.id_user
        LEFT JOIN likes ON posts.id_post = likes.id_post
        AND likes.id_user = :id_user
        ORDER BY posts.id_post DESC LIMIT 5');
        $req->execute(array( "id_user" => $id_user ));
        $data = $req->fetchAll(PDO::FETCH_CLASS, 'Post');
        return $data;
    }

    public static function getNextLastFivePosts($id_user, $offset) {
        $req = Database::getPDO()->prepare('SELECT posts.id_post,
        posts.photo_name,
        posts.id_user,
        likes_count,
        users.username,
        likes.id_user AS user_liked,
        (SELECT comments.comment
        FROM comments
        WHERE comments.id_post = posts.id_post
        ORDER BY comments.id_post DESC
        LIMIT 1) AS comment,
        (SELECT users.username FROM users
        JOIN comments ON comments.id_user = users.id_user
        WHERE comments.id_post = posts.id_post
        ORDER BY comments.id_post DESC LIMIT 1) AS commenter
        FROM posts
        LEFT JOIN (
            SELECT id_post, COUNT(*) AS likes_count
            FROM likes
            GROUP BY id_post
        ) likes_count ON likes_count.id_post = posts.id_post
        JOIN users ON posts.id_user = users.id_user
        LEFT JOIN likes ON posts.id_post = likes.id_post
        AND likes.id_user = :id_user
        ORDER BY posts.id_post DESC LIMIT :offset_posts , 5');
        $req->bindValue("id_user", $id_user, PDO::PARAM_INT);
        $req->bindValue("offset_posts", intval($offset), PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_CLASS, 'Post');
        return $data;
    }

    public static function getLastPostFromUser($id_user) {
        $req = Database::getPDO()->prepare('  SELECT posts.id_post, posts.photo_name
        FROM posts
        JOIN users ON posts.id_user = users.id_user
        WHERE posts.id_user = :id_user
        ORDER BY posts.id_post DESC LIMIT 1');
        $req->execute(array( "id_user" => $id_user ));
        $data = $req->fetch();
        return $data;
    }

    public static function getIdUserFromIdPost($id_post) {
        $req = Database::getPDO()->prepare('    SELECT id_user FROM posts
                                                WHERE id_post = :id_post');
        $req->execute(array( "id_post" => $id_post ));
        $data = $req->fetch();
        return $data;
    }

    public static function getUserPosts($username) {
        $req = Database::getPDO()->prepare('  SELECT posts.id_post, posts.photo_name, posts.id_user, likes_count, users.username
                                FROM posts
                                LEFT JOIN (
                                    SELECT id_post, COUNT(*) AS likes_count
                                    FROM likes
                                    GROUP BY id_post
                                ) likes_count ON likes_count.id_post = posts.id_post
                                JOIN users ON posts.id_user = users.id_user
                                WHERE users.username = :username');
        $req->execute(array( "username" => $username ));
        $data = $req->fetchAll(PDO::FETCH_CLASS, 'Post');
        return $data;
    }

    public static function getOnePost($id_post) {
        $req = Database::getPDO()->prepare('  SELECT posts.id_post, posts.photo_name, posts.id_user, likes_count, users.username
                                FROM posts
                                LEFT JOIN (
                                    SELECT id_post, COUNT(*) AS likes_count
                                    FROM likes
                                    GROUP BY id_post
                                ) likes_count ON likes_count.id_post = posts.id_post
                                JOIN users ON posts.id_user = users.id_user
                                WHERE posts.id_post = :id_post');
        $req->execute(array( "id_post" => $id_post ));
        $data = $req->fetch();
        return $data;
    }

    public static function isLikedBy($id_post, $id_user) {
        $req = Database::getPDO()->prepare('    SELECT id_user FROM likes
                                                WHERE id_user = :id_user
                                                AND id_post = :id_post');
        $req->execute(array(
            "id_post" => $id_post,
            "id_user" => $id_user
        ));
        $data = $req->fetch();
        return $data;
    }

}