<?php
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "blood_bank_management_system"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $rid = $_GET["rid"];

    // Check in DispatchLogs
    $dispatchQuery = "SELECT * FROM DispatchLogs WHERE RequestID = ?";
    $dispatchStmt = $conn->prepare($dispatchQuery);
    $dispatchStmt->bind_param("i", $rid);
    $dispatchStmt->execute();
    $dispatchResult = $dispatchStmt->get_result();

    // Check in PendingRequests
    $pendingQuery = "SELECT * FROM PendingRequests WHERE RequestID = ?";
    $pendingStmt = $conn->prepare($pendingQuery);
    $pendingStmt->bind_param("i", $rid);
    $pendingStmt->execute();
    $pendingResult = $pendingStmt->get_result();

    // Display result
    echo '<div>';
    if ($dispatchResult->num_rows > 0) {
        $row = $dispatchResult->fetch_assoc();
        echo '<p>Request ID: ' . $row["RequestID"] . '</p>';
        echo '<p>Status: Dispatched</p>';
        echo '<p>Blood Type: ' . $row["Blood_Type"] . '</p>';
        echo '<p>Quantity: ' . $row["Quantity"] . '</p>';
        echo '<p>Delivery Date: Within 5 days</p>';
    } elseif ($pendingResult->num_rows > 0) {
        $row = $pendingResult->fetch_assoc();
        echo '<p>Request ID: ' . $row["RequestID"] . '</p>';
        echo '<p>Status: Pending</p>';
        echo '<p>Blood Type: ' . $row["Blood_Type"] . '</p>';
        echo '<p>Quantity: ' . $row["Quantity"] . '</p>';
    } else {
        echo '<p>No records found for Request ID: ' . $rid . '</p>';
    }
    echo '</div>';

    // Close statements and connection
    $dispatchStmt->close();
    $pendingStmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly
    header("Location: track_request.html");
    exit();
}
?>
