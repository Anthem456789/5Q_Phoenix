<?php
session_start();

if (!isset($_SESSION["codiceFiscale"])) {
    die("Utente non loggato.");
}

$codiceFiscale = $_SESSION["codiceFiscale"];

$host = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reparto = $_POST["reparto"];
    $orarioInput = $_POST["orario"];

    // Verifica e formatta l'orario
    $dateTime = DateTime::createFromFormat('H:i:s', $orarioInput);
    if (!$dateTime) {
        die("Formato orario non valido. Utilizzare HH:MM.");
    }
    $orario = $dateTime->format('H:i:s');

    // Controlla se il dottore è disponibile
    $sql = "SELECT orario_inizio, orario_fine 
            FROM Dottore 
            WHERE id_reparto = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $reparto);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $orario_inizio = $row['orario_inizio'];
                $orario_fine = $row['orario_fine'];

                if ($orario >= $orario_inizio && $orario <= $orario_fine) {
                    // Inserisci la prenotazione
                    $sql = "INSERT INTO prenotazioni (id_reparto, codice_fiscale, data_ora)
                            VALUES (?, ?, ?)";

                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sss", $reparto, $codiceFiscale, $orario);
                        if ($stmt->execute()) {
                            echo "Prenotazione effettuata!";
                        } else {
                            echo "Errore: " . $stmt->error;
                        }
                    }
                } else {
                    echo "Il dottore non è disponibile in quest'orario.";
                }
            } else {
                echo "Nessun dottore trovato per questo reparto.";
            }
        } else {
            echo "Errore: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Visite Mediche</title>
    <link rel="stylesheet" type="text/css" href="Grafica.css?<?php echo time(); ?>">
    <meta name="ruolo-utente" content="<?php echo $_SESSION['ruolo']; ?>">
    <meta name="codiceFiscale" content="<?php echo $_SESSION['codiceFiscale']; ?>">
</head>

<body id="Prenotazioni-body">
    <div class="headerPrenotazioni">
        <a href="../Home.php" id="buttonPrenotazioni">Torna alla Home</a>
        <button id="ToggleBox">Effettua Prenotazione</button>
        <div id="popupBox" class="invisible">
            <form id="prenotazioniForm" method="post">
                <p id="resultPrenotazioni"></p>

                <input type="text" id="codiceFiscale" name="Fiscale" placeholder="Codice Fiscale" required><br>
                <input type="text" id="reparto" name="reparto" placeholder="Numero Reparto" required><br>
                <input type="time" id="orario" name="orario" placeholder="Orario della prenotazione" required><br>
                <button type="submit">Prenota</button>
            </form>
            <script>
            function aggiungiSecondi() {
        // Ottieni il valore dell'orario
        const orarioInput = document.getElementById("orario").value;

        // Aggiungi i secondi manualmente
        const orarioConSecondi = orarioInput + ":00";

        // Assegna il nuovo valore al campo nascosto
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "orarioConSecondi";
        hiddenInput.value = orarioConSecondi;

        // Aggiungi il campo nascosto al form
        document.getElementById("prenotazioniForm").appendChild(hiddenInput);

        return true; // Invia il form
    }
</script>
        </div>
    </div>

    <script src="FunzioniDinamiche.js?v=1.1" defer></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButton = document.getElementById("ToggleBox");

        if (toggleButton) {
            toggleButton.addEventListener("click", function() {
                const popupBox = document.getElementById("popupBox");
                popupBox.classList.toggle("invisible");
            });
        } else {
            console.log("Button not found.");
        }
    });
    </script>
</body>

</html>
