<?php require('app/autoload.php'); session_start();
if (!Auth::loggedin()) {
   ?>
   <!DOCTYPE html><html lang="en"><head><?php require('app/incs/head-metas.inc.php'); ?><title id="PageTitle">SFS</title></head><body><div id="app"></div><script>$(function() {$.ajax({url: "http://localhost/sfstrue/login",success: function(data) {$("#app").html(data);}});})</script></body></html>
   <?php
   exit();
}

if (!isset($_SESSION['userdata'])) {
   $_SESSION['userdata'] = DB::query('SELECT * FROM users WHERE id = :id', [':id' => Auth::loggedin()])[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title id="PageTitle">SFS</title>
</head>
<body onload="loadPosts()">
   <?php include('app/modules/nav.php'); ?>
   <div id="hn_page_left-column">
      <?php include('app/modules/feed/left-sidebar.php'); ?>
   </div>
   <div class="hn_page_right-side-columns clearfix">
      <div id="hn_page_right-column"><?php include('app/modules/feed/right-sidebar.php'); ?></div>
      <div id="hn_page_centre-column">
         <div id="user_create_new_post_container">
            <form method="post" id="user_create_new_post_form">
               <p>New Post: </p>
               <textarea name="user_create_new_post_textarea" id="user_create_new_post_textarea" cols="50" rows="5"></textarea>
               <br>
               <select name="user_create_new_post_privacy" id="user_create_new_post_privacy">
                  <option value="public">public</option>
                  <option value="friends">friends only</option>
                  <option value="private">private</option>
               </select>
               <button id="user_create_new_post_submit" name="user_create_new_post_submit" type="submit">post</button>
            </form>
            <script>
               $(function() {
                  $("#user_create_new_post_form").submit(function(e) {
                     e.preventDefault();
                     let content = $("#user_create_new_post_textarea").val();
                     let userid = <?php echo Auth::loggedin(); ?>;
                     let privacy = $("#user_create_new_post_privacy").val();
                     $.ajax({
                        url: "http://localhost/sfstrue/app/api/posts.php",
                        type: 'POST',
                        data: { APIload: true, NEWPOSTuserid: userid, NEWPOSTcontent: content, NEWPOSTprivacy: privacy},
                        success: function(data) {
                           $('#user_create_new_post_container').append(data);
                           $("#user_create_new_post_textarea").val('');
                        }
                     });
                  });
               });
            </script>
         </div>
         <div class="hn_posts"></div>
         <div id="hn-posts-loader"></div>
      </div>
      <div class="clearfix"></div>
   </div>

   <script>
      var start = 5;
      var working = false;
      var counter = 5; // to check if all posts have loaded
      var lastcount = 0;
      function loadPosts() {
         $("#hn-posts-loader").html('');
         $.ajax({
            type: "GET",
            url: "http://localhost/sfstrue/app/api/feed.php?start=0",
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(data) {
               let commentsloaded = false
               let posts = JSON.parse(data)
               $.each(posts, function(index) {
                  $('.hn_posts').append('<div class="hn_post"><div class="hn_post_header"><div class="hn_post_hd_ls"><a href="'+posts[index].ProfileURL+'" style="text-decoration: none;"><img class="hn_post_hdls_img" src="'+posts[index].AuthorImg+'"/></a><div class="hn_post_hdls_info"><a href="'+posts[index].ProfileURL+'" style="text-decoration: none; color: #5f5f5f"><span class="hn_posthdlsi_n">'+posts[index].AuthorName+'</span></a><span class="hn_posthdlsi_d"><i class="far fa-clock"></i>'+posts[index].PostDate+'</span></div></div><div class="hn_post_hd_rs"><button class="hd_post_hdrs-d-t"></button><ul class="hd_post_hdrs-dd hide"><li><a href="#">Edit</a></li><li><button>Delete</button></li></ul></div></div><div class="hn_post_content"><p>'+posts[index].PostBody+'</p></div><div class="hn_post_interactions"><button class="hn_post_i_btn" postid-like="'+posts[index].PostId+'"><i class="far fa-thumbs-up"></i><span>'+posts[index].PostLikes+'</span></button><label class="hn_post_i_btn" for="hn-post-'+posts[index].PostId+'-comment-btn"><button id="hn-post-'+posts[index].PostId+'-comment-btn" data-comment="'+posts[index].PostId+'"><i class="far fa-comment"></i> '+posts[index].CommentsNumber+'</button></label></div><div class="hn_post_comments" data-comment-container="'+posts[index].PostId+'"></div></div>')

                  // like post
                  $('[postid-like]').click(function() {
                     var buttonid = $(this).attr('postid-like');
                     $.ajax({
                        type: "POST",
                        url: "http://localhost/sfstrue/app/api/likes.php?id=" + $(this).attr('postid-like'),
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                           // console.log(r)
                           var res = JSON.parse(r)
                           $("[postid-like='"+buttonid+"']").html('<i class="far fa-thumbs-up"></i><span>'+res.Likes+'</span>')
                        },
                        error: function(r) {
                           console.log(r)
                        }
                     });
                  })

                  // show comments
                  if (!commentsloaded) {
                     $('[data-comment]').click(function() {
                        var commentBtnId = $(this).attr('data-comment');
                        var CommentURL = '';
                        CommentURL = 'http://localhost/sfstrue/app/api/comments.php?postid=' + $(this).attr('data-comment')

                        // console.log(CommentURL);

                        $.ajax({
                           url: CommentURL,
                           processData: false,
                           contentType: "application/json",
                           data: '',
                           success: function(commentSectionHTML) {
                              // console.log(commentSectionHTML)
                              $("[data-comment-container='"+commentBtnId+"']").html(commentSectionHTML)

                              commentsloaded = true
                           },
                           error: function(r) {
                              console.log(r)
                           }
                        })
                     })
                  }

               })

               scrollToAnchor(location.hash)
            },
            error: function(r) {
               console.log("Something went wrong!");
            },
            complete: function() {
               lastcount = $('.hn_posts > div').length;
               if (lastcount < 5) {
                  $("#hn-posts-loader").html("<div style='height: 100%; display: flex; justify-content: center; flex-direction: column; align-items: center;'><span style='font-size: 30px; color: var(--hncolor);margin-bottom:5px;'>Congrats!</span><span>You've made it. There are no posts left.</span></div>");
                  return 0;
               }
            }
         })
      }

      $(window).scroll(function() {
      if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
         if (working == false) {
            working = true;
            $.ajax({
               type: "GET",
               url: "http://localhost/sfstrue/app/api/feed.php?start="+start,
               processData: false,
               contentType: "application/json",
               data: '',
               beforeSend: function() {
                  if (lastcount == counter) {
                     working = false;
                     $("#hn-posts-loader").html("<div style='height: 100%; display: flex; justify-content: center; flex-direction: column; align-items: center;'><span style='font-size: 30px; color: var(--hncolor);margin-bottom:5px;'>Congrats!</span><span>You've made it. There are no posts left.</span></div>");
                     return;
                  }else {
                     $("#hn-posts-loader").html('<div class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>');
                  }
               },
               success: function(data) {
                  lastcount = $('.hn_posts > div').length;
                  if (lastcount < 5) {
                     working = true;
                     $("#hn-posts-loader").html("<div style='height: 100%; display: flex; justify-content: center; flex-direction: column; align-items: center;'><span style='font-size: 30px; color: var(--hncolor);margin-bottom:5px;'>Congrats!</span><span>You've made it. There are no posts left.</span></div>");
                     return 0;
                  }
                  let commentsloaded = false
                  let posts = JSON.parse(data)
                  $.each(posts, function(index) {
                     $('.hn_posts').append('<div class="hn_post"><div class="hn_post_header"><div class="hn_post_hd_ls"><a href="'+posts[index].ProfileURL+'" style="text-decoration: none;"><img class="hn_post_hdls_img" src="'+posts[index].AuthorImg+'"/></a><div class="hn_post_hdls_info"><a href="'+posts[index].ProfileURL+'" style="text-decoration: none; color: #5f5f5f"><span class="hn_posthdlsi_n">'+posts[index].AuthorName+'</span></a><span class="hn_posthdlsi_d"><i class="far fa-clock"></i>'+posts[index].PostDate+'</span></div></div><div class="hn_post_hd_rs"><button class="hd_post_hdrs-d-t"></button><ul class="hd_post_hdrs-dd hide"><li><a href="#">Edit</a></li><li><button>Delete</button></li></ul></div></div><div class="hn_post_content"><p>'+posts[index].PostBody+'</p></div><div class="hn_post_interactions"><button class="hn_post_i_btn" postid-like="'+posts[index].PostId+'"><i class="far fa-thumbs-up"></i><span>'+posts[index].PostLikes+'</span></button><label class="hn_post_i_btn" for="hn-post-'+posts[index].PostId+'-comment-btn"><button id="hn-post-'+posts[index].PostId+'-comment-btn" data-comment="'+posts[index].PostId+'"><i class="far fa-comment"></i> '+posts[index].CommentsNumber+'</button></label></div><div class="hn_post_comments" data-comment-container="'+posts[index].PostId+'"></div></div>')

                     // like post
                     $('[postid-like]').click(function() {
                        var buttonid = $(this).attr('postid-like');
                        $.ajax({
                           type: "POST",
                           url: "http://localhost/sfstrue/app/api/likes.php?id=" + $(this).attr('postid-like'),
                           processData: false,
                           contentType: "application/json",
                           data: '',
                           success: function(r) {
                              // console.log(r)
                              var res = JSON.parse(r)
                              $("[postid-like='"+buttonid+"']").html('<i class="far fa-thumbs-up"></i><span>'+res.Likes+'</span>')
                           },
                           error: function(r) {
                              console.log(r)
                           }
                        });
                     })

                     // show comments
                     if (!commentsloaded) {
                        $('[data-comment]').click(function() {
                           var commentBtnId = $(this).attr('data-comment');
                           var CommentURL = '';
                           CommentURL = 'http://localhost/sfstrue/app/api/comments.php?postid=' + $(this).attr('data-comment')

                           // console.log(CommentURL);

                           $.ajax({
                              url: CommentURL,
                              processData: false,
                              contentType: "application/json",
                              data: '',
                              success: function(commentSectionHTML) {
                                 // console.log(commentSectionHTML)
                                 $("[data-comment-container='"+commentBtnId+"']").html(commentSectionHTML)

                                 commentsloaded = true
                              },
                              error: function(r) {
                                 console.log(r)
                              }
                           })
                        })
                     }

                  })

                  counter = $('.hn_posts > div').length;


                  scrollToAnchor(location.hash)
                  setTimeout(function() {
                     working = false;
                     $("#hn-posts-loader").html('');
                  }, 1500)
                  start+=5;
               },
               error: function(r) {
                  console.log("Something went wrong!");
               }
            })
         }
      }
      })
   </script>

<?php include('app/modules/footer.php'); ?>
