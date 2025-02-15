<?php
session_start();
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "book_management";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
