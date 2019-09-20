<?php require('app/autoload.php'); session_start();

if (!Auth::loggedin()) {
   header('Location: ' . App::$APP_HOME_DIR);
   exit();
}

if (!isset($_SESSION['userdata'])) {
   $_SESSION['userdata'] = DB::query('SELECT * FROM users WHERE id = :id', [':id' => Auth::loggedin()])[0];
}

if (!(isset($_GET['tag'])) || strlen($_GET['tag']) < 2) {
   include('app/modules/hashtags/popular.php');
   exit();
}

$tag = Security::check($_GET['tag']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>SFS</title>
   <script src="<?php echo App::$APP_DIR; ?>/assets/js/jquery.js"></script>
   <link rel="stylesheet" href="<?php echo App::$APP_DIR; ?>/assets/css/style.css">
</head>
<body onload="loadPosts()">
   <?php include('app/modules/nav.php'); ?>
   <div id="hn_page_left-column"><?php include('app/modules/feed/left-sidebar.php'); ?></div>
   <div class="hn_page_right-side-columns clearfix">
      <div id="hn_page_right-column"><?php include('app/modules/feed/right-sidebar.php'); ?></div>
      <div id="hn_page_centre-column">
         <div class="hn_posts" style="background: #fff">
            <header style="padding: 10px">
               <h1 style="font-weight: light; font-size: 22px; margin: 0; padding-bottom: 10px; border-bottom: 1px solid var(--hndark)">Tag: <span style="font-weight: bold; color: var(--hncolor);"><?php echo $tag; ?></span></h1>
            </header>
            <div id="hn_posts"></div>
            <div id="hn-posts-loader"></div>
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
   <?php include('app/modules/footer.php'); ?>

   <script>
      var start = 10;
      var working = false;
      var counter = 10; // to check if all tags have loaded
      var lastcount = 0;
      function loadPosts() {
         $("#hn-posts-loader").html('');
         $.ajax({
            type: "GET",
            url: "http://localhost/sfstrue/app/api/feed.php?action=tag&tag=<?php echo $tag; ?>&start=0",
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(data) {
               let posts = JSON.parse(data)
               $.each(posts, function(index) {
                  $('#hn_posts').append('<div class="hn_post"><div class="hn_post_header"><div class="hn_post_hd_ls"><img class="hn_post_hdls_img" src="'+posts[index].AuthorImg+'"/><div class="hn_post_hdls_info"><span class="hn_posthdlsi_n">'+posts[index].AuthorName+'</span><span class="hn_posthdlsi_d"><i class="far fa-clock"></i>'+posts[index].PostDate+'</span></div></div><div class="hn_post_hd_rs"><button class="hd_post_hdrs-d-t"></button><ul class="hd_post_hdrs-dd hide"><li><a href="#">Edit</a></li><li><button>Delete</button></li></ul></div></div><div class="hn_post_content"><p>'+posts[index].PostBody+'</p></div><div class="hn_post_interactions"><button class="hn_post-thumbs">'+posts[index].PostLikes+'</button><button class="hn_post_i_btn"><div class="like-box"><i class="far fa-thumbs-up"></i><span>Like </span></div></button><label class="hn_post_i_btn"><i class="far fa-comment"></i><span>Comment </span><button>'+posts[index].PostComments+'</button></label></div><div class="hn_post_comments"></div></div>')
               })
               scrollToAnchor(location.hash)
            },
            error: function(r) {
               console.log("Something went wrong!");
            }
         })
      }

      function scrollToAnchor(aid){
         try {
            var aTag = $(aid);
            $('html,body').animate({scrollTop: aTag.offset().top},'slow');
         } catch (error) {
            console.log(error)
         }
      }

      $(window).scroll(function() {
      if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
         if (working == false) {
            working = true;
            $.ajax({
               type: "GET",
               url: "http://localhost/sfstrue/app/api/feed.php?action=tag&tag=<?php echo $tag; ?>&start="+start,
               processData: false,
               contentType: "application/json",
               data: '',
               beforeSend: function() {
                  if (lastcount == counter) {
                     working = false;
                     $("#hn-posts-loader").html("<div style='height: 100%; display: flex; justify-content: center; flex-direction: column; align-items: center;'><span style='font-size: 30px; color: var(--hncolor);margin-bottom:5px;'>Congrats!</span><span>You've made it. There are no tags left.</span></div>");
                     return;
                  }else {
                     $("#hn-posts-loader").html('<div class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>');
                  }
               },
               success: function(data) {
                  lastcount = $('#hn_posts > div').length;
                  let posts = JSON.parse(data)
                  $.each(posts, function(index) {
                     $('#hn_posts').append('<div class="hn_post"><div class="hn_post_header"><div class="hn_post_hd_ls"><img class="hn_post_hdls_img" src="'+posts[index].AuthorImg+'"/><div class="hn_post_hdls_info"><span class="hn_posthdlsi_n">'+posts[index].AuthorName+'</span><span class="hn_posthdlsi_d"><i class="far fa-clock"></i>'+posts[index].PostDate+'</span></div></div><div class="hn_post_hd_rs"><button class="hd_post_hdrs-d-t"></button><ul class="hd_post_hdrs-dd hide"><li><a href="#">Edit</a></li><li><button>Delete</button></li></ul></div></div><div class="hn_post_content"><p>'+posts[index].PostBody+'</p></div><div class="hn_post_interactions"><button class="hn_post-thumbs">'+posts[index].PostLikes+'</button><button class="hn_post_i_btn"><div class="like-box"><i class="far fa-thumbs-up"></i><span>Like </span></div></button><label class="hn_post_i_btn"><i class="far fa-comment"></i><span>Comment </span><button>'+posts[index].PostComments+'</button></label></div><div class="hn_post_comments"></div></div>')
                  })

                  counter = $('#hn_posts > div').length;


                  scrollToAnchor(location.hash)
                  setTimeout(function() {
                     working = false;
                     $("#hn-posts-loader").html('');
                  }, 3000)
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
