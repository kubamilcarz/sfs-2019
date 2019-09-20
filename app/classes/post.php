<?php

class Post
{

   private static $privacies = ['public', 'private', 'friends'];
   # public => 1
   # private => 0
   # friends => 2

   public static function createNew($userid, $content, $privacy) {

      if (!is_numeric($userid)) {
         echo 'User Id must be an intiger!';
         return false;
      } else if (!DB::query('SELECT id FROM users WHERE id = :userid', [':userid' => $userid])[0]['id']) {
         echo 'User with this user id does not exists!';
         return false;
      } else if (strlen($content) <= 3 || strlen($content) >= 512) {
         echo 'Post cannot be shorter than 3 characters & longer than 512 characters!';
         return false;
      } else if (!in_array($privacy, self::$privacies)) {
         echo 'Incorrent privacy!';
         return false;
      }

      if ($privacy == "public") {
         $privacy = 1;
      } else if ($privacy == "private") {
         $privacy = 0;
      } else if ($privacy == "friends") {
         $privacy = 2;
      }

      $topics = self::getTopics($content);
      $bdate = date('Y-m-d H:i:s');
      DB::query('INSERT INTO posts VALUES (\'\', :userid, :content, :topics, :privacy, :likes, :comments, :shares, :bdate)', [':userid' => $userid, ':content' => $content, ':topics' => $topics, ':privacy' => $privacy, ':likes' => 0, ':comments' => 0, ':shares' => 0, ':bdate' => $bdate]);
      
      # insert tags
      $topics = explode(",", $topics);
      foreach($topics as $tag) {
         if (strlen($tag) < 2) {
            return true;
         }
         if ((strpos($tag, '!') && strlen($tag) > 1) || (strpos($tag, '?') && strlen($tag) > 1) || (strpos($tag, '.') && strlen($tag) > 1) || (strpos($tag, ',') && strlen($tag) > 1)) {
            $tag = substr($tag, 0, -1);
         }
         if (!DB::query('SELECT tags_id FROM tags WHERE tags_name = :name', [':name' => $tag])[0]['tags_id']) {
            DB::query('INSERT INTO tags VALUES (\'\', :name, 1)', [':name'=>$tag]);
            #'
         } else {
            DB::query('UPDATE tags SET tags_popularity = tags_popularity+1 WHERE tags_name = :name', [':name'=>$tag]);
         }
      }
      return true;
   }

   public static function getTopics($text) {
      $text = explode(" ", $text);
      $topics = "";
      foreach ($text as $word) {
         if (substr($word, 0, 1) == "#") {
            $topics .= substr($word, 1).",";
         }
      }
      return $topics;
   }

   public static function link_add($text) {
      $text = explode(" ", $text);
      $newstring = "";
      $wordplus = "";
      $specials = ['@', '#', '[s]', '[b]', '[i]', '[u]'];
      foreach ($text as $word) {
         if ((strpos($word, '!') && strlen($word) > 1) || (strpos($word, '?') && strlen($word) > 1) || (strpos($word, '.') && strlen($word) > 1) || (strpos($word, ',') && strlen($word) > 1)) {
            if (in_array($word, $specials)) {
               $wordplus = substr($word, -1);
               $word = substr($word, 0, -1);
            }
         }
         if (substr($word, 0, 1) == "@") {
            $newstring .= "<a style='text-decoration: none; color: #3498db' href='" . App::$APP_DIR . "/profile/".substr($word, 1)."'>".htmlspecialchars($word) . "</a>" . $wordplus;
         } else if (substr($word, 0, 1) == "#") {
            $newstring .= "<a style='text-decoration: none; color: #3498db' href='" . App::$APP_DIR . "/tag/".substr($word, 1)."'>".htmlspecialchars($word)."</a>" . $wordplus . "  ";
         } else if (substr($word, 0, 3) == "[s]") { # SUPER!
            $newstring .= "<span style='color: orange; font-weight: bold;'>" . substr(substr(htmlspecialchars($word), 3), 0, -4) . "</span>" . $wordplus . "  ";
         } else if (substr($word, 0, 3) == "[b]") { # BOLD
            $newstring .= "<span style='font-weight: bold;'>" . substr(substr(htmlspecialchars($word), 3), 0, -4) . "</span>" . $wordplus . "  ";
         } else if (substr($word, 0, 3) == "[i]") { # ITALIC
            $newstring .= "<span style='font-style: italic;'>" . substr(substr(htmlspecialchars($word), 3), 0, -4) . "</span>" . $wordplus . "  ";
         } else if (substr($word, 0, 3) == "[u]") { # UNDERLINE
            $newstring .= "<span style='text-decoration: underline;'>" . substr(substr(htmlspecialchars($word), 3), 0, -4) . "</span>" . $wordplus . "  ";
         } else if (strtoupper($word) == "GRATULACJE" || strtoupper($word) == "CONGRATS" || strtoupper($word) == "CONGRATULATIONS") {
            $newstring .= "<span style='color: red'>" . htmlspecialchars($word) . "</span>" . $wordplus . "  ";
         } else {
            $newstring .= htmlspecialchars($word)." ";
         }
      }
      return $newstring;
   }

}
