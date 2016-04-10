<!DOCTYPE HTML>
<html>
  <head>
        <title>Dashboard - Queen's BnB</title>
        
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
    <script>
  $(document).ready(function(){
    $('.collapsible').collapsible({
      accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
  });

       
    </script>

  
  </head>

  <body>

  <?php
    include_once 'navbar.php';
    echo navbar(0);
    include 'config/connection.php';
    $firstname = $_SESSION['first_name'];
    $currentMemID = $_SESSION['mem_id'];
    $lastname = $_SESSION['last_name'];

  ?>

  <div class="container">
  <div class="divider"></div>
  <div class="section">
 <?php
  echo "<h3>".$firstname."     ".$lastname."</h3>";
  echo "<a class=\"waves-effect waves-light btn\" href='user/profile.php?id={$currentMemID}'><i class=\"material-icons right\">account_circle</i>View Profile</a>";
    ?>

  </div>
  <div class="divider"></div>
  <div class="section">



  <h4>Properties</h4>
  <?php
  
  //Query to obtain current member owned properties
  $query ="SELECT   street_num, street_name, apt_num, postal_code, type, prop_id
           FROM    `property` natural join `member`
           WHERE mem_id = $currentMemID";
  //Query to show bookings on current property
 
  try {
          //Execute first query 
          $stmt = $con->prepare($query); // prepare query for execution
          $stmt->execute(); // execute the query
          $result = $stmt->fetchAll();

          //Execute second query


          $resultString = "";
          $resultString .= <<<EOT

          <ul class="collapsible" data-collapsible="accordion">
           
EOT;
          $count=0;
          $count2=0;
            foreach ($result as $tuple) {
                $resultString .= <<<EOT

                <li>
EOT;
                $resultString .= "<div class=\"collapsible-header\">";
                $resultString .= "<a href='property.php?id={$tuple['prop_id']}'>" . $tuple['street_num'] . " " . $tuple['street_name']; // address
                $resultString .= "</div>";
                $currentPropID = $tuple['prop_id'];
                 $query2= "SELECT  date_booked, period, status, mem_id
                           FROM  booking 
                           WHERE booking.prop_id = $currentPropID 
                           ORDER BY date_booked DESC";

                $stmt2 = $con->prepare($query2); // prepare query for execution
                $stmt2->execute(); // execute the query
                $result2 = $stmt2->fetchAll();


                //Set up inner table to show bookings
                $resultString .="<div class=\"collapsible-body\"><p><table class=\"hightlight bordered\"><caption><strong>Booking Summary<strong></caption>";
                $resultString .="<tr><th>Date Booked</th><th>Period</th><th>Status</th>";
                //SHow bookings

                foreach ($result2 as $tuple2){
                  $count++;
                  $bookerID= $tuple2['mem_id'];
                  $resultString.= "<tr><td>".$tuple2['date_booked']."</td>";
                  $resultString.= "<td>".$tuple2['period']."</td>";
                 
                  //If booking is pending, give option to change
                  if ($tuple2['status']== "Pending"){
                  $resultString.= "<td><form id=\"updateStatus\" method=\"post\" action=\"dashboard.php\">  <div class=\"input-field s2\">
                                  <select id=\"update".$count."\" name=\"update".$count."\">
                                  <option value=\"\" disabled selected>" .$tuple2['status']. "</option>
                                  <option value=\"1\">Confirm</option>
                                  <option value=\"2\">Reject</option>
                                  </select>
                                  <label></label>
                                  </div></td>";
                  $resultString.="<td> <button class=\"btn waves-effect waves-light\" type=\"submit\" name=\"action\">Update
                                  <i class=\"material-icons right\">send</i>
                                  </button> </td></form>";
                if (isset($_POST['update'.$count] )) {
                  //echo  $_POST['update'.$count];
                  if ($_POST['update'.$count] == 1){
                    // echo "<h1> WOO! </h1>"
                    $updateStatus = "UPDATE qbandb.booking 
                                     SET status= \"Confirmed\"
                                     WHERE  mem_id = $bookerID and prop_id = $currentPropID;";                   
                  } else if ($_POST['update'.$count] == 2){
                    // echo "<h1> WOO! </h1>";
                    $updateStatus = "UPDATE qbandb.booking 
                                     SET status= \"Rejected\" 
                                     WHERE  mem_id = $bookerID and prop_id = $currentPropID;";

                  }
                  try {
                        // prepare query for execution
                       $stmt = $con->prepare($updateStatus);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }

                }
                    
                  }
                  //Else, disable change button
                  else {
                       $resultString.= "<td><div class=\"input-field s4\">
                        <select disabled>
                        <option value=\"\" disabled selected>" .$tuple2['status']. "</option>
                        <option value=\"1\">Confirm</option>
                        <option value=\"2\">Reject</option>
                        </select>
                        <label>Materialize Select</label>
                        </div></td>";
                  $resultString.="<td><form id=\"update status\">   
                                  <button disabled class=\"btn disabled\" >Update
                                  <i class=\"material-icons right\">send</i>
                                  </button></form></td>";
                  }
                  $resultString.="</tr>";
                }

            
                $resultString .="</table></p></div>";

                $resultString .= <<<EOT
               
EOT;
                $resultString .= <<<EOT
                <li>
EOT;
            }          
            $resultString .= <<<EOT
          </ul>
        <br>
EOT;
 echo $resultString;

         if ($result == NULL){
            echo "You do not own any properties";

          }


        }
        catch (Exception $e) {
            die(var_dump($e));
        }
  ?>
  <br>
  <br>
  <a class="waves-effect waves-light btn" href='edit-property.php'><i class="material-icons right">add</i>Add a Property</a>
  </div>
  <div class="divider"></div>
  <div class="section">
  <script src="cancelBooking.js"></script>


  <h4>Bookings</h4>
  <?php
    $bookingQuery ="SELECT  date_booked, period, status, property.prop_id, street_num,street_name, apt_num 
                    FROM  property, booking 
                    WHERE  property.prop_id = booking.prop_id and booking.mem_id = $currentMemID 
                    ORDER BY  date_booked DESC";
        try {
            // prepare query for execution
            $stmtbook = $con->prepare($bookingQuery);
            // Execute the query
            $stmtbook->execute();
            $resultbook = $stmtbook->fetchAll();

            $bookingResult = "";
            $bookingResult.= <<<EOT

            <table border=1>
            <tr><th>Date Booked</th><th>Period</th><th>Status</th><th>Address </th> <th> Cancel</th></tr>

EOT;
            foreach ($resultbook as $tuplebook){
              //echo $count2;
             
                $bookingResult.="<tr><td>".$tuplebook['date_booked']."</td>";
                $bookingResult.= "<td>".$tuplebook['period']."</td>";
                $bookingResult.= "<td>".$tuplebook['status']."</td>";
                $bookingResult.= "<td><a href='property.php?id={$tuplebook['prop_id']}'>".$tuplebook['street_num']." ".$tuplebook['street_name']."</td>";

                $bookingResult.= "<td><form class=\"col s12\" action=\"dashboard.php\" method=\"post\">
                      <button class=\"btn waves-effect waves-light\" type=\"submit\" name=\"action".$count2."\">Cancel
                      <i class=\"material-icons right\">block</i>
                      </button>
                      </form></td>";
               
                $bookingResult.= "</tr>";

                $bookingResult.= "<script> ";
                $bookingResult.= "$('#action'".$count2.").click(function(){ ";
                $bookingResult.= "$('#hiddenLogout').click();";
                $bookingResult.= "var val = $('#hiddenLogout').attr('name');";
                $bookingResult.= "});";
                $bookingResult.= "</script>";
          
                 $count2++;
                if (isset($_POST['action'.$count2])){
                    // echo "<h1> WOO! </h1>";
                  $cancelbook = "UPDATE qbandb.booking 
                                   SET status= \"Cancelled\" 
                                   WHERE  mem_id = {$currentMemID} and prop_id = {$tuplebook['prop_id']};";
                  try {
                        // prepare query for execution
                       $stmt = $con->prepare($cancelbook);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                  }
               
            }
            $bookingResult.= "</table>";
        }catch (Exception $e){
            die(var_dump($e));
        }
        echo $bookingResult;
    $resultString = "";

   ?>
  <br>
  
  </div>
  </container>



  </body>
</html>