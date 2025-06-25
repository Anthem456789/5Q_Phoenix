<?php


include "../../functionLog.php";
include "display_reparto.php";

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

?>


<div class= 'reparti-container'>

    
    <!-- Percorso assoluto poichè Infermiere è chiamato dinamicamente
    *  Si passa id reparto tramite URI (Protocollo REST) al reparto-infermiere
    -->
    <?php for($i=0; $i<sizeof($reparti); $i++) : ?>
        <div class='reparto-box' onclick="window.location.href='../src/BackBone_Phoenix/Infermiere/reparto-infermiere.php?id_reparto=<?= htmlspecialchars($reparti[$i]) ?>';">
          Reparto N°<?= htmlspecialchars($reparti[$i]) ?>
    </div>

    <?php endfor; ?>

<div>

<script src="../js/FunzioniDinamiche.js" defer></script>


</html>