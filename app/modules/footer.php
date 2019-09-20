 </div> <!-- hn container -->

   <!-- CHAT -->
   <div id="hn_page_chat">
     <div id="hn_page_chat_column">
       <ul class="hn_chattec_users"></ul>
       <div class="hn_chattec_search">
         <div class="hn_chattec_search_input_g">
           <button type="submit" id="hn_chattec_search_submit"><i class="fa fa-search"></i></button>
           <input type="search" placeholder="Search" id="hn_chattec_search_input"/>
         </div>
       </div>
     </div>
     <div id="hn_page_chatter">
       <div class="hn_chatter_bar">
         <div class="hn_chatter_mes">
           <button class="hn_chatter_user"><span class="hn_chatter_user_name">Paweł Popkiewicz</span></button>
           <div class="hn_chatter_box"></div>
         </div>
         <div class="hn_chatter_mes">
           <button class="hn_chatter_user"><span class="hn_chatter_user_name">Mateusz Bełz</span></button>
           <div class="hn_chatter_box"></div>
         </div>
         <div class="hn_chatter_mes">
           <button class="hn_chatter_user"><span class="hn_chatter_user_name">Janek Max Pietruszka</span></button>
           <div class="hn_chatter_box"></div>
         </div>
       </div>
     </div>
   </div>
   <!-- / CHAT -->

   </div> <!-- <div class="hn_page_has_chat" id="hn_page"> -->
   <script> <?php # USER DATA FETCHER AND LOADER ?>
      $(function() {
         $.ajax({
            type:'POST',
            url:'http://localhost/sfstrue/app/api/user.php',
            dataType: "json",
            data:{ APIload: true, APIUserId: <?php echo Auth::loggedin(); ?> },
            success:function(data) {
               if(data.status === true) {
                  <?php # IMPORTANT: remove id parser ?>
                  $('.hn_ajax_user-name').text(data['name']).attr('href', 'u/'+data['username']+'/home');
                  $('#hn_plcuis_nPosts').text(data['numberOfPosts'])
                  $('#hn_plcuis_nFollowers').text(data['numberOfFollowers'])
                  $('#userJSON').find("#uid").text(data['id']);
                  $('#userJSON').find("#uusername").text(data['username']);
               } else{
                  alert("User not found...");
               }
            }
         });
      })
   </script>
   <script src="<?php echo App::$APP_DIR; ?>/assets/js/functions.js"></script>
</body>
</html>
