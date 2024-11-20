<?php
// Database connection settings
$host = 'localhost';
$dbname = 'flight_reservation_system';
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the flight ID from the query string
$flight_id = isset($_GET['flight_id']) ? $_GET['flight_id'] : '';

// SQL query to fetch flight details
$sql = "SELECT * FROM flights WHERE flight_id = :flight_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':flight_id', $flight_id);
$stmt->execute();

// Fetch the flight details
$flight = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flight) {
    die("Flight not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the booking details from the form
    $name = $_POST['name'];
    $ic_no = $_POST['ic_no']; // Get the IC No from the form
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $passengers = $_POST['passengers'];
    $departure_date = $_POST['departure_date']; // Capture departure date

    // Calculate total price based on passengers
    $price_per_person = $flight['price']; // Price from the database
    $total_price = $price_per_person * $passengers; // Multiply by the number of passengers

    // Insert booking into the bookings table
    $booking_sql = "INSERT INTO bookings (flight_id, name, ic_no, email, phone, passengers, departure_date, total_price) 
                    VALUES (:flight_id, :name, :ic_no, :email, :phone, :passengers, :departure_date, :total_price)";
    $booking_stmt = $pdo->prepare($booking_sql);
    $booking_stmt->bindValue(':flight_id', $flight_id);
    $booking_stmt->bindValue(':name', $name);
    $booking_stmt->bindValue(':ic_no', $ic_no); // Bind the IC No value
    $booking_stmt->bindValue(':email', $email);
    $booking_stmt->bindValue(':phone', $phone);
    $booking_stmt->bindValue(':passengers', $passengers);
    $booking_stmt->bindValue(':departure_date', $departure_date);
    $booking_stmt->bindValue(':total_price', $total_price);
    
    if ($booking_stmt->execute()) {
        echo "<p>Booking successful! Your total price is RM" . number_format($total_price, 2) . ". Your booking details will be sent to your email.</p>";
        // Add Home button after booking confirmation
        echo '<a href="index.html"><button style="padding: 10px 20px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Go to Home</button></a>';
    } else {
        echo "<p>Error occurred while booking the flight. Please try again later.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Flight</title>
    <style>
        /* Add your styles here */
        body {
    font-family: Arial, sans-serif;
    background-image: url('bookflight.avif'); /* Path to your background image */
    background-size: cover; /* Ensure the background image covers the entire page */
    background-position: center; /* Center the background image */
    color: #333;
    padding: 20px;
    background-attachment: fixed; /* Keeps the background image fixed during scroll */
}

        .booking-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .booking-form h2 {
            text-align: center;
            color: #3498db;
        }

        .booking-form input, .booking-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .booking-form button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }


        .booking-form button:hover {
            background-color: #2980b9;
        }

        .flight-details {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="flight-details">
        <h1>Flight Booking Details</h1>
        <h2>Flight #<?= htmlspecialchars($flight['flight_id']) ?></h2>
        <p><strong>From:</strong> <?= htmlspecialchars($flight['departure_city']) ?> (<?= htmlspecialchars($flight['departure_airport']) ?>)</p>
        <p><strong>To:</strong> <?= htmlspecialchars($flight['destination_city']) ?> (<?= htmlspecialchars($flight['destination_airport']) ?>)</p>
        <p><strong>Departure:</strong> <?= htmlspecialchars($flight['departure_time']) ?></p>
        <p><strong>Price:</strong> RM<?= htmlspecialchars($flight['price']) ?> per pax</p>
    </div>

    <div class="booking-form">
        <h2>Book Your Flight</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="ic_no" placeholder="IC No" required> <!-- New IC No field -->
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <select name="passengers" required>
                <option value="">Select Number of Passengers</option>
                <option value="1">1 Passenger</option>
                <option value="2">2 Passengers</option>
                <option value="3">3 Passengers</option>
                <option value="4">4 Passengers</option>
                <option value="5">5 Passengers</option>
            </select>

            <!-- Editable Departure Date (Date Picker) -->
            <input type="date" name="departure_date" value="" required>

            <button type="submit">Book Flight</button>
        </form>
    </div>
</body>
</html>
