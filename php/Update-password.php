<?php
$servername = "localhost:3306";
$username = "root";
$password = "ppp";
$dbname = "physicaltherapytoday";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
  // Retrieve email and new password values from form data
  $email = $_POST['email'];
  $new_password = $_POST['password'];

  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  $sql = "UPDATE therapist_profiles SET password=? WHERE email=?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $email);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Password updated successfully!";
  } else {
    echo "Error: Could not update password!";
  }
}

mysqli_close($conn);
?>
