<?php

class Comment {

   public static function createNew($postid, $body, $sender) {

      DB::query('INSERT INTO comments VALUES (\'\', :postid, :authorid, :body, :likes, NOW())', [':postid' => $postid, ':body' => $body, ':likes'=> 0, ':authorid' => $sender]);
      #'
      DB::query('UPDATE posts SET posts_comments=posts_comments+1 WHERE posts_id=:postid', [':postid'=>$postid]);

      return DB::query('SELECT posts_comments FROM posts WHERE posts_id=:postid', [':postid'=>$postid])[0]['posts_comments'];
   }

}
