<?php
session_start();
require_once('config/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Tarkista käyttäjän tiedot
    $check_sql = "SELECT * FROM users WHERE email='$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Kirjautuminen onnistui
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            header("Location: vote.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}

$conn->close();
?>
