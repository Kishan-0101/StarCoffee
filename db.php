<?php

$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME') ?: 'starcoffee';

if ($username === false || $password === false) {
    die('Database configuration is missing. Set DB_USER and DB_PASSWORD in the PHP environment.');
}

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>