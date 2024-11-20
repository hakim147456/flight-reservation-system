<?php
// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flight_reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_type = $_POST['report_type'];
    $report_data = $_POST['report_data'];

    $sql = "INSERT INTO reports (report_type, report_data) VALUES ('$report_type', '$report_data')";

    if ($conn->query($sql) === TRUE) {
        echo "Report data has been saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Reservation Reporting</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the body takes up the full viewport height */
            background: url('subreport.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        header {
            background-color: rgba(52, 152, 219, 0.8); /* Semi-transparent header */
            padding: 15px 0;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 80%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        nav ul li a i {
            margin-right: 8px;
        }

        main {
            flex: 1; /* Make the main content take up remaining space */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }

        .reporting-dashboard {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
        }

        .reporting-dashboard h2 {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .report {
            margin-bottom: 20px;
        }

        .report label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .report input, .report select, .report textarea, .report button {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .report button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }

        .report button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        footer {
            background-color: rgba(52, 152, 219, 0.8); /* Semi-transparent footer */
            color: white;
            text-align: center;
            padding: 15px;
            position: relative;
            width: 100%;
            margin-top: auto; /* Push the footer to the bottom */
        }

        footer p {
            margin: 0;
        }

    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>A/H Flight Reservation - Reporting</h1>
            <nav>
                <ul>
                    <li><a href="index.html"><i class="fas fa-home"></i>Home</a></li>
                    <li><a href="search.html"><i class="fas fa-search"></i>Search Flights</a></li>
                    <li><a href="login.php"><i class="fas fa-tachometer-alt"></i>Admin Dashboard</a></li>
                    <li><a href="reporting.php"><i class="fas fa-chart-line"></i>Reporting</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <section class="reporting-dashboard">
            <h2>Flight Reservation Reports</h2>
            
            <form action="reporting.php" method="POST">
                <div class="report">
                    <label for="report_type">Report Type</label>
                    <select name="report_type" id="report_type" required>
                        <option value="Booking Summary">Booking Summary</option>
                        <option value="User Activity Report">User Activity Report</option>
                        <option value="Flight Status Report">Flight Status Report</option>
                        <option value="Popular Flight Routes">Popular Flight Routes</option>
                        <option value="Revenue Report">Revenue Report</option>
                        <option value="Cancellations Report">Cancellations Report</option>
                    </select>
                </div>
                
                <div class="report">
                    <label for="report_data">Report Data</label>
                    <textarea name="report_data" id="report_data" rows="5" required></textarea>
                </div>

                <div class="report">
                    <button type="submit">Submit Report</button>
                </div>
            </form>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2024 Flight Reservation System | All rights reserved</p>
    </footer>
</body>
</html>
