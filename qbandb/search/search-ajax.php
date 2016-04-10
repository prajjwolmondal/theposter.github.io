<?php
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'search':

                $address = $_POST['address'];
                $beds = $_POST['beds'];
                $rooms = $_POST['rooms'];
                $price = $_POST['price'];
                $rating = $_POST['rating'];
                $district = $_POST['district'];
                $features = $_POST['features'];

                searchPropertyListings($address, $beds, $rooms, $price, $rating, $district, $features);
                break;
        }
    }

    function searchPropertyListings($address, $beds, $rooms, $price, $rating, $dist, $features) {

        include_once '../config/connection.php';

        $query =    "   SELECT * 
                        FROM property natural join district
                    ";

        $whereFlag = false;

        // address
        if ($whereFlag == false) {
            $query .= " WHERE ( street_num LIKE '%{$address}%' or
                                street_name LIKE '%{$address}%' or
                                postal_code LIKE '%{$address}%' )";
            $whereFlag = true;
        }
        else {
            $query .= " and ( street_num LIKE '%{$address}%' or
                                street_name LIKE '%{$address}%' or
                                postal_code LIKE '%{$address}%' )";
        }

        // beds available
        if ($beds == -1) {

        }
        else if ($whereFlag == false) {
            $query .= " WHERE beds_avail = {$beds}";
            if ($beds == 12)
                $query .= " WHERE beds_avail > {$beds}";
            $whereFlag = true;
        }
        else {
            $query .= " and beds_avail = {$beds}";
            if ($beds == 12)
                $query .= " and beds_avail > {$beds}";
        }

        // rooms
        if ($rooms == -1) {

        }
        else if ($whereFlag == false) {
            $query .= " WHERE num_rooms = {$rooms}";
            if ($rooms == 12)
                $query .= " WHERE num_rooms > {$rooms}";
            $whereFlag = true;
        }
        else {
            $query .= " and num_rooms = {$rooms}";
            if ($rooms == 12)
                $query .= " and num_rooms > {$rooms}";
        }

        // price
        if ($whereFlag == false) {
            $query .= " WHERE price >= " . substr($price[0], 1);
            if ($price[1] != 500) // no maximum if set to hard-coded maximum (500)
                $query .= " and price <= " . substr($price[1], 1);
            $whereFlag = true;
        }
        else {
            $query .= " and price >= " . substr($price[0], 1);
            if ($price[1] != 500) // no maximum if set to hard-coded maximum (500)
                $query .= " and price <= " . substr($price[1], 1);
        }

        // rating
        if ($whereFlag == false) {
            $query .= " WHERE overall_rating >= " . $rating[0] . " and overall_rating <= " . $rating[1];
            $whereFlag = true;
        }
        else {
            $query .= " and overall_rating >= " . $rating[0] . " and overall_rating <= " . $rating[1];
        }

        // district
        if ($dist == -1) {

        }
        else if ($whereFlag == false) {
            $query .= " WHERE dist_id = " . $dist;
            $whereFlag = true;
        }
        else {
            $query .= " and dist_id = " . $dist;
        }

        // features 
        foreach ($features as $key => $val) { // go through each feature
            if ($val == 0 || $val == 1) { // add if not indeterminate (purposely checked on or off)

                if ($whereFlag == false) { // first WHERE expression
                    $query .= " WHERE " . $key . " = " . $val;
                    $whereFlag = true;
                }
                else {
                    $query .= " and ". $key . " = " . $val;
                }
            }
        }


        try {
        
            // echo $query; // UNCOMMENT for DEBUGGING

            // prepare query for execution
            $stmt = $con->prepare($query);

            // Execute the query
            $stmt->execute();
     
            $result = $stmt->fetchAll();

            $resultString = "";
            $resultString .= "<ul class=\"collection\">";

            foreach ($result as $tuple) {

                $resultString .= "<li class=\"collection-item avatar\">";

                $address = $tuple['street_num'] . " " . $tuple['street_name'];
                if ($tuple['apt_num'])
                    $address .= " Apt #" . $tuple['apt_num']; // add apartment number if included
                $postCode = $tuple['postal_code'];
                $district = "<strong>District:</strong> " . $tuple['dist_name'];
                $price = "<strong>Price:</strong> $" . $tuple['price'] . " per day";
                $available = "<strong>Beds available:</strong> " . $tuple['beds_avail'];
                $rating = $tuple['overall_rating'];
/*
                if ($red) {
                    $resultString .= "<div class='row' style='background-color: red'>";
                }
                else {*/
                    $resultString .= "<div class='row'>";
  //              }


                $resultString .= <<<EOT

                <div class="col s4">
                    <img src="images/yuna.jpg" alt="" class="circle">
                    <span class="title"><a href=../property.php?id={$tuple['prop_id']}>{$address}</a><br/>{$postCode}</span>
                </div>
                <div class="col s3 offset-s9">
                    <a href="#!" class="secondary-content"><i class="material-icons">grade</i> {$rating}</a>
                </div>
            </div>
            <div class="row">
                <div class="col s3">
                    {$district}
                </div>
                <div class="col s3">
                    {$price}
                </div>
                <div class="col s3">
                    {$available}
                </div>
            </div>
            <div class="row">
                <div class="col s3"><strong>Features:</strong></div>
                <div class="row">
                    <div class="col s3">
EOT;

                if ($tuple['full_kitchen'])
                    $resultString .= "<span>&bull; Full Kitchen</span><br/>";
                else
                    $resultString .= "<span class=\"greyedOut\">&bull; Full Kitchen</span><br/>";

                if ($tuple['laundry'])
                    $resultString .= "<span>&bull; Laundry</span><br/>";
                else
                    $resultString .= "<span class=\"greyedOut\">&bull; Laundry</span><br/>";

                if ($tuple['pool'])
                    $resultString .= "<span>&bull; Pool</span><br/>";
                else
                    $resultString .= "<span class=\" greyedOut\">&bull; Pool</span><br/>";

                if ($tuple['gym'])
                    $resultString .= "<span>&bull; Gym</span>";
                else
                    $resultString .= "<span class=\"greyedOut\">&bull; Gym</span>";

                $resultString .= "</div><div class=\"col s3\">";

                if ($tuple['shared_room'])
                    $resultString .= "<span>&bull; Shared Room</span><br/>";
                else
                    $resultString .= "<span class=\"greyedOut\">&bull; Shared Room</span><br/>";

                if ($tuple['private_room'])
                    $resultString .= "<span>&bull; Private Room</span><br/>";
                else
                    $resultString .= "<span class=\"greyedOut\">&bull; Private Room</span><br/>";

                if ($tuple['close_to_transit'])
                    $resultString .= "<span>&bull; Close to Transit</span>";
                else
                    $resultString .= "<span class=\"greyedOut\">&bull; Close to Transit</span>";



                $resultString .= <<<EOT
                </div>
            </div>
EOT;
            }

            $resultString .= <<<EOT
        </ul>
EOT;
        }

        catch (Exception $e) {
            die(var_dump($e));
        }

        if ($result == []) // no results
            echo "No listings available :(";
        else
            echo $resultString;
        exit;
    }
?>