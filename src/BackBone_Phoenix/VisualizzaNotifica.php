<?php
session_start();
include "../functionLog.php";

$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "5q_ombrello_phoenix"; 


$conn = new mysqli($host, $username, $password, $dbname);

?>

<!-- Inizio parte HTML -->
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Notifiche</title>
    <!-- Link al css da redifinire successivamente-->
    <!--link rel="stylesheet" type="text/css" href="Grafica/home.css?<?php echo time(); ?>-->
    <script src="../js/FunzioniDinamiche.js" defer></script>
</head>

<body>

    <?php
    /* Verifica se la sessione del Codice Fiscale è operativa (Idealmente ci sono altri parametri da verificare.) */ 
    if (isset($_SESSION["codiceFiscale"])) {
        
            
        if($_SESSION["ruolo"] == "Infermiere"){
            /* Prende il ruolo  dell'utente loggato basandosi sul suo codice fiscale*/
            $sql = " SELECT categoria,titolo,descrizione,data_creazione,visualizzato
                FROM Notifica
                WHERE categoria = 'Infermiere'
            ";
        }

        if($_SESSION["ruolo"] == "Paziente"){
            /* Prende il ruolo  dell'utente loggato basandosi sul suo codice fiscale*/
            $sql = " SELECT categoria,titolo,descrizione,data_creazione,visualizzato
                FROM Notifica
                WHERE categoria = 'Paziente'
            ";
        }

            /*si prepara lo statement*/ 
            if ($stmt = $conn->prepare($sql)) {
                /* Associa il parametro alla query (s per string) */
                $stmt->execute();
                $result = $stmt->get_result();
                echo "<div class='Notifica-container'>";
                /* mette i risulati della riga in un array associativo */
                while ($row = $result->fetch_assoc()) {

                    echo "<div class= 'notifica-box'>";

                    echo "<div class='notifica-titolo'>";
                    echo $row['titolo'] . " in data " .$row['data_creazione'];
                    echo "</div>";
                    echo "<div class='notifica-descrizione'>";
                    echo $row['descrizione'] ;
                    echo "</div>";

                    echo "</div>";
                } 
                $stmt->close();   
                
                echo "</div>";
            }
            else {
                echo "Errore nella preparazione della query: " . $conn->error;
            }

    } else {
        echo "Codice fiscale non trovato nella sessione.";
    }
    ?>


</body>