<?php
// Start the session to track the user
session_start();

// Database connection variables
$servername = "localhost"; // Your database server, usually localhost
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "flight_reservation_system"; // The name of your database

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Prepare and execute the SQL query to get the user
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);  // "s" means the parameter is a string
    $stmt->execute();
    $stmt->store_result();
    
    // Check if the username exists
    if ($stmt->num_rows > 0) {
        // Bind the result variables
        $stmt->bind_result($username, $hashed_password);
        $stmt->fetch();

        // Check if the password is correct
        if (password_verify($input_password, $hashed_password)) {
            // Password is correct, set session and redirect
            $_SESSION['username'] = $username;
            header('Location: user-profile.php');
            exit();
        } else {
            // Invalid password
            $error_message = 'Invalid password';
        }
    } else {
        // Invalid username
        $error_message = 'Invalid username';
    }

    $stmt->close(); // Close the prepared statement
}

// Close the database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - A/H Flight Reservation</title>
</head>
<body>
    <header>
        <h1>Login to A/H Flight Reservation</h1>
    </header>
    <main>
        <!-- Display error message if any -->
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        
        <form action="login-action.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.html">Sign up here</a></p>
    </main>
</body>
</html>
