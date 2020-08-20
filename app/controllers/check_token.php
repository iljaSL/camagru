<?php

function check_token() {
    if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])) {
        return ($_SESSION['token'] == $_POST['token']);
    }
}