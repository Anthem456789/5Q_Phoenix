

<?php

header('Content-Type: application/json');

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";


$conn = new mysqli($servername, $username, $password, $dbname);

?>

<!DOCTYPE html>
<html lang="it">
    <head>
    <link rel="stylesheet" type="text/css" href="Grafica.css?<?php echo time(); ?>">
    </head>
<?php

if (isset($_SESSION['codiceFiscale']) && isset($_SESSION['nome']) && isset($_SESSION['cognome']) && isset($_SESSION['email']) && isset($_SESSION['ruolo'])) {
    $id_utente = $_SESSION["codiceFiscale"];
    $nome = $_SESSION["nome"];
    $cognome = $_SESSION["cognome"];
    $email = $_SESSION["email"];
    $ruolo = $_SESSION["ruolo"];
} else {
    die("Errore: Dati utente non disponibili nella sessione.");
}



echo json_encode($_SESSION["ruolo"] . "<br><br>"); 

/* parte di display letti occupati (da migliorare, ovvio) */

/* Si evita Sql Injection  */
$id_reparto = isset($_GET['id_reparto']) ? (int)$_GET['id_reparto'] : 0;  

if($id_reparto > 0){

    $sql3 = "SELECT DISTINCT T.id_letto, Y.isTaken
FROM reparto_letto AS T
JOIN letto AS Y 
ON T.id_letto = Y.id_letto
WHERE T.id_reparto = ?";

if ($stmt3 = $conn->prepare($sql3)) {
    $stmt3->bind_param('i', $id_reparto);
$stmt3->execute();
$result3 = $stmt3->get_result();

echo json_encode("<div class='letti-container'>");

while ($row3 = $result3->fetch_assoc()) {
$idLetto = htmlspecialchars($row3['id_letto']);
$isTaken = $row3['isTaken'];

echo json_encode("<div class= 'letto-box'>");
echo json_encode("ID letto: " . $row3['id_letto'] . " >>> Reparto: " . $id_reparto . "<br>");

if ($row3['isTaken'] == 1) {
    echo json_encode("<button id='btn-{$row3['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row3['id_letto']}', 0)\">Rilascia</button><br>");
} else {
    echo json_encode("<button id='btn-{$row3['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row3['id_letto']}', 1)\">Assegna</button><br>");
}
echo json_encode("</div>");
}
echo json_encode("</div>") ;
$stmt3->close();
}

}

?>

<script src="FunzioniDinamiche.js" defer></script>


</html>
