<?php
/* da pdd sistemare */
include "../Generale/Db.php"; 

if (!isset($_SESSION["codiceFiscale"])) {
    die("Utente non loggato.");
}

$codiceFiscale = $_SESSION["codiceFiscale"];


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Visite Mediche</title>
    <link rel="stylesheet" type="text/css" href="../css/Grafica.css?">
    <meta name="ruolo-utente" content="<?php echo $_SESSION['ruolo']; ?>">
    <meta name="codiceFiscale" content="<?php echo $_SESSION['codiceFiscale']; ?>">
</head>

<body id="Prenotazioni-body">
    <div class="headerPrenotazioni">
        <a href="../../Home.php" id="buttonPrenotazioni">Torna alla Home</a>
        <button id="ToggleBox">Effettua Prenotazione</button>
        <div id="popupBox" class="invisible">
            <form id="prenotazioniForm" method="post">
                <p id="resultPrenotazioni"></p>

                <input type="text" id="codiceFiscale" name="Fiscale" placeholder="Codice Fiscale" required><br>
                <input type="text" id="reparto" name="reparto" placeholder="Numero Reparto" required><br>
                <input type="time" id="orario" name="orario" placeholder="Orario della prenotazione" required><br>
                <button type="submit">Prenota</button>
            </form>

        
  
            <div class="container-Prenotazioni">
                
            </div>

        </div>
    </div>

    <script src="../js/FunzioniDinamiche.js" defer></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButton = document.getElementById("ToggleBox");

        if (toggleButton) {
            toggleButton.addEventListener("click", function() {
                console.log("Pulsante cliccato");
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