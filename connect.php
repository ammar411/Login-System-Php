<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';  // No password for XAMPP by default
$DATABASE = 'signupforms'; // Ensure this database exists

$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
