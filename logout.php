<?php
session_start();
// Poista istuntoon liittyvät tiedot
session_unset();
// Tuhoa istunto
session_destroy();
// Ohjaa käyttäjä takaisin kirjautumissivulle
header("Location: login.php");
exit();
?>
