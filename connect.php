<?php
$servername = "localhost";
$username = "root";
$password = "secret";
$dbname = "csv";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// sql to create table
$sql = "CREATE TABLE csv (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
name VARCHAR(30) NOT NULL,
email VARCHAR(30) NOT NULL,
age INT(6) NOT NULL,
gender VARCHAR(30) NOT NULL,
phone INT(11) NOT NULL,
address VARCHAR(30) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table csv created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>