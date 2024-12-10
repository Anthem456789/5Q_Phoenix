<?php


$serverName = "localhost";
$username = "root"; 
$password = ""; 
$db_name = "5q_ombrello_phoenix"; 



// Creazione della connessione
$conn = new mysqli($serverName, $username, $password);

$sql = "CREATE DATABASE IF NOT EXISTS 5q_ombrello_phoenix;";

// Controllo della connessione
if ($conn->query($sql)) {
    echo "Database creato con successo<br>";
} else {
    echo $conn->error;
}

$conn2 = new mysqli($serverName, $username, $password, $db_name);

$sql = " CREATE TABLE IF NOT EXISTS utenti (
    codiceFiscale VARCHAR(20) NOT NULL DEFAULT '',
    nome VARCHAR(100) DEFAULT NULL,
    cognome VARCHAR(50) DEFAULT NULL,
    data_nascita DATE NOT NULL,
    email VARCHAR(50) DEFAULT NULL,
    password VARCHAR(100) DEFAULT NULL,
    CONSTRAINT ChiavePrimaria PRIMARY KEY(codiceFiscale)
); ";


if ($conn2->query($sql)) {
    echo "Tabella \"utenti\" creata con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */


$sql = " CREATE TABLE IF NOT EXISTS Ruoli (
    tipoRuolo VARCHAR(30) NOT NULL DEFAULT '',
    id_ruoli INT(6) NOT NULL,
    CONSTRAINT ChiavePrimariaRuoli PRIMARY KEY(id_ruoli)
); ";


if ($conn2->query($sql)) {
    echo "Tabella \"Ruoli\" creata con successo<br>";
} else {
    echo $conn->error;
}



/* ---------------------------------------------------- */


$sql = " CREATE TABLE IF NOT EXISTS Documenti (
    id_documento INT(6) NOT NULL,
    codiceFiscale VARCHAR(20) NOT NULL,
    CONSTRAINT ChiavePrimariaDoc PRIMARY KEY(id_documento),
    CONSTRAINT FK_codiceFiscale FOREIGN KEY(codiceFiscale)
        REFERENCES utenti(codiceFiscale)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"Documenti\" creata con successo<br>";
} else {
    echo $conn->error;
}


/* ---------------------------------------------------- */


$sql = " CREATE TABLE IF NOT EXISTS Patologia (
    id_malattia INT(6) NOT NULL,
    CONSTRAINT ChiavePrimariaMalattia PRIMARY KEY(id_malattia)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"Patologia\" creata con successo<br>";
} else {
    echo $conn->error;
}


/* ---------------------------------------------------- */


$sql = " CREATE TABLE IF NOT EXISTS utenti_ruoli (
    codiceFiscale VARCHAR(20) NOT NULL,
    id_ruoli INT(6) NOT NULL,
    CONSTRAINT ChiavePrimariaRuoloUtente PRIMARY KEY(codiceFiscale, id_ruoli),
    CONSTRAINT FK_codiceFiscale_utenti FOREIGN KEY(codiceFiscale)
        REFERENCES utenti(codiceFiscale),
    CONSTRAINT FK_idRuolo FOREIGN KEY(id_ruoli)
        REFERENCES Ruoli(id_ruoli)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"utenti_ruoli\" creata con successo<br>";
} else {
    echo $conn->error;
}


/* ---------------------------------------------------- */


$sql = " CREATE TABLE IF NOT EXISTS Patologia_Documenti (
    id_documento INT(6) NOT NULL,
    id_malattia INT(6) NOT NULL,
    codiceFiscale VARCHAR(20) NOT NULL,
    CONSTRAINT ChiavePrimariaDoc PRIMARY KEY(id_documento, id_malattia),
    CONSTRAINT FK_idDocumento_test FOREIGN KEY(id_documento)
        REFERENCES Documenti(id_documento),
    CONSTRAINT FK_codiceFiscale_test FOREIGN KEY(codiceFiscale)
        REFERENCES utenti(codiceFiscale),
    CONSTRAINT FK_idMalattia_test FOREIGN KEY(id_malattia)
        REFERENCES Patologia(id_malattia)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"Patologia_Documenti\" creata con successo<br>";
} else {
    echo $conn->error;
}


?>