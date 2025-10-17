<?php

$db_name = 'mysql:host=localhost;dbname=shop_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO($db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('connection failed: ' . $e->getMessage());
}

?>
