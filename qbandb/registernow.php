<!DOCTYPE HTML>
<html>
<body>

 <?php

 	include_once 'config/connection.php';


	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$pass = $_POST['pass'];
	$repass = $_POST['repass'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$faculty = $_POST['faculty'];
	$degree = $_POST['degree'];
	$year = $_POST['year'];
	$about = $_POST['about'];
	$credit = $_POST['credit'];
	$expiry = $_POST['expiry'];
	$cvv = $_POST['cvv'];

	$query="SELECT max(mem_id) from qbandb.member;";
	$stmt1 = $con->prepare($query);
    $stmt1->execute();
    $result=$stmt1->fetchAll();
    foreach ($result as $tuple){
    	$memid=$tuple['max(mem_id)'];
	}

	// Create a user session or resume an existing one
	session_start ();

	$_SESSION['mem_id'] = $memid;
	$_SESSION['first_name'] = $fname;
	$_SESSION['last_name'] = $lname;


   $query2= "INSERT INTO qbandb.member values ($memid+1, 0, '$fname', '$lname', $degree, $faculty, '$phone', $year, '$about', '$email', '$pass', '$credit', $expiry, $cvv);";
	$stmt = $con->prepare($query2);
    $stmt->execute();

  ?>
 
<meta charset="UTF-8">
<meta http-equiv="refresh" content="1; url=dashboard.php">
 
<script>
  window.location.href = "dashboard.php"
</script>
 
<title>Page Redirection</title>
 
<!-- Note: don't tell people to `click` the link, just tell them that it is a link. -->
You are all done! You will now be redirected to the dashboard <br>
If you are not redirected automatically, follow the <a href='dashbaord.php'>link to example</a>

</body>
</html>