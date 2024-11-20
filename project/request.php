<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection (update with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_request'])) {
    $id = intval($_POST['id']);
    $delete_sql = "DELETE FROM requests WHERE request_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Request deleted successfully.";
    } else {
        $message = "Error deleting request: " . $conn->error;
    }

    $stmt->close();
}

// Fetch requests from database
$sql = "SELECT request_id, request_type, original_departure_date, new_departure_date, ic_no, other_request FROM requests ORDER BY request_id DESC";
$result = $conn->query($sql);

// Function to return '-' if the date is NULL
function displayDate($date) {
    return $date ? htmlspecialchars($date) : '-';
}

function displayOtherRequest($other_request) {
    return $other_request ? htmlspecialchars($other_request) : '-';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('request.png'); /* Add your image path here */
            background-size: cover; /* Makes the background image cover the whole screen */
            background-position: center; /* Centers the background image */
            background-attachment: fixed; /* Keeps the background fixed while scrolling */
            color: #333;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            display: inline;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .delete-btn, .edit-btn {
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            margin-right: 10px;
        }
        .delete-btn {
            background-color: #dc3545;
            border: none;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .edit-btn {
            background-color: #007bff;
            text-decoration: none;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Requests</h1>

        <?php if (isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>IC No</th>
                        <th>Request Type</th>
                        <th>Original Departure Date</th>
                        <th>New Departure Date</th>
                        <th>Other Request</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['request_id']) ?></td>
                            <td><?= htmlspecialchars($row['ic_no']) ?></td>
                            <td><?= htmlspecialchars($row['request_type']) ?></td>
                            <td><?= displayDate($row['original_departure_date']) ?></td>
                            <td><?= displayDate($row['new_departure_date']) ?></td>
                            <td><?= displayOtherRequest($row['other_request']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="manage-booking.php?id=<?= htmlspecialchars($row['request_id']) ?>" class="edit-btn">Edit</a>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['request_id']) ?>">
                                        <button type="submit" name="delete_request" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No requests found.</p>
        <?php endif; ?>

        <a href="admin-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
