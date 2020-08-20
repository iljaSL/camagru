<?php

require_once __DIR__.'/Database.php';

class Like {

    public static function createLike($id_post, $id_user) {
        $req = Database::getPDO()->prepare("  INSERT INTO likes (id_user, id_post)
                                VALUES (:id_user, :id_post)");
        $req->execute(array(
            "id_user" => $id_user,
            "id_post" => $id_post
        ));
    }

    public static function deleteLike($id_post, $id_user) {
        $req = Database::getPDO()->prepare("  DELETE FROM likes
                                WHERE id_user = :id_user
                                AND id_post = :id_post");
        $req->execute(array(
            "id_user" => $id_user,
            "id_post" => $id_post
        ));
    }

    public static function alreadyLiked($id_post, $id_user) {
        $req = Database::getPDO()->prepare("  SELECT id_user FROM likes
                                WHERE id_user = :id_user
                                AND id_post = :id_post");
        $req->execute(array(
            "id_user" => $id_user,
            "id_post" => $id_post
        ));
        $data = $req->fetch();
        return $data;
    }

}
