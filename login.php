<?php
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "team";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // SQL injection prevention
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Retrieve user from database
    $sql = "SELECT * FROM reg WHERE email='$username' AND pwd='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Authentication successful, redirect to dashboard or homepage
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        // Authentication failed, display error message
        $error = "Invalid username or password.";
    }
}

$conn->close();
?>
