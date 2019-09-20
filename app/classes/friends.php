<?php

class Friends
{

   public static function sendFriendRequest($senderid, $receiverid, $message) {
      if (!is_numeric($senderid) || !is_numeric($receiverid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      }
      if (!DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $senderid, ':friendid' => $receiverid])[0]['friends_id']) {
         if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $senderid,':receiverid' => $receiverid])[0]['friendr_id']) {
            echo 'That request already exists! You can accept it or resend it.';
            return false;
         } else if (strlen($message) < 3 || strlen($message) > 32) {
            echo 'Invalid message. min: 3, max: 32';
            return false;
         }
      } else {
         echo "you can't resend requests. You already have this user in you friends list.";
         return false;
      }

      $bdate = date('Y-m-d H:i:s');

      DB::query('INSERT INTO friend_requests VALUES (\'\', :senderid, :receiverid, :dos, :message)', [':senderid' => $senderid, ':receiverid' => $receiverid, ':dos' => $bdate, ':message' => $message]);
      ## echo ';
      return true;
   }

   public static function cancelFriendRequest($requestid, $senderid, $receiverid) {
      if (!is_numeric($senderid) || !is_numeric($receiverid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      }
      if (!DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $senderid, ':friendid' => $receiverid])[0]['friends_id']) {
         if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $senderid,':receiverid' => $receiverid])[0]['friendr_id']) {
            DB::query('DELETE FROM friend_requests WHERE friendr_id = :friendrid AND (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':friendrid' => $requestid,':senderid' => $senderid,':receiverid' => $receiverid]);
            return true;
         } else {
            return false;
         }
      } else {
         echo "you can't cancel requests.";
         return false;
      }
   }

   public static function acceptFriendRequest($requestid, $senderid, $receiverid) {
      if (!is_numeric($senderid) || !is_numeric($receiverid)) {
         echo 'Senderid & receiverid must be intigers!';
         return false;
      }
      if (!DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $senderid, ':friendid' => $receiverid])[0]['friends_id']) {
         if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $senderid,':receiverid' => $receiverid])[0]['friendr_id']) {
            DB::query('DELETE FROM friend_requests WHERE friendr_id = :friendrid AND (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':friendrid' => $requestid,':senderid' => $senderid,':receiverid' => $receiverid]);
            $today = date('Y-m-d');
            $bdate = date('Y-m-d H:i:s');
            DB::query('INSERT INTO friends VALUES (\'\', :userid, :friendid, :since, :acceptdate)', [':userid' => $senderid, ':friendid' => $receiverid, ':since' => $today, ':acceptdate' => $bdate]);
            # '
            Follow::startFollowing($senderid, $receiverid, "1");
            Follow::startFollowing($receiverid, $senderid, "1");
            return true;
         } else {
            return false;
         }
      } else {
         echo "you can't accept request. You already have this user in you friend list.";
         return false;
      }
   }

   public static function removeFriend($friendid, $userid) {
      if (!is_numeric($friendid) || !is_numeric($userid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      }
      if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $userid, ':friendid' => $friendid])[0]['friends_id']) {
         DB::query('DELETE FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $userid, ':friendid' => $friendid]);
         Follow::stopFollowing($userid, $friendid);
         Follow::stopFollowing($friendid, $userid);
         return true;
      } else {
         echo 'you cannot delete';
         return false;
      }
   }

   public static function are($userid, $loggedinuser) {
      if ($userid == $loggedinuser) {
         return true;
      }
      if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) {
         return true;
      } else {
         return false;
      }
   }

   public static function displayFriendsCtas($userid, $loggedinuser) {
      # check if users aren't friends
      if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) {
         # users are friends so show option to delete from friends
         ?>
         <form method="post" id="REMOVEfriendForm">
            <button type="submit" name="removeFR" id="REMOVEfriendFormBtn">remove from friends</button>
         </form>
         <script>
            $('#REMOVEfriendForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               $.post( "http://localhost/sfstrue/app/api/friends.php", { REMOVEuserid: userid, REMOVEloggedUserid: loggedUserid })
               .done(function( data ) {
                  $('#friendActionsCTAS').html(data);
               });
            });
         </script>
         <div id="friendFollowingsActionsCTAS"></div>
         <script>
            <?php if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) { ?>
               $(function() {
                  $.post( "http://localhost/sfstrue/app/api/follow.php", { APIload: true, APIUserId: <?php echo $userid; ?>, APILoggedinId: <?php echo $loggedinuser; ?> })
                  .done(function( data ) {
                     $('#friendFollowingsActionsCTAS').html(data);
                  });
               });
            <?php } ?>
         </script>
      <?php } else { ?>

         <?php if (DB::query('SELECT friendr_id FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $loggedinuser, ':receiverid' => $userid])[0]['friendr_id']) { ?>
            <form method="post" id="CANCELfriendForm">
               <button type="submit" name="cancelFR" id="CANCELfriendFormBtn">cancel friend request</button>
            </form>
            <script>
               $('#CANCELfriendForm').submit(function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  $.post( "http://localhost/sfstrue/app/api/friends.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
                  .done(function( data ) {
                     $('#friendActionsCTAS').html(data);
                  });
               });
            </script>
         <?php } else if (!DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $loggedinuser, ':receiverid' => $userid])[0]['friendr_id']) { ?>
            <form method="post" id="SENDfriendForm">
               <input type="text" name="sendFRMessage" placeholder="message" id="SENDfriendFormMessage">
               <button type="submit" name="sendFR" id="SENDfriendFormBtn">add to friends</button>
            </form>
            <script>
               $('#SENDfriendForm').submit(function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  let message = $("#SENDfriendFormMessage").val();
                  $.post( "http://localhost/sfstrue/app/api/friends.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid, SENDMessage: message })
                  .done(function( data ) {
                     $('#friendActionsCTAS').html(data);
                  });
               });
            </script>
         <?php } # SEND ?>

         <?php if (DB::query('SELECT friendr_id FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $userid, ':receiverid' => $loggedinuser])[0]['friendr_id']) { ?>
            <p><?php
            $message = DB::query('SELECT friendr_message FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $userid, ':receiverid' => $loggedinuser])[0]['friendr_message'];
            echo DB::query('SELECT users_name FROM users WHERE id = :userid', [':userid'=>$userid])[0]['users_name'] . " has sent you a friend request! <br> " . $message;
            ?></p>
            <form method="post" id="ACCEPTfriendForm">
               <button type="submit" name="acceptFR" id="ACCEPTfriendFormBtn">accept</button>
            </form>
            <script>
               $('#ACCEPTfriendForm').submit(function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  $.post( "http://localhost/sfstrue/app/api/friends.php", { ACCEPTuserid: userid, ACCEPTloggedUserid: loggedUserid })
                  .done(function( data ) {
                     $('#friendActionsCTAS').html(data);
                  });
               });
            </script>
            <form method="post" id="REJECTfriendForm">
               <button type="submit" name="rejectFR" id="REJECTfriendFormBtn">reject friend request</button>
            </form>
            <script>
               $('#REJECTfriendForm').submit(function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  $.post( "http://localhost/sfstrue/app/api/friends.php", { REJECTuserid: userid, REJECTloggedUserid: loggedUserid })
                  .done(function( data ) {
                     $('#friendActionsCTAS').html(data);
                  });
               });
            </script>
         <?php # ACCEPT
         }
      }
   }

}
