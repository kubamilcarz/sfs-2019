<?php

   # Created: 15 september 2019 9:15 pm
   # Author: Jakub Milcarz
   # Note: View Controller for auth pages

   require('app/autoload.php');
   session_start();
   // session_destroy();

   if (Auth::loggedin() || !isset($_GET['view'])) {
      header('Location: ' . App::$APP_HOME_DIR);
      exit();
   }

   // special case for /register
   if ($_GET['view'] == "register") {
      if (!isset($_GET['step']) || !in_array($_GET['step'], [1, 2, 3, 4])) { # in_array compares which steps are valid
         header('Location: ' . App::$APP_DIR . '/register?step=1');
         exit();
      }
   }

   $view = Security::check($_GET['view']);

   $views = ['login', 'register', 'forgot-password'];

   if (!in_array($view, $views)) {
      header('Location: ' . App::$APP_HOME_DIR);
      exit();
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title id="PageTitle"><?php echo ucfirst($view); ?></title>
</head>
<body>
   <?php include('app/modules/nav-nl.php'); ?>

   <div class="hn_page_right-side-columns clearfix" id="auth-container">
      <?php include './app/modules/auth/' . $view . '.php'; ?>
      <div class="clearfix"></div>
   </div>


   <?php include('app/modules/footer-nl.php'); ?>
