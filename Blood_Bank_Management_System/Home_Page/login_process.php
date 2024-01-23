<?php
// Add your database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_bank_management_system";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection error". $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example code to check username and password from the database
    $loginQuery = "SELECT * FROM recipientregistration WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($loginQuery);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        // Login successful
        header('Content-Type: application/json');
        echo json_encode(array('success' => $success));
    }
}

// If login fails or if the request is not POST, redirect back to the login page with an error parameter
header("Location: Register_Page.html?error=1");
exit();

// Add your database connection closing code here

?>