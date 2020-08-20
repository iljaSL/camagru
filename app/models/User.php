<?php

require_once __DIR__.'/Database.php';

class User {

    public static function insertUser($user) {
        $req = Database::getPDO()->prepare("  INSERT INTO users (username, email, pswd, verif_hash, confirmed, email_when_comment, creation_date)
                                VALUES (:username, :email, :pswd, :verif_hash, :confirmed, :email_when_comment, :creation_date)");
        $req->execute(array(
            "username" => $user[0],
            "email" => $user[1],
            "pswd" => $user[2],
            "verif_hash" => $user[3],
            "confirmed" => $user[4],
            "email_when_comment" => $user[5],
            "creation_date" => $user[6]
        ));
    }

    public static function userCredsOK($user_cred) {
        $username = $user_cred['username'];
        $password = $user_cred['password'];
        $req = Database::getPDO()->prepare("  SELECT pswd FROM users
                                WHERE username = :username");
        $req->execute( array('username' => $username) );
        $hashed_password = $req->fetch();
        if (password_verify($password, $hashed_password['pswd'])) {
            $req = Database::getPDO()->prepare("  SELECT id_user, username, confirmed, email, email_when_comment FROM users
                                    WHERE username = :username");
            $req->execute( array('username' => $username) );
            $data = $req->fetch();
            return $data;
        } else {
            return false;
        }
    }

    public static function usernameExists($username) {
        $req = Database::getPDO()->prepare("  SELECT * FROM users
                                WHERE username = :username");
        $req->execute( array( 'username' => $username ) );
        $data = $req->fetch();
        return $data;
    }

    public static function emailExists($email) {
        $req = Database::getPDO()->prepare("  SELECT verif_hash FROM users
                                WHERE email = :email");
        $req->execute( array( 'email' => $email ) );
        $data = $req->fetch();
        return $data;
    }

    public static function userConfirmed($email, $hash) {
        if (User::emailHashMatch($email, $hash)) {
            $req = Database::getPDO()->prepare("  UPDATE users SET confirmed = :confirmed
                                    WHERE email = :email");
            $req->execute( array(
                'confirmed' => '1',
                'email' => $email
            ));
            return true;
        } else {
            return false;
        }
    }

    public static function emailHashMatch($email, $hash) {
        $req = Database::getPDO()->prepare("  SELECT verif_hash FROM users
                                WHERE email = :email");
        $req->execute( array( 'email' => $email ) );
        $verif_hash = $req->fetch();
        return ($hash === $verif_hash['verif_hash']);
    }

    public static function pswdEmailMatch($pswd, $email) {
        $req = Database::getPDO()->prepare("  SELECT pswd FROM users
                                WHERE email = :email");
        $req->execute( array('email' => $email) );
        $hashed_pswd = $req->fetch();
        if (password_verify($pswd, $hashed_pswd['pswd'])) {
            $req = Database::getPDO()->prepare("  SELECT id_user, username, confirmed FROM users
                                    WHERE email = :email");
            $req->execute( array('email' => $email) );
            $data = $req->fetch();
            return $data;
        } else {
            return false;
        }
    }

    public static function pswdUsernameMatch($pswd, $username) {
        $req = Database::getPDO()->prepare("  SELECT pswd FROM users
                                WHERE username = :username");
        $req->execute( array('username' => $username) );
        $hashed_pswd = $req->fetch();
        if (password_verify($pswd, $hashed_pswd['pswd'])) {
            $req = Database::getPDO()->prepare("  SELECT id_user, username, confirmed FROM users
                                    WHERE username = :username");
            $req->execute( array('username' => $username) );
            $data = $req->fetch();
            return $data;
        } else {
            return false;
        }
    }

    public static function updatePswd($email, $new_pswd) {
        $req = Database::getPDO()->prepare("  UPDATE users SET pswd = :pswd
                                WHERE email = :email");
        $req->execute( array(
            'pswd' => $new_pswd,
            'email' => $email
        ));
    }


    public static function updateUsername($current_username, $new_username) {
        $req = Database::getPDO()->prepare("  UPDATE users SET username = :new_username
                                WHERE username = :current_username");
        $req->execute( array(
            'new_username' => $new_username,
            'current_username' => $current_username
        ));
    }

    public static function updateEmail($username, $new_email) {
        $req = Database::getPDO()->prepare("  UPDATE users SET email = :email
                                WHERE username = :username");
        $req->execute( array(
            'email' => $new_email,
            'username' => $username
        ));
    }

    public static function sendEmailWhenComment($id_user) {
        $req = Database::getPDO()->prepare("  SELECT username, email, email_when_comment FROM users
                                WHERE id_user = :id_user");
        $req->execute( array( 'id_user' => $id_user ) );
        $data = $req->fetch();
        return $data;
    }

    public static function updateEmailPref($username, $email_pref) {
        $req = Database::getPDO()->prepare("  UPDATE users SET email_when_comment = :email_when_comment
                                WHERE username = :username");
        $req->execute( array(
            'username' => $username,
            'email_when_comment' => $email_pref
        ));
    }
}