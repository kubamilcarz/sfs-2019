<?php

$users = DB::query('SELECT followers_id, followers_followerid, users_username, users_name, users_avatar FROM followers, users WHERE followers_userid=:profileid AND id=followers_followerid', [':profileid'=>$profileUser['id']]);
?>
<h1 style="text-transform: capitalize"><?php echo $profileUser['users_name']; ?>'s followers (<?php echo count($users); ?>)</h1>
<hr>
<?php
foreach($users as $user) {
   if ($profileUser['id'] != $user['followers_id']) {
      echo '<div><a href="'. App::PrintProfileURL($user['users_username'], 'home') .'"><img src="" height="40" width="40" />'.$user['users_name'].'</a></div>';
   }
}

?>
