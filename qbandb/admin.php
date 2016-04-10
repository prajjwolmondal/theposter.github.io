    <!DOCTYPE HTML>
    <html>
        <head>
            <title>Admin - Queen's BnB</title>
            
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
            }); 
        </script>
        <script>
            $('#comments').val('New Text');
            $('#comments').trigger('autoresize');
        </script>
        <script src="admin.js"></script>
        </head>
    <body>
        <?php 

            include_once 'navbar.php'; 
            echo navbar(0);
            
        ?>
        <h3> Welcome to the admin page </h3>
        <!-- Form -->
        <div class="row">
            <form class="col s12" action="admin.php" method="post">
                <!-- Admin Action -->
                <div class="input-field col s4">
                    <select name="inputType" id="admin_action" onChange="showInput()" required>
                        <option value="null" disabled selected>Select an action</option>
                        <option value="1">Delete a member and all their properties</option>
                        <option value="2">Delete a property</option>
                        <option value="3">Summarize bookings and ratings per property</option>
                        <option value="4">Summarize bookings and ratings per supplier</option>
                        <option value="5">Summarize booking activity per consumer</option>
                    </select>
                <label for="rating">Action</label>
                </div>
                <div id="admin_input">
                </div>
                <button class='btn waves-effect waves-light' type='submit' name='action'>Submit
                    <i class='material-icons right'>send</i>
                </button>
            </form>
        </div>

        <?php
            include 'config/connection.php';
            // if(isset($_POST['admin_action']) and isset(($_POST['mem_id']))){
            if (isset($_POST['inputType'], $_POST['member_id'])) {
                $selectedMemID = $_POST['member_id'];
                if ($_POST['inputType'] == 1){
                    // echo "<h1> WOO! </h1>";
                    $query = "DELETE  from property WHERE  mem_id = $selectedMemID;";
                    try {
                        // prepare query for execution
                       $stmt = $con->prepare($query);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                    $query2 = "DELETE  from member WHERE mem_id = $selectedMemID;";
                    try {
                        // prepare query for execution
                       $stmt = $con->prepare($query2);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                    $query3 = "UPDATE  booking SET  status = 'Cancelled' WHERE  mem_id = $selectedMemID and status != ‘Finished’;";
                    try {
                        // prepare query for execution
                       $stmt = $con->prepare($query2);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                    echo "<h4> Done! </h4>";
                }
                else if($_POST['inputType'] == 5){
                    // echo "<h1> WOO5! </h1>";
                    $query = "SELECT  date_booked, period, status, property.prop_id, street_num,street_name, apt_num FROM  property, booking WHERE  property.prop_id = booking.prop_id and booking.mem_id = $selectedMemID ORDER BY  date_booked DESC";
                    try {
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // Execute the query
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        echo "<table border=1><caption>Booking Summary</caption>";
                        echo "<tr><th>Date Booked</th><th>Period</th><th>Status</th><th>Address </th><th> Link </th></tr>";
                        foreach ($result as $tuple){
                            echo "<tr><td>".$tuple['date_booked']."</td>";
                            echo "<td>".$tuple['period']."</td>";
                            echo "<td>".$tuple['status']."</td>";
                            echo "<td>".$tuple['street_num']." ".$tuple['street_name']."</td>";
                            echo "<td>";
                            echo "<a href='property.php?id={$tuple['prop_id']}'>";
                            echo "Go to property"."</a>";
                            echo "</td>";
                        }
                        echo "</table>";
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                }
                else if($_POST['inputType'] == 4){
                    // echo "<h1> WOO4! </h1>";
                    $query = "SELECT  date_booked, period, status, property.prop_id, street_num,street_name, apt_num FROM  property, booking WHERE  property.prop_id = booking.prop_id and booking.mem_id = $selectedMemID ORDER BY  date_booked DESC";
                    try {
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // Execute the query
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        echo "<table border=1><caption>Booking Summary</caption>";
                        echo "<tr><th>Date Booked</th><th>Period</th><th>Status</th><th>Address </th></tr>";
                        foreach ($result as $tuple){
                            echo "<tr><td>".$tuple['date_booked']."</td>";
                            echo "<td>".$tuple['period']."</td>";
                            echo "<td>".$tuple['status']."</td>";
                            echo "<td>".$tuple['street_num']." ".$tuple['street_name']."</td>";
                        }
                        echo "</table>";
                    }catch (Exception $e){
                        die(var_dump($e));
                    }

                    $query2 = "SELECT  date_added, comment, reply, property.prop_id, street_num, street_name, apt_num FROM  property, comments WHERE  property.prop_id = comments.prop_id and comments.mem_id = $selectedMemID ORDER BY  date_added DESC";

                    try {
                        // prepare query for execution
                        $stmt = $con->prepare($query2);
                        // Execute the query
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        echo "<table border=1><caption>Comments Summary</caption>";
                        echo "<tr><th>Date Added</th><th>Comment</th><th>Reply</th><th>Property ID</th><th>Address</th><th>Link</th></tr>";
                        foreach ($result as $tuple){
                            echo "<tr><td>".$tuple['date_added']."</td>";
                            echo "<td>".$tuple['comment']."</td>";
                            echo "<td>".$tuple['reply']."</td>";
                            echo "<td>".$tuple['prop_id']."</td>";
                            echo "<td>".$tuple['street_num']." ".$tuple['street_name']."</td>";
                            echo "<td>";
                            echo "<a href='property.php?id={$tuple['prop_id']}'>";
                            echo "Go to property"."</a>";
                            echo "</td>";
                        }
                        echo "</table>";
                    }catch (Exception $e){
                        die(var_dump($e));
                    }

                }
            }
            if (isset($_POST['inputType'], $_POST['property_id'])) {
                $selectedPropID = $_POST['property_id'];
                if ($_POST['inputType'] == 2){
                    // echo "<h1> WOO2! </h1>";
                    $query = "DELETE from comments WHERE  prop_id = $selectedPropID;";
                    try {
                        // prepare query for execution
                       $stmt = $con->prepare($query);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                    $query2 = "DELETE from property WHERE  prop_id = $selectedPropID;";
                    try {
                        // prepare query for execution
                       $stmt = $con->prepare($query2);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                    $query3 = "DELETE from booking WHERE prop_id = $selectedPropID;";
                    try {
                        // prepare query for execution
                       $stmt = $con->prepare($query2);
                        // Execute the query
                        $stmt->execute();
                    }catch (Exception $e){
                        die(var_dump($e));
                    }
                    echo "<h4> Done! </h4>";
                }
                else if($_POST['inputType'] == 3){
                    // echo "<h1> WOO3! </h1>";
                    $query = "SELECT  date_booked, period, status, property.prop_id, street_num, street_name, apt_num FROM  property, booking WHERE property.prop_id = booking.prop_id and property.prop_id = $selectedPropID ORDER BY date_booked DESC";

                    try {
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // Execute the query
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        echo "<table border=1><caption>Booking Summary</caption>";
                        echo "<tr><th>Date Booked</th><th>Period</th><th>Status</th><th>Property ID</th><th>Address</th><th>Link</th></tr>";
                        foreach ($result as $tuple){
                            echo "<tr><td>".$tuple['date_booked']."</td>";
                            echo "<td>".$tuple['period']."</td>";
                            echo "<td>".$tuple['status']."</td>";
                            echo "<td>".$tuple['prop_id']."</td>";
                            echo "<td>".$tuple['street_num']." ".$tuple['street_name']."</td>";
                            echo "<td>";
                            echo "<a href='property.php?id={$tuple['prop_id']}'>";
                            echo "Go to property"."</a>";
                            echo "</td>";
                        }
                        echo "</table>";
                    }catch (Exception $e){
                        die(var_dump($e));
                    }

                    $query2 = "SELECT  date_added, comment, reply, property.prop_id, street_num, street_name, apt_num FROM  property, comments WHERE  property.prop_id = comments.prop_id and property.prop_id = $selectedPropID ORDER BY  date_added DESC";

                    try {
                        // prepare query for execution
                        $stmt = $con->prepare($query2);
                        // Execute the query
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        echo "<table border=1><caption>Comments Summary</caption>";
                        echo "<tr><th>Date Added</th><th>Comment</th><th>Reply</th><th>Property ID</th><th>Street Number</th><th>Street Name</th></tr>";
                        foreach ($result as $tuple){
                            echo "<tr><td>".$tuple['date_added']."</td>";
                            echo "<td>".$tuple['comment']."</td>";
                            echo "<td>".$tuple['reply']."</td>";
                            echo "<td>".$tuple['prop_id']."</td>";
                            echo "<td>".$tuple['street_num']."</td>";
                            echo "<td>".$tuple['street_name']."</td>";
                        }
                        echo "</table>";
                    }catch (Exception $e){
                        die(var_dump($e));
                    }

                }
            }
        ?>
</body>
</html>