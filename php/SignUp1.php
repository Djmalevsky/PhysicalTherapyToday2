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
  session_start();
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['password'] = $_POST['password'];
  $_SESSION['confirm_password'] = $_POST['confirm_password'];

  mysqli_autocommit($conn, false);

  // Check if email already exists
  $sql = "SELECT * FROM therapist_profiles WHERE email='{$_SESSION['email']}'";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die('Error: ' . mysqli_error($conn));
  }

  if (mysqli_num_rows($result) > 0) {
    echo "Error: Email already exists!";
  } else {
    // Hash password using bcrypt
    $hash = password_hash($_SESSION['password'], PASSWORD_BCRYPT);

    // Insert new therapist profile into therapist_profiles table
    $sql = "INSERT INTO therapist_profiles (email, street_address, city, state, zip_code, password, first_name, middle_name, last_name, credentials, license_number, license_state, license_expiration, company, phone_number, education_bachelors, education_graduate, education_residency, education_fellowship, treat_one_on_one, billing_info) 
    VALUES ('{$_SESSION['email']}', 'N/A', 'N/A', 'N/A', 'N/A', '$hash', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '1970-01-01', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'No', 'N/A')";

    if (mysqli_query($conn, $sql)) {
      echo "New record created successfully";
      mysqli_commit($conn);
      // Redirect to sign-up-second-page.html
      header("Location: ../sign-up-second-page.html");
      exit();
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  }

  // Close connection
  mysqli_close($conn);
}
?>
