<?php
### this code will work only with /register.php ###

$_SESSION['completed_steps']['step1'] = false;

?>

<div id="errors-box"></div>
<div id="form-box">
   <form method="post" id="register-form-box">
   <div>
      <label for="firstname">first name: </label><input type="text" name="firstname" value="" id="firstname" pattern="[a-zA-ZąćęłńóśźżĄĘŁŃÓŚŹŻ]+" title="First name can contain only letters!">
   </div>
   <div>
      <label for="lastname">last name: </label><input type="text" name="lastname" value="" id="lastname" pattern="[a-zA-ZąćęłńóśźżĄĘŁŃÓŚŹŻ]+" title="Last name can contain only letters!">
   </div>
   <div>
      <label for="email">email: </label><input type="text" name="email" value="" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Incorrect email address!">
   </div>
   <div>
      <label for="password">password: </label><input type="password" name="password" value="" id="password">
   </div>
   <div>
      <label for="rpassword">repeat password: </label><input type="password" name="rpassword" value="" id="rpassword">
   </div>
   <div>
      <select name="birthD" id="birthD">
         <?php
            $days = ['day', 1, 2, 3 ,4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
            foreach ($days as $day) {
               echo '<option value="' . $day . '">' . $day . '</option>';
            }
         ?>
      </select>
      <select name="birthM" id="birthM">
         <?php
            $months = ['0' => ['month', 'month'], '1' => ['jan', 'january'], '2' => ['feb', 'february'], '3' => ['mar', 'march'], '4' => ['apr', 'april'], '5' => ['may', 'may'], '6' => ['jun', 'june'], '7' => ['jul', 'july'], '8' => ['aug', 'august'], '9' => ['sep', 'september'], '10' => ['oct', 'october'], '11' => ['nov', 'november'], '12' => ['dec', 'december']];
            for($i = 0; $i <= count($months) - 1; $i++) {
               echo '<option value="' . $months[$i][0] . '">' .  $months[$i][1]. '</option>';
            }
         ?>
      </select>
      <select name="birthY" id="birthY">
         <?php
            $years = ['year',
               2004, 2003, 2002, 2001, 2000,
               1999, 1998, 1997, 1996, 1995,
               1994, 1993, 1992, 1991, 1990,
               1989, 1988, 1987, 1986, 1985,
               1984, 1983, 1982, 1981, 1980,
               1979, 1978, 1977, 1976, 1975,
               1974, 1973, 1972, 1971, 1970,
               1969, 1968, 1967, 1966, 1965,
               1964, 1963, 1962, 1961, 1960,
               1959, 1958, 1957, 1956, 1955,
               1954, 1953, 1952, 1951, 1950,
               1949, 1948, 1947, 1946, 1945,
               1944, 1943, 1942, 1941, 1940
            ];
            foreach ($years as $year) {

               echo '<option value="' . $year . '">' . $year . '</option>';
            }
         ?>
      </select>
   </div>
   <div>
      <input type="radio" name="gender" value="male" checked> Male <input type="radio" name="gender" value="female"> Female
   </div>
   <div><button type="submit" name="registerS1">register</button></div>
</form>
</div>
<script type="text/javascript">
   $("#register-form-box").on('submit', function(e) {
      e.preventDefault();
      let firstname = $("#firstname").val();
      let lastname = $("#lastname").val();
      let email = $("#email").val();
      let password = $("#password").val();
      let rpassword = $("#rpassword").val();
      let birthD = $("#birthD").val();
      let birthM = $("#birthM").val();
      let birthY = $("#birthY").val();
      let gender = $("#register-form-box").find("[name='gender']:checked").val();
      $.ajax({
         url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php?step=1&form=1",
         type: 'POST',
         data: { action: 2, step: 1, firstname: firstname, lastname: lastname, email: email, password: password, rpassword: rpassword, birthD: birthD, birthM: birthM, birthY: birthY, gender: gender },
         success: function(r) {
            $("#errors-box").html(r);
            r = JSON.parse(r)
            if (r.type == "error") {
               $("#errors-box").html(r.message);
            } else if (r.type == "success") {
               $("#errors-box").html(r.message);
               $.ajax({
                  url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php",
                  type: 'POST',
                  data: { action: 2, step: <?php echo $step; ?> },
                  success: function(r) {  
                     $("#register-page").html(r);
                     changePageTitle("Register | Step 2");
                  }
               })
            } 
            $("#password").val("");
            $("#rpassword").val("");  
            
         }
      })

   })
</script>
   
