<?php
session_start(); // Start session on every page load

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prms_db";

// Create a secure connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    // Log the error in the server logs instead of showing it
    error_log("Database Connection Failed: " . $conn->connect_error);
    die("Something went wrong. Please try again later!"); // Hide error details from the user for security
}

// Set charset to UTF-8 for supporting special characters like emojis
$conn->set_charset("utf8mb4");

// Enable strict mode to prevent bad data insertion and SQL injection risks
$conn->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES'");
