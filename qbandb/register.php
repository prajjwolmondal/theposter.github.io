<!DOCTYPE HTML>
<html>
    <head>
        <title>Register - Queen's BnB</title>
        
        <!-- Compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- JS animations -->
  
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<!-- Compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
    <!-- Dropdown Animations -->
    <script>  
      $(document).ready(function() {
      $('select').material_select();
      }); </script>


  
    </head>
<body>

	<?php

      // Create a user session or resume an existing one
      session_start ();

      // should not be able to register if logged in
      if (isset($_SESSION['mem_id'])) {
        header("Location: /qbandb/index.php"); // you're not supposed to be here
      }

	    include_once 'registernav.php';
	    include_once 'config/connection.php';
	?>
<!-- Register Form -->
  <br><br>
  <div class="container">
  <div class="row">
    <form id="registerform" class="col s8 offset-s2" action="registernow.php" method="post">
      <!-- Name -->
      <div class="row">
        <div class="input-field col s6">
          <input id="firstname" type="text" length="40" maxlength="40" name="fname" required>
          <label class="active"  for="firstname">first name</label>
        </div>
        <div class="input-field col s6">
          <input id="lastname" type="text" length="40" maxlength="40" name="lname" required>
          <label class="active"  for="lastname">last name</label>
        </div>
      </div>
      <!-- Password -->
      <div class="row">
        <div class="input-field col s6">
          <input id="password" type="password" name="pass" required>
          <label class="active"  for="password">password</label>
        </div>   
        <div class="input-field col s6">
          <input id="retypepassword" type="password" class="validate" name="repass" required>
          <label class="active" for="retypepassword">retype password</label>
        </div>
      </div>
      <!-- Email and Phone -->
      <div class="row">
        <div class="input-field col s6">
          <input id="email" type="email" class="validate" name="email" required>
          <label class="active" data-error="invalid email" for="email">email</label>
        </div>
         <div class="input-field col s6">
          <input id="phonenum" type="tel" name="phone" class="validate">
          <label class="active" for="phonenum">phone number</label>
        </div>
      </div>
      <!-- Faculty -->
      <div class="input-field col s4">
        <select name="faculty" id="faculty" class="validate" required>
          <option value="" disabled selected>select your faculty</option>
          <option value="1">Computing</option>
          <option value="2">Engineering</option>
          <option value="3">Commerce</option>
          <option value="4">Arts and Science</option>
          <option value="5">Cooking</option>
        </select>
        <label>faculty</label>
      </div>
      <!-- Degree -->
      <div class="input-field col s4">
        <select name="degree" id="faculty" required>
          <option value="" disabled selected>select your degree</option>
          <option value="1">B.Comp</option>
          <option value="2">B.Eng</option>
          <option value="3">B.Comm</option>
          <option value="4">B.Sc</option>
          <option value="5">B.Cook</option>
        </select>
        <label>degree</label>
      </div>
        <div class="input-field col s1">
          <input id="year" type="number" name="year">
          <label class="active" for="year">year</label>
        </div>
        <!-- About me -->
      <div class="row">
        <div class="input-field col s8" >
          <textarea id="aboutme" maxlength="500" length="500" name="about" class="materialize-textarea" required></textarea>
          <label for="aboutme">about me</label>
        </div>
      </div>
      <!-- Credit Card Info -->
      <div class="row">
        <div class="input-field col s3" >
          <input id="credit" type="text" class="validate" name="credit" maxlength="16" required>
          <label class="active" for="credit">credit card number</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s1" >
          <input id="expiry" type="text" class="validate" name="expiry" maxlength="4" required>
          <label class="active" for="expiry">expiry</label>
        </div>
        <div class="input-field col s1" >
          <input id="cvv" type="text" class="validate" name="cvv" maxlength="3" required>
          <label class="active" for="cvv">cvv</label>
        </div>
      </div>

      <button class="btn waves-effect waves-light" type="submit" name="action">register
      <i class="material-icons right">send</i>
      </button>
    </form>
  </div>
  </div>
  <script src="checkPass.js"></script>
 

</body>
</html>