<?php
$servername = "mariadb.vamk.fi";
$username = "e2203079";
$password = "pAhqugNZY6W";
$database = "e2203079_database_3";

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $database);

// Tarkista yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}
?>
