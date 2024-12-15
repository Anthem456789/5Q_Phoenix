<?php

session_start();

$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "5q_ombrello_phoenix"; 


$conn = new mysqli($host, $username, $password, $dbname);

/* Usato per Debug: vediamo se le variabili sessioni sono passate correttamente */
echo "<pre>";
print_r( $_SESSION);
echo "</pre>";

?>

<!-- Inizio parte HTML -->
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <!-- Link al css da redifinire successivamente-->
    <link rel="stylesheet" type="text/css" href="Grafica/home.css?<?php echo time(); ?>">
    <script src="FunzioniDinamiche.js" defer></script>
</head>

<body>

    <?php
    /* Verifica se la sessione del Codice Fiscale è operativa (Idealmente ci sono altri parametri da verificare.) */ 
    if (isset($_SESSION["codiceFiscale"])) {
    $codiceFiscale = $_SESSION["codiceFiscale"];

    /* Prende il ruolo  dell'utente loggato basandosi sul suo codice fiscale*/
    $sql = " SELECT T.tipoRuolo
        FROM Ruoli AS T
        WHERE T.id_ruoli IN (
            SELECT Y.id_ruoli
            FROM utenti_ruoli AS Y
            WHERE Y.codiceFiscale = ?
        );
    ";

    /*si prepara lo statement*/ 
    if ($stmt = $conn->prepare($sql)) {
        /* Associa il parametro alla query (s per string) */
        $stmt->bind_param("s", $codiceFiscale);


        $stmt->execute();
        $result = $stmt->get_result();
        /* mette i risulati della riga in un array associativo */
        while ($row = $result->fetch_assoc()) {
            echo "Tipo Ruolo: " . " ". $row['tipoRuolo'] . "<br>";

            if($row['tipoRuolo'] === 'Paziente'){
                    $_SESSION['ruolo'] = 'Paziente';
                    ?>
                    <a href="BackBone_Phoenix/Prenotazioni.php"><i class="Infermiere"></i>PrenotaVisita</a>

                <?php }

            /* Inizio codice per Infermiere*/
            if($row['tipoRuolo'] === 'Infermiere') { 
                
                $_SESSION['ruolo'] = 'Infermiere';
             /* Si chiude il tag php per permettere al link <a> di funzionare in quanto HTML */
            ?>
    <!-- onclick="caricapagina('BackBone_Phoenix/Infermiere.php')"   Funzione da riattivare quando le funzionalità base saranno ottimizzate -->
    <a href="BackBone_Phoenix/Infermiere.php"><i class="Infermiere"></i>Infermiere</a>
    <!-- Si riapre per completare la logica del While e successivi-->
    <?php }
        }  
        $stmt->close();

    } else {
        echo "Errore nella preparazione della query: " . $conn->error;
    }
} else {
    echo "Codice fiscale non trovato nella sessione.";
}

?>

    <a href="BackBone_Phoenix/Logout.php" class="Button">Logout</a>

</body>

</html>
