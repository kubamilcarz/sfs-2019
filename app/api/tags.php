<?php

# Author: Jakub Milcarz
# Note: This is tag ajax request manager.
#       Works like post loader, but here you can change results by creating next type

# Types: 1. Sidebar - left sidebar, position 'popular today'
#

require('../autoload.php');
session_start();

$user = $_SESSION['userdata'];
$start = (int)$_GET['start'];

if (isset($_GET['type'])) {
   $type = Security::check($_GET['type']);
   if ($type == 'sidebar') {
      $tags = DB::query('SELECT tags.tags_id, tags.tags_name, tags.tags_popularity FROM tags ORDER BY tags_popularity DESC LIMIT 5');
   }
} else {
   $tags = DB::query('SELECT tags.tags_id, tags.tags_name, tags.tags_popularity FROM tags ORDER BY tags_popularity DESC LIMIT 10 OFFSET '.$start);
}

$response = array();
foreach ($tags as $tag) {
   $row = ['TagId'=>$tag['tags_id'],'TagName'=>$tag['tags_name'], 'TagPopularity'=>$tag['tags_popularity'], 'TagPath'=>App::PrintTagURL($tag['tags_name'])];
   array_push($response, $row);
}

http_response_code(200);
echo json_encode($response);

?>
