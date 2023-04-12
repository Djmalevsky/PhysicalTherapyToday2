<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost:3306";
$username = "root";
$password = "ppp";
$dbname = "physicaltherapytoday";

// Start session
session_start();

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // ... (the rest of the $_POST fields)
  $street_address = $_POST['street_address'];
  $city = $_POST['City'];
  $state = $_POST['state'];
  $zip_code = $_POST['zip_code'];
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $last_name = $_POST['last_name'];
  $credentials = $_POST['credentials'];
  $license_number = $_POST['license_number'];
  $license_state = $_POST['license_state'];
  $license_expiration = $_POST['license_expiration'];
  $company = $_POST['company'];
  $phone_number = $_POST['phone_number'];
  $education_bachelors = isset($_POST['residency']) && in_array('Bachelors', $_POST['residency']) ? 1 : 0;
  $education_graduate = isset($_POST['residency']) && in_array('Graduate', $_POST['residency']) ? 1 : 0;
  $education_residency = isset($_POST['residency']) && in_array('Residency', $_POST['residency']) ? 1 : 0;
  $education_fellowship = isset($_POST['residency']) && in_array('Fellowship', $_POST['residency']) ? 1 : 0;
  $do_you_treat_one_on_one = isset($_POST['do_you_treat_one_on_one_in_your_sessions']) ? 1 : 0;
// Handle the uploaded file
    $profile_picture = '';
    if (isset($_FILES['upload_']) && $_FILES['upload_']['error'] == 0) {
        // Validate file type and size
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2 MB

        $file_tmp = $_FILES['upload_']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['upload_']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_extensions) && $_FILES['upload_']['size'] <= $max_file_size) {
            $file_name = uniqid() . '.' . $file_ext;
            $file_path = '../images/ProfilePics/' . $file_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($file_tmp, $file_path)) {
                $profile_picture = $file_path;
            } else {
                die("Failed to move the uploaded file.");
            }
        } else {
            die("Invalid file type or size.");
        }
    }
    // Retrieve email from session
    $email = $_SESSION['email'];

    // Update therapist profile in therapist_profiles table using prepared statements
    $sql = "UPDATE therapist_profiles SET first_name=?, middle_name=?, last_name=?, credentials=?, license_number=?, license_state=?, license_expiration=?, company=?, street_address=?, city=?, state=?, zip_code=?, phone_number=?, education_bachelors=?, education_graduate=?, education_residency=?, education_fellowship=?, treat_one_on_one=?, billing_info='N/A', profile_picture=? WHERE email=?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssssssssssiiiiisss', $first_name, $middle_name, $last_name, $credentials, $license_number, $license_state, $license_expiration, $company, $street_address, $city, $state, $zip_code, $phone_number, $education_bachelors, $education_graduate, $education_residency, $education_fellowship, $do_you_treat_one_on_one, $profile_picture, $email);

        if (mysqli_stmt_execute($stmt)) {
            echo "Therapist profile updated successfully";
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}
?>
