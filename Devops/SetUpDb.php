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
    codiceFiscale VARCHAR(20) NOT NULL,
    nome VARCHAR(100) DEFAULT NULL,
    cognome VARCHAR(50) DEFAULT NULL,
    data_nascita DATE NOT NULL,
    email VARCHAR(50) DEFAULT NULL,
    telefono VARCHAR(15) DEFAULT NULL,
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
    tipoRuolo VARCHAR(30) NOT NULL,
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


$sql = " CREATE TABLE IF NOT EXISTS Reparto (
    id_reparto INT(6) NOT NULL,
    id_malattia int(6) NOT NULL,
    nome_reparto VARCHAR(30) NOT NULL,
    CONSTRAINT ChiavePrimariaReparto PRIMARY KEY(id_reparto),
    CONSTRAINT FK_idMalattia FOREIGN KEY(id_malattia)
            REFERENCES Patologia(id_malattia)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"Reparto\" creata con successo<br>";
} else {
    echo $conn->error;
}


/* ---------------------------------------------------- */

$sql = " CREATE TABLE IF NOT EXISTS Letto (
    id_letto INT(6) NOT NULL,
    isTaken BOOLEAN NOT NULL,
    CONSTRAINT ChiavePrimariaLetto PRIMARY KEY(id_letto)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"Letto\" creata con successo<br>";
} else {
    echo $conn->error;
}


/* ---------------------------------------------------- */

$sql = " CREATE TABLE IF NOT EXISTS Reparto_Letto (
    id_reparto INT(6) NOT NULL,
    id_letto INT(6) NOT NULL,
   CONSTRAINT ChiavePrimariaRepartoLetto PRIMARY KEY(id_reparto,id_letto),
   CONSTRAINT FK_IdReparto_Reparto FOREIGN KEY(id_reparto)
        REFERENCES Reparto(id_reparto),
    CONSTRAINT FK_IdLetto_Letto FOREIGN KEY(id_letto)
        REFERENCES Letto(id_letto)
);";


if ($conn2->query($sql)) {
    echo "Tabella \"Reparto_Letto\" creata con successo<br>";
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

/* ---------------------------------------------------- */

$sql = " CREATE TABLE IF NOT EXISTS Notifica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codiceFiscale VARCHAR(20) NOT NULL,
    categoria VARCHAR(12),
    titolo VARCHAR(100) NOT NULL,
    descrizione VARCHAR(3000) NOT NULL,
    data_creazione DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    visualizzato BOOLEAN DEFAULT FALSE,
    CONSTRAINT FK_codiceFiscale_Notifica FOREIGN KEY(codiceFiscale)
          REFERENCES utenti(codiceFiscale)
);";



/* ----------Inserimento Dati Significativi--------------- */

$sql = "INSERT IGNORE INTO Ruoli (tipoRuolo, id_ruoli) VALUES
('Dottore', 1),
('Infermiere', 2),
('Paziente', 3);
";
if ($conn2->query($sql)) {
    echo "Dati
     \"Ruoli\" creati con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO utenti (codiceFiscale, nome, cognome, data_nascita, email, password) VALUES
('RSSMRA85M01H501Z', 'Mario', 'Rossi', '1985-01-01', 'mario.rossi@gmail.com', '7c6a180b36896a0a8c02787eeafb0e4c'),
('VRNGNN90A01H501Y', 'Giovanni', 'Verdi', '1990-02-15', 'giovanni.verdi@gmail.com', '6cb75f652a9b52798eb6cf2201057c73'),
('BNCMRA75D01H501X', 'Anna', 'Bianchi', '1975-03-20', 'anna.bianchi@gmail.com', '819b0643d6b89dc9b579fdfc9094f28e'),
('CMLFNC80B01H501W', 'Francesca', 'Colombo', '1980-04-25', 'francesca.colombo@gmail.com', '34cc93ece0ba9e3f6f235d4af979b16c'),
('DLMNMR85C01H501V', 'Luca', 'De Luca', '1985-05-30', 'luca.deluca@gmail.com', 'db0edd04aaac4506f7edab03ac855d56'),
('FRTGNN92E01H501U', 'Marco', 'Ferrari', '1992-06-10', 'marco.ferrari@gmail.com', '218dd27aebeccecae69ad8408d9a36bf'),
('PLMZRT88F01H501T', 'Elena', 'Pellegrini', '1988-07-15', 'elena.pellegrini@gmail.com', '00cdb7bb942cf6b290ceb97d6aca64a3'),
('GHTMRA80G01H501S', 'Simone', 'Gatti', '1980-08-20', 'simone.gatti@gmail.com', 'b25ef06be3b6948c0bc431da46c2c738'),
('RNGNMR76H01H501R', 'Chiara', 'Rinaldi', '1976-09-25', 'chiara.rinaldi@gmail.com', '5d69dd95ac183c9643780ed7027d128a'),
('TSTLRA83I01H501Q', 'Alessandro', 'Tosi', '1983-10-30', 'alessandro.tosi@gmail.com', '87e897e3b54a405da144968b2ca19b45'); ";

if ($conn2->query($sql)) {
    echo "Dati \"utenti\" creati con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO Documenti (id_documento, codiceFiscale) VALUES
(1, 'RSSMRA85M01H501Z'),
(2, 'VRNGNN90A01H501Y'),
(3, 'BNCMRA75D01H501X'),
(4, 'CMLFNC80B01H501W'),
(5, 'DLMNMR85C01H501V'),
(6, 'FRTGNN92E01H501U'),
(7, 'PLMZRT88F01H501T'),
(8, 'GHTMRA80G01H501S'),
(9, 'RNGNMR76H01H501R'),
(10, 'TSTLRA83I01H501Q'); ";

if ($conn2->query($sql)) {
    echo "Dati \"Documenti\" creati con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO Patologia (id_malattia) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10);
";


if ($conn2->query($sql)) {
    echo "Dati \"Patologia\" creati con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO utenti_ruoli (codiceFiscale, id_ruoli) VALUES
('RSSMRA85M01H501Z', 1),
('VRNGNN90A01H501Y', 2),
('BNCMRA75D01H501X', 3),
('CMLFNC80B01H501W', 1),
('DLMNMR85C01H501V', 2),
('FRTGNN92E01H501U', 3),
('PLMZRT88F01H501T', 1),
('GHTMRA80G01H501S', 2),
('RNGNMR76H01H501R', 3),
('TSTLRA83I01H501Q', 1); ";

if ($conn2->query($sql)) {
    echo "Dati \"utenti_ruoli\" creati con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO Patologia_Documenti (id_documento, id_malattia, codiceFiscale) VALUES
(1, 1, 'RSSMRA85M01H501Z'),
(2, 2, 'TSTLRA83I01H501Q'); ";

if ($conn2->query($sql)) {
    echo "Dati \"Patologia_Documenti\" creati con successo<br>";
} else {
    echo $conn->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO Reparto (id_reparto, id_malattia, nome_reparto) VALUES
(1, 1, 'Oncologia'),
(2, 2, 'Neurologia'),
(3, 3, 'Cardiologia'),
(4, 4, 'Dermatologia')";

if ($conn2->query($sql)) {
    echo "Dati inseriti nella tabella \"Reparto\" con successo<br>";
} else {
    echo $conn2->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO Letto (id_letto, isTaken) VALUES
(1, TRUE),
(2, TRUE),
(3, FALSE),
(4, FALSE)";

if ($conn2->query($sql)) {
    echo "Dati inseriti nella tabella \"Letto\" con successo<br>";
} else {
    echo $conn2->error;
}

/* ---------------------------------------------------- */

$sql = "INSERT IGNORE INTO Reparto_Letto (id_reparto, id_letto) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 4)";

if ($conn2->query($sql)) {
    echo "Dati inseriti nella tabella \"Reparto_Letto\" con successo<br>";
} else {
    echo $conn2->error;
}

$conn->close();

?>
