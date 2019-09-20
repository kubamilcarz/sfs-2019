<?php

# Created: 11 june 2019 2:04 pm
# Author: Jakub Milcarz
# Note: This page accesible only for logged in users

require('app/autoload.php');
session_start();

if (!Auth::loggedin()) {
   header("Location: " . App::$APP_DIR);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>SFS</title>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body style="margin: 0">
   <?php include('app/modules/nav.php'); ?>
   <h1>Settings</h1>
   <form method="post" id="generateEditForm"></form>

   <script>
      $(function() {
         $.ajax({
            type:'POST',
            url:'<?php echo App::$APP_DIR; ?>/app/api/auth/settings.php',
            // TODO: Add security token
            data: { load: true, userid: <?php echo Auth::loggedin(); ?> },
            success:function(data) {
               // $('#userJSON').find("#uusername").text(data['username']);
               $('#generateEditForm').html(data);
            }
         });
      })
   </script>
</body>
</html>
