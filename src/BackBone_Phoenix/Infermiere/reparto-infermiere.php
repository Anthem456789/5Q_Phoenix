<?php

include "../../functionLog.php";

include_once "../Generale/Db.php";

?>

<!DOCTYPE html>

<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="ruolo-utente" content="<?php echo $_SESSION['ruolo']; ?>">
    <meta name="codiceFiscale" content="<?php echo $_SESSION['codiceFiscale']; ?>">
    <link rel="stylesheet" type="text/css" href="../css/Grafica.css?<?php echo time(); ?>">
</head>

<header class="header-repartoInfermiere">
    <a href="../../Home.php"> Torna indietro</a>
</header>

<body>
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

    /* parte di display letti occupati */

    /* Si evita Sql Injection dal valore preso dall'URI nel GET */
    $id_reparto = isset($_GET['id_reparto']) ? (int) $_GET['id_reparto'] : 0;

    if ($id_reparto > 0) {

        $sql3 = "SELECT DISTINCT T.id_letto, Y.isTaken, Y.cf_Paziente
                    FROM reparto_letto AS T
                    JOIN letto AS Y 
                    ON T.id_letto = Y.id_letto
                    WHERE T.id_reparto = ?";

        if ($stmt3 = $conn->prepare($sql3)) {
            $stmt3->bind_param('i', $id_reparto);
            $stmt3->execute();
            $result3 = $stmt3->get_result();

            echo "<div class='letti-container'>";

            while ($row3 = $result3->fetch_assoc()) {
                $idLetto = htmlspecialchars($row3['id_letto']);
                $isTaken = $row3['isTaken'];

                echo "<div class= 'letto-box'>";
                echo "ID letto: " . $row3['id_letto'] . " >>> Reparto: " . $id_reparto . "<br>";

                /* Inserire opzione per spostare i pazienti */
                if ($row3['isTaken'] == 1) {
                    echo "<button id='btn-{$row3['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row3['id_letto']}', 0)\">Rilascia</button><br>";
                } 
                /*else {
                    echo "<button id='btn-{$row3['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row3['id_letto']}', 1)\">Assegna</button><br>";
                } 
            */
                echo "</div>";
            }
            echo "</div>";
            $stmt3->close();
        }

    }

    ?>
</body>
<script src="../js/FunzioniDinamiche.js" defer></script>


</html>