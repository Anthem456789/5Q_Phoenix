<?php

session_start();

$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "5q_ombrello_phoenix"; 


$conn = new mysqli($host, $username, $password, $dbname);

$codiceFiscale = $_SESSION["codiceFiscale"];

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
        <button id="ToggleBox">Crea Corso</button>
        <div id="popupBox" class="invisible">
            <form id="prenotazioniForm">
                <p id="resultPrenotazioni"></p>

                <input type="text" id="codiceFiscale" name="Fiscale" placeholder="Codice Fiscale" required><br>
                <input type="text" id="reparto" name="reparto" placeholder="Numero Reparto" required><br>
                <button type="submit">Prenota</button>
            </form>
        </div>
    </div>

    <div class="container-Prenotazioni">
        <?php

$sql = "SELECT id,CodiceFiscale,id_reparto
        FROM prenotazioni
        WHERE CodiceFiscale = ?";

if ($stmt = $conn->prepare($sql)) {

       $stmt->bind_param("s",$codiceFiscale);
        /* Associa il parametro alla query (s per string) */
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result){
            
            echo "<div class='prenotazioni-container'>";
        /* mette i risulati della riga in un array associativo */
        while ($row = $result->fetch_assoc()) {

            echo "<div class= 'prenotazione-box'>";

            echo "<div class='Prenotazione-titolo'>";
            echo "Prenotazione N°: ". $row['id'];
            echo "</div>";
            echo "</div>";
        }

       
        } else {
            echo "Errore nel recupero dei dati: " . $conn->error;
    }
    $stmt->close();
}
        ?>
    </div>

    <script src="FunzioniDinamiche.js?v=1.1" defer></script>

    <script>
    /* pulsante per le prenotazioni */
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