<?php require('app/autoload.php'); session_start();

if (!Auth::loggedin()) {
   header('Location: ' . App::$APP_HOME_DIR);
   exit();
}

if (isset($_GET['q'])) {
   $query = Security::check($_GET['q']);
   if (strlen($query) < 2) {
      echo 'query is too short!';
      exit();
   }

   ?>
      <!DOCTYPE html>
      <html lang="en">
      <head>
         <?php require('app/incs/head-metas.inc.php'); ?>
         <title><?php echo $query; ?> - | search</title>
         <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

      </head>
      <body style="margin: 0">
         <?php include('app/modules/nav.php'); ?>
         <h1>Results for: <?php echo $query; ?></h1>
         <script>
            $('#search-form').submit(function(e) {
               e.preventDefault();
               let searchphrase = $("#search-input").val();
               $.ajax({
                  url: "http://localhost/sfstrue/app/api/search.php",
                  type: 'POST',
                  async: false,
                  cache: false,
                  timeout: 30000,
                  data: {query: searchphrase},
                  beforeSend: function() {
                     $('#results').html("Loading");
                  },
                  error: function() {
                     $('#results').html("Error");
                  },
                  success: function(data) {
                     $('#results').html(data);
                  }
               });
            });
         </script>
         <div id="results">
            <?php
            $users = DB::query("SELECT users.users_name, users.users_avatar, users.users_username FROM users WHERE users_name = :query OR (users_firstname LIKE CONCAT(:query, '%') OR users_lastname LIKE CONCAT(:query, '%')) ORDER BY users_name ASC", [':query' => $query]);
            foreach ($users as $user) {
            ?>
            <a href='profile/<?php echo $user['users_username']; ?>'>
               <img src="<?php
               if ($user['users_avatar'] == 'no-photo') {
                  echo 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
               } else {
                  echo 'http://localhost/facebook/storage/pictures/%E2%81%A9' . $user['users_avatar'];
               }
               ?>" alt="" width="50" height="50">
               <?php echo $user['users_name']; ?>
            </a><hr>
            <?php } ?>
         </div>
      </body>
      </html>
   <?php
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>Search</title>
</head>
<body style="margin: 0">
   <?php include('app/modules/nav.php'); ?>
   <h1>Search</h1>
   <form id="search-form">
      <input type="text" placeholder="Search" id="search-input">
      <button type="submit">-></button>
   </form>
   <div id="results"></div>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <script>
      $('#search-form').submit(function(e) {
         e.preventDefault();
         let searchphrase = $("#search-input").val();
         $.ajax({
            url: "http://localhost/sfstrue/app/api/search.php",
            type: 'POST',
            async: false,
            cache: false,
            timeout: 30000,
            data: {query: searchphrase},
            beforeSend: function() {
               $('#results').html("Loading");
            },
            error: function() {
               $('#results').html("Error");
            },
            success: function(data) {
               $('#results').html(data);
            }
         });
      });
   </script>
</body>
</html>
