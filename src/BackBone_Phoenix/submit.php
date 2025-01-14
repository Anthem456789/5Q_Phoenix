<?php
$servername = "localhost";
$username = "root";        
$password = "";            
$dbname = "5q_ombrello_phoenix";       

// Creare la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Controlla se i dati sono stati inviati
if (isset($_POST['codiceFiscale']) && isset($_POST['tipoPrenotazione'])) {
    $codiceFiscale = $_POST['codiceFiscale'];
    $tipoPrenotazione = $_POST['tipoPrenotazione'];

    // Query per verificare se l'utente esiste
    $sql = "SELECT * FROM utenti WHERE codiceFiscale = '$codiceFiscale'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Se l'utente esiste
        echo "Prenotazione effettuata per l'utente con codice fiscale: $codiceFiscale tramite $tipoPrenotazione.";
    } else {
        // Se l'utente non esiste
        echo "Nessun utente trovato con il codice fiscale: $codiceFiscale.";
    }
} else {
    echo "Dati mancanti!";
}

$conn->close();
?>