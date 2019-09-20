<?php
require('../autoload.php');
session_start();

if (isset($_POST['APIload']) && isset($_POST['APIloadUserId'])) {
   $userid = Security::check($_POST['APIloadUserId']);
   Post::displayPosts($userid, 2);
}

if (isset($_POST['NEWPOSTuserid']) && isset($_POST['NEWPOSTcontent']) && isset($_POST['NEWPOSTprivacy'])) {
   $userid = Security::check($_POST['NEWPOSTuserid']);
   $content = Security::check($_POST['NEWPOSTcontent']);
   $privacy = Security::check($_POST['NEWPOSTprivacy']);
   $status = Post::createNew($userid, $content, $privacy);

   $author = DB::query('SELECT users_name, users_username FROM users WHERE id = :id', [':id'=>$userid])[0];

   if ($status == false) {
      exit();
   }
   ?>
   <div class="hn_posts">
   <div class="hn_post">
      <div class="hn_post_header">
         <div class="hn_post_hd_ls">
            <img class="hn_post_hdls_img" src="#"/>
            <div class="hn_post_hdls_info">
               <span class="hn_posthdlsi_n"><?php echo $author['users_name']; ?></span>
               <span class="hn_posthdlsi_d"><i class="far fa-clock"></i> <?php echo date('Y-m-d H:i:s'); ?></span>
            </div>
         </div>
         <div class="hn_post_hd_rs">
            <button class="hd_post_hdrs-d-t"></button>
            <ul class="hd_post_hdrs-dd" style="display: none">
               <li><a href="#">Edit</a></li>
               <li><button>Delete</button></li>
            </ul>
         </div>
      </div>
      <div class="hn_post_content"><p><?php echo Post::link_add($content); ?></p></div>
      <div class="hn_post_interactions">
         <button class="hn_post-thumbs"></button>
         <button class="hn_post_i_btn">
            <div class="like-box">
               <i class="far fa-thumbs-up"></i> 0 Likes
            </div>
         </button>
         <label class="hn_post_i_btn">
            <button><i class="far fa-comment"></i>0 Comments</button>
         </label>
      </div>
      <div class="hn_post_comments"></div>
   </div>
   </div>
   <?php

}
