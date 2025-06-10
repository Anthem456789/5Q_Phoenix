<?php

    include "../../functionLog.php" ; 
    include_once "../Generale/Db.php" ; 

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

                echo "<div class='letto-box'>";
                echo "ID letto: " . $row3['id_letto'] . " >>> Reparto: " . $id_reparto . "<br>";

                if ($row3['isTaken'] == 1) {
                    echo "<button id='btn-{$row3['id_letto']}' class='Cambia-btn' onclick=\"gestisciLetti('{$row3['id_letto']}', 0)\">Rilascia</button><br>";
                    
                    echo "<button id='apriOpzioni-{$row3['id_letto']}' class='Cambia-btn'>Opzioni</button>";
                } else if($row3['isTaken'] == 0) {
                    echo "<button id='btn-{$row3['id_letto']}' class='Cambia-btn assegna-btn' data-id='{$row3['id_letto']}'>Assegna</button><br>";

                    echo "<button id='apriOpzioni-{$row3['id_letto']}' class='Cambia-btn'>Opzioni</button>";
                }
                echo "</div>";
            }
            echo "</div>";
            $stmt3->close();
        }
    }

    ?>

    <!-- Riquadro a comparsa per pulsante Rilascia -->
    <div id="popup_rilascia" class="popup">
        <div class="popup-content">
            <span id="closeBtn" class="close-btn">&times;</span>
            <form id="Inserimento_cf">
                <input type="hidden" id="id_letto" name="id_letto">
                <input type="text" id="cf_paziente" name="cf_paziente" placeholder="Codice Fiscale" required>
                <button>Invia</button>
            </form>
        </div>
    </div>

    <!-- Riquadro a comparsa per pulsante opzioni -->
    <div id="popup_opzioni" class="popup">
        <div class="popup-content">
            <span id="closeBtn" class="close-btn">&times;</span>
            <p id="popupMessage_opzioni">Questo è un riquadro a comparsa!</p>
        </div>
    </div>

</body>

<script src="../js/Funzioni_infermiere.js" defer></script>
<script>
function openPopup(lettId) {
    const popup = document.getElementById('popup_opzioni');
    const popupMessage = document.getElementById('popupMessage_opzioni');


    popup.style.display = 'flex';


    fetch('getInfo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id_letto=' + lettId
        })
        .then(response => response.text())
        .then(text => {
            console.log('Risposta del server:', text);

            try {

                const data = JSON.parse(text);

                if (data.success === true) {
                    const cf_Paziente = data.cf_Paziente;
                    let message = `<p>Codice Fiscale Paziente: ${cf_Paziente}</p>`;


                    if (data.documents && data.documents.length > 0) {
                        let documentsMessage = '';
                        for (let i = 0; i < data.documents.length; i++) {
                            documentsMessage +=
                                `<p>ID Documento: ${data.documents[i].id_documento} | Contenuto: ${data.documents[i].contenuto}</p>`;
                        }
                        message += documentsMessage;
                    } else {
                        message += "<p>No documents found.</p>";
                    }

                     /* Imposta il contenuto del popup */
                    document.getElementById('popupMessage_opzioni').innerHTML = message;
                } else if (data.error) {
                    document.getElementById('popupMessage_opzioni').innerHTML =
                        `<span style="color:red;">${data.error}</span>`;
                } else {
                    document.getElementById('popupMessage_opzioni').innerHTML = "Errore sconosciuto.";
                }
            } catch (e) {
                console.error('Errore nel parsing JSON:', e);
                document.getElementById('popupMessage_opzioni').innerHTML =
                    'Si è verificato un errore nella risposta del server.';
            }
        })
        .catch(error => {
            console.error('Errore durante la richiesta:', error);
            document.getElementById('popupMessage_opzioni').innerHTML =
                'Si è verificato un errore nella richiesta.';
        });
}


const closeBtn = document.getElementById('closeBtn');
closeBtn.onclick = function() {
    const popup = document.getElementById('popup_opzioni');
    popup.style.display = 'none';
}

/* Per chiuderlo quando clicchi fuori dal contenuto */
window.onclick = function(event) {
    const popup = document.getElementById('popup_opzioni');
    if (event.target === popup) {
        popup.style.display = 'none';
    }
}

/* Aggiunge un listener a ciascun pulsante "Opzioni"  */
document.querySelectorAll('.Cambia-btn').forEach(button => {
    if (button.id.includes('apriOpzioni')) {
        // Ottieni l'ID del letto dal pulsante
        const lettoId = button.id.split('-')[1];
        button.addEventListener('click', function() {
            openPopup(lettoId); // Passa l'ID del letto al popup
        });
    }
});


/* Per ogni pulsante con "Assegna" aggiunge un listener e prende data-id */
document.querySelectorAll('.assegna-btn').forEach(button => {
    button.addEventListener('click', function() {
        const lettoId = this.getAttribute('data-id');
        document.getElementById('id_letto').value = lettoId;

        /* Apre il popup */
        document.getElementById('popup_rilascia').style.display = 'flex';
    });
});

const form = document.getElementById('Inserimento_cf');
form.addEventListener('submit', function(event) {
    event.preventDefault();

    const id_letto = document.getElementById('id_letto').value;
    gestisciLetti(id_letto, 1); 
});

/* Per chiuderlo quando clicchi fuori dal contenuto */
window.onclick = function(event) {
    const popup = document.getElementById('popup_rilascia');
    if (event.target === popup) {
        popup.style.display = 'none';
    }
}
</script>

</html>