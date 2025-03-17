<?php

include "../functionLog.php";

checkLog();

/*if (!isset($_SESSION["codiceFiscale"])) {
    die("Utente non loggato.");
}*/

$codiceFiscale = $_SESSION["codiceFiscale"];

$host = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


$sql = " SELECT T.tipoRuolo
        FROM Ruoli AS T
        WHERE T.id_ruoli IN (
            SELECT Y.id_ruoli
            FROM utenti_ruoli AS Y
            WHERE Y.codiceFiscale = ?
        );
    ";
    if ($stmt = $conn->prepare($sql)) {
        /* Associa il parametro alla query (s per string) */
        $stmt->bind_param("s", $codiceFiscale);
    
    
        myExecute($stmt);

    }