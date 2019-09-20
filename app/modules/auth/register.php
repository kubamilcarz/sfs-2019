<?php

   # Created: 15 september 2019 9:15 pm
   # Author: Jakub Milcarz
   # Note: Register view for /auth.php
   # 		- works only with _GET var 'step' equal to [1, 2, 3, 4]
   
   // session_destroy();

   if (!isset($_SESSION['step'])) {
   		$_SESSION['step'] = 1;
   }

   // validate $_GET['step']
   $step = Security::check($_GET['step']);
   if (!is_numeric($step)) {
   	header('Location: ' . App::$APP_DIR);
   }

   // set session variables to normal
   if (!isset($_SESSION['completed_steps'])) {
     $_SESSION['completed_steps'] = array(
     	'step1' => false,
     	'step2' => false,
     	'step3' => false,
     	'step4' => false
     );
  }

  // check if url step and session step are the same
  if ($_SESSION['step'] != $step) {
  	header("Location: " . App::$APP_DIR . "/register?step=" . $_SESSION['step']);
  }

  // check which steps were completed
  if (!$_SESSION['completed_steps']['step1'] && !$_SESSION['completed_steps']['step2'] && !$_SESSION['completed_steps']['step3'] && !$_SESSION['completed_steps']['step4']) {
  	$_SESSION['step'] = 1;
  } else if ($_SESSION['completed_steps']['step1'] && !$_SESSION['completed_steps']['step2'] && !$_SESSION['completed_steps']['step3'] && !$_SESSION['completed_steps']['step4']) {
  	$_SESSION['step'] = 2;
  } else if ($_SESSION['completed_steps']['step1'] && $_SESSION['completed_steps']['step2'] && !$_SESSION['completed_steps']['step3'] && !$_SESSION['completed_steps']['step4']) {
  	$_SESSION['step'] = 3;
  } else if ($_SESSION['completed_steps']['step1'] && $_SESSION['completed_steps']['step2'] && $_SESSION['completed_steps']['step3'] && !$_SESSION['completed_steps']['step4']) {
  	$_SESSION['step'] = 4;
  } else if ($_SESSION['completed_steps']['step1'] && $_SESSION['completed_steps']['step2'] && $_SESSION['completed_steps']['step3'] && $_SESSION['completed_steps']['step4']) {
  	header("Location: " . App::$APP_DIR);
  }


echo '<h1>Register</h1>';
echo '<a href="./login">Login</a><br>';

echo 'Completed Steps: '; print_r($_SESSION['completed_steps']);
echo '<br>Actual Step: ' . $step;
?>

<style type="text/css">
	#register-page {
		padding-top: 30px;
	}
</style>

<div id="register-page">
   <div id="errors-box"></div>
   <div id="form-box"></div>
</div>
<script type="text/javascript">
	function load() {
		$.ajax({
			url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php",
			type: 'POST',
			data: { action: 2, step: <?php echo $step; ?> },
			success: function(r) {  
				$("#register-page").html(r);
			}
		})
	}
	load();	
</script>
