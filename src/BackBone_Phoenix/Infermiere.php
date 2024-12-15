<script src="FunzioniDinamiche.js" defer></script>

<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";


$conn = new mysqli($servername, $username, $password, $dbname);

/* Debug */
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

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
    while ($row2 = $result2->fetch_assoc()) {
        $idLetto = htmlspecialchars($row2['id_letto']);
        $isTaken = $row2['isTaken'];

        echo "ID letto: " . $row2['id_letto'] . " >>> Reparto: " . $row2['id_reparto'] . "<br>";

        if ($isTaken == 1) {
            echo "<button class='iscriviti-btn' onclick=\"aggiorna('$idLetto', 0)\">Rilascia</button><br>";
        } else {
            echo "<button class='iscriviti-btn' onclick=\"aggiorna('$idLetto', 1)\">Assegna</button><br>";
        }
    }
    $stmt2->close();
}


?>