<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 02/11/18
 * Time: 4:14 AM
 */

$servername   = "beacandid";
$database = "candid_database";
$username = "root";
$password = "password";
$port = "8889";

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
  echo "Connected successfully";
?>