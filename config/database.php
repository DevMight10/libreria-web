<?php
$host = 'localhost';
$dbname = 'libreria_adrimarth_db';
$username = 'root';
$password = '';

define('PROJECT_ROOT', dirname(__DIR__));

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
