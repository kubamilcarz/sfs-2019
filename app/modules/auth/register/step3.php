<?php
### this code will work only with /register.php ###

$_SESSION['completed_steps']['step3'] = false;

$countries = array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

?>
<div id="errors-box"></div>
<div id="form-box">
   <form method="post" id="register-form-box">
      <div>
         <label for="language">language: </label>
         <select name="language" id="language">
            <option value="en">English</option>
            <option value="es">Español</option>
            <option value="pl">Polski</option>
         </select>
         <p>If your language is not available, do not worry, we will add it soon.</p>
      </div>
      <div>
         <label for="country">country: </label>
         <select name="country" id="country">
            <?php
            
            foreach ($countries as $country) {
               echo "<option value='" . strtolower($country) . "'>" . strtolower($country) . "</option>";
            }
            ?>
         </select>
      </div>
      <div>
         <label for="city">city: </label>
         <input type="text" name="city" id="city" pattern="[a-zA-Z]+" title="City name can contain only letters!">
         <?php // IDEA: later we'll make it rather in a different way. Our team gonna create PAGE for each indivual city on the whole world by asigning them to the `city` category! DROPDOWN, LITTLE THUMBNAIL  ?>
      </div>

      <div><button type="submit">continue</button></div>
   </form>
</div>
<script type="text/javascript">
   $("#register-form-box").on('submit', function(e) {
      e.preventDefault();
      
      let lang = $("#language").val();
      let country = $("#country").val();
      let city = $("#city").val();

      $.ajax({
         url: "<?php echo App::$APP_DIR; ?>/app/api/auth.php?step=3&form=3",
         type: 'POST',
         data: { action: 2, step: 3, lang: lang, country: country, city: city },
         success: function(r) {
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
                     changePageTitle("Register | Step 4");
                  }
               })
            } 
         }
      })

   })
</script>
