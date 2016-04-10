<?php

    include './config/connection.php';

    // DEBUGGING
    /*
    echo "\$_GET: <br/>";
    foreach ($_GET as $key => $val) {
        echo $key . ": " . $val . "<br/>";
    }
    */

    /*
    echo "\$_POST: <br/>";
    foreach ($_POST as $key => $val) {
        echo $key . ": " . $val . "<br/>";
    }
    */

    // if submit button was clicked
    if (isset($_POST['submitBtn'])){

        session_start();

        include './config/connection.php';

        // count number of properties in property to figure out the prop_id
        $query = "SELECT max(prop_id) from qbandb.property;";
        $stmt1 = $con->prepare($query);
        $stmt1->execute();
        $tuple = $stmt1->fetch(PDO::FETCH_ASSOC);
        
        $propID = $tuple['max(prop_id)'] + 1;

        $memID = $_SESSION['mem_id']; // get from user logged in
        $propID;

        foreach ($_POST as $key => $val) {
            if (gettype($val) == "string")
                $_POST[$key] = htmlspecialchars($val);
            echo $key . ": ".$val . "<br/>";
        }


        $streetNum = $_POST['addrNum'];
        $streetName = $_POST['addrName'];
        $aptNum;
        if (!isset($_POST['aptNum'])) {
            $aptNum = -1;
            echo "s";
        }
        else {
            $aptNum = $_POST['aptNum'];
        }
        $postCode = $_POST['postal'];
        $numRooms = $_POST['numRooms'];
        $bedsAvail = $_POST['bedsAvail'];
        $distID = $_POST['districtID'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $type = $_POST['type'];

        $sumVotes = 0;
        $numVotes = 0;
        $overallRating = 0;

        $kitchen           = (isset($_POST['kitchen']) ? 1 : 0);
        $laundry           = (isset($_POST['laundry']) ? 1 : 0);
        $pool              = (isset($_POST['pool']) ? 1 : 0);
        $gym               = (isset($_POST['gym']) ? 1 : 0);
        $shared            = (isset($_POST['shared']) ? 1 : 0);
        $private           = (isset($_POST['private']) ? 1 : 0);
        $closeToTransit    = (isset($_POST['closeToTransit']) ? 1 : 0);

        if (!isset($_POST['aptNum']))
            $query = "  INSERT into `property`
                    values ($memID, $propID, $streetNum, '$streetName', NULL, '$postCode', $distID, $sumVotes, $numVotes, 
                            $overallRating, '$type', $numRooms, $bedsAvail, $price, $kitchen, $laundry,
                            $shared, $private, $pool, $closeToTransit, $gym, '$description');";     
        else
            $query = "  INSERT into `property`
                    values ($memID, $propID, $streetNum, '$streetName', $aptNum, '$postCode', $distID, $sumVotes, $numVotes, 
                            $overallRating, '$type', $numRooms, $bedsAvail, $price, $kitchen, $laundry,
                            $shared, $private, $pool, $closeToTransit, $gym, '$description');";    
     
        try {
            // prepare query for execution
            $stmt = $con->prepare($query);
            // Execute the query
            $stmt->execute();
        }
        catch (Exception $e){
            die(var_dump($e));
        }
        $con = null;
    }




    // file upload
    if (isset($_FILES['propImg'])) {
        $temp = $_FILES["propImg"]["tmp_name"];
        if (is_uploaded_file($temp)){

            $ext = pathinfo($_FILES["propImg"]["name"], PATHINFO_EXTENSION);
            $photoServerName = "img/property/" . $propID . '.' . ".png";

            // overwrite if file exists
            if(file_exists($photoServerName)) unlink($photoServerName);
                        
            move_uploaded_file($temp, $photoServerName);
            print $photoName." has been uploaded";
            print "<br/>";
            print "<img src=\"" . $photoServerName . "\"/><br/>";
        }
    }


    header("Location: /property.php?id={$propID}");



?>