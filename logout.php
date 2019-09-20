<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();

function __autoload($class_name) {
     require_once('app/classes/' . $class_name . '.php');
}

if (Auth::loggedin()) {
   session_destroy();
   Auth::logout();
}
