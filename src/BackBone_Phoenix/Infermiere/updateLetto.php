<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = '5q_ombrello_phoenix';

$conn = new mysqli($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idLetto = $_POST['id_letto'];
    $isTaken = $_POST['isTaken'];

    $sqlUpdate = "UPDATE letto SET isTaken = ? WHERE id_letto = ?";
    if ($stmt = $conn->prepare($sqlUpdate)) {
        $stmt->bind_param("ii", $isTaken, $idLetto);
        $stmt->execute();
        include '../Generale/Notifiche.php';
        echo "Stato aggiornato correttamente!";
        $stmt->close();
        
    } else {
        echo "Errore nell'aggiornamento: " . $conn->error;
    }
}

$conn->close();
?>
