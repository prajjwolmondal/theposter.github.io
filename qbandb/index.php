<!DOCTYPE HTML>
<html>
    <head>
        <title>Home - Queen's BnB</title>
        
        <!-- Materialize - Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">

        <!-- jQuery -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Materialize - Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
  
        <!-- Material icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Script for intializing parallax effect -->
        <script>
        $(document).ready(function(){
            $('.parallax').parallax();
        });
        </script>

    </head>
<body>

    <?php
        include_once 'navbar.php';
        echo navbar(0);
    ?>
        <div class = "parallax-container">
            <div class="parallax"><img id="image" src="images/homepage1.jpg"></div>
        </div>
    <div class="container" style="margin: 20px auto 20px auto;">

    <h3>Top properties</h3>


    <?php
        include 'config/connection.php';
        $query = "  SELECT prop_id, street_num, street_name, type, beds_avail, overall_rating 
                    FROM         property 
                    GROUP BY     overall_rating 
                    ORDER BY     overall_rating DESC";
        try {
            $stmt = $con->prepare($query); // prepare query for execution
            
            $stmt->execute(); // execute the query
     
            /* resultset */
            $result = $stmt->fetchAll();
            $resultString = "";
            $resultString .= <<<EOT
    <table class="responsive-table striped">
        <thead>
          <tr>
              <th data-field="id">Property ID</th>
              <th data-field="address">Address</th>
              <th data-field="type">Type</th>
              <th data-field="available">Available</th>
              <th data-field="rating">Rating</th>
              <th>Link</th>
          </tr>
        </thead>
        <tbody>
EOT;
            foreach ($result as $tuple) {
                $resultString .= "<tr>";
                $resultString .= "<td>" . $tuple['prop_id'] . "</td>"; // property ID
                $resultString .= "<td>" . $tuple['street_num'] . " " . $tuple['street_name'] . "</td>"; // address
                $resultString .= "<td>" . $tuple['type'] . "</td>"; // type
                $resultString .= "<td>" . $tuple['beds_avail'] . " beds </td>"; // beds available
                $resultString .= "<td>" . $tuple['overall_rating'] . "</td>"; // rating

                $resultString .= "<td> <a href='property.php?id={$tuple['prop_id']}' class='btn waves-effect waves-orange  '>Let's Go</a></td>";
                $resultString .= "</tr>";
            }
            
            $resultString .= <<<EOT
        </tbody>
    </table>
EOT;
            echo $resultString;
        }
        catch (Exception $e) {
            die(var_dump($e));
        }

        echo "<h3> Top Point of Interests </h3>";
        $query = "SELECT  point_of_interest
                  FROM  poi";
        try {
            $stmt = $con->prepare($query); // prepare query for execution
            
            $stmt->execute(); // execute the query
     
            /* resultset */
            $result = $stmt->fetchAll();
            $resultString = "";
            $resultString .= <<<EOT
    <table class="responsive-table striped">
        <tbody>
EOT;
            foreach ($result as $tuple) {
                $resultString .= "<tr>";
                $resultString .= "<td>" . $tuple['point_of_interest'] . "</td>";
                $resultString .= "</tr>";
            }
            
            $resultString .= <<<EOT
        </tbody>
    </table>
EOT;
            echo $resultString;
        }
        catch (Exception $e) {
            die(var_dump($e));
        }

        echo "<h3> Top Districts </h3>";
        $query = "SELECT  dist_name
                  FROM  district";
        try {
            $stmt = $con->prepare($query); // prepare query for execution
            
            $stmt->execute(); // execute the query
     
            /* resultset */
            $result = $stmt->fetchAll();
            $resultString = "";
            $resultString .= <<<EOT
    <table class="responsive-table striped">
        <tbody>
EOT;
            foreach ($result as $tuple) {
                $resultString .= "<tr>";
                $resultString .= "<td>" . $tuple['dist_name'] . "</td>";
                $resultString .= "</tr>";
            }
            
            $resultString .= <<<EOT
        </tbody>
    </table>
EOT;
            echo $resultString;
        }
        catch (Exception $e) {
            die(var_dump($e));
        }
    ?>


    </div>
    <div class = "parallax-container">
        <div class="parallax"><img id="image" src="images/homepage2.jpg"></div>
    </div>

<footer class="page-footer teal lighten-2" style="margin-top: 0;">
    <div class="nav-wrapper teal lighten-2">
      <div class="container">
        <div class="row">
          <div class="col l s20">
            <h5 class="white-text">About Us</h5>
            <p class="grey-text text-lighten-4">Founded in March of 2016 and based in Kingston, Ontario. QBandB is a not-so-trusted community marketplace for people to list, discover, and book unique accommodations. With a nonexistent customer service and a rapidly declining community of users, QBandB is the easiest way for people to monetize their extra space and showcase it to an audience of at least 3.</p>
            <p class="grey-text text-lighten-4">This website was created by Simon Zhang, Jay Zhao, and Prajjwol Mondal.</p>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
            &copy; 2016 QBandB
        </div>
      </div>
    </div>
</footer>
</body>
</html>