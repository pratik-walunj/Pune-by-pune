<?php

$server = "localhost:3307";
$username = "root";
$password = "";
$dbname = "community";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

?>