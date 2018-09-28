
<?php

$servername = "localhost";
$username = "root";
$password = "secret";
$dbname = "csv";

unset($_COOKIE['chunk']);
unset($_COOKIE['chunks_lenght']);

$content = $_REQUEST['data'];
$lines = explode("\n", $content);
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
foreach($lines as $line){
    $csv_row = str_getcsv($line);
    $sql = "INSERT INTO csv (name, email, age, gender, phone, address)
		VALUES ($csv_row[0], $csv_row[1], $csv_row[2], $csv_row[3], $csv_row[4], $csv_row[5])";
    $conn->query($sql);
}

setcookie('chunk', $_REQUEST['chunk'], time() + (86400 * 30), "/"); // 86400 = 1 day
setcookie('chunks_lenght', $_REQUEST['chunks_lenght'], time() + (86400 * 30), "/"); // 86400 = 1 day

echo $_REQUEST['chunk'];