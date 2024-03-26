<?php
session_start();

// Tarkista, onko käyttäjä kirjautunut sisään
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Tarkista, onko äänestäjä jo äänestänyt
if (isset($_SESSION['voted'])) {
    // Ohjaa takaisin äänestys-sivulle, jotta tulokset voidaan näyttää
    header("Location: vote_process.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Äänestys</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Äänestys</h1>
        <form action="vote_process.php" method="post">
            <input type="text" name="candidate" placeholder="Ehdokkaan nimi" required>
            <button type="submit" class="btn">Äänestä</button>
        </form>
        <a href="logout.php" class="logout-btn">Kirjaudu ulos</a>
    </div>
</body>
</html>

