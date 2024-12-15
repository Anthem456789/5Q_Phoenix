<?php
$serverName = "localhost";
$username = "root";
$password = "";
$db_name = "5q_ombrello_phoenix";


$conn = new mysqli($serverName, $username, $password, $db_name);


if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Variabile per il messaggio di conferma
$confirmationMessage = "";
$isConfermato = False;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codiceFiscale = $_POST['codiceFiscale'];
    $tipoPrenotazione = $_POST['tipoPrenotazione'];

    
    $sql = "SELECT * FROM utenti WHERE codiceFiscale = '$codiceFiscale'";
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) === 1) {
        $confirmationMessage = "Prenotazione effettuata per l'utente con codice fiscale: $codiceFiscale tramite $tipoPrenotazione.";
        $isConfermato = True;
    } else {
        $confirmationMessage = "Nessun utente trovato con il codice fiscale: $codiceFiscale.";
        $isConfermato = False;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Visite Mediche</title>
</head>
<body>
    <div class="container">
        <h1>Prenotazione Visite Mediche</h1>
        <form method="post" action="">
            <label for="codiceFiscale">Codice Fiscale:</label>
            <input type="text" id="codiceFiscale" name="codiceFiscale" required>

            <h2>Metodo di Prenotazione</h2>
            <div>
                <input type="radio" id="email" name="tipoPrenotazione" value="email" checked>
                <label for="email">Email</label>
            </div>
            <div>
                <input type="radio" id="telefono" name="tipoPrenotazione" value="telefono">
                <label for="telefono">Telefono</label>
            </div>

            <button type="submit">Prenota</button>
        </form>

        <?php if ($confirmationMessage && $isConfermato == True): ?>
            <div class="confirmation">
                <h2>Prenotazione Confermata!</h2>
                <p><?php echo $confirmationMessage; ?></p>
            </div>
            <?php endif; ?>
           <?php if($confirmationMessage && $isConfermato == False): ?>
            <div class="confirmation">
                <h2>Prenotazione Non Confermata!</h2>
                <p><?php echo $confirmationMessage; ?></p>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>