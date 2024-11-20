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

// Get the search parameters from the form
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';
$passengers = isset($_GET['passengers']) ? $_GET['passengers'] : 1;

// SQL query to find matching flights (removed departure_date filter)
$sql = "SELECT * FROM flights WHERE 
        departure_city LIKE :from 
        AND destination_city LIKE :to";

$stmt = $pdo->prepare($sql);

// Bind parameters to the query
$stmt->bindValue(':from', "%$from%");
$stmt->bindValue(':to', "%$to%");

// Execute the query and fetch results
$stmt->execute();
$flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
            background-color: #fafafa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(0, 123, 255, 0.7); /* Semi-transparent blue background */
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 24px;
            z-index: 1; /* Ensures text appears on top of the video */
        }

        .container {
            flex: 1; /* Ensures this section takes up the available space */
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            z-index: 1; /* Ensures the table content is above the video */
        }

        .back-button {
            background-color: #007BFF; /* Blue color */
            color: white;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            border-radius: 8px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        table th {
            background-color: #007BFF; /* Blue color */
            color: white;
        }

        table td a {
            color: #0056b3; /* Dark blue for links */
            text-decoration: none;
            font-weight: bold;
        }

        table td a:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <video class="video-background" autoplay muted loop>
        <source src="use.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <header>
        Flight Search Results
    </header>
    
    <div class="container">
        <a href="search.html" class="back-button">Back to Search</a>

        <?php if ($flights): ?>
            <table>
                <thead>
                    <tr>
                        <th>Flight ID</th>
                        <th>Departure City</th>
                        <th>Destination City</th>
                        <th>Price (RM)</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($flights as $flight): ?>
                        <tr>
                            <td><?= htmlspecialchars($flight['flight_id']) ?></td>
                            <td><?= htmlspecialchars($flight['departure_city']) ?></td>
                            <td><?= htmlspecialchars($flight['destination_city']) ?></td>
                            <td>RM<?= htmlspecialchars($flight['price']) ?></td>
                            <td><a href="flight detail.php?flight_id=<?= htmlspecialchars($flight['flight_id']) ?>">View Details</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-results">
                <p>No flights found matching your criteria.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2024 Flight Reservation System. All rights reserved.
    </footer>
</body>
</html>
