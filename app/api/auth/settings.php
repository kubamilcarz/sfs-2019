<?php

# Created: 11 june 2019 7:53 pm
# Author: Jakub Milcarz
# Note: Ajax controller for settings page (settings.php in the root folder)

require('../../autoload.php');
session_start();

# session variable storing all user data (IMPORTANT : including password for now)
if (!isset($_SESSION['userdata'])) {
   die('no user data. Please login again.');
}

$user = $_SESSION['userdata'];

if (isset($_POST['load']) && isset($_POST['userid'])) {
   if ($_POST['userid'] != $user['id']) {
      die("Ids do not match.");
   }
   ?>
   <div class="edit-form">
      <input type="hidden" token="">
      <input type="hidden" id="t" value="1">
      <input type="text" id="fname" placeholder="first name" value="<?php echo $user['users_firstname']; ?>">
      <input type="text" id="lname" placeholder="last name" value="<?php echo $user['users_lastname']; ?>">
      <br>
      <input type="text" id="uname" placeholder="<?php echo $user['users_username']; ?>" disabled>You cannot change your username
      <br>
      <input type="email" id="email" placeholder="email" value="<?php echo $user['users_email']; ?>">
      <br>
      <input type="text" id="phone" placeholder="phone" value="<?php echo $user['users_phone']; ?>">
      <br>
      <a href="<?php echo App::$APP_DIR; ?>/change-password.php">change your password</a><br>
      <button type="submit" name="submitchanges">save changes</button>
   </div>
   <script>
      $("#generateEditForm").submit(function(e) {
         e.preventDefault();
         let fname = $("#fname").val();
         let lname = $("#lname").val();
         let email = $("#email").val();
         let phone = $("#phone").val();
         $.ajax({
            url: '<?php echo App::$APP_DIR; ?>/app/api/auth/settings.php',
            type: 'POST',
            data: { update: true, load: true, fname: fname, lname: lname, email: email, phone: phone, t: $("#t").val() },
            success: function(data) {
               $('#generateEditForm').html(data);
            }
         });
      });
   </script>
   <?php
}

if (isset($_POST['update']) && isset($_POST['load'])) {
   $fname = Security::check($_POST['fname']);
   $lname = Security::check($_POST['lname']);
   $email = Security::check($_POST['email']);
   $phone = Security::check($_POST['phone']);
   $try = Security::check($_POST['t']);

   if ($try > 3) {
      echo 'You have clicked too much times `update` button.';
      exit();
   }

   # TODO: check values alogrithm

   # NOTE: for now just updating database
   DB::query('UPDATE users SET users_firstname = :fname, users_lastname = :lname, users_name = :name, users_email = :email, users_phone = :phone WHERE id = :id', [':id'=>$user['id'],':fname'=>ucfirst($fname),':lname'=>ucfirst($lname),':name'=>ucfirst($fname." ".$lname),':email'=>$email,':phone'=>$phone]);
   $_SESSION['userdata'] = DB::query('SELECT * FROM users WHERE id=:id', [':id'=>$user['id']])[0];
   ?>
   <div class="edit-form">
      <h3>Succesfully updated your account.</h3>
      <input type="hidden" id="t" value="<?php echo $try + 1; ?>">
      <input type="hidden" token="">
      <input type="text" id="fname" placeholder="first name" value="<?php echo $user['users_firstname']; ?>">
      <input type="text" id="lname" placeholder="last name" value="<?php echo $user['users_lastname']; ?>">
      <br>
      <input type="text" id="uname" placeholder="<?php echo $user['users_username']; ?>" disabled>You cannot change your username
      <br>
      <input type="email" id="email" placeholder="email" value="<?php echo $user['users_email']; ?>">
      <br>
      <input type="text" id="phone" placeholder="phone" value="<?php echo $user['users_phone']; ?>">
      <br>
      <a href="<?php echo App::$APP_DIR; ?>/change-password.php">change your password</a><br>
      <button type="submit" name="submitchanges">save changes</button>
   </div>
   <?php
}
