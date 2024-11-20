<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bookings
$sql = "SELECT booking_id, flight_id, name, email, phone, passengers, departure_date, total_price, ic_no, status FROM bookings";
$result = $conn->query($sql);

// Update booking details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_details'])) {
        $booking_id = $_POST['booking_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $departure_date = $_POST['departure_date'];
        $status = $_POST['status'];

        // Update query
        $update_sql = "UPDATE bookings SET name=?, email=?, phone=?, departure_date=?, status=? WHERE booking_id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssisi", $name, $email, $phone, $departure_date, $status, $booking_id);

        if ($stmt->execute()) {
            echo "<script>alert('Details updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating details: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('manage.png'); /* Change this to your image path */
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            color: #333;
        }
        h1 {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 123, 255, 0.8);
            color: #fff;
            font-size: 24px;
            margin: 0;
        }
        .container {
            width: 90%;
            margin: 30px auto;
            padding: 20px 0;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #343a40;
            color: white;
            padding: 12px;
        }
        td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        input[type="text"], input[type="email"], input[type="date"], select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 14px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        button.cancel {
            background-color: #dc3545;
        }
        button.cancel:hover {
            background-color: #c82333;
        }
        .footer {
            text-align: center;
            margin: 20px 0;
        }
        .footer .home-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }
        .footer .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Manage Bookings</h1>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Flight ID</th>
                    <th>Name</th>
                    <th>IC No</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Passengers</th>
                    <th>Departure Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="post">
                                <td><?= $row['booking_id'] ?></td>
                                <td><?= $row['flight_id'] ?></td>
                                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required></td>
                                <td><?= $row['ic_no'] ?></td>
                                <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required></td>
                                <td><input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" required></td>
                                <td><?= $row['passengers'] ?></td>
                                <td><input type="date" name="departure_date" value="<?= $row['departure_date'] ?>" required></td>
                                <td>RM <?= number_format($row['total_price'], 2) ?></td>
                                <td>
                                    <select name="status" required>
                                        <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                                    <button type="submit" name="update_details">Save</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="no-data">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <a href="admin-dashboard.php" class="home-button">Back to Dashboard</a>
    </div>

</body>
</html>

<?php
$conn->close();
?>
