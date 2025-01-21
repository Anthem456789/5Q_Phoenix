<?php

session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";


$conn = new mysqli($host, $username, $password, $dbname);

/* Usato per Debug: vediamo se le variabili sessioni sono passate correttamente 
echo "<pre>";
print_r( $_SESSION);
echo "</pre>";
*/
/* Se non ci sono variabili sessioni, allora non è stato fatto il login */
if (isset($_SESSION["codiceFiscale"]) && isset($_SESSION["email"])) {

              /* Verifica se la sessione del Codice Fiscale è operativa (Idealmente ci sono altri parametri da verificare.) */
                if (isset($_SESSION["codiceFiscale"])) {
                    $codiceFiscale = $_SESSION["codiceFiscale"];

                    /* Prende il ruolo  dell'utente loggato basandosi sul suo codice fiscale*/
                    $sql = " SELECT T.tipoRuolo
        FROM Ruoli AS T
        WHERE T.id_ruoli IN (
            SELECT Y.id_ruoli
            FROM utenti_ruoli AS Y
            WHERE Y.codiceFiscale = ?
        );
    ";
                } else {
                    echo "Codice fiscale non trovato nella sessione.";
                }
/*si prepara lo statement*/
if ($stmt = $conn->prepare($sql)) {
    /* Associa il parametro alla query (s per string) */
    $stmt->bind_param("s", $codiceFiscale);


    $stmt->execute();
    $result = $stmt->get_result();
    /* mette i risulati della riga in un array associativo */
    while ($row = $result->fetch_assoc()) {
        if ($row['tipoRuolo'] === 'Paziente') {
            $_SESSION['ruolo'] = 'Paziente';

        }
        if ($row['tipoRuolo'] === 'Infermiere') {
            $_SESSION['ruolo'] = 'Infermiere';
            /* Si chiude il tag php per permettere al link <a> di funzionare in quanto HTML */
        
    }

    if ($row['tipoRuolo'] === 'Dottore') {
        $_SESSION['ruolo'] = 'Dottore';
        /* Si chiude il tag php per permettere al link <a> di funzionare in quanto HTML */
    
}   
    }
}
    ?>

<!-- Inizio parte HTML -->
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <!-- Link al css da redifinire successivamente-->
    <link rel="stylesheet" type="text/css" href="BackBone_Phoenix/Grafica.css?<?php echo time(); ?>">
    <script src="Backbone_Phoenix/FunzioniDinamiche.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <meta name="ruolo-utente" content="<?php echo $_SESSION['ruolo']; ?>">
    <meta name="codiceFiscale" content="<?php echo $_SESSION['codiceFiscale']; ?>">

</head>

<body>

    <header class="header">
        <section class="copertina">
            <h2 class="generico">Phoenix</h2>
        </section>
    </header>

    <nav class="sidebar">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="images/Phoenix.jpg" alt="Profilo">
                </span>

                <div class="text header-text">
                    <span class="nome">Phoenix</span>
                    <span class="tipo">Rinasci più forte di Prima</span>
                </div>
            </div>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-home-smile icona'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="#" onclick="caricapagina('BackBone_Phoenix/VisualizzaNotifica.php')">
                            <i class='bx bx-bell icona'></i>
                            <span class="text nav-text">Notifiche</span>
                        </a>
                    </li>
                    <li class="nav-link">

                        <?php 
                            if ($_SESSION['ruolo'] == 'Paziente') { ?>
                        <a href="BackBone_Phoenix/Prenotazioni.php">
                            <?php }
                                    /* Inizio codice per Infermiere*/
                                    if ($_SESSION['ruolo'] == 'Infermiere') { ?>
                            <a href="#" onclick="caricapagina('BackBone_Phoenix/Infermiere.php')">
                                <?php } ?>

                                <i class='bx bx-home-smile icona'></i>
                                <span class="text nav-text">
                                    <?php if ($_SESSION['ruolo'] == "Paziente") {
                                            echo "Prenota Visita";
                                        } else if ($_SESSION['ruolo'] == "Infermiere") {
                                            echo "Gestione Letti";
                                        } else {
                                            echo "Gestione Pazienti(?)";
                                        }
                                        ?>
                                </span>
                            </a>
                    </li>
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-home-smile icona'></i>
                            <span class="text nav-text">Lorem ipsum</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="BackBone_Phoenix/Logout.php" class="Button">
                            <i class='bx bx-home-smile icona'></i>
                            <span class="text nav-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div id="content" class="riquadri">

    </div>


</body>

</html>

<?php
} else {
    header("Location: Login.php");
    exit();
}
?>