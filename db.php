<?php

// Replace with your actual database credentials
$servername = "localhost";
$username = "daytrade_insight";
$password = "y#-mXT580?zb";
$dbname = "daytrade_insight";

// Create connection
$con1 = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con1->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Replace these with your actual API credentials
$api_key = 'dfwxhkf1tpgxrngf';
$access_token = 'QxVUmYgOUZut12ApMfuylZi7XdT3zR0p';

?>