<?php
session_start();
require_once ("pdo.php");
// Check if the user is logged in and the name parameter is set
if (!isset($_SESSION['username'])) {
    die('Not logged in');
}

if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die('Name parameter missing');
}

$name = htmlspecialchars($_GET['name']);



$message = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Input validation
    if (strlen($_POST['make']) < 1) {
        $error = "Make is required";
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $error = "Mileage and year must be numeric";
    } else {
        // Insert data into the database
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute([
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ]);
        $message = "Record inserted";
    }
}

// Fetch all automobile records
$stmt = $pdo->query('SELECT make, year, mileage FROM autos');
$autos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Automobile Management</title>
</head>
<body>
    <h1>Tracking Autos for <?= htmlspecialchars($name) ?></h1>

    <?php
    if (!empty($message)) {
        echo "<p style='color: green;'>$message</p>";
    }

    if (!empty($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>

    <form method="post">
        <p>
            <label for="make">Make:</label>
            <input type="text" name="make" id="make">
        </p>
        <p>
            <label for="year">Year:</label>
            <input type="text" name="year" id="year">
        </p>
        <p>
            <label for="mileage">Mileage:</label>
            <input type="text" name="mileage" id="mileage">
        </p>
        <p>
            <input type="submit" value="Add">
        </p>
    </form>

    <h2>Automobiles</h2>
    <ul>
        <?php
        if (count($autos) > 0) {
            foreach ($autos as $auto) {
                echo "<li>" . htmlspecialchars($auto['year'])." ".htmlspecialchars($auto['make']) . " / " . htmlspecialchars($auto['mileage']) . "</li>";
            }
        } else {
            echo "<p>No entries found.</p>";
        }
        ?>
    </ul>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
