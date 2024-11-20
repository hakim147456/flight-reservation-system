<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Body and Background Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('dashboard.png'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #000;
        }

        /* Container for content */
        .container {
            max-width: 900px;
            margin: 80px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        /* Header Styling */
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        /* Links styling (Buttons) */
        .links {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .links a {
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 18px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .links a:hover {
            background-color: #0056b3;
            transform: translateY(-3px); /* Subtle hover effect */
        }

        /* Logout Button Styling */
        .logout {
            display: block;
            margin-top: 40px;
            padding: 15px 30px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 6px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .logout:hover {
            background-color: #c82333;
        }

        /* Responsive Design for smaller screens */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .links {
                flex-direction: column;
                align-items: center;
            }

            .links a {
                margin-bottom: 10px;
                width: 80%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
        <p style="text-align: center; font-size: 1.2em;">This is your admin dashboard where you can manage bookings, view reports, and handle requests.</p>

        <div class="links">
            <a href="manage-booking.php">Manage Bookings</a>
            <a href="reports.php">View Reports</a>
            <a href="request.php">Manage Requests</a>
        </div>

        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>
