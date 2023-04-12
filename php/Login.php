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

if(isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if email exists
  $sql = "SELECT * FROM therapist_profiles WHERE email='$email'";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die('Error: ' . mysqli_error($conn));
  }

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    // Verify password
    if (password_verify($password, $row['password'])) {
      echo "Login successful!";
      // Set session variables
      session_start();
      $_SESSION['email'] = $email;
      // Redirect to my-account.html
      header("Location: ../my-account.html");
      exit();
    } else {
      echo "Error: Incorrect password!";
    }
  } else {
    echo "Error: Email not found!";
  }

  // Close connection
  mysqli_close($conn);
}
?>
