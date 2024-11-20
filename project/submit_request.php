<?php
// Database configuration
$host = 'localhost'; // Database host (change if using a different host)
$dbname = 'flight_reservation_system'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password (leave empty for default local MySQL setup)

try {
    // Create a new PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling

    // Capture form data from POST request
    $icNo = isset($_POST['icNo']) ? $_POST['icNo'] : null;  // IC number of the user
    $requestType = isset($_POST['requestType']) ? $_POST['requestType'] : null; // Type of request (cancel/change/other)
    $originalDepartureDate = isset($_POST['originalDepartureDate']) ? $_POST['originalDepartureDate'] : null;
    $newDepartureDate = isset($_POST['newDepartureDate']) ? $_POST['newDepartureDate'] : null;
    $otherRequest = isset($_POST['otherRequest']) ? $_POST['otherRequest'] : null;

    // Check if IC number exists in the database
    $checkIcNoQuery = "SELECT COUNT(*) FROM requests WHERE ic_no = :icNo";
    $stmtCheck = $pdo->prepare($checkIcNoQuery);
    $stmtCheck->bindParam(':icNo', $icNo);
    $stmtCheck->execute();
    $icNoExists = $stmtCheck->fetchColumn();

    if ($icNoExists == 0) {
        // IC number not found
        echo "
            <div style='background-image: url(\"free.png\"); background-size: cover; background-position: center; height: 100vh; text-align: center; font-family: Arial, sans-serif; color: red;'>
                <h2>No IC number found in our records!</h2>
                <p>Please check the IC number and try again.</p>
                <a href='request.html' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Back to Request</a>
            </div>
        ";
        exit(); // Stop further execution if IC number is not found
    }

    // Logic to nullify fields based on request type
    if ($requestType === 'cancel') {
        // Nullify change and other fields for cancellation requests
        $originalDepartureDate = null;
        $newDepartureDate = null;
        $otherRequest = null;
    } elseif ($requestType === 'change') {
        // Nullify other field for change requests
        $otherRequest = null;
    } elseif ($requestType === 'other') {
        // Nullify cancel and change fields for other requests
        $originalDepartureDate = null;
        $newDepartureDate = null;
    }

    // SQL query to insert data into the 'requests' table
    $query = "INSERT INTO requests (ic_no, request_type, original_departure_date, new_departure_date, other_request) 
              VALUES (:icNo, :requestType, :originalDepartureDate, :newDepartureDate, :otherRequest)";

    $stmt = $pdo->prepare($query); // Prepare the SQL query

    // Bind parameters to the prepared statement
    $stmt->bindParam(':icNo', $icNo); 
    $stmt->bindParam(':requestType', $requestType);
    $stmt->bindParam(':originalDepartureDate', $originalDepartureDate);
    $stmt->bindParam(':newDepartureDate', $newDepartureDate);
    $stmt->bindParam(':otherRequest', $otherRequest);

    // Execute the query
    $stmt->execute();

    // Success message after the request is inserted into the database
    echo "
        <div style='background-image: url(\"free.png\"); background-size: cover; background-position: center; height: 100vh; text-align: center; font-family: Arial, sans-serif; color: white;'>
            <h2>Your request has been successfully submitted!</h2>
            <a href='index.html' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Go to Home</a>
        </div>
    ";

} catch (PDOException $e) {
    // Error handling if the query fails
    echo "
        <div style='background-image: url(\"free.png\"); background-size: cover; background-position: center; height: 100vh; text-align: center; font-family: Arial, sans-serif; color: red;'>
            <h2>Oops! Something went wrong.</h2>
            <p>Error: " . htmlspecialchars($e->getMessage()) . "</p>
            <a href='index.html' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Go to Home</a>
        </div>
    ";
}
?>

