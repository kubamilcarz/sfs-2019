<?php
### this code will work only with /register.php ###

$_SESSION['completed_steps']['step2'] = false;

?>
<div id="errors-box"></div>
<div id="form-box">
   <form method="post" id="register-form-box" enctype="multipart/form-data">
      <div>
         <label for="username">username: </label>
         <?php

            $username = strtolower(strtr($_SESSION['firstname'], $normalize) . "." . strtr($_SESSION['lastname'], $normalize));
            $username = $username . rand(1, 100);
         ?>
         <input type="text" name="username" value="<?php echo $username; ?>" id="username">
         <p>Add your unique username to mention others or to be mentioned!</p>
      </div>
      <hr>
      <div>
         <label for="phone">phone number: </label>
         <input type="tel" name="phone" id="phone" phone="123456789">
         <p>Add phone number to receive sms codes to loggin faster! (You can always add phone number later)</p>
      </div>
      <hr>
      <div>
         <label for="bio">bio: </label>
         <textarea name="bio" id="bio" rows="5" cols="50" value=""></textarea>
         <p>Write something about yourself! (You can always add bio later)</p>
      </div>
      <hr>
         <div>
            <label for="avatar">profile avatar: </label>
         <input type="file" name="avatar" id="avatar" accept="image/*" data-type="image" onchange="return validateFile('avatar', 'avatar-preview')">
         <div id="avatar-preview" style="width: 100px; height: 100px"></div>
         <p>Add profile avatar to be recognized by your potential friends! (You can always add profile avatar later)</p>
      </div>
      <hr>
      <div>
         <label for="backgroundphoto">profile's background image: </label>
         <input type="file" name="backgroundphoto" id="backgroundphoto" accept="image/*" data-type="image" onchange="return validateFile('backgroundphoto', 'background-preview')">
         <p>Add profile's background image to decorate your profile! (You can always add profile's background photo later)</p>
         <div id="background-preview" style="width: 300px; height: 140px"></div>
      </div>
      <div><button type="submit" name="registerS2" id="registerS2">continue</button></div>
   </form>
</div>
<script type="text/javascript">
   $("#register-form-box").on('submit', function(e) {
      e.preventDefault();
      
      let username = $("#username").val();
      let phone = $("#phone").val();
      let bio = $("#bio").val();
      let avatar = $("#avatar").val();
      let backgroundphoto = $("#backgroundphoto").val();

      $.ajax({
         url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php?step=2&form=2",
         type: 'POST',
         data: { action: 2, step: 2, username: username, phone: phone, bio: bio, avatar: avatar, backgroundphoto: backgroundphoto },
         success: function(r) {
            r = JSON.parse(r)
            if (r.type == "error") {
               $("#errors-box").html(r.message);
            } else if (r.type == "success") {
               $("#errors-box").html(r.message);
               $.ajax({
                  url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php",
                  type: 'POST',
                  data: { action: 2, step: <?php echo $step; ?> },
                  success: function(r) {  
                     $("#register-page").html(r);
                     changePageTitle("Register | Step 3");
                  }
               })
            } 
         }
      })

   })
</script>
