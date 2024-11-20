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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Details</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Full screen video background */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1; /* Places the video behind the content */
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: rgba(44, 62, 80, 0.8); /* Semi-transparent background */
            padding: 20px 0;
            text-align: center;
            z-index: 1;
        }

        header nav ul {
            list-style-type: none;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin: 0 15px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        header nav ul li a:hover {
            text-decoration: underline;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .flight-details {
            text-align: center;
        }

        .flight-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            text-align: left;
        }

        .flight-info div {
            flex: 1;
            margin-right: 20px;
        }

        .flight-info div:last-child {
            margin-right: 0;
        }

        .flight-info h2 {
            color: #2c3e50;
            font-size: 26px;
            margin-bottom: 10px;
        }

        .flight-info p {
            font-size: 18px;
            line-height: 1.6;
            color: #7f8c8d;
        }

        .flight-info p strong {
            color: #2c3e50;
        }

        .booking-options {
            text-align: center;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <video class="video-background" autoplay muted loop>
        <source src="ticket.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="search.html">Search Flights</a></li>
                <li><a href="admin-dashboard.html">Admin Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="flight-details">
            <h1>Flight Details</h1>

            <!-- Flight Information -->
            <div class="flight-info">
                <div>
                    <h2>Flight #<?= htmlspecialchars($flight['flight_id']) ?></h2>
                    <p><strong>From:</strong> <?= htmlspecialchars($flight['departure_city']) ?> (<?= htmlspecialchars($flight['departure_airport']) ?>)</p>
                    <p><strong>To:</strong> <?= htmlspecialchars($flight['destination_city']) ?> (<?= htmlspecialchars($flight['destination_airport']) ?>)</p>
                </div>
                <div>
                    <p><strong>Departure:</strong> <?= htmlspecialchars($flight['departure_time']) ?></p>
                    <p><strong>Arrival:</strong> <?= htmlspecialchars($flight['arrival_time']) ?></p>
                    <p><strong>Price:</strong> RM<?= htmlspecialchars($flight['price']) ?></p>
                </div>
            </div>

            <!-- Button to book flight -->
            <div class="booking-options">
                <a href="book-flight.php?flight_id=<?= htmlspecialchars($flight['flight_id']) ?>" class="btn">Book Flight</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Flight Reservation System. All rights reserved.</p>
    </footer>
</body>
</html>
