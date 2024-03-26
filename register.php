<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekisteröidy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Rekisteröidy</h1>
        <form action="register_process.php" method="post">
            <input type="text" name="username" placeholder="Käyttäjänimi" required>
            <input type="email" name="email" placeholder="Sähköposti" required>
            <input type="password" name="password" placeholder="Salasana" required>
            <button type="submit" class="btn">Rekisteröidy</button>
        </form>
    </div>
</body>
</html>
