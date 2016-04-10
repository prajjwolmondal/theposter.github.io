<!DOCTYPE HTML>
<html>
    <head>
        <?php 
            if (isset($_GET['id']))
                echo "<title>Edit Property - Queen's BnB</title>";
            else
                echo "<title>Create Property - Queen's BnB</title>";
        ?>
        
        <!-- Materialize - Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">

        <!-- jQuery -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Materialize - Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
  
        <!-- Material icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <script>
            $(document).ready(function() {

                $('select').material_select();

        
                var addressNumber = document.getElementById('addrNum');
                var postalElem = document.getElementById('postal');
                var priceElem = document.getElementById('price');
                var aptNumElem = document.getElementById('aptNum');
                
                var checkAddressNumber = function() {
                    if (isNaN(addressNumber.value)) {
                        addressNumber.setCustomValidity('please enter a valid street number');
                    } else {
                        addressNumber.setCustomValidity('');
                    }        
                };

                var checkApartmentNumber = function() {
                    if (isNaN(aptNumElem.value)) {
                        aptNumElem.setCustomValidity('please enter a valid apartment number');
                    } else {
                        aptNumElem.setCustomValidity('');
                    }        
                };

                var checkPostalCode = function(){
                    var regex = new RegExp(/^[A-Za-z]{1}\d[A-Za-z]{1}\d[A-Za-z]{1}\d$/);
                    if (!regex.test(postalElem.value)) {
                        postalElem.setCustomValidity('please enter a valid postal code');
                    }
                    else {
                        postalElem.setCustomValidity('');
                    }

                };

                var checkPrice = function() {
                    if (isNaN(priceElem.value) || price.value < 0 || price.value.length > 10) {
                        priceElem.setCustomValidity('please enter a valid integer');
                    } else {
                        priceElem.setCustomValidity('');
                    }        
                };
              
                addressNumber.addEventListener('change', checkAddressNumber, false);
                postalElem.addEventListener('change', checkPostalCode, false);
                priceElem.addEventListener('change', checkPrice, false);
                aptNumElem.addEventListener('change', checkApartmentNumber, false);
                
                var form = document.getElementById('edit-prop');
                form.addEventListener('submit', function(event) {
                    checkAddressNumber();
                    if (!this.checkValidity()) {
                        event.preventDefault();
                        addressNumber.focus();
                    }
                    checkPostalCode();
                    if (!this.checkValidity()) {
                        event.preventDefault();
                        postalElem.focus();
                    }
                    checkApartmentNumber();
                    if (!this.checkValidity()) {
                        event.preventDefault();
                        priceElem.focus();
                    }
                    checkPrice();
                    if (!this.checkValidity()) {
                        event.preventDefault();
                        aptNumElem.focus();
                    }
                }, false);
            });
        </script>

    </head>
<body>


    <?php


        include_once './getFunctions.php';

        include_once './navbar.php'; // include navbar
        echo navbar(0);
    ?>

    <div class="container">

    <?php
        include './config/connection.php';

        // if not logged in
        if (!isset($_SESSION['mem_id'])) {
            echo "<h3>You don't have permission to access this page.</h3>";
            echo "<p>You need to be logged in to create/edit a property.</p>";
            echo "<script>Materialize.toast(\"Please login to continue!\", 1500)</script>"; // display message
            die();
        }
        // if ID is set in $_GET, edit a property instead of inserting a new one
        else if (isset($_GET['id'])) {


            include './config/connection.php';
            $query = "  SELECT  * 
                        FROM    property natural join district
                        WHERE   prop_id = {$_GET['id']}; and mem_id = {$_SESSION['mem_id']}";

            try {

                // prepare query for execution
                $stmt = $con->prepare($query);

                // Execute the query
                $stmt->execute();

                $numRows = $stmt->rowCount();
         
                if ($numRows <= 0) {
                    echo "<h3>404: Property does not exist!</h3>";
                    echo "<p>We cannot find this property! :(</p>";
                    echo "<script>Materialize.toast(\"This property does not exist!\", 1500)</script>"; // display message
                    die();
                }
                else {
                    $tuple = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // if logged in user does not match property owner
                    if ($_SESSION['mem_id'] != $tuple['mem_id']) { 
                        echo "<h3>Hey, you don't own this property!</h3>";
                        echo "<p>You don't have permission to edit this property.</p>";
                        echo "<script>Materialize.toast(\"You don't have permission to edit this property.\", 1500)</script>"; // display message
                        die();
                    }

                    echo <<<EOT
                <script>
                    $(document).ready(function() {

                        document.getElementsByClassName('actionLabel')[0].innerHTML = 'Edit Property';
                        document.getElementsByClassName('actionLabel')[1].innerHTML = 'Edit Property<i class="material-icons right">send</i>';

                        document.getElementById('addrNum').value = {$tuple['street_num']};
                        document.getElementById('addrName').value = '{$tuple['street_name']}';
                        document.getElementById('aptNum').value = {$tuple['apt_num']};
                        document.getElementById('postal').value = '{$tuple['postal_code']}';
                        document.getElementById('price').value = {$tuple['price']};
                        document.getElementById('selectType').value = '{$tuple['type']}';
                        document.getElementById('selectDistrict').value = {$tuple['dist_id']};
                        document.getElementById('selectRoom').value = {$tuple['num_rooms']};
                        document.getElementById('selectBed').value = {$tuple['beds_avail']};
                        $('select').material_select();

                        $('#descriptionField').val('{$tuple['about_prop']}');

                        //document.getElementById('imageUploader')
                        //document.getElementById('imageUploaderName')

                        document.getElementById('fullKitchen').checked = {$tuple['full_kitchen']};
                        document.getElementById('laundry').checked = {$tuple['laundry']};
                        document.getElementById('pool').checked = {$tuple['pool']};
                        document.getElementById('gym').checked = {$tuple['gym']};
                        document.getElementById('sharedRoom').checked = {$tuple['shared_room']};
                        document.getElementById('privateRoom').checked = {$tuple['private_room']};
                        document.getElementById('closeToTransit').checked = {$tuple['close_to_transit']};

                    });
                </script>
EOT;


                }
            }
            catch (Exception $e) {
                die(var_dump($e));
            }
            $con = null;
        }
    ?>

        <div class="row">
            <div class="col s8 offset-s2">

                <div class="row">
                    <h3 class="actionLabel">Create Property</h3>
                </div>

                <div class="row">
                    <form class="col s12" name='edit-prop' id='edit-prop' action='edit-propertynow.php' method='post' enctype="multipart/form-data" >

                        <div class="row">
                            <h4>Address</h4>
                        </div>

                        <div class="row">
                            <div class="input-field col s2">
                                <input id="addrNum" type="text" class="validate" name="addrNum" maxlength="6" required>
                                <label for="addrNum">number</label>
                            </div>

                            <div class="input-field col s10">
                                <input id="addrName" type="text" class="validate" name="addrName" maxlength="40" required>
                                <label for="addrName">street name</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s3">
                                <input id="aptNum" type="text" class="validate" name="aptNum" maxlength="5">
                                <label for="aptNum">apartment number</label>
                            </div>

                            <div class="input-field col s3">
                                <input id="postal" type="text" class="validate" name="postal" maxlength="6" required>
                                <label for="postal">postal code</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s6">
                                <select id="selectType" name="type" required>
                                  <option value="-1" selected>choose a type</option>
                                    <?php
                                        $typeArr = getTypes(0);
                                        foreach ($typeArr as $type) {
                                            echo "<option value=\"{$type['type']}\">{$type['type']}</option>";
                                        }
                                    ?>

                                </select>
                                <label>Type</label>
                            </div>
                            <div class="input-field col s6">
                                <select id='selectDistrict' required name="districtID">
                                    <option value="-1" selected disabled>choose a district</option>
                                    <?php
                                        $distArr = getDistricts(0);
                                        foreach ($distArr as $dist) {
                                            echo "<option value=\"{$dist['dist_id']}\">{$dist['dist_name']}</option>";
                                        }
                                    ?>
                                </select>
                                <label>District</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s2">
                                <select id="selectRoom" name="numRooms" required>
                                    <option value="-1" disabled selected>0</option>
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
                                    <option value="11">11</option>
                                    <option value="12">12+</option>
                                </select>
                                <label>number of rooms</label>
                            </div>

                            <div class="input-field col s2">
                                <select id="selectBed" name="bedsAvail" required>
                                    <option value="-1" disabled selected>0</option>
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
                                    <option value="11">11</option>
                                    <option value="12">12+</option>
                                </select>
                                <label>beds available</label>
                            </div>

                            <div class="input-field col s4">
                                <input id="price" type="number" class="validate" name="price" maxlength="10">
                                <label for="price">price per day ($CAD)</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">mode_edit</i>
                                <textarea id="descriptionField" class="materialize-textarea validate" length=400 maxlength=400 name="description"></textarea>
                                <label for="descriptionField">about this property</label>
                            </div>
                        </div>

                        <div class="file-field input-field">
                            <div class="waves-effect waves-light btn">
                                <span>Upload image</span>
                                <input id="imageUploader" type="file" name="propImg">
                            </div>
                            <div class="file-path-wrapper">
                                <input id="imageUploaderName" class="file-path" type="text" placeholder="upload an image" name="propImgName" disabled>
                            </div>
                        </div>


                        <br/>

                        <div class="row">
                            <h4>Features</h4>
                        </div>

                        <div class="row">
                            <p>
                              <input type="checkbox" id="fullKitchen" name="kitchen" value=1/>
                              <label for="fullKitchen">full kitchen</label>
                              &nbsp;
                              <input type="checkbox" id="laundry" name="laundry" value=1/>
                              <label for="laundry">laundry</label>
                              &nbsp;
                              <input type="checkbox" id="pool" name="pool" value=1/>
                              <label for="pool">pool</label>
                              &nbsp;
                              <input type="checkbox" id="gym" name="gym" value=1/>
                              <label for="gym">gym</label>
                            </p>
                            <p>
                              <input type="checkbox" id="sharedRoom" name="shared" value=1/>
                              <label for="sharedRoom">shared room</label>
                                &nbsp;
                              <input type="checkbox" id="privateRoom" name="private" value=1/>
                              <label class="tooltipped" data-position="top" data-delay="50" data-tooltip="Each guest is given their own room" for="privateRoom">private room</label>
                            </p>
                            <p>
                              <input type="checkbox" id="closeToTransit" name="closeToTransit" value=1/>
                              <label for="closeToTransit">close to transit</label>
                            </p>
                        </div>

                        <div class="row">
                            <button class="actionLabel waves-effect waves-light btn" name="submitBtn" value="1">
                                Create Property
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </form>
                 </div>
            </div>
        </div>
    </div>

</body>
</html>