#!/usr/bin/php
<?php

require_once "database.php";

try {
 
    $DB_DSN = 'mysql:dbname=camagru;host=127.0.0.1';
    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $PDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $password = password_hash('password', PASSWORD_BCRYPT);
    $verif_hash = md5(uniqid(rand(), true));
    $req = $PDO->prepare("INSERT INTO users (username, email, pswd, verif_hash, confirmed, email_when_comment, creation_date) VALUES (:username, :email, :pswd, :verif_hash, :confirmed, :email_when_comment, :creation_date)");
    $users = array(
        array('boris', 'boris@gmail.com', $password, $verif_hash, '1', '0', '2019-04-01 00:00:00'),
        array('frankie', 'frankie@gmail.com', $password, $verif_hash, '0', '0', '2018-04-01 00:00:00'),
        array('lou', 'lou@gmail.com', $password, $verif_hash, '1', '0', '2017-04-01 00:00:00'),
        array('charlie', 'charlie@gmail.com', $password, $verif_hash, '1', '0', '2016-04-01 00:00:00')
    );
    foreach ($users as $user) {
        $req->execute(array(
            "username" => $user[0], 
            "email" => $user[1],
            "pswd" => $user[2],
            "verif_hash" => $user[3],
            "confirmed" => $user[4],
            "email_when_comment" => $user[5],
            "creation_date" => $user[6]
        ));
        print("User $user[0] created.\n");
    }

    $req = $PDO->prepare("INSERT INTO posts (photo_name, creation_date, id_user) VALUES (:photo_name, :creation_date, :id_user)");
    $posts = array(
        array('1590173855.png', '2019-03-01 00:00:00', '1'),
        array('1590173859.png', '2019-05-01 00:00:00', '2'),
        array('1590173985.png', '2019-01-01 00:00:00', '1'),
        array('1590173989.png', '2019-02-01 00:00:00', '3')
    );
    foreach ($posts as $post) {
        $req->execute(array(
                "photo_name" => $post[0],
                "creation_date" => $post[1],
                "id_user" => $post[2],
                ));
        print("Post with photo $post[0] created.\n");
    }

} catch (PDOException $e) {
    die("DB ERROR: ". $e->getMessage());
}