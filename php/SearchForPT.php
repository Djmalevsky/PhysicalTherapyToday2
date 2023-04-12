<?php
echo "PHP code is executing!<br>";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "ppp";
$dbname = "physicaltherapytoday";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check for form submission
if(isset($_POST['zipcode'])) {
    echo "Form submitted!<br>";

    // Sanitize input
    $zipcode = filter_var($_POST['zipcode'], FILTER_SANITIZE_STRING);

    // Prepare and execute query
    echo "SELECT * FROM therapist_profiles WHERE zip_code = ".$zipcode;

    $stmt = mysqli_prepare($conn, "SELECT * FROM therapist_profiles WHERE zip_code = ?");
    mysqli_stmt_bind_param($stmt, "s", $zipcode);
    mysqli_stmt_execute($stmt);
    if(mysqli_stmt_error($stmt)) {
       echo "Error: ".mysqli_stmt_error($stmt);
    }

    // Fetch results and display them
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['first_name'] . ' ' . $row['last_name'] . '<br>';
        }
    } else {
        echo "No results found.<br>";
    }

    // Redirect to search results page with zip code as parameter
    header("Location: ../search-results-page.php?zipcode=".$zipcode);
    exit();
	    // Add the following line to confirm that the redirect header is being executed
    echo "Redirecting to search results page with zip code ".$zipcode;
}
?>
