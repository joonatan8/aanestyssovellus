<?php
session_start();
require_once('config/db-config.php');

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

// Tarkista, onko lomake lähetetty
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tarkista, onko ehdokas annettu lomakkeessa
    if (isset($_POST['candidate']) && !empty($_POST['candidate'])) {
        // Suojaudu SQL-injektioilta
        $candidate = mysqli_real_escape_string($conn, $_POST['candidate']);
        
        // Käyttäjän ID
        $user_id = $_SESSION['user_id'];

        // Tarkista, onko käyttäjä jo äänestänyt
        $check_vote_sql = "SELECT * FROM blockchain_votes WHERE user_id='$user_id'";
        $check_vote_result = $conn->query($check_vote_sql);
        if ($check_vote_result && $check_vote_result->num_rows > 0) {
            echo "<div style='font-family: Arial, sans-serif; background-color: #f2f2f2; padding: 20px; border-radius: 5px;'>";
            echo "<p>Olet jo äänestänyt. Äänestyksen tulokset:</p>";

            // Näytä äänestyksen tulokset
            $sql = "SELECT candidate, COUNT(*) AS votes FROM blockchain_votes GROUP BY candidate";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2 style='color: #8E3A59;'>Äänestyksen tulokset:</h2>";
                echo "<ul style='list-style-type: none; padding: 0;'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li style='margin-bottom: 10px;'>";
                    echo "<span style='font-weight: bold; color: #8E3A59;'>" . $row["candidate"] . ":</span> " . $row["votes"] . " ääntä";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Ei äänestyksen tuloksia vielä.</p>";
            }
            echo "</div>";
        } else {
            // Lisää ääni tietokantaan
            $previous_hash = calculate_previous_hash(); // Laske edellisen lohkon hash
            $timestamp = date('Y-m-d H:i:s');
            $hash = sha1($previous_hash . $candidate . $user_id . $timestamp); // Laske lohkon hash
            $sql = "INSERT INTO blockchain_votes (user_id, candidate, hash) VALUES ('$user_id', '$candidate', '$hash')";
            if ($conn->query($sql) === TRUE) {
                $block_sql = "INSERT INTO blocks (previous_hash, candidate, user_id, hash) VALUES ('$previous_hash', '$candidate', '$user_id', '$hash')";
                if ($conn->query($block_sql) === TRUE) {
                    echo "<div style='font-family: Arial, sans-serif; background-color: #f2f2f2; padding: 20px; border-radius: 5px;'>";
                    echo "<p>Äänesi on tallennettu onnistuneesti!</p>";

                    // Aseta istuntoon äänestäneeksi merkintä
                    $_SESSION['voted'] = true;

                    // Näytä äänestyksen tulokset
                    $sql = "SELECT candidate, COUNT(*) AS votes FROM blockchain_votes GROUP BY candidate";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<h2 style='color: #8E3A59;'>Äänestyksen tulokset:</h2>";
                        echo "<ul style='list-style-type: none; padding: 0;'>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<li style='margin-bottom: 10px;'>";
                            echo "<span style='font-weight: bold; color: #8E3A59;'>" . $row["candidate"] . ":</span> " . $row["votes"] . " ääntä";
                            echo "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Ei äänestyksen tuloksia vielä.</p>";
                    }
                    echo "</div>";
                } else {
                    echo "Virhe tallennettaessa lohkoa: " . $conn->error;
                }
            } else {
                echo "Virhe tallennettaessa ääntä: " . $conn->error;
            }
        }
    } else {
        echo "<p>Ehdokkaan nimi puuttuu. Palaa takaisin ja täytä kaikki tarvittavat kentät.</p>";
    }
}

// Funktio edellisen lohkon hashin laskemiseksi
function calculate_previous_hash() {
    global $conn;
    $previous_hash = '';
    $last_block_sql = "SELECT * FROM blocks ORDER BY block_id DESC LIMIT 1";
    $last_block_result = $conn->query($last_block_sql);
    if ($last_block_result->num_rows > 0) {
        $last_block = $last_block_result->fetch_assoc();
        $previous_hash = $last_block['hash'];
    }
    return $previous_hash;
}

// Sulje tietokantayhteys
$conn->close();
?>

