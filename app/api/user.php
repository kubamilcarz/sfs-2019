<?php
require('../autoload.php');
if (isset($_POST['APIload'])) {
   $dataReturn = array();
   $userid = $_POST['APIUserId'];

   $data = DB::query('SELECT id, users_name, users_username, users_data FROM users WHERE id = :id', [':id' => $userid])[0];

   // $dataReturn = ['info' => $data['users_data'], 'status' => true];

   $dataReturn['id'] = $data['id'];
   $dataReturn['name'] = $data['users_name'];
   $dataReturn['username'] = $data['users_username'];
   $dataReturn['info'] = $data['users_data'];
   $dataReturn['status'] = true;
   $dataReturn['numberOfPosts'] = DB::query('SELECT COUNT(*) FROM posts WHERE posts_authorid = :id', [':id'=>$_SESSION['userdata']['id']])[0][0];
   $dataReturn['numberOfFollowers'] = DB::query('SELECT COUNT(*) FROM followers WHERE followers_followerid = :id', [':id'=>$_SESSION['userdata']['id']])[0][0];


   // $json_string = json_decode($dataN, true); <- it produces an array
   echo json_encode($dataReturn);
}
