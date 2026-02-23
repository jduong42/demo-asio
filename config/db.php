<?php
/**
 * Database Configuration
 * 
 * Connection settings for MySQL database
 */

// Database credentials
define('DB_HOST', getenv('MYSQL_HOST') ?: 'db');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'asio_db');
define('DB_USER', getenv('MYSQL_USER') ?: 'asio_user');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: 'asio_pass');
define('DB_CHARSET', 'utf8mb4');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset(DB_CHARSET);

// Return connection for use in other files
return $conn;
