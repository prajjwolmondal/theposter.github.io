<?php

// Create a user session or resume an existing one
session_start ();

?>
<script>


    $(document).ready(function(){
        // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
        $('.modal-trigger').leanModal();

        $('#loginNav').click(function(){
    		$('#emailLogin').removeClass('valid invalid');
    		$('#emailLogin').focus();

    		$('#passwordLogin').removeClass('valid invalid');
        });

        // make clicking logout link trigger click on hidden input element
        // to trigger form
        $('#logoutBtn').click(function(){ 
    		$('#hiddenLogout').click();
    		var val = $('#hiddenLogout').attr('name');
        });

    });

    function failedLogin(emailLogin) {

    	$('.errorMessage').text('Email/password combination not recognized. Login failed. :(');
    	$('#modal1').openModal({
      		in_duration: 300, // Transition in duration
    	});

    	$('#emailLogin').val(emailLogin);
    	$('#emailLogin').addClass('invalid');

    	$('#passwordLogin').focus();
    	$('#passwordLogin').addClass('invalid');
    	$('#passwordLogin').attr('autofocus', true);
            
    }

</script>

<style type="text/css">
a.brand-logo{
    padding-left: 20px;
}    
</style>

<!-- Login Modal -->
<div id="modal1" class="modal">
    <div class="row">
        <div class="col s8 offset-s2">

        	<br/>
            <h3>Login</h3>

            <!-- Error message -->
            <div class="row" >
            	<div class="col s12 errorMessage" style="color: red;">
            	</div>
            </div>

    		<!-- Login form -->
            <form class="col s12" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]);?>" method="post">
              <div class="row">
                <div class="input-field col s1">
                    <i class="material-icons">account_circle</i>
                </div>
                <div class="input-field col s11">
                  <input id="emailLogin" type="email" name="email" class="validate">
                  <label id="emailLoginLabel" for="emailLogin" class="">Email</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                  <input id="passwordLogin" type="password" name="password" class="validate">
                  <label for="passwordLogin">Password</label>
                </div>
              </div>
              <div class="row">
                <button class="btn modal-action waves-effect waves-light" name='loginBtn' type='submit'>Login </button>
              </div>
              <div class="row">
                Don't have an account? 
                <a href="{$directoryString}register.php">Register</a>.
                <br/>
              </div>
            </form>
        </div>
    </div>
</div>
<?php

echo submitLoginLogout();

// UNCOMMENT for DEBUGGING
/*
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br/>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br/>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br/>";
*/

/*
// UNCOMMENT for DEBUGGING
foreach ($_POST as $val) {
	echo $val;
}
*/

function submitLoginLogout () {

    $resultString = "";

    // if not already logged in and login requested
    // former prevents another "login" on refresh
    if (!isset($_SESSION['mem_id']) && isset($_POST['loginBtn'])) {
        

        include 'config/connection.php';

        $query = "  SELECT  first_name, mem_id, email, password, last_name
                    FROM    member 
                    WHERE   email=:email AND password=:password";

        try {
        
            // echo $query; // UNCOMMENT for DEBUGGING

            // prepare query for execution
            $stmt = $con->prepare($query);

            // bind parameters
            $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);

            $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            $numRows = $stmt->rowCount();

            // username and password do not match
            if ($numRows <= 0) {
                $resultString .= "<script>failedLogin(\"{$_POST['email']}\");</script>";
            }
            else {
                $myrow = $stmt->fetch(PDO::FETCH_ASSOC);

                // set session variables
                $_SESSION['mem_id'] = $myrow['mem_id'];
                $_SESSION['first_name'] =$myrow['first_name'];
                $_SESSION['last_name'] =$myrow['last_name'];

                // display message
                $resultString .= "<script>Materialize.toast(\"Welcome back {$myrow['first_name']}!\", 1500)</script>";
            }


        }
        catch (Exception $e) {
            die(var_dump($e));
        }
    }
    // if logged in and log out clicked
    else if (isset($_SESSION['mem_id']) && isset($_POST['logoutBtn'])) {
        
        $resultString .= "<script>Materialize.toast(\"Logged out successfully.\", 1500)</script>"; // display message

		// end session
		unset($_SESSION['mem_id']);
        unset($_SESSION['first_name']);
        unset($_SESSION['last_name']);
		session_destroy();

		// send to index.php if current page is dashboard or admin
		$currentPage = htmlspecialchars($_SERVER['REQUEST_URI']);
        //echo $currentPage;

		//if ($currentPage == '/qbandb/dashboard.php' || $currentPage == '/qbandb/admin.php') { // localhost
        if ($currentPage == '/dashboard.php' || $currentPage == '/admin.php') { //server
			//header("Location: /qbandb/index.php"); // localhost
            header("Location: /index.php"); // server
		}
    }
    
    return $resultString;
}

function navbar($directoryLevel) {

	$directoryString = "";
	for ($i = 0; $i < $directoryLevel; $i += 1) {
		$directoryString .= "../";
	}
	$directoryString .= "./";


	$returnString = "";
	$returnString .= <<<EOT
    <div class="navbar-fixed">
	<nav>
	  <div class="nav-wrapper teal lighten-2">
	    <a href="{$directoryString}" class="brand-logo">Queen's BnB</a>
		    <ul id="nav-mobile" class="right hide-on-med-and-down">
EOT;


	$navbarLinks = 	[
						"search/index.php" => "Search Listings"
					];

    // if logged in
    if (isset($_SESSION['mem_id'])) {
        $navbarLinks["dashboard.php"] = "Dashboard";
        $navbarLinks["user/profile.php?id={$_SESSION['mem_id']}"] = "{$_SESSION['first_name']}";
        $navbarLinks["admin.php"] = " &nbsp; ";
    }

	foreach ($navbarLinks as $page => $pageName) {
		$currentPage = htmlspecialchars($_SERVER['PHP_SELF']);

		// if not current page, make it a link
		//if (("/qbandb/" . $page) != $currentPage) { // for simon when developing on his localhost
		if (("/" . $page ) != $currentPage) { // for the server
			$returnString .= <<<EOT
				<li><a href="{$directoryString}{$page}" class="waves-effect waves-light">{$pageName}</a></li>
EOT;
		}
		else { // if this is the current page
			$returnString .= <<<EOT
				<li class="active" style="font-weight: bold;"><a href="{$directoryString}{$page}" class="waves-effect waves-light">{$pageName}</a></li>
EOT;
		}
	}

    // if logged in, show log out button
    if (isset($_SESSION['mem_id'])) {
	
    	$currentPage = htmlspecialchars($_SERVER['REQUEST_URI']);

		$returnString .= <<<EOT
		    	<li>
	    			<form class="col s12" action="{$currentPage}" method="post">
						<input id="hiddenLogout" type="submit" name="logoutBtn" style="display: none;"/>
	    				<a id="logoutBtn" class="waves-effect waves-light" type="submit" >Log out</a>
					</form>
				</li>
EOT;
	}
	else { // if not logged in, show register button
		$returnString .= <<<EOT
		    	<li><a href="{$directoryString}register.php" class="waves-effect waves-light">Register</a></li>
		    	<li><a id="loginNav" class="waves-effect waves-light modal-trigger" href="#modal1">Login</a></li>
EOT;
	}

	$returnString .= <<<EOT
		    </ul>        
		</div>
	</nav>
    </div>
EOT;
	return $returnString;
}


?>