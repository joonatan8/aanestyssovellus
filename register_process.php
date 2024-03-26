<?php
session_start();
require_once('config/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Tarkista, onko sähköposti jo käytössä
    $check_sql = "SELECT user_id FROM users WHERE email='$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        echo "Email already exists. Please choose a different one.";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Lisää uusi käyttäjä tietokantaan
        $insert_sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "New user registered successfully. Please log in.";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
