<?php

# Created: 10 september 2019 12:10 pm
# Author: Jakub Milcarz
# Note: View Controller for account.php page

if (isset($_POST['view'])) {
   require '../autoload.php';
   session_start();

    if (!isset($_SESSION['userdata'])) {
        echo 'no data';
    }

    $view = Security::check($_POST['view']);

    $views = Account::$views; # account views

    if (!in_array($view, $views)) {
        echo 'error';
    }

    foreach ($views as $v) {
        if ($view == $v) {
            include './../modules/account/' . $view . '.php';
        }
    }

}
