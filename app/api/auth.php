<?php

# Created: 15 september 2019 9:27 pm
# Author: Jakub Milcarz
# Note: AJAX Requests Handler for 'auth.php'

if (isset($_POST['action'])) {
   require '../autoload.php';

   $action = Security::check($_POST['action']);

   if ($action == 1) {
      // echo Security::check($_POST['login']) . ' ' . Security::check($_POST['password']);
      echo Auth::login(Security::check($_POST['login']), Security::check($_POST['password']));
   } else if ($action == 2) {
      session_start();
      $step = Security::check($_POST['step']);
      $normalize = array('Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T', 'ą'=>'a', 'Ą'=>'A', 'ę'=>'e', 'Ę'=>'E', 'ó'=>'o', 'Ó'=>'O', 'ł'=>'l', 'Ł'=>'L', 'ć'=>'c', 'Ć'=>'C', 'ś'=>'s', 'Ś'=>'S', 'ź'=>'z', 'Ź'=>'Z','ż'=>'z', 'Ż'=>'Z');

      // validation
      if (!is_numeric($step)) {
         die('Wrong step number');
      }else if ($step != $_SESSION['step']) {
         $step = $_SESSION['step']; 
      }

      // validate forms
      if (isset($_GET['form'])) {
         http_response_code(200); // return data encoding

         if ($_GET['form'] == 1) { // step 1 form submit
            if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['rpassword']) || !isset($_POST['gender'])) {
               $r = ['type' => 'error', 'message' => 'An error occured'];
               echo json_encode($r);
               exit();
            }
            if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['rpassword']) || empty($_POST['gender']) || empty($_POST['birthD']) || empty($_POST['birthM']) || empty($_POST['birthY'])) {
               $r = ['type' => 'error', 'message' => 'All fields must be filled out'];
               echo json_encode($r);
               exit();
            }

            $r = Auth::registerStep1(Security::check($_POST['firstname']), Security::check($_POST['lastname']), Security::check($_POST['email']), Security::check($_POST['password']), Security::check($_POST['rpassword']), Security::check($_POST['birthD']), Security::check($_POST['birthM']), Security::check($_POST['birthY']), Security::check($_POST['gender']));

            if ($r["type"] == "success") {
               $_SESSION['firstname'] = ucfirst(Security::check($_POST['firstname']));
               $_SESSION['lastname'] = ucfirst(Security::check($_POST['lastname']));
               $_SESSION['name'] = ucfirst(Security::check($_POST['firstname']) . " " . Security::check($_POST['lastname']));
               $_SESSION['email'] = Security::check($_POST['email']);
               $_SESSION['passwordnh'] = Security::check($_POST['password']);
               $_SESSION['password'] = password_hash(Security::check($_POST['password']), PASSWORD_DEFAULT);
               $_SESSION['gender'] = Security::check($_POST['gender']);
               $_SESSION['birthday'] = Security::check($_POST['birthY'])."-".Security::check($_POST['birthM'])."-".Security::check($_POST['birthD']);

               if ($_SESSION['gender'] == "male") {
                  $_SESSION['sex'] = "m";
               } else if ($_SESSION['gender'] == "female") {
                  $_SESSION['sex'] = "f";
               } else {
                  $_SESSION['sex'] = "m";
               }

               $_SESSION['completed_steps']['step1'] = true;
               $_SESSION['step'] = 2;
               echo json_encode($r);

            } else {
               echo json_encode($r);
            }       
         }
         if ($_GET['form'] == 2) { // step 2 form submit
            if (empty($_POST['username'])) {
               $r = ['type' => 'error', 'message' => 'Username field must be filled out'];
               echo json_encode($r);
               exit();
            }

            if (empty(Security::check($_POST['phone']))) {
               $phone = "666";
            } else { $phone = Security::check($_POST['phone']); }

            if (empty(Security::check($_POST['bio']))) {
               $bio = "nothing";
            } else { $bio = Security::check($_POST['bio']); }

            if (empty($_FILES['avatar']['name'])) {
               $avatar = "nothing";
            } else {
               $avatar = $_FILES['avatar'];
            }
            if (empty($_FILES['backgroundphoto']['name'])) {
               $backgroundphoto = "nothing";
            } else {
               $backgroundphoto = $_FILES['backgroundphoto'];
            }

            $r = Auth::registerStep2(Security::check($_POST['username']), $phone, $bio, $avatar, $backgroundphoto);

            if ($r["type"] == "success") {

               $_SESSION['username'] = Security::check($_POST['username']);
               $_SESSION['phone'] = $phone;
               $_SESSION['bio'] = $bio;
               if ($avatar == "nothing") {
                  $_SESSION['avatar'] = "no-photo";
               }else {
                  $_SESSION['avatar'] = $r['avatar_new_name'];
               }
               if ($backgroundphoto == "nothing") {
                  $_SESSION['backgroundphoto'] = "no-photo";
               }else {
                  $_SESSION['backgroundphoto'] = $r['background_new_name'];
               }

               if ($_SESSION['phone'] == "666") {
                  $_SESSION['phone'] = 0;
               }

               $_SESSION['completed_steps']['step2'] = true;
               $_SESSION['step'] = 3;
               echo json_encode($r);
            } else {
               echo json_encode($r);
            } 
         }

         if ($_GET['form'] == 3) { // step 3 form submit
            
            if (empty($_POST['lang']) || empty($_POST['country']) || empty($_POST['city'])) {
               $r = ['type' => 'error', 'message' => 'All fields must be filled out'];
               echo json_encode($r);
               exit();
            }

            $r = Auth::registerStep3(Security::check($_POST['lang']), Security::check($_POST['country']), Security::check($_POST['city']));

            if ($r["type"] == "success") {
               // construct 'data' column in the DB
               $data = '\'{"lang": "'.Security::check($_POST['lang']).'", "country": "'.Security::check($_POST['country']).'", "city": "'.Security::check($_POST['city']).'", "bio": "'.$_SESSION['bio'].'" ,"gender": "'.$_SESSION['gender'].'"}\'';

               DB::query('INSERT INTO users VALUES (\'\', :username, :firstname, :lastname, :name, :email, :phone, :password, :birthday, :sex, :avatar, :backgroundphoto, :data)', [':username' => $_SESSION['username'], ':firstname' => $_SESSION['firstname'], ':lastname' => $_SESSION['lastname'], ':name' => $_SESSION['name'], ':email' => $_SESSION['email'], ':phone' => $_SESSION['phone'], ':password' => $_SESSION['password'], ':birthday' => $_SESSION['birthday'], ':sex' => $_SESSION['sex'], ':avatar' => $_SESSION['avatar'], ':backgroundphoto' => $_SESSION['backgroundphoto'], ':data' => $data]);

               $userid = DB::query('SELECT id FROM users WHERE users_username = :username AND users_email = :email AND users_password = :password', [':username' => $_SESSION['username'], ':email' => $_SESSION['email'], ':password' => $_SESSION['password']])[0]['id'];

               DB::query('INSERT INTO followers VALUES (\'\', :followerid, :followerid, 1, NOW())', [':followerid' => $userid]);

               $_SESSION['completed_steps']['step3'] = true;
               $_SESSION['step'] = 4;
               echo json_encode($r);
            } else {
               echo json_encode($r);
            }
         }

         if ($_GET['form'] == 4) { // step 4 form submit
            $login = $_SESSION['email'];
            $pass = $_SESSION['passwordnh'];
            session_destroy(); 
            Auth::login($login, $pass);
            // TODO: redirect to index.php
            // note: require(index.php) and header() does not work
         }

         exit(); // FIX: for now, delete it later
      }

      // include separate files
      for ($i = 1; $i <= $step; $i++) {
         if ($step == $i) {
            include '../modules/auth/register/step'.$i.'.php';
         }
      }

   } else if ($action == 3) {
      if (!isset($_POST['email'])) {
         $r = ['type' => 'error', 'message' => 'Email field is empty'];
         echo json_encode($r);
         exit();
      }

      $r = Auth::forgotPassword(Security::check($_POST['email']));

      // if ($r["type"] == "success") {
   
      // }   
   }

}
