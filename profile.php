<?php
require('app/autoload.php');
session_start();

if (!isset($_GET['u']) || !isset($_GET['view'])) {
   header("Location: " . App::$APP_HOME_DIR);
   exit();
}

$gotuser = Security::check($_GET['u']);
$view = Security::check($_GET['view']);

if (is_numeric($gotuser)) {
   $gotuser = (int)$gotuser;
   if (DB::query('SELECT id FROM users WHERE id = :userid', [':userid' => $gotuser])[0]['id']) {
      $profileUser = DB::query('SELECT * FROM users WHERE id = :userid', [':userid' => $gotuser])[0];
   }else {
      require('app/modules/error_pages/profile.php');
      exit();
   }
} else {
   if (DB::query('SELECT users_username FROM users WHERE users_username = :username', [':username' => $gotuser])[0]['users_username']) {
      $gotUserId = (int)DB::query('SELECT id FROM users WHERE users_username = :username', [':username' => $gotuser])[0]['id'];
      $profileUser = DB::query('SELECT * FROM users WHERE id = :userid', [':userid' => $gotUserId])[0];
   } else {
      require('app/modules/error_pages/profile.php');
      exit();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title><?php echo $profileUser['users_name']; ?> | <?php echo ucfirst($view); ?></title>
</head>
<body>
   <?php include('app/modules/nav.php'); ?>

   <div id="hn_page_left-column">
      <nav style="width: 70px">
         <a href="#" data-profile-location="home">home</a>
         <a href="#" data-profile-location="friends">friends</a>
         <a href="#" data-profile-location="followers">followers</a>
         <br>
      </nav>
   </div>
   <div class="hn_page_right-side-columns clearfix">
      <div id="hn_page_right-column"><?php include('app/modules/feed/right-sidebar.php'); ?></div>
      <div id="hn_page_centre-column">
         <div id="hn-profile-page"></div>
      </div>
      <div class="clearfix"></div>
   </div>

   <?php if ($_SESSION['userdata']) { ?>
      <script>
         $(function() {
            $.post( "http://localhost/sfstrue/app/api/friends.php", { APIload: true, APIUserId: <?php echo $profileUser['id']; ?>, APILoggedinId: <?php echo $_SESSION['userdata']['id']; ?> })
            .done(function( data ) {
               $('#friendActionsCTAS').html(data);

            });
         });

      <?php if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $_SESSION['userdata']['id'], ':friendid' => $profileUser['id']])[0]['friends_id']) { ?>
         $(function() {
            $.post( "http://localhost/sfstrue/app/api/follow.php", { APIload: true, APIUserId: <?php echo $_SESSION['userdata']['id']; ?>, APILoggedinId: <?php echo $_SESSION['userdata']['id']; ?> })
            .done(function( data ) {
               $('#friendFollowingsActionsCTAS').html(data);
            });
         });
      </script>
   <?php } else { echo '</script>'; ?>
      <script>
         setInterval(function() {
            $.ajax({
               url: "http://localhost/sfstrue/app/api/friends.php",
               type: 'POST',
               data: { APIload: true, APIUserId: <?php echo $profileUser['id']; ?>, APILoggedinId: <?php echo $_SESSION['userdata']['id']; ?> },
               success: function(data) {
                  $('#friendActionsCTAS').html(data);
               }
            })
         }, 15000);
      </script>
   <?php }} ?>
   <script>
      $(function() {
         $.ajax({
            url: "http://localhost/sfstrue/app/api/profile.php",
            type: 'POST',
            data: { userid: <?php echo $_SESSION['userdata']['id']; ?>, profileid: <?php echo $profileUser['id']; ?>, view: '<?php echo $view; ?>' },
            success: function(data) {
               $('#hn-profile-page').html(data);
            }
         })
      });


      $('[data-profile-location]').click(function(e) {
         e.preventDefault();
         let button = $(this).attr('data-profile-location');
         window.history.pushState(null, '<?php echo $profileUser['users_name']; ?> | ' + button.charAt(0).toUpperCase() + button.slice(1), button);
         document.title = '<?php echo $profileUser['users_name']; ?> | ' + button.charAt(0).toUpperCase() + button.slice(1)
         $.ajax({
            url: "http://localhost/sfstrue/app/api/profile.php",
            type: 'POST',
            data: { userid: <?php echo $_SESSION['userdata']['id']; ?>, profileid: <?php echo $profileUser['id']; ?>, view: button },
            success: function(data) {
               $('#hn-profile-page').html(data);
            }
         })
         return false;
      });

   </script>

   <?php include('app/modules/footer.php'); ?>
