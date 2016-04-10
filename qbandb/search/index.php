<!DOCTYPE HTML>
<html>
    <head>
        <title>Search - Queen's BnB</title>
        
        <!-- Materialize - Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">

        <!-- jQuery -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Materialize - Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>

        <!-- Material icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- NoUISlider -->
        <link href="./nouislider/nouislider.css" rel="stylesheet">
        <script type="text/javascript" src="./nouislider/nouislider.min.js"></script>

        <!-- Custom JS -->
        <script src="./search.js" type="text/javascript"></script>

        <!-- Custom CSS -->
        <link href="./search.css" rel="stylesheet">

    </head>
<body>

    <?php
        include_once '../getFunctions.php';

        include_once '../navbar.php';
        echo navbar(1);
    ?>

    <div class="container">
        <div class="row">
            <h3>Search Listings</h3>
        </div>

        <div class="row">
            <div class="input-field col s6">
                <input id="address" type="text" class="validate">
                <label id="addressLabel" for="address">Address</label>
            </div>
            <div class="col s3">
                <label>Beds Available</label>
                <select id='selectBeds'>
                    <option value="-1" selected>Any</option>
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
            </div>
            <div class="col s3">
                <label>Total Number of Rooms</label>
                <select id='selectRooms'>
                    <option value="-1" selected>Any</option>
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
            </div>
        </div>

        <div class="row">
            <div class="col s8">
                <label>Price per day ($CAD)</label>
                <div id="slider1"></div>
            </div>
            <div class="col s4">
                <label>Rating (1-10)</label>
                <div id="slider2"></div>
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col s6">
                <div class="input-field">
                    <select id="selectType">
                      <option value="-1" selected>All types</option>
                        <?php
                            $typeArr = getTypes(1);
                            foreach ($typeArr as $type) {
                                echo "<option>{$type['type']}</option>";
                            }
                        ?>

                    </select>
                    <label>Type</label>
                </div>
            </div>
            <div class="col s6">
                <div class="input-field">
                    <select id='selectDistricts'>
                        <option value="-1" selected>All districts</option>
                        <?php
                            $distArr = getDistricts(1);
                            foreach ($distArr as $dist) {
                                echo "<option value=\"{$dist['dist_id']}\">{$dist['dist_name']}</option>";
                            }
                        ?>
                    </select>
                    <label>District</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s2">
                <label>Features</label>
            </div>
            <div class="col s3">

                <input type="checkbox" id="fullKitchen" />
                <label for="fullKitchen">Full Kitchen</label>
                <br/>
                <input type="checkbox" id="laundry" />
                <label for="laundry">Laundry</label>
                <br/>
                <input type="checkbox" id="pool" />
                <label for="pool">Pool</label>
                <br/>
                <input type="checkbox" id="gym" />
                <label for="gym">Gym</label>

            </div>
            <div class="col s3">

                <input type="checkbox" id="sharedRm" />
                <label for="sharedRm">Shared Room</label>
                <br/>
                <input type="checkbox" id="privateRm" />
                <label for="privateRm">Private Room</label>
                <br/>
                <input type="checkbox" id="closeToTransit" />
                <label for="closeToTransit">Close to Transit</label>

            </div>
            <div class="col s3">
                <button class="btn resetBtn" type="submit" value="reset filters"/>reset filters <i class="material-icons">autorenew</i></button>
            </div>
        </div>

        <div class="row">

            <button class="btn searchBtn" type="submit" value="search" onClick="window.location='#results';">search <i class="material-icons">search</i></button>

        </div>
        <div class="row">
        
            <h3 id="results">Results</h3>

        </div>
        <div class="row">

            <div class="searchResults">Enter a query above!</div>

        </div>

    </div>



</body>
</html>