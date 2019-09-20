<?php
require('app/autoload.php');
session_start();

if (!isset($_SESSION['userdata'])) {
   echo 'Your data got lost. Re login to continue.';
   exit();
}

if (!isset($_GET['view'])) {
   header("Location: " . App::$APP_HOME_DIR);
   exit();
}

$view = Security::check($_GET['view']);

if (!in_array($view, Account::$views)) {
   echo '<h1>404</h1>';
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title id="PageTitle">Account | <?php echo ucfirst($view); ?></title>
</head>
<body view="<?php echo $view; ?>">
   <?php include('app/modules/nav.php'); ?>

   <nav style="width: 100px !important; padding-right: 15px">
      <ul style="list-style-type: none; padding: 0; margin: 0;">
         <?php foreach (Account::$views as $view) {
            echo '<li><a href="#" data-account-location="'.$view.'">'.$view.'</a></li>';
         } ?>
      </ul>
   </nav>
   <div id="hn-page"></div>

   <?php include('app/modules/footer.php'); ?>

   <script>
      $(function() {
         $.ajax({
            url: "http://localhost/sfstrue/app/api/account.php",
            type: 'POST',
            data: { view: $("body").attr("view") },
            success: function(data) {
               $('#hn-page').html(data);
            }
         })
      });


      $('[data-account-location]').click(function(e) {
         e.preventDefault();
         let button = $(this).attr('data-account-location');
         window.history.pushState(null, 'Account | '+button.charAt(0).toUpperCase() + button.slice(1), button);
         document.title = 'Account | ' + button.charAt(0).toUpperCase() + button.slice(1)
         $.ajax({
            url: "http://localhost/sfstrue/app/api/account.php",
            type: 'POST',
            data: { view: button },
            success: function(data) {
               $('#hn-page').html(data);
               $("body").attr("view", button);
            }
         })
         return false;
      });

   </script>
