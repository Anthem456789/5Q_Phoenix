<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = '5q_ombrello_phoenix';

$conn = new mysqli($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idLetto = $_POST['id_letto'];
    $isTaken = $_POST['isTaken']; 
    $cf_paziente = $_POST['cf_paziente'] ?? null;

    if ($isTaken == 1 && $cf_paziente !== null) {
        $sqlUpdate = "UPDATE letto SET isTaken = ?, cf_paziente = ? WHERE id_letto = ?";
        if ($stmt = $conn->prepare($sqlUpdate)) {
            $stmt->bind_param("isi", $isTaken, $cf_paziente, $idLetto);
            $stmt->execute();
            echo "Stato aggiornato correttamente!";
            $stmt->close();
        } else {
            echo "Errore nell'aggiornamento: " . $conn->error;
        }

    } elseif ($isTaken == 0) {
        $sqlUpdate = "UPDATE letto SET isTaken = ?, cf_paziente = NULL WHERE id_letto = ?";
        if ($stmt = $conn->prepare($sqlUpdate)) {
            $stmt->bind_param("ii", $isTaken, $idLetto);
            $stmt->execute();
            echo "Stato aggiornato correttamente!";
            $stmt->close();
        } else {
            echo "Errore nell'aggiornamento: " . $conn->error;
        }
    }
}

$conn->close();
?>