<?php
   require('../autoload.php');
   session_start();

      $user = $_SESSION['userdata'];

      $start = (int)$_GET['start'];

      if (isset($_GET['action'])) {
         $action = Security::check($_GET['action']);
         // changed this to 'action' to let showing posts on profile page
         if ($action == 'tag' && isset($_GET['tag'])) {
            $tag = Security::check($_GET['tag']);
            $posts = DB::query('SELECT posts.posts_id, posts.posts_authorid, posts.posts_content, posts.posts_likes, posts.posts_comments, posts.posts_timestamp, posts.posts_privacy, users_avatar, users.id AS users_id FROM posts, users, followers WHERE users.id = posts.posts_authorid AND posts.posts_privacy = 1 AND followers.followers_userid = :userid AND posts.posts_authorid = followers.followers_followerid AND posts.posts_topics LIKE CONCAT("%", :tag, "%") ORDER BY posts_timestamp DESC LIMIT 10 OFFSET '.$start, [':userid' => $user['id'], ':tag'=>$tag]);
         } else if ($action == 'profile' && isset($_GET['userid'])) {
            $profile_id = Security::check($_GET['userid']);
            $posts = DB::query('SELECT posts.posts_id, posts.posts_authorid, posts.posts_content, posts.posts_likes, posts.posts_comments, posts.posts_timestamp, posts.posts_privacy, users_avatar, users.id AS users_id FROM posts, users WHERE posts_authorid = :profileid AND id = :profileid ORDER BY posts_timestamp DESC LIMIT 5 OFFSET '.$start, [':profileid' => $profile_id]);
         }
      } else {
            $posts = DB::query('SELECT posts.posts_id, posts.posts_authorid, posts.posts_content, posts.posts_likes, posts.posts_comments, posts.posts_timestamp, posts.posts_privacy, users_avatar, users.id AS users_id FROM posts, users, followers WHERE users.id = posts.posts_authorid AND posts.posts_privacy = 1 AND followers.followers_userid = :userid AND posts.posts_authorid = followers.followers_followerid ORDER BY posts_timestamp DESC LIMIT 5 OFFSET '.$start, [':userid' => $user['id']]);
      }


      $response = array();
      foreach ($posts as $post) {
         // profile img
         if ($post['users_avatar'] == 'no-photo') {
            $img = 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
         } else {
            $img =  App::$IMG_STORAGE . $post['users_avatar'];
         }

         // author info
         $author = DB::query('SELECT users_name, users_username FROM users WHERE id = :id', [':id'=>$post['posts_authorid']])[0];

         // post likes
         if ($post['posts_likes'] == 1) {
            $likes = '1 like';
         } else {
            $likes = $post['posts_likes'] . ' likes';
         }

         // number of comments
         if ($post['posts_comments'] == 1) {
            $Ncomment = '1 Comment';
         } else {
            $Ncomment = $post['posts_comments'] . ' comments';
         }

         $row = [
            'PostId'=> $post['posts_id'],'PostBody'=>Post::link_add($post['posts_content']),
            'AuthorName'=> $author['users_name'], 'PostDate'=>$post['posts_timestamp'],
            'PostLikes'=> $likes, 'CommentsNumber'=>$Ncomment,
            'AuthorImg'=> $img, 'AuthorId'=>$post['users_id'],
            'ProfileURL' => App::PrintProfileURL($author['users_username'], 'home')
         ];
         array_push($response, $row);
      }

      ## NOTE: I think in this way it works! ^

      http_response_code(200);
      echo json_encode($response);

?>
