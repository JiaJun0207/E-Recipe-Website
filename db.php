<?php
$host = 'localhost';
$username = 'root'; 
$password = '';     
$database = 'e_recipe_system'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>