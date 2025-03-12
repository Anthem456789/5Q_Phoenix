<?php


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

echo $_SESSION["ruolo"] . "<br><br>";


/* Display dei reparti */
$sql2 = "SELECT id_reparto
FROM reparto"; 

if($stmt2 = $conn->prepare($sql2)){
$stmt2->execute();
$result2 = $stmt2->get_result();


echo "<div class= 'reparti-container'>";
while($row2 = $result2->fetch_assoc()){
    
    /* Percorso assoluto poichè Infermiere è chiamato dinamicamente
    *  Si passa id reparto tramite URI (Protocollo REST) al reparto-infermiere
    */
    echo "<div class='reparto-box' onclick=\"window.location.href='/src/BackBone_Phoenix/reparto-infermiere.php?id_reparto=" . $row2['id_reparto'] . "';\">";
          echo "Reparto N°" . $row2['id_reparto'] . "<br>"; 
          echo "</div>";
}
echo "<div>";
$stmt2->close();
}
?>

<script src="FunzioniDinamiche.js" defer></script>


</html>