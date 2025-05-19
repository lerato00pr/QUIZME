<?php
$host = '127.0.0.1';
$db = 'qna_system';
$user = 'root'; // Default XAMPP username
$pass = ''; // Default XAMPP password

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage(); // Changed line to provide clearer output
    exit(); // Exiting to prevent further script execution if connection fails
}
?>

