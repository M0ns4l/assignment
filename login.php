<?php
session_start();

// Database connection
require_once ("pdo.php");

 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the username contains an "@" symbol
        if (strpos($username, '@') === false) {
            // Log the error for a failed attempt
            error_log("Failed login attempt: Username must contain '@'. Username: $username", 0);
            echo "Login failed: Username must contain '@'.";
        } else {
            // Prepare and execute the SQL statement
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Successful login, log the success
                $_SESSION['user'] = $user['username'];
                error_log("Successful login for username: $username", 0);
                echo "Login successful. Welcome, " . htmlspecialchars($user['username']) . "!";
            } else {
                // Log the error for a failed attempt
                error_log("Failed login attempt for username: $username", 0);
                echo "Login failed: Invalid username or password.";
            }
        }
    }
?>

<!-- HTML form -->
<form method="post">
    <p>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
    </p>
    <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </p>
    <p>
        <input type="submit" value="Login">
    </p>
</form>
