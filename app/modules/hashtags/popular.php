<?php

# Created: 13 june 2019 2:32 pm
# Author: Jakub Milcarz
# Note: This file is an include for hashtag.php

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>SFS</title>
   <script src="<?php echo App::$APP_DIR; ?>/assets/js/jquery.js"></script>
   <link rel="stylesheet" href="<?php echo App::$APP_DIR; ?>/assets/css/style.css">
</head>
<body onload="loadTags()">
   <?php include('app/modules/nav.php'); ?>
   <div id="hn_page_left-column"><?php include('app/modules/feed/left-sidebar.php'); ?></div>
   <div class="hn_page_right-side-columns clearfix">
      <div id="hn_page_right-column"><?php include('app/modules/feed/right-sidebar.php'); ?></div>
      <div id="hn_page_centre-column">
         <div class="hn_posts" style="background: #fff">
            <header style="padding: 10px">
               <h1 style="font-weight: light; font-size: 22px; margin: 0; padding-bottom: 10px; border-bottom: 1px solid var(--hndark)">Explore Tags</h1>
            </header>
            <div id="hn_posts"></div>
            <div id="hn-posts-loader"></div>
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
   <?php include('app/modules/footer.php'); ?>

   <script>
      var start = 5;
      var working = false;
      var counter = 5; // to check if all posts have loaded
      var lastcount = 0;
      function loadTags() {
         $("#hn-posts-loader").html('');
         $.ajax({
            type: "GET",
            url: "http://localhost/sfstrue/app/api/tags.php?start=0",
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(data) {
               let tags = JSON.parse(data)
               $.each(tags, function(index) {
                  $('#hn_posts').append('<div class="hn_post" style="box-sizing: border-box; padding: 10px 15px;"><a href="<?php echo App::$APP_DIR; ?>/tag/'+tags[index].TagName+'">'+tags[index].TagName+'</a><span style="float:right;">'+tags[index].TagPopularity+'</span><div class="clearfix"></div></div>')
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
               url: "http://localhost/sfstrue/app/api/tags.php?start="+start,
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
                  lastcount = $('#hn_posts > div').length;
                  let tags = JSON.parse(data)
                  $.each(tags, function(index) {
                     $('#hn_posts').append('<div class="hn_post" style="box-sizing: border-box; padding: 10px 15px;"><a href="<?php echo App::$APP_DIR; ?>/tag/'+tags[index].TagName+'">'+tags[index].TagName+'</a><span style="float:right;">'+tags[index].TagPopularity+'</span><div class="clearfix"></div></div>')
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
