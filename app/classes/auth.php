<?php

class Auth {
   public static $system_cookie_name = 'HONET';
   public static $error = "";
   private static $countries = array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

   public static $errors = [
      'en' => [
         'register' => [
            // step 1
               'Incorrect birthday', #1
               'Incorrect day', #2
               'Incorrect year', #3
               'Incorrect month', #4
               '29th of February is not a valid date', #5
               'This month has 30 days', #6
               'February has only 28 or 29 days', #7
               'Incorrect email address', #8
               'Email address is already taken', #9
               'Passwords do not match', #10
               'Password has to be at least 8 characters long', #11
               'Email address has to be at least 8 characters long', #12
               'Incorrect gender', #13
               'Incorrect first name (min: 2 max: 32 characters)', #14
               'Incorrect last name (min: 2 max: 32 characters)', #15
               'Name can contain only letters', #16
            // step 2
               'Incorrect username (min: 3 max: 32 characters)', #17
               'Username is already taken', #18
               'Username can contain only letters & numbers', #19
               'Phone number can contain only numbers', #20
               'Incorrect bio (min: 3 max: 64 characters)', #21
               'Problem occured while uploading your profile picture', #22
               'Problem occured while uploading your background picture', #23
            // step 3
               'Incorrect language', #24
               'Incorrect country', #25
               'Incorrect city', #26
               'City name has to be at least 3 characters long' #27
         ],
         'forgot-password' => [
            'Incorrect email address', #1
            'Email address is not used' #2
         ]
      ]
   ];

   public function logout() {
      DB::query('DELETE FROM login_tokens WHERE logint_userid=:userid', array(':userid'=>self::loggedin()));
      setcookie("" . self::$system_cookie_name . "", '1', time()-3600);
      setcookie("" . self::$system_cookie_name . "_", '1', time()-3600);
      header('Location: index.php');
      exit();
   }

   public static function loggedin() {
      if (isset($_COOKIE['' . self::$system_cookie_name . ''])) {
         if (DB::query('SELECT logint_userid FROM login_tokens WHERE logint_token=:token', [':token'=>sha1($_COOKIE['' . self::$system_cookie_name . ''])])) {
            $userid = DB::query('SELECT logint_userid FROM login_tokens WHERE logint_token=:token', [':token'=>sha1($_COOKIE['' . self::$system_cookie_name . ''])])[0]['logint_userid'];
            if (isset($_COOKIE['' . self::$system_cookie_name . '_'])) {
               return $userid;
            } else {
               $cstrong = true;
               $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
               DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', [':token'=>sha1($token), ':user_id'=>$userid]);
               ## echo ';
               DB::query('DELETE FROM login_tokens WHERE logint_token=:token', [':token'=>sha1($_COOKIE["" . self::$system_cookie_name . ""])]);
               setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
               setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
               return $userid;
            }
         }
      }
      return false;
   }

   public function guard() {
      if (!self::loggedin()) {
         // require_once("../app/modules/guard-error.html");
         // TODO: guard error page include here
         exit();
      }
   }

   // TODO: Errors
   /**
    * [login description]
    * @param  string $login [description]
    * @param  string $pass  [description]
    * @return string        [description]
    */
   public function login($login, $pass) {

      if (strpos($login, '@') == true) { # email login here
         if (!DB::query('SELECT users_email FROM users WHERE users_email=:email', [':email'=>$login])[0]['users_email']) {
            return "Wrong Password or Wrong Email";
         }
         if (!password_verify($pass, DB::query('SELECT users_password FROM users WHERE users_email=:email', [':email'=>$login])[0]['users_password'])) {
            return "Wrong Password or Wrong Email";
         }

         $user_id = DB::query('SELECT id FROM users WHERE users_email=:email', [':email'=>$login])[0]['id']; # get user_id

         // create & insert login token
         $cstrong = true;
         $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
         DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', [':token'=>sha1($token), ':user_id'=>$user_id]);

         // set cookies
         setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
         setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

         return true;

      } else { # username login here
         if (!DB::query('SELECT users_username FROM users WHERE users_username=:username', [':username'=>$login])[0]['users_username']) {
            return "Wrong Password or Wrong Username";
         }
         if (!password_verify($pass, DB::query('SELECT users_password FROM users WHERE users_username=:username', [':username'=>$login])[0]['users_password'])) {
            return "Wrong Password or Wrong Username";
         }

         $user_id = DB::query('SELECT id FROM users WHERE users_username=:username', [':username'=>$login])[0]['id']; # get user_id

         // create & insert login token
         $cstrong = true;
         $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
         DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', [':token'=>sha1($token), ':user_id'=>$user_id]);

         // set cookies
         setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
         setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

         return true;
      }
   }

   public function forgotPassword($email) {
      if (strpos($email, '@') !== false) {
         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (DB::query('SELECT users_email FROM users WHERE users_email=:email', [':email'=>$email])[0]['users_email']) {
               $cstrong = True;
               $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
               $user_id = DB::query('SELECT id FROM users WHERE users_email=:email', [':email'=>$email])[0]['id'];
               DB::query('INSERT INTO password_tokens VALUES (\'\', :token, :user_id, NOW())', [':token'=>sha1($token), ':user_id'=>$user_id]);

               // TODO: connect to mailing system
               // Mail::sendMail('Zresetuj hasło.', "<a href='http://localhost/facebook/change-password.php?token=$token'>http://localhost/social-network/change-password.php?token=$token</a>", $email);
               return ['type' => 'success', 'message' => 'success'];
            } else {
               return ['type' => 'error', 'message' => self::$errors['en']['forgot-password'][1]];
            }
         } else {
            return ['type' => 'error', 'message' => self::$errors['en']['forgot-password'][0]];
         }
      } else {
         return ['type' => 'error', 'message' => self::$errors['en']['forgot-password'][0]];
      }
   }

   public function changePassword($opass, $npass, $rnpass) {
      if (password_verify($opass, DB::query('SELECT user_password FROM users WHERE id=:userid', [':userid'=>self::loggedin()])[0]['user_password'])) {
         if ($npass == $rnpass) {
            if (strlen($npass) >= 8 && strlen($npass) <= 64) {
               DB::query('UPDATE users SET user_password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($npass, PASSWORD_BCRYPT), ':userid'=>self::loggedin()));
               self::logout();
               // echo 'Password changed successfully!';
               header('Location: forgot-password.php?error=1'); exit();
            }
         }else {
            // self::$error = "Podane hasła nie są identyczne!";
            header('Location: forgot-password.php?error=2'); exit();
         }
      }else {
         // self::$error = "Niepoprawne stare hasło!";
         header('Location: forgot-password.php?error=3'); exit();
      }
   }

   public function changePasswordToken($npass, $rnpass, $userid) {
      if ($npass == $rnpass) {
         if (strlen($npass) >= 8 && strlen($npass) <= 64) {
            DB::query('UPDATE users SET user_password=:newpassword WHERE id=:userid', [':newpassword'=>password_hash($npass, PASSWORD_BCRYPT), ':userid'=>$userid]);
            echo 'Password changed successfully!';
            DB::query('DELETE FROM password_tokens WHERE passwordt_userid=:userid', [':userid'=>$userid]);
         }
      }else {self::$error = "Podane hasła nie są identyczne!";}
   }

   // register step 1 validation
   public function registerStep1($firstname, $lastname, $email, $password, $rpassword, $birthD, $birthM, $birthY, $gender) {

      // DOB VALIDATION
         $days = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
         $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
         $years = [2004, 2003, 2002, 2001, 2000, 1999, 1998, 1997, 1996, 1995, 1994, 1993, 1992, 1991, 1990, 1989, 1988, 1987, 1986, 1985, 1984, 1983, 1982, 1981, 1980, 1979, 1978, 1977, 1976, 1975, 1974, 1973, 1972, 1971, 1970, 1969, 1968, 1967, 1966, 1965, 1964, 1963, 1962, 1961, 1960, 1959, 1958, 1957, 1956, 1955, 1954, 1953, 1952, 1951, 1950, 1949, 1948, 1947, 1946, 1945, 1944, 1943, 1942, 1941, 1940];
         if ($birthD == "day" || $birthM == "month" || $birthY == "year") {
            return ['type' => 'error', 'message' => 'Incorrect birthday'];
         } else if (!in_array($birthD, $days)) {
            return ['type' => 'error', 'message' => 'Incorrect day'];
         } else if (!in_array($birthY, $years)) {
            return ['type' => 'error', 'message' => 'Incorrect year'];
         } else if (!in_array($birthM, $months)) {
            return ['type' => 'error', 'message' => 'Incorrect month'];
         } else if ($birthD == 29 && $birthM == "feb" && date('L', mktime(0, 0, 0, 1, 1, $birthY)) == 0) {
            return ['type' => 'error', 'message' => '29th of February is not a valid date'];
         } else if ($birthD == 31 && ($birthM == "apr" || $birthM == "jun" || $birthM == "sep" || $birthM == "nov")) {
            return ['type' => 'error', 'message' => 'This month has 30 days'];
         } else if (($birthD == 30 && $birthM == "feb") || ($birthD == 31 && $birthM == "feb")) {
            return ['type' => 'error', 'message' => 'February has only 28 or 29 days'];
         }

      // REST OF VALIDATION
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['type' => 'error', 'message' => 'Incorrect email address'];
         } else if (DB::query('SELECT users_email FROM users WHERE users_email = :email', [':email' => $email])[0]['users_email']) {
            return ['type' => 'error', 'message' => 'Email address is already taken'];
         } else if ($password != $rpassword) {
            return ['type' => 'error', 'message' => 'Passwords do not match'];
         } else if ((strlen($password) <= 8) || (strlen($password) >= 64)) {
            return ['type' => 'error', 'message' => 'Password has to be at least 8 characters long'];
         } else if ((strlen($rpassword) <= 8) || (strlen($rpassword) >= 64)) {
            return ['type' => 'error', 'message' => 'Password has to be at least 8 characters long'];
         } else if ((strlen($email) <= 8) || (strlen($email) >= 64)) {
            return ['type' => 'error', 'message' => 'Email address has to be at least 8 characters long'];
         } else if ($gender != "male" && $gender != "female") {
            return ['type' => 'error', 'message' => 'Incorrect gender'];
         } else if ((strlen($firstname) <= 2) || (strlen($firstname) >= 32)) {
            return ['type' => 'error', 'message' => 'Incorrect first name (min: 2 max: 32 characters)'];
         } else if ((strlen($lastname) <= 2) || (strlen($lastname) >= 32)) {
            return ['type' => 'error', 'message' => 'Incorrect last name (min: 2 max: 32 characters)'];
         } else if (!preg_match("/^[a-zA-Z]*$/", $firstname) && !preg_match("/^[a-zA-Z]*$/", $lastname)) {
            return ['type' => 'error', 'message' => 'Name can contain only letters'];
         }

      return ['type' => 'success', 'message' => 'Going to step 2!'];
   }

   // register step 2 validation
   public function registerStep2($username, $phone, $bio, $avatar, $backgroundphoto) {
      if ((strlen($username) <= 3) || (strlen($username) >= 32)) {
         return ['type' => 'error', 'message' => 'Incorrect username (min: 3 max: 32 characters)'];
      }else if (DB::query('SELECT users_username FROM users WHERE users_username = :username', [':username' => $username])[0]['users_username']) {
         return ['type' => 'error', 'message' => 'Username already taken'];
      }else if (!preg_match("/[a-z0-9.]/i", $username)) {
         return ['type' => 'error', 'message' => 'Username can contain only letters & numbers'];
      }else if ($phone != "666" && !preg_match("/^[0-9]*$/", $phone)) {
         return ['type' => 'error', 'message' => 'Phone number can contain only numbers'];
      }else if ($bio != "nothing" && (strlen($bio) <= 3) && strlen($bio) >= 64) {
         return ['type' => 'error', 'message' => 'Incorrect bio (min: 3 max: 64 characters)'];
      }

      $avatar_new_name = "nothing";
      if ($avatar != "nothing") {
         $fileExt = exploade('.', $_FILES['avatar']['name']);
         $fileActualExt = strtolower(end($fileExt));
         $allowed = ['jpg', 'jpeg', 'png'];
         if (!in_array($fileActualExt, $allowed)) {
            return ['type' => 'error', 'message' => 'Problem occured while uploading your profile picture'];
         } else if ($_FILES['avatar']['error'] === 0) {
            return ['type' => 'error', 'message' => 'Problem occured while uploading your profile picture'];
         } else if ($_FILES['avatar']['size'] > 5000000) {
            return ['type' => 'error', 'message' => 'Problem occured while uploading your profile picture'];
         }

         $avatar_new_name = uniqid('', true) . "." . $fileActualExt;
         $file_destination = '/sfstrue/storage/pictures/' . $avatar_new_name;

         move_uploaded_file($_FILES['avatar']['tmp_name'], $file_destination);
      }

      $background_new_name = "nothing";
      if ($backgroundphoto != "nothing") {
         $fileExt = exploade('.', $_FILES['backgroundphoto']['name']);
         $fileActualExt = strtolower(end($fileExt));
         $allowed = ['jpg', 'jpeg', 'png'];
         if (!in_array($fileActualExt, $allowed)) {
            return ['type' => 'error', 'message' => 'Problem occured while uploading your background photo'];
         } else if ($_FILES['backgroundphoto']['error'] === 0) {
            return ['type' => 'error', 'message' => 'Problem occured while uploading your background photo'];
         } else if ($_FILES['backgroundphoto']['size'] > 10000000) {
            return ['type' => 'error', 'message' => 'Problem occured while uploading your background photo'];
         }

         $background_new_name = uniqid('', true) . "." . $fileActualExt;
         $file_destination = '/sfstrue/storage/pictures/' . $background_new_name;

         move_uploaded_file($_FILES['backgroundphoto']['tmp_name'], $file_destination);
      }

      return ['type' => 'success', 'message' => 'Going to step 3!', 'avatar_new_name' => $avatar_new_name, 'background_new_name' => $backgroundphoto];

   }

   // register step 3 validation
   public static function registerStep3($language, $country, $city) {
      if (!in_array($language, App::$LANGUAGES)) {
         return ['type' => 'error', 'message' => 'Incorrect language'];
      } else if (!in_array(ucfirst($country), self::$countries)) {
         return ['type' => 'error', 'message' => 'Incorrect country'];
      } else if (!preg_match("/[a-zA-Zs]/i", $city)) {
         return ['type' => 'error', 'message' => 'Incorrect city'];
      } else if ((strlen($city) <= 3) && (strlen($city) >= 48)) {
         return ['type' => 'error', 'message' => 'City name has to be at least 3 characters long'];
      }

      return ['type' => 'success', 'message' => 'Going to step 4!'];
   }

}
