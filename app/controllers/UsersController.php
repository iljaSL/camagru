<?php

require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/check_token.php';

// Controlling the views

function please_login() {
    require_once __DIR__.'/../views/pages/please_login.php';
}

function view_signup() {
    require_once './app/views/pages/signup.php';
}

function view_login() {
    require_once './app/views/pages/login.php';
}

if (isset($_POST['action']) && $_POST['action'] === "login"
    && isset($_POST['username'])
    && isset($_POST['password'])
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        if ($user = User::userCredsOK($_POST)) {
            if ($user['confirmed'] === '1') {
                $_SESSION['username'] = $user['username'];
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['email_when_comment'] = $user['email_when_comment'];
            } else {
                http_response_code(400);
                echo "You need to confirm your registration first! Check your mail.";
            }
        } else {
            http_response_code(400);
            echo "Sorry wrong username or password";
        }
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
} 

function view_logout() {
    session_start();
    if (isset($_SESSION['username'])) {
        unset($_SESSION['username']);
    }
    if (isset($_SESSION['id_user'])) {
        unset($_SESSION['id_user']);
    }
    if (isset($_SESSION['email'])) {
        unset($_SESSION['email']);
    }
    if (isset($_SESSION['email_when_comment'])) {
        unset($_SESSION['email_when_comment']);
    }
    if (isset($_SESSION['token'])) {
        unset($_SESSION['token']);
    }
    session_destroy();
    header('Location: index.php');
}


function view_account() {
    if (isset($_SESSION['username'])) {
        require_once __DIR__.'/../views/pages/account.php';
    } else {
        require_once __DIR__.'/../views/pages/please_login.php';
    }
}


function view_account_confirmation($email, $hash) {
    if (User::userConfirmed($email, $hash)) {
        $_SESSION['user_confirmed'] = '1';
        echo '<div class="notification text-success"><div class="container"><p>Congratulations. You\'ve just confirmed your account <br> <b>Enter your username and password to login</b></p></div></div>';
    } else {
        echo '<div class="notification text-dark"><div class="container"><p>Sorry but the link you used to confirmed your email is out of touch :(</p></div></div>';
    }
    require_once './app/views/pages/login.php';
}


function view_reset_password_email($email, $hash) {
    if (User::emailHashMatch($email, $hash)) {
        $match = true;
    } else {
        $match = false;
        echo '<div class="notification text-dark"><div class="container"><p>Sorry but the link you used to confirmed your email is out of touch :(</p></div></div>';
    }
    require_once __DIR__.'/../views/pages/reset_password.php';
}

// CRUD is happening here

if (isset($_POST['action']) && $_POST['action'] === "signup"
    && isset($_POST['username'])
    && isset($_POST['email'])
    && isset($_POST['password'])
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        create_user();
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

function create_user() {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $errors = array();
    if (!preg_match("/^[A-Za-z0-9]{3,10}$/", $username)) {
        $errors['username_format'] = 'Please enter a username between 3 and 10 characters, containing only numbers and letters, no special characters allowed!';
    }
    if (User::usernameExists($username)) {
        $errors['username_or_email_exist'] = 'Ups! This username or this email already exists';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email_invalid'] = 'Please enter a valid email address';
    }
    if (User::emailExists($email)) {
        $errors['username_or_email_exist'] = 'Ups! This username or this email already exists';
    }
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $password)) {
        $errors['password_format'] = 'Please enter a password, it should contain at least 6 characters, at least one upper letter and one lower letter and one number';
    }
    if (count($errors) !== 0) {
        http_response_code(400);
        echo json_encode($errors);
        exit;
    }
    $pswd = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $verif_hash = md5(uniqid(rand(), true));
    $creation_date = date("Y-m-d H:i:s");
    $user_data = array($username, $email, $pswd, $verif_hash, '0', '1', $creation_date);
    $user = User::insertUser($user_data);
    $user_cred = array(
        "username" => $username,
        "pswd" => $pswd,
    );
    $subject = 'Welcome to Camagru!';
    $message = '

    Thanks for signing up!
    Your account has been created, you can login with the following credentials, BUT first you need to activated your account, click the link below.

    ------------------------
    Username: '.$user_data[0].'
    ------------------------

    Please click this link to activate your account:
    http://127.0.0.1:8080/camagru/index.php?p=confirmation&email='.$user_data[1].'&hash='.$user_data[3].'

    ';
    mail($user_data[1], $subject, $message);
}

function create_user_errors() {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['pswd'];
    $errors = array();
    if (!preg_match("/^[A-Za-z0-9]{3,10}$/", $username)) {
        $errors['username_format'] = 'Please enter a username between 3 and 10 characters containing only numbers and letters.';
    }
    if (User::usernameExists($username)) {
        $errors['username_exist'] = 'This username or this email already exists.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email_invalid'] = 'Please enter a proper email address.';
    }
    if (User::emailExists($email)) {
        $errors['email_exist'] = 'This username or this email already exists.';
    }
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $password)) {
        $errors['password_format'] = 'Please enter a password at least 6 characters long containing at least one upper letter, one lower letter and one number.';
    }
    if (count($errors) !== 0) {
        require_once './app/views/pages/signup.php';
        return true;
    } else {
        return false;
    }
}

// Update username

if (isset($_POST['action']) && $_POST['action'] === "update_username"
    && isset($_POST['newUsername'])
    && isset($_POST['pswd'])
    && isset($_POST['token'])) {
    session_start();
    $errors = array();
    if (check_token()) {
        $pswd = $_POST['pswd'];
        $new_username = htmlspecialchars($_POST['newUsername'], ENT_QUOTES, 'UTF-8');
        $current_username = $_SESSION['username'];
        if (!User::pswdUsernameMatch($pswd, $current_username)) {
            $errors['wrong_password'] = "Wrong password";
        }
        if (!preg_match("/^[A-Za-z0-9]{3,10}$/", $new_username)) {
            $errors['username_format'] = 'Please enter a username between 3 and 10 characters containing only numbers and letters';
        } else if (User::usernameExists($new_username)) {
            $errors['username_taken'] = 'This username is already taken';
        }
        if (count($errors) !== 0) {
            http_response_code(400);
            echo json_encode($errors);
            exit;
        }
        User::updateUsername($current_username, $new_username);
        $_SESSION['username'] = $new_username;
        http_response_code(200);
        echo "Username changed ✌️";
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

// Update email

if (isset($_POST['action']) && $_POST['action'] === "update_email"
    && isset($_POST['newEmail'])
    && isset($_POST['pswd'])
    && isset($_POST['token'])) {
    session_start();
    $errors = array();
    if (check_token()) {
        $pswd = $_POST['pswd'];
        $new_email = htmlspecialchars($_POST['newEmail'], ENT_QUOTES, 'UTF-8');
        $username = $_SESSION['username'];
        if (!User::pswdUsernameMatch($pswd, $username)) {
            $errors['wrong_password'] = "Wrong password";
        }
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_format'] = 'Please enter a proper email address.';
        } else if (User::emailExists($new_email)) {
            $errors['email_exist'] = 'This email already exists.';
        }
        if (count($errors) !== 0) {
            http_response_code(400);
            echo json_encode($errors);
            exit;
        }
        User::updateEmail($username, $new_email);
        $_SESSION['email'] = $new_email;
        echo "Email has been changed!";
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

// Update password

if (isset($_POST['action']) && $_POST['action'] === "update_password"
    && isset($_POST['newPassword'])
    && isset($_POST['currentPswd'])
    && isset($_POST['token'])) {
    session_start();
    $errors = array();
    if (check_token()) {
        $current_pswd = $_POST['currentPswd'];
        $new_pswd = $_POST['newPassword'];
        $hashed_pswd = password_hash($new_pswd, PASSWORD_BCRYPT);
        $email = $_SESSION['email'];
        $username = $_SESSION['username'];
        if (!User::pswdUsernameMatch($current_pswd, $username)) {
            $errors['wrong_password'] = "Wrong password";
        }
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $new_pswd)) {
            $errors['password_format'] = 'Please enter a password at least 6 characters long containing at least one upper letter, one lower letter and one number.';
        }
        if (count($errors) !== 0) {
            http_response_code(400);
            echo json_encode($errors);
            exit;
        }
        User::updatePswd($email, $hashed_pswd);
        echo "Password has been changed!";
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

// Update email preferences

if (isset($_POST['action']) && $_POST['action'] === "update_email_pref"
    && isset($_POST['email_pref'])
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        User::updateEmailPref($_SESSION['username'], $_POST['email_pref']);
        $_SESSION['email_when_comment'] = $_POST['email_pref'];
        echo "Email preferences updated ❤️";
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}

// Update password via reset password email

if (isset($_POST['action']) && $_POST['action'] === "reset_password"
    && isset($_POST['new_pswd'])
    && isset($_POST['email'])
    && isset($_POST['hash'])) {
    $new_pswd = $_POST['new_pswd'];
    $hashed_pswd = password_hash($new_pswd, PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $hash = $_POST['hash'];
    if (User::emailHashMatch($email, $hash)) {
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $new_pswd)) {
            http_response_code(400);
            echo 'Please enter a password at least 6 characters long containing at least one upper letter, one lower letter and one number';
        } else {
            User::updatePswd($email, $hashed_pswd);
            echo 'Password has been changed! You wanna <a href="index.php?p=login">login</a> ?';
        }
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }

}

// Send email to reset password

if (isset($_POST['action']) && $_POST['action'] === "reset_password_email"
    && isset($_POST['email'])
    && isset($_POST['token'])) {
    session_start();
    if (check_token()) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo 'Please enter a proper email address';
            exit;
        }
        if ($verif_hash = User::emailExists($email)) {
            $subject = 'Reset your password';
            $message = '
            Hi,
            We\'ve just received  a request to reset your password. If you didn\'t make the request, just ignore this email.
            Otherwise you can reset your password using this link:

            http://127.0.0.1:8080/camagru/index.php?p=reset_password_email&email='.$email.'&hash='.$verif_hash['verif_hash'].'

            Thanks,
            The Camagru Team
            ';
            // echo 'http://127.0.0.1:8080/index.php?p=reset_password_email&email='.$email.'&hash='.$verif_hash['verif_hash'];
            mail($email, $subject, $message);
        }
    } else {
        http_response_code(401);
        echo "User is not authenticated";
    }
}
