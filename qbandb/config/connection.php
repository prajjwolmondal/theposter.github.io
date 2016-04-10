<?php
// used to connect to the database
$host = "eu-cdbr-azure-west-d.cloudapp.net";
$db_name = "qbandb";
$username = "bd22e1627c7160";
$password = "91bad711";

try {
    $con = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
 
// show error
catch(Exception $exception){
    echo "Connection error: " . $exception->getMessage();
}
/*
 $con = new mysqli($host,$username,$password, $db_name);
 //$con = mysqli_connect($host,$username,$password, $db_name);
 // Check connection
 if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die();
  }
   */
?>