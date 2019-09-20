<?php

   # Created: 15 september 2019 9:15 pm
   # Author: Jakub Milcarz
   # Note: Forgot Password view for /auth.php

echo '<h1>Forgot Password</h1>';

?>
<div id="forgot-password-page">
	<div id="errors-box"></div>
	<div id="form-box">
		<form id="forgot-form-box" method="post">
			<input type="email" name="email" id="email" placeholder="E-mail address">
      		<input type="submit" name="send" value="send email">
		</form>
	</div>
</div>
<script>
   $("#forgot-form-box").on('submit', function(e) {
      e.preventDefault();

      $.ajax({
         url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php",
         type: 'POST',
         data: { action: 3, email: $('#email').val() },
         success: function(r) {
				r = JSON.parse(r)
            if (r.type == "error") {
               $("#errors-box").html(r.message);
            } else if (r.type == "success") {
               $("#errors-box").html(r.message);
					$('#email').val("");
            }
         }
      })

   })
</script>
