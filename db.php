<?php
$host = "localhost";
$user = "root";   // change if you use a different username
$pass = "";       // change if your MySQL has a password
$db   = "drobotic_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
