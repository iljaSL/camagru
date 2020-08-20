<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?=$css?>" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title><?= $title ?></title>
</head>
<body class="site">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="navbar">
        <a class="navbar-brand" href="#">Camagru<i class="fas fa-compress-arrows-alt"></i></a>
                <ul class="navbar-nav mr-auto" id="menu">
                    <li class="nav-item">
                        <?php if ($title !== 'Gallery') { ?>
                            <a class="button" href="index.php?p=gallery">Gallery<i class="fas fa-camera-retro"></i></a>
                        <?php } ?>
                        <?php if (isset($_SESSION['username'])) { ?>
                            <?php if ($title !== 'My posts') { ?>
                            <a class="button" href="index.php?p=my_posts">My posts <i class="fas fa-images"></i></a>
                            <?php } ?>
                            <?php if ($title !== 'Account') { ?>
                            <a class="button" href="index.php?p=account">Account <i class="fas fa-user"></i></a>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($title !== 'Login') { ?>
                            <a class="button" href="index.php?p=login">Login <i class="fas fa-sign-in-alt"></i></a>
                            <?php } ?>
                            <?php if ($title !== 'Signup') { ?>
                            <a class="button" href="index.php?p=signup">Signup <i class="fas fa-user-plus"></i></a>
                            <?php } ?>
                        <?php } ?>
                    </li>
                    <li>
                    <a class="nav-item">
                        <?php if (isset($_SESSION['username'])) { ?>
                            <?php if ($title !== 'Logout') { ?>
                                <a class="button text-danger" href="index.php?p=logout">Logout <i class="fas fa-sign-out-alt"></i></a>
                            <?php } ?>
                        <?php } ?>
                    <a>
                    </li>
                </ul>
    </nav>

    <main class="site-content">