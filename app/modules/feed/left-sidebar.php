<div class="hn_plc_user hn_plc-panel">
   <img class="hn_plcu_bg" src="<?php
      if ($_SESSION['userdata']['users_backgroundphoto'] == 'no-photo') {
      echo 'https://i.imgur.com/LTtOyNfg.png';
      } else {
      echo App::$APP_DIR . 'storage/pictures/%E2%81%A9' . $_SESSION['userdata']['users_backgroundphoto'];
      }
   ?>"/>
   <a href="<?php echo App::$APP_DIR . "/u/" . $_SESSION['userdata']['users_username'] . "/home"; ?>"><img class="hn_plcu_prof" src="<?php
      if ($_SESSION['userdata']['users_avatar'] == 'no-photo') {
      echo 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
      } else {
      echo App::$APP_DIR . 'storage/pictures/%E2%81%A9' . $_SESSION['userdata']['users_avatar'];
      }
   ?>"/></a>
   <div class="hn_plcu_info">
      <a href="<?php echo App::$APP_DIR . "/u/" . $_SESSION['userdata']['users_username'] . "/home"; ?>" style="text-decoration: none">
         <span class="hn_plcui_n hn_ajax_user-name"></span>
      </a>
      <div class="hn_plcui_statistics">
         <div>
            <span class="hn_plcuis_t">Posts</span><span class="hn_plcuis_n" id="hn_plcuis_nPosts"></span>
         </div>
         <div>
            <span class="hn_plcuis_t">Followers</span><span class="hn_plcuis_n" id="hn_plcuis_nFollowers"></span>
         </div>
      </div>
   </div>
</div>
<div class="hn_plc_menu hn_plc-panel">
   <span class="hn_plcp-t">Quick Menu</span>
   <ul class="hn_plcm_list">
      <li><a href="<?php echo App::$APP_DIR; ?>/feed"><i class="fa fa-bolt"></i>Feed</a></li>
      <li><a href="<?php echo App::$APP_DIR; ?>/explore"><i class="fa fa-globe-americas"></i>Explore</a></li>
      <li><a href="<?php echo App::$APP_DIR; ?>/messages"><i class="far fa-comment"></i>Messages</a></li>
      <li><a href="<?php echo App::$APP_DIR; ?>/categories"><i class="far fa-grip-horizontal"></i>Categories</a></li>
   </ul>
</div>
<div class="hn_plc_tags hn_plc-panel">
   <span class="hn_plcp-t">Popular today</span>
   <ul class="hn_plct_list"></ul>
   <a class="hn_plcp-link" href="<?php echo App::$APP_DIR; ?>/explore">Explore</a>
</div>
