<?php

# Created: 16 june 2019 1:19 pm
# Author: Jakub Milcarz
# Note: View Controller for profile.php page

if (isset($_POST['view']) && isset($_POST['userid']) && isset($_POST['profileid'])) {
   require '../autoload.php';
   session_start();

    if (!isset($_SESSION['userdata'])) {
        echo 'no data';
    }

    $view = Security::check($_POST['view']);
    $userid = Security::check($_POST['userid']);
    $profileid = Security::check($_POST['profileid']);
    $profileUser = DB::query('SELECT * FROM users WHERE id = :userid', [':userid' => $profileid])[0];

    $views = ['home', 'friends', 'followers', ' ', '%20', '']; # profile views

    if (!in_array($view, $views)) {
        echo 'error';
    }

    foreach ($views as $v) {
        if ($view == $v) {
            include './../modules/profile/' . $view . '.php';
        }
    }

}
