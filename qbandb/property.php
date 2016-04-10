    <!DOCTYPE HTML>
    <html>
        <head>
            <title>Property - Queen's BnB</title>
            
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
                    $('#comments').val('New Text');
                    $('#comments').trigger('autoresize');
                    $('.datepicker').pickadate({
                        selectMonths: true, // Creates a dropdown to control month
                        selectYears: 15 // Creates a dropdown of 15 years to control year
                    });
                }); 

            </script>
            <script src="property.js"></script>

            <style>
                .greyedOut {
                    color: lightgray;
                    text-decoration: line-through;
                }
            </style>

        </head>
    <body>


        <!-- PHP code for inserting new comment -->
        <?php

            // no property specified
            if (!isset($_GET['id']) || $_GET['id'] == '') {
                header ("Location: /qbandb/index.php");
            }


            include_once 'navbar.php';
            echo navbar(0);

            // not logged in
            if (!isset($_SESSION['mem_id'])) {
                echo "<script>Materialize.toast(\"You're not logged in! You will not be able to rate/comment on properties!\", 5000)</script>"; // display message
            
                echo <<<EOT
                        <script>
                            $(document).ready(function() {
                                document.getElementById('strangerRating').disabled = true;
                                $('#strangerRating').material_select();

                                document.getElementById('strangerCommentBtn').disabled = true;
                                document.getElementById('strangerCommentBox').setAttribute("disabled", "true");
                                document.getElementById('strangerCommentBox').value = "You must be logged in to comment!";
                            });
                        </script>
EOT;
            }

            echo "<div class=\"container\">";

            include 'config/connection.php';


            $currentMemID = -1;

            if (isset($_SESSION['mem_id']))
                $currentMemID = $_SESSION['mem_id']; // ID of user currently logged in

            // echo $currentMemID;
            $currentPropID = $_GET['id']; // ID of property currently viewing
            $date = new DateTime();
            $currentDate = $date->format('Y-m-d');
             
            // add new stranger/property comment and rating to property
            if (isset($_POST['strangerComment']) and isset($_POST['user_comment']) and isset($_POST['user_rating'])) {

                $comment = $_POST['user_comment'];
                $newRating = $_POST['user_rating'];
                $query = "INSERT into qbandb.comments 
                          values ($currentMemID, $newRating, '$comment', NULL, $currentPropID, '$currentDate');";
                try {
                    // prepare query for execution
                   $stmt = $con->prepare($query);
                    // Execute the query
                    $stmt->execute();
                }
                catch (Exception $e){
                    die(var_dump($e));
                }
                $query2 = " UPDATE property
                SET sum_votes = sum_votes + $newRating, num_votes = num_votes + 1, overall_rating = sum_votes / num_votes
                WHERE prop_id = $currentPropID;";
                try {
                    // prepare query for execution
                   $stmt = $con->prepare($query2);
                    // Execute the query
                    $stmt->execute();
                }catch (Exception $e){
                    die(var_dump($e));
                }
            }
            // add owner's reply
            else if (isset($_POST['ownerReply'])) {
                //echo "Owner's reply: " . $_POST['ownerReply'];

                $commenter_id = $_POST['ownerReply']; // commenter's id was passed through here

                /*echo "reply = {$_POST['user_comment']}
                                WHERE   prop_id = {$currentPropID}
                                and     mem_id = {$commenter_id}";
*/
                $query =    "   UPDATE  qbandb.comments
                                SET     reply = \"{$_POST['user_comment']}\"
                                WHERE   prop_id = {$currentPropID}
                                and     mem_id = {$commenter_id};
                            ";
                try {
                    // prepare query for execution
                    $stmt = $con->prepare($query);
                    
                    // Execute the query
                    $stmt->execute();
                }
                catch (Exception $e){
                    die(var_dump($e));
                }

            }

            $propID = $_GET['id'];

            $query = 
            "SELECT  mem_id, street_num, street_name, postal_code, type, num_rooms, beds_avail, overall_rating, price, dist_name, full_kitchen, laundry, shared_room, private_room, pool, close_to_transit, gym, first_name, last_name, overall_rating, about_prop
            FROM (property natural join district) natural join member
            WHERE prop_id = $propID";
            $property_owner_id = 0;

            try {

                // prepare query for execution
                $stmt = $con->prepare($query);

                // Execute the query
                $stmt->execute();

                /* resultset */
                $result = $stmt->fetchAll();

                // no results from query: property does not exist
                if (empty($result)) {
                    echo "<h3>404: Property does not exist!</h3>";
                    echo "<p>We cannot find this property! :(</p>";
                    echo "<script>Materialize.toast(\"This property does not exist!\", 1500)</script>"; // display message
                    die();
                }

                $done = true;

                foreach ($result as $tuple) {

                    $property_owner_id = $tuple['mem_id'];

                    if (isset($_SESSION['mem_id']) && $_SESSION['mem_id'] == $property_owner_id ) {

                        echo <<<EOT
                        <script>
                            $(document).ready(function() {
                                $('#editPropertyBtn').show();
                            });
                        </script>
EOT;
                    }     

                    // Adding booking to database
                    if (isset($_POST['datePicked'])){
                        if ($done == true){
                            $bookingPeriod = $_POST['datePicked'];
                            // echo "<h1>".$bookingPeriod."</h1>";

                            $bookingPeriod = htmlspecialchars($bookingPeriod);
                            $currentDate = htmlspecialchars($currentDate);
                            $bookingPeriod = date('Y-m-d', strtotime(str_replace('-', '/', $bookingPeriod)));
                            $currentDate = date('Y-m-d', strtotime(str_replace('-', '/', $currentDate)));

                            $query = 
                                "INSERT  into qbandb.booking 
                                 VALUES ($currentMemID, $currentPropID, $property_owner_id, '$bookingPeriod', 'Pending', '$currentDate');";
                            try {
                                // prepare query for execution
                                $stmt = $con->prepare($query);
                                // Execute the query
                                $stmt->execute();
                            }
                            catch (Exception $e){
                                die(var_dump($e));
                            }
                            $done = false;
                        }
                    }

                    echo <<<EOT
        <div class="row">
            <ul class="collection">
                <div class="row collection-item">
                    <div class="col 4 offset-s1 ">
                        <h3> {$tuple['street_num']} {$tuple['street_name']} </h3>
                        <h4> {$tuple['postal_code']} </h4>
                        <form action="edit-property.php?id={$currentPropID}" method="post">
                        <button class="btn waves-effect waves-light" type="submit" id="editPropertyBtn" name="editPropertyBtn" style="display:none">Edit Property
                        <i class="material-icons right">edit</i>
                        </button>
                        </form>
                    </div>
                    <div class="col 2 offset-s1">
                        <br/>
                        District: {$tuple['dist_name']} 
                        <br/>
                        Type: {$tuple['type']}
                        <br/>
                        Price: {$tuple['price']}
                    </div>
                    <div class="col 2 offset-s1">
                        <br/>
                        Beds Available: {$tuple['beds_avail']}
                        <br/>
                        Rooms: {$tuple['num_rooms']} 
                    </div>
                    <div class="col 1 offset-s1">
                        <br/>
                        <a href="#strangerRating" style="color: #26A69A;"><i class="material-icons">grade</i>{$tuple['overall_rating']}</a>
                    </div>

                </div>
                <div class="row collection-item">
                    <div class="col 4 collection-item avatar offset-s1">
                        <a href="user/profile.php?id={$tuple['mem_id']}">
                            <br/>
EOT;

                    if (file_exists ( 'img/user/{$tuple[\'mem_id\']}.png' ))
                        echo "<img src=\"img/user/{$tuple['mem_id']}.png\" class=\"circle\">";
                    else
                        echo "<img src=\"img/user/default.png\" class=\"circle\">";




                    echo <<<EOT
                            <h5> {$tuple['first_name']} {$tuple['last_name']} </h5>
                        </a>
                    </div>
                    <div class="col s3 offset-s1">
EOT;
                        if ($tuple['full_kitchen'] == 1){
                            echo "<span>&bull; Full Kitchen</span><br>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Full Kitchen</span><br/>";
                        if ($tuple['laundry'] == 1){
                            echo "<span>&bull; Laundry</span><br/>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Laundry</span><br/>";
                        if ($tuple['shared_room'] == 1){
                            echo "<span>&bull; Shared Room</span><br/>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Shared Room</span><br/>";
                        if ($tuple['private_room'] == 1){
                            echo "<span>&bull; Private Room</span><br/>";

                    echo "</div>";
                    echo "<div class=\"col s3\"> <br/>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Private Room</span><br/>";
                        if ($tuple['pool'] == 1){
                            echo "<span>&bull; Pool</span><br/>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Pool</span><br/>";
                        if ($tuple['close_to_transit'] == 1){
                            echo "<span>&bull; Close to Transit</span><br/>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Close to Transit</span><br/>";
                        if ($tuple['gym'] == 1){
                            echo "<span>&bull; Gym</span><br/>";
                        }
                        else echo "<span class=\"greyedOut\">&bull; Gym</span><br/>";
                    echo <<<EOT
                    </div>
                </div>
                <div class="row " >
                    <div class="col 12" style="width: 100%;" >
                        <h4 style="text-align: center;">"{$tuple['about_prop']}"</h4>
                    </div>
                </div>
                <div class="row collection-item">
                    <!-- Booking Form -->
                      <div class="row">
                        <form class="col s12 offset-s3" action="property.php?id={$currentPropID}" method="post">
                          <!-- Rating -->
                          <div class="input-field col s4">
                            <input type="date" name="datePicked" class="datepicker" id="datePicker" />
                            <label class="active" for="datePicker">Book a date... </label>
                          </div>
                            <button class="btn waves-effect waves-light" type="submit" id="strangerCommentBtn" name="strangerComment">Book
                            <i class="material-icons right">av_timer</i>
                            </button>
                        </form>
                    </div>
                </div>
EOT;

                }
            } catch (Exception $e) {
                die(var_dump($e));
            }   // End of try/catch

            $query = "SELECT first_name, last_name, date_added, comment, reply, rating, mem_id
                        FROM  comments natural join member
                        WHERE prop_id = $propID";
            try{
                // prepare query for execution
                $stmt = $con->prepare($query);
                // Execute the query
                $stmt->execute();
                /* resultset */
                $result = $stmt->fetchAll();

                echo <<<EOT
                <div class='row'>
                    <div class='col 11 offset-s1'>
                        <h4>Reviews</h4>
                    </div>
                </div>
EOT;
                foreach ($result as $tuple){

                    $commenter_id = $tuple['mem_id'];

                    //echo $currentMemID;
                    //echo $property_owner_id;
                    $flag = false;
                    // while going through all comments,
                    // if the person logged in has made a comment (or is the owner),
                    // disable the rating/commenting
                    if ($currentMemID == $commenter_id || $currentMemID == $property_owner_id) {
                        $flag = true;
                        echo <<<EOT
                <script>
                    $(document).ready(function() {

                        document.getElementById('strangerRating').disabled = true;
                        $('#strangerRating').material_select();

                        document.getElementById('strangerCommentBtn').disabled = true;
                        document.getElementById('strangerCommentBox').setAttribute("disabled", "true");

EOT;
                        $commentBoxValue = "";

                        if ($currentMemID == $commenter_id) {
                            $commentBoxValue = "document.getElementById('strangerCommentBox').value = \"You already made a comment!\";";
                        }
                        if ($currentMemID == $property_owner_id) { // precedence over having already made a comment
                            $commentBoxValue = "document.getElementById('strangerCommentBox').value = \"You cannot comment on your own property!\";";
                        }

                        echo <<<EOT
                        {$commentBoxValue}
                    });
                </script>
EOT;



                    }

                    echo <<<EOT
                
                <div class="row">
                    <div class="col s12 m8 offset-m2">
                        <div class="card blue-grey darken-1">
                            <div class="card-content white-text">
                                <span class="card-title">
                                    From <a href='user/profile.php?id={$tuple['mem_id']}'> {$tuple['first_name']} {$tuple['last_name']} </a>
                                </span>
                                 on {$tuple['date_added']}
                                <p>{$tuple['comment']}</p>
                                <br/>
                                <strong>Rating: </strong>{$tuple['rating']}
                            </div>
EOT;
                    // enable ability to add reply if a reply does not already exist
                    if ($tuple['reply'] == NULL) {
                        // if the ID if the currently logged in user matches the ID this property's owner
                        if ($property_owner_id == $currentMemID){

                            $currentPage = htmlspecialchars($_SERVER['REQUEST_URI']);

                            echo <<<EOT
                            <div class="card-action">
                                <div class="owner_reply">
                                    <button class='btn waves-effect waves-light' type='submit' id='btn'>Reply?
                                        <i class="material-icons right">trending_flat</i>
                                    </button>
                                </div>

                                <div id="ownerReplyTextArea" style="display: none">
                                    <div>
                                        <div class='row'>
                                            <form class='col s12' action='{$currentPage}' method='post'>
                                                <div class='row'>
                                                    <div class='input-field col s12'>
                                                    <textarea id='ownerCommentBox' length='500' class='materialize-textarea' name='user_comment'></textarea>
                                                    <label for='comments'>Your Comment (Optional)</label>
                                                    </div>
                                                </div>
                                                <button class='btn waves-effect waves-light' type='submit' name='ownerReply' value='$commenter_id'>Submit 
                                                    <i class='material-icons right'>trending_flat</i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
EOT;

                        }
                    }
                    echo <<<EOT
                        </div>
                    </div>
                </div>
EOT;

                    if ($tuple['reply'] != NULL) {
                        echo <<<EOT
                    <div class="row">
                        <div class="col s12 m7 offset-m3">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">
                                        From the owner:
                                    </span>
                                    <p>{$tuple['reply']}</p>
                                    <br/>
                                    <strong>Rating: </strong>{$tuple['rating']}
                                </div>
                            </div>
                        </div>
                    </div>
EOT;
                    }
                }
            }
            catch (Exception $e){
                die(var_dump($e));
            }
?>

<!-- Rating Form -->
  <div class="row">
    <form class="col s6 offset-s3" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]);?>" method="post">
        <!-- Rating -->
        <div class="input-field col s6">
            <select id="strangerRating" class="validate" name="user_rating" required>
              <option value="" disabled selected>Select your rating</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select>
        <label for="rating">Rating (Required)</label>
      </div>
        <!-- Comment -->
        <div class="row">
            <div class="input-field col s12">
              <textarea id="strangerCommentBox" length="500" class="materialize-textarea" name="user_comment"></textarea>
              <label for="comments">Your Comment (Optional)</label>
            </div>
        </div>
        <button class="btn waves-effect waves-light" type="submit" id="strangerCommentBtn" name="strangerComment">Submit
        <i class="material-icons right">send</i>
        </button>
    </form>
  </div>
</ul>


</div> <!-- end container -->

</body>
</html>