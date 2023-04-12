<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$servername = "localhost:3306";
$username = "root";
$password = "ppp";
$dbname = "physicaltherapytoday";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
