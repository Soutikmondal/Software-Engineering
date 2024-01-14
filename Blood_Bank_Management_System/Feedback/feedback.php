<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_bank_management_system";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

// Check if the form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $username = $_POST["username"];
    $requestid = $_POST["requestid"];
    $feedbacktext = $_POST["feedbacktext"];

    // Fetch RID from recipientregistration based on the provided username using prepared statement
    $sqlFetchRid = "SELECT RID FROM recipientregistration WHERE username = ?";
    $stmtFetchRid = $conn->prepare($sqlFetchRid);
    $stmtFetchRid->bind_param("s", $username);
    $stmtFetchRid->execute();
    $resultFetchRid = $stmtFetchRid->get_result();

    if ($resultFetchRid->num_rows > 0) {
        // Fetch the RID
        $rowFetchRid = $resultFetchRid->fetch_assoc();
        $rid = $rowFetchRid["RID"];

        // Insert data into the feedback table
        $sqlInsertFeedback = "INSERT INTO feedback (RID, RequestID, FeedbackText)
                              VALUES (?, ?, ?)";
        $stmtInsertFeedback = $conn->prepare($sqlInsertFeedback);
        $stmtInsertFeedback->bind_param("iss", $rid, $requestid, $feedbacktext);

        // Perform the query
        if ($stmtInsertFeedback->execute()) {
            $message = "Your feedback has been accepted!";
            $class = "success-message";;
        } else {
            $message = "Your feedback can't be accepted!";
            $class = "error-message";  
            $stmtInsertFeedback->error;
        }

        // Close the statement for feedback table
        $stmtInsertFeedback->close();
    } else {
        $message = "Your usernamenot found!";
            $class = "error-message";
    }

    // Close the result set for recipientregistration table
    $resultFetchRid->close();
    $stmtFetchRid->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Result</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 80%; /* Adjusted for responsiveness */
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center; /* Centering content within the container */
    }

    h2 {
        color: #3498db;
    }

    .success-message {
        color: #27ae60;
        margin-top: 20px; /* Increased margin for better spacing */
    }

    .error-message {
        color: #e74c3c;
        margin-top: 20px; /* Increased margin for better spacing */
    }

    .go-home {
        margin-top: 30px; /* Increased margin for better spacing */
    }

    .go-home a {
        display: inline-block; /* Added to make the link a block element */
        text-decoration: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: #3498db;
        transition: background-color 0.3s ease;
    }

    .go-home a:hover {
        background-color: #2980b9;
    }
</style>

</head>
<body>

<div class="container">
    <h2>Feedback Record</h2>
    <div class="<?php echo $class; ?>">
        <p><?php echo $message; ?></p>
        <?php if ($class === "success-message"): ?>
            <p>Feedback Added.Thankyou!</p>
        <?php endif; ?>
    </div>
    <div class="go-home">
    <a href="http://localhost/Blood_Bank_Management_System/Home_Page.html">Go to Home</a>
    </div>
</div>

</body>
</html>
