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

    // Check if the request is in DispatchLogs
    $dispatchQuery = "SELECT * FROM DispatchLogs WHERE RequestID = ?";
    $dispatchStmt = $conn->prepare($dispatchQuery);
    $dispatchStmt->bind_param("i", $rid);
    $dispatchStmt->execute();
    $dispatchResult = $dispatchStmt->get_result();

    // Check if the request is in PendingRequests
    $pendingQuery = "SELECT * FROM PendingRequests WHERE RequestID = ?";
    $pendingStmt = $conn->prepare($pendingQuery);
    $pendingStmt->bind_param("i", $rid);
    $pendingStmt->execute();
    $pendingResult = $pendingStmt->get_result();

    // Check if the request is in Recipients
    $recipientQuery = "SELECT * FROM RecipientRequest WHERE RequestID = ?";
    $recipientStmt = $conn->prepare($recipientQuery);
    $recipientStmt->bind_param("i", $rid);
    $recipientStmt->execute();
    $recipientResult = $recipientStmt->get_result();

    if ($dispatchResult->num_rows > 0) {
        // Request is in DispatchLogs, update BloodAvailable and delete records
        $dispatchRow = $dispatchResult->fetch_assoc();
        $updateBloodAvailableQuery = "UPDATE BloodAvailable SET Quantity = Quantity + ? WHERE Blood_Type = ?";
        $updateBloodAvailableStmt = $conn->prepare($updateBloodAvailableQuery);
        $updateBloodAvailableStmt->bind_param("is", $dispatchRow["Quantity"], $dispatchRow["Blood_Type"]);
        $updateBloodAvailableStmt->execute();

        // Delete record from DispatchLogs
        $deleteDispatchQuery = "DELETE FROM DispatchLogs WHERE RequestID = ?";
        $deleteDispatchStmt = $conn->prepare($deleteDispatchQuery);
        $deleteDispatchStmt->bind_param("i", $rid);
        $deleteDispatchStmt->execute();

        // Delete record from Recipients
        $deleteRecipientQuery = "DELETE FROM RecipientRequest WHERE RequestID = ?";
        $deleteRecipientStmt = $conn->prepare($deleteRecipientQuery);
        $deleteRecipientStmt->bind_param("i", $rid);
        $deleteRecipientStmt->execute();

        echo '<p>Request (ID: ' . $rid . ') successfully canceled. Blood quantity updated.</p>';
    } elseif ($pendingResult->num_rows > 0) {
        // Request is in PendingRequests, delete records
        $deletePendingQuery = "DELETE FROM PendingRequests WHERE RequestID = ?";
        $deletePendingStmt = $conn->prepare($deletePendingQuery);
        $deletePendingStmt->bind_param("i", $rid);
        $deletePendingStmt->execute();

        // Delete record from Recipients
        $deleteRecipientQuery = "DELETE FROM RecipientRequest WHERE RequestID = ?";
        $deleteRecipientStmt = $conn->prepare($deleteRecipientQuery);
        $deleteRecipientStmt->bind_param("i", $rid);
        $deleteRecipientStmt->execute();

        echo '<p>Request (ID: ' . $rid . ') successfully canceled from Pending Requests.</p>';
    } elseif ($recipientResult->num_rows > 0) {
        // Request is only in Recipients, delete record
        $deleteRecipientQuery = "DELETE FROM RecipientRequest WHERE RequestID = ?";
        $deleteRecipientStmt = $conn->prepare($deleteRecipientQuery);
        $deleteRecipientStmt->bind_param("i", $rid);
        $deleteRecipientStmt->execute();

        echo '<p>Request (ID: ' . $rid . ') successfully canceled from RecipientRequest.</p>';
    } else {
        echo '<p>No records found for Request ID: ' . $rid . '</p>';
    }

    // Close statements and connection
    $dispatchStmt->close();
    $pendingStmt->close();
    $recipientStmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly
    header("Location: cancel_request.html");
    exit();
}
?>
