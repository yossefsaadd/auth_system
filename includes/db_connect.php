<?php

$host = 'localhost';
$dbname = 'auth_system';    
$username = 'root';                 
$password = '';                     

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die(" failed connect with database: " . $e->getMessage());
}
?>
