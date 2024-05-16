<?php
// Connect to MySQL database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "team";
  $conn = new mysqli($servername, $username, $password, $dbname); 
  if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form fields
    $fname = clean_input($_POST["fname"]);
    $lname = clean_input($_POST["lname"]);
    $email = clean_input($_POST["Email"]);
    $pnum = clean_input($_POST["pnum"]);
    $pwd = clean_input($_POST["pwd"]);
    $cpwd = clean_input($_POST["cpwd"]);
    $gender = clean_input($_POST["gender"]);
    $agree = clean_input($_POST["agree"]);

    // Create an array to store error messages
    $errors = array();

    // Validate first name
    if (empty($fname)) {
        $errors['fname'] = "First name is required";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
        $errors['fname'] = "Only letters and white space allowed";
    }

    // Validate last name
    if (empty($lname)) {
        $errors['lname'] = "Last name is required";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
        $errors['lname'] = "Only letters and white space allowed";
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    // Validate phone number
    if (empty($pnum)) {
        $errors['pnum'] = "Phone number is required";
    } else if (!preg_match("/^[0-9]{10}$/", $pnum)) {
        $errors['pnum'] = "Invalid phone number format";
    }

    // Validate password
    if (empty($pwd)) {
        $errors['pwd'] = "Password is required";
    }

    // Validate re-entered password
    if (empty($cpwd)) {
        $errors['cpwd'] = "Please re-enter password";
    } else if ($pwd != $cpwd) {
        $errors['cpwd'] = "Passwords do not match";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Prepare SQL statement and insert data into the database
        $sql = "INSERT INTO reg (fname, lname, email, pnum, pwd, gender) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $fname, $lname, $email, $pnum, $pwd, $gender);
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Display error messages
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}

// Function to sanitize input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}