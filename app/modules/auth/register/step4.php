<?php
### this code will work only with /register.php ###

$_SESSION['step4_completed'] = false;

if (isset($_POST['procceedtostep5'])) {

   session_destroy();
   Auth::login($_SESSION['email'], $_SESSION['passwordnh']);
   header("Location: " . App::$APP_HOME_DIR);
}

?>
<div id="errors-box"></div>
<div id="form-box">
   <h2>People you might know.</h2>
   <div id="recommendBox"></div>
	<form method="post" id="register-form-box">
		<button type="submit" name="procceedtostep5">countinue to summary</button>
	</form>
</div>
<script type="text/javascript">
	$(function() {
		$.ajax({
			type: "POST",
			url: 'app/api/auth/recommend-users.php',
			success: function(data){
				$('#recommendBox').html(data);
			}
		});
	})

	$("#register-form-box").on('submit', function(e) {
		e.preventDefault();

		$.ajax({
			url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php?step=4&form=4",
			type: 'POST',
			data: { action: 2, step: 4 },
			success: function(r) {
				$("html").html(r);
			}
		})
   })
</script>