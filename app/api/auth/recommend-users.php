<style> .users {display: flex;flex-wrap: wrap;} .user {width: 450px;background: #ecebec;display: flex;height: 120px;align-items: center;margin: 15px;box-sizing: border-box;padding: 15px;} .user img {height: 100px;width: 100px;object-fit: cover;}.user .user-info {display: flex;flex-direction: column;height: calc(100% - 10px);margin-left: 10px;}.user .user-info h1 {margin: 0;font-size: 22px;}.user .user-info h2 {margin: 5px 0 0 0;font-size: 16px;}.user .user-info p {margin: 0;font-size: 14px;margin-top: 10px;} </style>
<?php
### This code will only work with /register.php! ###
require('../../autoload.php');
session_start();

$users = DB::query('SELECT id, users_username, users_name, users_avatar, users_backgroundphoto FROM users WHERE users_email <> :email ORDER BY id LIMIT 20', [':email' => $_SESSION['email']]);
?><div class="users">
<?php foreach ($users as $user) { ?>
   <div class="user">
      <?php
      if ($user['users_avatar'] == 'no-photo') {
         echo '<img src="https://www.steakenbierrestaurant.nl/wp-content/uploads/2017/06/user_default.png" alt="">';
      }else {
         echo '<img src="http://localhost/storage/pictures/%E2%81%A9' . $user['users_avatar'] . '" alt="">';
      }
      ?>
      <div class="user-info">
         <h1><?php echo $user['users_name']; ?></h1>
         <h2>@<?php echo $user['users_username']; ?></h2>
      </div>
      <div class="cta" id= "ctaBoxId<?php echo $user['id']; ?>">
         <?php
            if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $_SESSION['userid'], ':receiverid' => $user['id']])[0]['friendr_id']) {
               ?>
               <form id="form<?php echo $user['id']; ?>">
                  <button type="submit" id="cancel<?php echo $userid; ?>">cancel friend request</button>
               </form>
               <script>
                  $('#form<?php echo $user['id']; ?>').on('submit', function(e) {
                     e.preventDefault();
                     let userid = <?php echo $user['id']; ?>;
                     let loggedUserid = <?php echo $_SESSION['userid']; ?>;
                     $.post( "./app/api/auth/friendRequestHandler.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
                     .done(function( data ) {
                        $('#ctaBoxId<?php echo $user['id']; ?>').html(data);
                     });
                  });
               </script>
               <?php
            } else {
         ?>
            <form id="form<?php echo $user['id']; ?>">
               <button type="submit" id="send<?php echo $user['id']; ?>">add to friends</button>
            </form>
            <script>
               $('#form<?php echo $user['id']; ?>').on('submit', function(e) {
                  e.preventDefault();
                  let userid = <?php echo $user['id']; ?>;
                  let loggedUserid = <?php echo $_SESSION['userid']; ?>;
                  $.post( "./app/api/auth/friendRequestHandler.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid })
                  .done(function( data ) {
                     $('#ctaBoxId<?php echo $user['id']; ?>').html(data);
                  });
               });
            </script>
         <?php } ?>
      </div>
   </div>
<?php } ?>
</div>
