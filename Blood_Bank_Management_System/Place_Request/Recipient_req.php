<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_bank_management_system";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection error". $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form 
   
    $reqid = rand(1000, 9999);
    $name = $_POST["name"];
    $bloodType = $_POST["bloodType"];
    $quantity = $_POST["quantity"];
   
    $contact = $_POST["contact"];
    $address = $_POST["address"];
    $requestDate = date("Y-m-d"); //current date
    $username=$_POST["user_name"];
    $password=$_POST["password"];
    // Connect to the database (assuming you have a connection script)
    $loginQuery = "SELECT * FROM recipientregistration WHERE username = ? AND password = ?";
    $sql = $conn->prepare($loginQuery);
    $sql->bind_param("ss", $username, $password);
    $sql->execute();
    $result1 = $sql->get_result()->fetch_assoc();

    if ($result1) {
        // Login successful
       $rid=$result1['RID'];
    }else{
        echo"Wrong username and password";
    }
$sql->close();
    // Insert data into the Recipients table
    $query = "INSERT INTO RecipientRequest (RID,RequestID,RecipientName, Blood_Type, Quantity, RequestDate, ContactNumber, Address) VALUES (?,?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissisis",$rid,$reqid, $name, $bloodType, $quantity, $requestDate, $contact, $address);

    if ($stmt->execute()) {
        echo '<body style="background-color: #f4f4f4;">'; // Set the background color for the entire page
        echo '<div style="text-align: center; padding: 20px; background-color: #2ecc71; color: #fff; margin-top: 20px;">'; // Change the background color to #2ecc71
        echo '<p>Request submitted successfully!</p>';
        echo '<p>Your Request ID is: ' . $reqid . ' please note in down</p>'; 
        echo '<a href="http://localhost/Blood_Bank_Management_System/Home_Page.html" style="text-decoration: none; color: #fff; background-color: #333; padding: 8px 16px; border-radius: 5px;">Back to Home Page</a>';
        echo '</div>';
        echo '</body>';
    } else {
        echo "Error submitting request: " . $stmt->error;
    }
    $checkAvailabilityQuery = "SELECT * FROM BloodAvailable WHERE Blood_Type = ? AND Quantity >= ?";
    $checkAvailabilityStmt = $conn->prepare($checkAvailabilityQuery);
    $checkAvailabilityStmt->bind_param("si", $bloodType, $quantity);
    $checkAvailabilityStmt->execute();
    $result = $checkAvailabilityStmt->get_result();
    if ($result->num_rows > 0) {
        // Blood type and quantity are available, insert into DispatchLogs
        $insertDispatchQuery = "INSERT INTO DispatchLogs (RID,RequestID, Blood_Type, Quantity) VALUES (?,?, ?, ?)";
        $insertDispatchStmt = $conn->prepare($insertDispatchQuery);
        $insertDispatchStmt->bind_param("iiss",$rid, $reqid, $bloodType, $quantity);
        if($insertDispatchStmt->execute()==false) {
echo"Dispatch error".$insertDispatchStmt->error;
        }
         // Update BloodAvailable table
         $updateBloodAvailableQuery = "UPDATE BloodAvailable SET Quantity = Quantity - ? WHERE Blood_Type = ?";
         $updateBloodAvailableStmt = $conn->prepare($updateBloodAvailableQuery);
         $updateBloodAvailableStmt->bind_param("is", $quantity, $bloodType);
         if($updateBloodAvailableStmt->execute()==false) {
            echo"BloodAvailable error".$insertDispatchStmt->error;
                    }

       
    }else {
        // Blood type and/or quantity not available, insert into PendingRequests
        $insertPendingQuery = "INSERT INTO PendingRequests (RID, RequestID, Blood_Type, Quantity) VALUES (?, ?, ?)";
        $insertPendingStmt = $conn->prepare($insertPendingQuery);
        $insertPendingStmt->bind_param("iss", $rid,$reqid, $bloodType, $quantity);
       if( $insertPendingStmt->execute()==false) {
        echo"Dispatch error". $insertPendingStmt->error;
       }
    }

    // Close statement and connection
    $checkAvailabilityStmt->close();
    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly
    header("Location: Recipient_Request.html");
    exit();
}
?>


