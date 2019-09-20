<?php

/**
 * type
 * 0 -> none
 * 1 -> user
 * 2 -> page
 * 3 -> group
 * 4 -> circle
 * 5 -> list
 * 6 -> place
 * ...
 */

class Follow {

   public static function startFollowing($userid, $followerid, $type) {
      if (!DB::query('SELECT followers_id FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid AND followers_type = :type', [':userid' => $userid, ':followerid' => $followerid, ':type' => $type])[0]['followers_id']) {
         $bdate = date('Y-m-d H:i:s');
         DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid, :type, :bdate)', [':userid' => $userid, ':followerid' => $followerid, ':type' => $type, ':bdate' => $bdate]);
         # '
         return true;
      } else {
         echo 'u can\'t follow';
         return false;
      }
   }

   public static function stopFollowing($userid, $followerid) {
      if (!DB::query('SELECT followers_id FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid AND followers_type = :type', [':userid' => $userid, ':followerid' => $followerid, ':type' => $type])[0]['followers_id']) {
         DB::query('DELETE FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid', ['userid' => $userid, ':followerid' => $followerid]);
         return true;
      }else {
         echo 'u can\'t unfollow';
         return false;
      }
   }

   public static function displayFollowersCTA($userid, $loggedinuser) {
      if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) { ?>
         <?php if (!DB::query('SELECT followers_id FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid AND followers_type = :type', [':userid' => $loggedinuser, ':followerid' => $userid, ':type' => 1])[0]['followers_id']) { ?>
            <form method="post" id="STARTFollowingForm">
               <button type="submit" id="STARTFollowingBtn">follow</button>
            </form>
            <script>
               $('#STARTFollowingForm').submit(function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  let data = {STARTuserid: userid, STARTloggedUserid: loggedUserid}
                  $.post( "http://localhost/sfstrue/app/api/follow.php", data)
                  .done(function( data ) {
                     $('#friendFollowingsActionsCTAS').html(data);
                  });
               });
            </script>
         <?php } else { ?>
            <form method="post" id="STOPFollowingForm">
               <button type="submit" id="STOPFollowingBtn">unfollow</button>
            </form>
            <script>
               $('#STOPFollowingForm').submit(function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  let data = {STOPuserid: userid, STOPloggedUserid: loggedUserid}
                  $.post( "http://localhost/sfstrue/app/api/follow.php", data)
                  .done(function( data ) {
                     $('#friendFollowingsActionsCTAS').html(data);
                  });
               });
            </script>
         <?php }
      }
   }

}
