<?php

$serverName = "localhost";
$username = "root";
$password = "";
$db_name = "5q_ombrello_phoenix";

$conn = new mysqli($serverName, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Disabilita i vincoli di chiave esterna
$conn->query("SET FOREIGN_KEY_CHECKS = 0;");

// Svuota le tabelle in ordine
$tables = [
    'Patologia_Documenti',
    'utenti_ruoli',
    'Reparto_Letto',
    'Letto',
    'Reparto',
    'Documenti',
    'utenti',
    'Ruoli',
    'Patologia',
    'Notifica'
];

foreach ($tables as $table) {
    $sql = "TRUNCATE TABLE $table;";
    if ($conn->query($sql) === TRUE) {
        echo "Tabella \"$table\" svuotata con successo<br>";
    } else {
        echo "Errore durante lo svuotamento della tabella \"$table\": " . $conn->error . "<br>";
    }
}

// Riabilita i vincoli di chiave esterna
$conn->query("SET FOREIGN_KEY_CHECKS = 1;");

$conn->close();

?>
