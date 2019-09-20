<?php

# Created: 11 june 2019 9:45 am
# Author: Jakub Milcarz
# Note: This file works only while user is logged in to the site
# Update: 12 june 2019 - implement hn design
#         14 june 2019 - autocomplete search


?>
<div class="hide" id="hn_nav_notification-d-ol"></div>
<div class="hide" id="hn_nav_user-d-ol"></div>
<div class="hn_page_has_chat" id="hn_page">
   <div id="hn_page_nav">
     <div class="hn_page_container">
       <div class="hn_nav_left_side"><a class="hn_nav_logo" href="<?php echo App::$APP_DIR; ?>" style="text-decoration: none; color: #fff; margin: 2px 10px 0 0; padding: 0;">SFS</a>
         <div class="hn_nav_search">
           <form class="hn_nav_search_input_g" id="hn_nav_search_form" autocomplete="off">
             <input type="search" placeholder="Search" id="hn_nav_search_input" name="q" autocomplete="off" />
             <button type="submit" id="hn_nav_search_submit"><i class="fa fa-search"></i></button>
           </form>
           <div id="hn_nav_search_results">
             <p>Recent Searches</p>
             <ul class="hn_nsearch_result-list" id="hn_nsearch_result_list"></ul>
           </div>
         </div>
       </div>
       <div class="hn_nav_right_side"><a class="hn_nav_link" href="<?php echo App::$APP_DIR; ?>"><i class="fa fa-home"></i> Home</a>
         <div class="hn_nav_notification-d">
           <button class="hn_nav_link" id="hn_nav_notification-d-t"><i class="fa fa-bell"></i> Notifications</button>
           <ul class="hide" id="hn_nav_notifications_dd">
             <p>lorem lorem lorem lorem</p>
           </ul>
        </div><a class="hn_nav_link" href="<?php echo App::$APP_DIR; ?>/messages"><i class="fa fa-comments"></i> Messages</a>
         <div class="hn_nav_user-d">
           <button class="hn_nav_link" id="hn_nav_user-d-t"><img src="" /> <?php echo $_SESSION['userdata']['users_firstname']; ?></button>
           <ul class="hide" id="hn_nav_user_dd">
             <li><a href="<?php echo App::PrintProfileURL($_SESSION['userdata']['users_username'], 'home'); ?>"> <i class="far fa-user"></i>Profile</a></li>
             <li><a href="<?php echo App::$APP_DIR . "/explore";?>"> <i class="fa fa-globe-americas"></i>Explore</a></li>
             <li><a href="<?php echo App::$APP_DIR . "/feed";?>"> <i class="fa fa-bolt"></i>Feed</a></li>
             <li><a href="<?php echo App::$APP_DIR . "/messages";?>"> <i class="far fa-comment"></i>Messages</a></li>
             <li><a href="<?php echo App::$APP_DIR . "/categories";?>"> <i class="fa fa-grip-horizontal"></i>Categories</a></li>
             <li class="divider"></li>
             <li><a href="<?php echo App::$APP_DIR . "/account/settings";?>"> <i class="fa fa-cog"></i>Setting & Privacy</a></li>
             <li><a href="<?php echo App::$APP_DIR . "/account/activity-log";?>"> <i class="far fa-clipboard"></i>Activity Log</a></li>
             <li><a href="<?php echo App::$APP_DIR . "/honet/help"; ?>"> <i class="fa fa-question"></i>Help Centre</a></li>
             <li>
                <form action="" method="post">
                    <button name="logoutbtn" type="submit"><i class="fa fa-sign-out-alt"></i> Log Out</button>
                </form>
             </li>
             <li class="divider"></li>
             <li>
               <button id="hn_toggle_nightmode"> <i class="far fa-moon"></i>Night Mode</button>
             </li>
           </ul>
         </div>
       </div>
     </div>
   </div>
</div>
<div class="hn_page_has_chat" id="hn_page">
  <div class="hn_page_container" id="hn_page_container">
