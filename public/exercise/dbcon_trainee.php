<?php
$servername = "localhost"; // e.g., "localhost"
$username = "root"; // e.g., "root"
$password = ""; // e.g., ""
$dbname = "driving_car_training_system"; // Name of the trainee database

// Create connection
$conn_trainee = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn_trainee->connect_error) {
    die("Connection failed: " . $conn_trainee->connect_error);
}
?>