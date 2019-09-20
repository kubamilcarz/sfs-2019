<?php
   require('../autoload.php');
   session_start();

   $user = $_SESSION['userdata'];
   $postId = $_GET['id'];

   if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', [':postid'=>$postId, ':userid'=>$user['id']])) {
      DB::query('UPDATE posts SET posts_likes=posts_likes+1 WHERE posts_id=:postid', array(':postid'=>$postId));
      DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid, NOW())', array(':postid'=>$postId, ':userid'=>$user['id']));
      #'
   } else {
      DB::query('UPDATE posts SET posts_likes=posts_likes-1 WHERE posts_id=:postid', array(':postid'=>$postId));
      DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$user['id']));
   }

   $likes = DB::query('SELECT posts_likes FROM posts WHERE posts_id=:postid', array(':postid'=>$postId))[0]['posts_likes'];
   if ($likes == 1) {
      $likes = '1 like';
   } else {
      $likes = $likes . ' likes';
   }

   echo "{";
      echo '"Likes":';
         echo '"'.$likes.'"';
   echo "}";
