<?php

$users = DB::query('SELECT friends_id, friends_friendid, users_username, users_name, users_avatar, COUNT(*) FROM friends, users WHERE friends_userid=:profileid AND id=friends_friendid', [':profileid'=>$profileUser['id']]);
?>
<h1 style="text-transform: capitalize"><?php echo $profileUser['users_name']; ?>'s Friends (<?php echo count($users); ?>)</h1>
<hr>
<?php
foreach($users as $user) {
   echo '<div><a href="'.App::PrintProfileURL($u['users_username'], 'home').'"><img src="" height="40" width="40" />'.$user['users_name'].'</a></div>';
}

?>
