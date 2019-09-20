<?php

   # Created: 15 september 2019 9:15 pm
   # Author: Jakub Milcarz
   # Note: Login view for /auth.php

echo '<h1>Login</h1>';
echo '<a href="./register">Register</a>';

?>

<div id="login-page">
   <div id="errors-box"></div>
   <div id="form-box">
      <form id="login-form-box" method="post">
         <input type="text" id="input-login" placeholder="username or email address">
         <input type="password" id="input-password" placeholder="password">
         <button type="submit" name="loginbtn">login</button>
      </form>
      <a href="#" data-location="forgot-password" style="margin-left: 155px">forgot password</a>
   </div>
</div>

<script>
   $("#login-form-box").on('submit', function(e) {
      e.preventDefault();

      $.ajax({
         url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php",
         type: 'POST',
         data: { action: 1, login: $('#input-login').val(), password: $('#input-password').val() },
         success: function(r) {
            if (r == true) {
               location.reload();
            } else {
               $('#input-password').val("");
               $("#errors-box").html(r);
            }

         }
      })
   })
   $('[data-location]').click(function(e) {
      e.preventDefault();
      let button = $(this).attr('data-location');
      window.history.pushState(null, button.charAt(0).toUpperCase() + button.slice(1), button);
      document.title = 'SFS';
      $.ajax({
         url: "http://localhost/sfstrue/"+button,
         success: function(data) {
            $('#auth-container').html(data);
         }
      })
   });
</script>
