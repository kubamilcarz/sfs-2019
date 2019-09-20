<?php
   require('../autoload.php');
   session_start();

   if (isset($_POST['a'])) {
      if ($_POST['a'] == 'create') {
         $postid = $_POST['postid'];
         $body = $_POST['body'];
         $sender = $_POST['sender'];

         $commentsNumber = Comment::createNew($postid, $body, $_SESSION['userdata']['id']);

         $response = array();
         // $response[0] = '<input type="text" placeholder="Write comment..." id="commentPostInput-'.$postid.'" /><button type="submit" id="commentPostBtn-'.$postid.'"><i class="fa fa-angle-right"></i></button>';
         // $response[1] = $commentsNumber . ' Comments';

         $response = [
            'form' => '<input type="text" placeholder="Write comment..." id="commentPostInput-'.$postid.'" /><button type="submit" id="commentPostBtn-'.$postid.'"><i class="fa fa-angle-right"></i></button>',
            'counter' => '<i class="far fa-comment"></i> ' . $commentsNumber . ' Comments',
            'comment' => [
               'body' => $body,
               'author' => [
                  'name' => $_SESSION['userdata']['users_name'],
                  'username' => $_SESSION['userdata']['users_username'],
                  'avatar' => $_SESSION['userdata']['users_avatar']
               ]
            ]
         ];

         http_response_code(200);
         echo json_encode($response);
      }
   }

   if (isset($_GET['postid'])) {
      $postid = $_GET['postid'];
      $user = $_SESSION['userdata'];

      $comments = DB::query('SELECT * FROM comments WHERE comments_post_id=:postid ORDER BY comments_timestamp DESC', [':postid'=>$postid]);
      ?>

      <ul class="hn_post_com_box">
         <?php

            foreach ($comments as $comment) {
               // author info
                  $author = DB::query('SELECT users_name, users_username, users_avatar FROM users WHERE id = :id', [':id'=>$comment['comments_authorid']])[0];

               //profile img
                  if ($author['users_avatar'] == 'no-photo') {
                     $img = 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
                  } else {
                     $img =  App::$IMG_STORAGE . $author['users_avatar'];
                  }

               echo '<li>';
               echo '<img src="'.$img.'" alt="'.$author['users_name'].'\'s Profile Picture" />';
               echo '<div class="hn_post_com_body"><a href="'.App::PrintProfileURL($author['users_username'], 'home').'">'.$author['users_name'].'</a> '.$comment['comments_content'].'</div>';
               echo '</div></li>';
            }

         ?>

      </ul>
      <div class="hn_post_com_form"><img src="<?php echo $_SESSION['userdata']['users_avatar']; ?>"/>
         <form id="commentform-<?php echo $postid; ?>">
            <input type="text" placeholder="Write comment..." id="commentPostInput-<?php echo $postid; ?>" />
            <button type="submit" id="commentPostBtn-<?php echo $postid; ?>"><i class="fa fa-angle-right"></i></button>
         </form>
         <script>

            $('#commentform-<?php echo $postid; ?>').submit(function(e) {
               e.preventDefault();
               $.ajax({
                  url: 'http://localhost/sfstrue/app/api/comments.php',
                  type: 'POST',
                  data: { a: 'create', postid: <?php echo $postid; ?>, body: $('#commentPostInput-<?php echo $postid; ?>').val(), sender: <?php echo $user['id']; ?> },
                  success: function(data) {
                     console.log(data)
                     let res = JSON.parse(data)
                     $('#commentform-<?php echo $postid; ?>').html(res.form)
                     $('[data-comment-container=<?php echo $postid; ?>]').find('ul').append('<li><img src="#" /><div class="hn_post_com_body"><a href="<?php echo App::$APP_DIR; ?>/u/'+res.comment.author.username+'/home">'+res.comment.author.name+'</a> '+res.comment.body+'</div></li>');
                     $('#hn-post-<?php echo $postid; ?>-comment-btn').html(res.counter)

                  },
                  error: function(r) {
                     console.log(r)
                  }
               })
            })


         </script>
      </div>

      <?php
   }
