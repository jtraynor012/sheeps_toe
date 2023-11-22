<?php
$host="sheeps-toe.cfx8iusvt6mw.us-east-1.rds.amazonaws.com";
$username="bigbaa";
$password="Easy1234";
$database="sheepstoe";

// Create a new PDO instance to connect to the MySQL database
try {
    $mysql = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);
    // set the PDO error mode to exception
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Handle connection errors
}

?>