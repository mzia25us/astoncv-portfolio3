<?php
//Database connection configuration
$host = 'localhost';
$user= 'dg250118283';
$password = "uPKfB7k0ok9lnGZbMKNiSc7dB";
$database = "dg250118283_astoncv";

//Create a new MySQLi connection.
$conn = new mysqli($host, $user, $password, $database);

//Check if the connection was successful.
if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}
?>