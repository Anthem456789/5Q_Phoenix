<?php

include "../functionLog.php";


header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = '5q_ombrello_phoenix';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connessione al database fallita: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codiceFiscale = isset($_POST['codiceFiscale']) ? $_POST['codiceFiscale'] : null;
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : null;
    $titolo = isset($_POST['titolo']) ? $_POST['titolo'] : null;
    $descrizione = isset($_POST['descrizione']) ? $_POST['descrizione'] : null;
    
    if (!$codiceFiscale) {
        echo json_encode(['error' => 'Errore: a']);
        exit;
    }
    if (!$categoria) {
        echo json_encode(['error' => 'Errore: b']);
        exit;
    }
    if (!$titolo) {
        echo json_encode(['error' => 'Errore: c']);
        exit;
    }
    if (!$descrizione) {
        echo json_encode(['error' => 'Errore: d']);
        exit;
    }

 
    

    $sql = "INSERT INTO Notifica (codiceFiscale, categoria, titolo, descrizione, visualizzato) 
            VALUES (?, ?, ?, ?, FALSE)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $codiceFiscale, $categoria, $titolo, $descrizione);

        if ($stmt->execute()) {
            echo json_encode(['success' => 'Notifica inviata con successo!']);
        } else {
            echo json_encode(['error' => 'Errore durante l\'invio della notifica: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Errore nella preparazione della query: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Richiesta non andata a buon fine!']);
}

$conn->close();
?>
