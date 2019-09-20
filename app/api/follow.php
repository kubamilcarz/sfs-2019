<?php
require('../autoload.php');
if (isset($_POST['APIload'])) {
   $userid = $_POST['APIUserId'];
   $loggedinuser = $_POST['APILoggedinId'];

   Follow::displayFollowersCTA($userid, $loggedinuser);
}

if (isset($_POST['STARTuserid']) && isset($_POST['STARTloggedUserid'])) {
   $userid = $_POST['STARTuserid'];
   $loggedinuser = $_POST['STARTloggedUserid'];
   $type = '1';

   echo 'im here';

   $status = Follow::startFollowing($loggedinuser, $userid, $type);
   if ($status == false) {
      exit();
   }
   ?>
   <form method="post" id="STOPFollowingForm">
      <button type="submit" id="STOPFollowingBtn">unfollow</button>
   </form>
   <script>
      $('#STOPFollowingForm').submit(function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedinuser; ?>;
         $.post( "http://localhost/sfstrue/app/api/follow.php", { STOPuserid: userid, STOPloggedUserid: loggedUserid })
         .done(function( data ) {
            $('#friendFollowingsActionsCTAS').html(data);
         });
      });
   </script>
   <?php
}

if (isset($_POST['STOPuserid']) && isset($_POST['STOPloggedUserid'])) {
   $userid = $_POST['STOPuserid'];
   $loggedinuser = $_POST['STOPloggedUserid'];

   $status = Follow::stopFollowing($loggedinuser, $userid);
   if ($status == false) {
      exit();
   }
   ?>
   <form method="post" id="STARTFollowingForm">
      <button type="submit" id="STARTFollowingBtn">follow</button>
   </form>
   <script>
      $('#STARTFollowingForm').submit(function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedinuser; ?>;
         $.post( "http://localhost/sfstrue/app/api/follow.php", { STARTuserid: userid, STARTloggedUserid: loggedUserid })
         .done(function( data ) {
            $('#friendFollowingsActionsCTAS').html(data);
         });
      });
   </script>
   <?php
}
