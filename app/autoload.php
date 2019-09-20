<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function __autoload($class_name) {
     require_once('classes/' . $class_name . '.php');
}

if (Auth::loggedin()) {
   session_start();
}

if (isset($_POST['logoutbtn'])) {
   session_destroy();
   Auth::logout();
}
