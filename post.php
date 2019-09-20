<?php

# Created: 20 september 2019 10:10 pm
# Author: Jakub Milcarz


require('app/autoload.php');
session_start();

if (!isset($_GET['id'])) {
   header("Location: " . App::$APP_HOME_DIR);
   exit();
}

$postid = Security::check($_GET['id']);

$post = DB::query('SELECT posts_content, posts_likes, posts_comments, posts_shares, posts_timestamp, users_username, users_name, users_avatar FROM posts, users, followers WHERE posts_id = :postid AND id=posts_authorid AND (id=followers_followerid OR id=followers_userid)', [':postid' => $postid]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title id="PageTitle">Post</title>
</head>
<body view="<?php echo $view; ?>">
   <?php include('app/modules/nav.php'); ?>

   <div id="hn-page">
      <?php
         if (count($post) < 1) {
            echo '<h1 style="font-size: 70px;">You do not have<br>access to this post</h1>';
            echo '<a href="'.App::$APP_HOME_DIR.'">< go back</a>';
            exit();
         } else {
            $post = $post[0];
         }
      ?>
      <div>
         <h2><?php echo $post['users_name']; ?><br><small style="font-size: 13px"><?php echo $post['posts_timestamp']; ?></small></h2>
         <hr>
         <p id="post-content"><?php echo $post['posts_content']; ?></p>
         <hr>
      </div>
      <div>
         <h3>Comments</h3>
         <?php
            $comments = DB::query('SELECT comments_content, comments_likes, comments_timestamp, users_username, users_name, users_avatar FROM comments, users WHERE comments_post_id = :postid AND id = comments_authorid', [':postid' => $postid]);

            foreach ($comments as $comment) {
               echo '<p>'.$comment['users_name'].' ~ '.$comment['comments_content'].'</p>';
            }
         ?>
      </div>
   </div>
   <script>
      $(function() {
         let postBody = $("#post-content").text();
         let postTitle = '';
         if (postBody.length > 27) {
            postTitle = postBody.slice(0, 27) + '...';
         } else {
            postTitle = postBody;
         }

         $("#PageTitle").html(postTitle);
      })
   </script>

   <?php include('app/modules/footer.php'); ?>
