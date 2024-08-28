<?php 
try {
    // Corrected Database connection using PDO
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=automobiles_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Handle connection errors
    error_log("Database error: " . $e->getMessage(), 0);
    echo "Database connection error.";
}
?>
