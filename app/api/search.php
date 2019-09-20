<?php
   if(isset($_GET['query'])) {
      require('../autoload.php');

      $query = Security::check($_GET['query']);

      $users = DB::query('SELECT users_name, users_avatar, users_username FROM users WHERE users_name = :query OR users_firstname LIKE :query OR users_lastname LIKE :query ORDER BY users_name DESC LIMIT 10', [':query' => $query]);
      if (count($users) < 1) {
         echo 'No result found :/';
      }
      // <a style="display: flex; align-items: center; border-bottom:1px solid #000; padding: 5px 10px; box-sizing: border-box;" href='<?php echo App::$APP_DIR;/profile/echo $u['users_username'];'>

      $response = array();
      foreach ($users as $u) {
         if ($u['users_avatar'] == 'no-photo') {
               $u['users_avatar'] = 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
            } else {
               $u['users_avatar'] = App::$IMG_STORAGE . $u['users_avatar'];
            }
         $row = ['UserName'=>$u['users_name'],'UserImg'=>$u['users_avatar'], 'UserPath'=>App::PrintProfileURL($u['users_username'], 'home')];
         array_push($response, $row);
      }

      http_response_code(200);
      echo json_encode($response);
   }
