<!DOCTYPE HTML>
<html>
    <head>
        <title>Edit User Profile - Queen's BnB</title>
        
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
        include_once '../navbar.php'; // include navbar
        include '../config/connection.php';
        echo navbar(1);
        echo "<div class=\"container\">";


            $memID = -1;

            if (isset($_SESSION['mem_id']))
                $memID = $_SESSION['mem_id']; // ID of user currently logged in

        // Updating only the stuff that the user changed
        if(isset($_POST['fname']) or isset($_POST['lname']) or isset($_POST['email']) or isset($_POST['phone']) or isset($_POST['degree']) or isset($_POST['faculty']) or isset($_POST['year']) or isset($_POST['about'])) {

            // echo "<h1> Hello </h1>";
            // foreach($_POST as $key=>$asd){
            //   echo $key."-> ".$asd."<br>";
            // }

            $postFname = $_POST['fname'];
            $postLname = $_POST['lname'];
            // $postDegree = $_POST['degree'];
            // $postFaculty = $_POST['faculty'];
            $postPhone = $_POST['phone'];
            $postYear = $_POST['year'];
            $postAbout = $_POST['about'];
            // $postEmail = $_POST['email'];
            
              $query = 
              "UPDATE `member` SET first_name = '$postFname', last_name = '$postLname', phone_num = '$postPhone', year = $postYear, aboutme = '$postAbout' WHERE mem_id = $memID;";

              try {
                  // prepare query for execution
                 $stmt = $con->prepare($query);
                  // Execute the query
                  $stmt->execute();
              } catch(Exception $e){
                  die(var_dump($e));
              }   
        }
        // Importing and displaying data from database
        $query = 
            "SELECT first_name, last_name, degree_name, faculty_name, faculty_id, degree_id, phone_num, year, aboutme, email FROM  (`member` natural join `degree`) natural join `faculty` WHERE mem_id=$memID;";
            try {
                // prepare query for execution
               $stmt = $con->prepare($query);
                // Execute the query
                $stmt->execute();
                /* resultset */
                $result = $stmt->fetchAll();
                echo "<table border='1'>";
                foreach ($result as $tuple){
                
    ?>
    <!-- Register Form -->
  <br><br>
  <div class="row">
    <form class="col s8 offset-s2" action="./edit.php" method="post">
      <!-- Name -->
      <div class="row">
        <div class="input-field col s6">
          <input id="firstname" type="text" length="40" name="fname" value="<?php echo $tuple['first_name'];?>"required>
          <label class="active"  for="firstname">first name</label>
        </div>
        <div class="input-field col s6">
          <input id="lastname" type="text" length="40" name="lname" value="<?php echo $tuple['last_name'];?>"required>
          <label class="active"  for="lastname">last name</label>
        </div>
      </div>
      <!-- Email and Phone -->
      <div class="row">
        <div class="input-field col s6">
          <input id="email" type="email" class="validate" name="email" value="<?php echo $tuple['email'];?>"required>
          <label class="active" data-error="inval id email" for="email">email</label>
        </div>
         <div class="input-field col s6">
          <input id="phonenum" type="tel" name="phone" value="<?php echo $tuple['phone_num'];?>">
          <label class="active" for="phonenum">phone number</label>
        </div>
      </div>
      <!-- Faculty -->
      <div class="input-field col s4">
        <select name="faculty" id="faculty" required>
          <option value="" disabled selected>Choose your faculty</option>
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
        <select name="degree" id="degree"required>
          <option value="" disabled selected>Choose your degree</option>
          <option value="1">B.Comp</option>
          <option value="2">B.Eng</option>
          <option value="3">B.Comm</option>
          <option value="4">B.Sc</option>
          <option value="5">B.Cook</option>
        </select>
        <label>degree</label>
      </div>
        <!-- Year -->
        <div class="input-field col s1">
          <input id="year" type="number" name="year" value="<?php echo $tuple['year'];?>">
          <label class="active" for="year">year</label>
         </div>
        <!-- About me -->
      <div class="row">
        <div class="input-field col s8" >
          <textarea id="aboutme" length="500" name="about" class="materialize-textarea" required><?php echo $tuple['aboutme'];?></textarea>
          <label for="aboutme">about me</label>
        </div>
      </div>
      <button class="btn waves-effect waves-light" type="submit" value="submit" name="action">Update Info
      <i class="material-icons right">replay</i>
      </button>
    </form>
  </div>
  <?php
        } // end of for loop
      } catch(Exception $e) {
                  die(var_dump($e));
      }   // End of try/catch
  ?>
  </div>
</body>
</html>