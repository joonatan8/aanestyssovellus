<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjaudu sisään</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Kirjaudu sisään</h1>
        <form action="login_process.php" method="post">
            <input type="email" name="email" placeholder="Sähköposti" required>
            <input type="password" name="password" placeholder="Salasana" required>
            <button type="submit" class="btn">Kirjaudu</button>
        </form>
    </div>
</body>
</html>
