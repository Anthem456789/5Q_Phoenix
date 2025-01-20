

<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";


$conn = new mysqli($servername, $username, $password, $dbname);

?>

<!DOCTYPE html>
<html lang="it">
<?php

if (isset($_SESSION['codiceFiscale']) && isset($_SESSION['nome']) && isset($_SESSION['cognome']) && isset($_SESSION['email']) && isset($_SESSION['ruolo'])) {
    $id_utente = $_SESSION["codiceFiscale"];
    $nome = $_SESSION["nome"];
    $cognome = $_SESSION["cognome"];
    $email = $_SESSION["email"];
    $ruolo = $_SESSION["ruolo"];
} else {
    die("Errore: Dati utente non disponibili nella sessione.");
}

echo $_SESSION["ruolo"] . "<br><br>";


/* parte di display letti occupati (da migliorare, ovvio) */

$sql2 = "SELECT DISTINCT T.id_letto, T.id_reparto , Y.isTaken
         FROM reparto_letto AS T
         JOIN letto AS Y 
         ON T.id_letto = Y.id_letto";

if ($stmt2 = $conn->prepare($sql2)) {
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    echo "<div class='letti-container'>";

    while ($row2 = $result2->fetch_assoc()) {
        $idLetto = htmlspecialchars($row2['id_letto']);
        $isTaken = $row2['isTaken'];

        echo "<div class= 'letto-box'>";
        echo "ID letto: " . $row2['id_letto'] . " >>> Reparto: " . $row2['id_reparto'] . "<br>";

        if ($row2['isTaken'] == 1) {
            echo "<button id='btn-{$row2['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row2['id_letto']}', 0)\">Rilascia</button><br>";
        } else {
            echo "<button id='btn-{$row2['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row2['id_letto']}', 1)\">Assegna</button><br>";
        }
        echo "</div>";
    }
    echo "</div>";
    $stmt2->close();
}


?>

<script src="FunzioniDinamiche.js" defer></script>


</html>