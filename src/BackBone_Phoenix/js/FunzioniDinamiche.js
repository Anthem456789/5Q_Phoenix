
function caricapagina(pg) {

    const content = document.getElementById('content');
    var richiesta = new XMLHttpRequest();
    richiesta.open('GET', pg, true);
    richiesta.onreadystatechange = function () {
        if (richiesta.readyState == 4 && richiesta.status == 200) {
            content.innerHTML = richiesta.responseText;
        }
    };
    richiesta.send();
}



/* ===parte prenotazioni=== */

document.addEventListener("DOMContentLoaded", function () {

    // Prendi il ruolo dal meta tag nell'header
    const ruoloUtente = document.querySelector('meta[name="ruolo-utente"]').getAttribute('content');
    // Controlla il ruolo e gestisci la logica corrispondente
    if (ruoloUtente === 'Paziente') {
        gestisciPrenotazionePaziente();
    } else if (ruoloUtente === 'Infermiere') {
        gestisciLetti(idLetto, newStatus);
    }
});

// Funzione per la gestione delle prenotazioni del paziente
function gestisciPrenotazionePaziente() {
    const form = document.getElementById('prenotazioniForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevenire il comportamento predefinito del form

        const codiceFiscale = document.getElementById('codiceFiscale').value.trim();
        const reparto = document.getElementById('reparto').value.trim();
        const orario = document.getElementById('orario').value.trim();
        /* Non prende il contenuto */
        const categoria = document.querySelector('meta[name="ruolo-utente"]').getAttribute('content');
        const titolo = "Prenotazione si!";
        const descrizione = "pute!";

    
        if (!codiceFiscale || !reparto) {
            alert("Entrambi i campi sono obbligatori!");
            return;
        }

        const formData = new FormData();
        formData.append('Fiscale', codiceFiscale);
        formData.append('reparto', reparto);
        formData.append('orario', orario);
        formData.append('categoria', categoria);
        formData.append('titolo', titolo);
        formData.append('descrizione', descrizione);



        fetch('../Paziente/make-prenotazioni.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())  
        .then(data => {
            console.log("Risposta server:", data); 
        
            if (data.success) {
                document.getElementById('resultPrenotazioni').innerHTML = `<span style="color:green;">${data.success}</span>`;
                inviaNotificaPaziente(codiceFiscale, categoria, titolo, descrizione);
            } else if (data.error) {
                document.getElementById('resultPrenotazioni').innerHTML = `<span style="color:red;">${data.error}</span>`;
            } else {
                document.getElementById('resultPrenotazioni').innerHTML = "Errore sconosciuto.";
            }
        })
        .catch(error => {
            console.error('Errore durante la richiesta:', error);
            document.getElementById('resultPrenotazioni').innerHTML = 'Si è verificato un errore nella richiesta.';
        });
    });
}

// Funzione per inviare la notifica al paziente
function inviaNotificaPaziente(codiceFiscale, reparto, titolo, descrizione, categoria) {

    const notificaData = new FormData();

    notificaData.append('codiceFiscale', codiceFiscale);
    notificaData.append('categoria', categoria);
    notificaData.append('titolo', titolo);
    notificaData.append('descrizione', descrizione);

    console.log(codiceFiscale);

    fetch("../Generale/Notifiche.php", {
        method: 'POST',
        body: notificaData
    })
        .then(response => response.json())
        .then(notificaData => {
            if (notificaData.success) {
                console.log('Notifica inviata con successo');
            } else {
                console.error('Errore nell\'invio della notifica:', notificaData.error);
            }
        })
        .catch(error => console.error('Errore nell\'invio della notifica:', error));
}

// Funzione per la gestione dei letti da parte dell'infermiere

function gestisciLetti(idLetto, newStatus) {

    const CodiceFiscale = document.querySelector('meta[name="codiceFiscale"]').getAttribute('content');
    const categoria = document.querySelector('meta[name="ruolo-utente"]').getAttribute('content');
    const titolo = "Stato modificato con successo!";
    const descrizione = "Lo stato del letto è stato modificato con successo!";



    fetch('updateLetto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_letto=' + idLetto + '&isTaken=' + newStatus + '&codiceFiscale=' + CodiceFiscale + '&categoria=' + categoria + '&titolo=' + titolo + '&descrizione=' + descrizione
    })
        .then(response => response.text())
        .then(data => {
            if (data.includes('Stato aggiornato correttamente!')) {
                const button = document.getElementById(`btn-${idLetto}`);
                if (button) {
                    if (newStatus == 1) {
                        button.textContent = "Rilascia";
                        button.setAttribute('onclick', `gestisciLetti('${idLetto}', 0`);
                        inviaNotificaInfermiere(CodiceFiscale, categoria, titolo, descrizione);
                    } else {
                        button.textContent = "Assegna";
                        button.setAttribute('onclick', `gestisciLetti('${idLetto}', 1)`);
                        inviaNotificaInfermiere(CodiceFiscale, categoria, titolo, descrizione);
                    }
                }
            } else {
                console.error('Errore:', data);
                alert('Si è verificato un errore. Riprova.');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Si è verificato un errore. Riprova.');
        });

    console.log("Codice per infermiere caricato.");
}


// Funzione per inviare la notifica all'infermiere
function inviaNotificaInfermiere(CodiceFiscale, categoria, titolo, descrizione) {

    const notificaData = new FormData();

    notificaData.append('codiceFiscale', CodiceFiscale);
    notificaData.append('categoria', categoria);
    notificaData.append('titolo', titolo);
    notificaData.append('descrizione', descrizione);

    /* Siccome Infermiere.php è stato chiamato dinamicamente bisogna mettere un percoso assoluto */
    fetch( "../Generale/Notifiche.php" , {
        method: 'POST',
        body: notificaData
    })
        .then(response => response.json())
        .then(notificaData => {
            if (notificaData.success) {
                console.log('Notifica inviata con successo');
            } else {
                console.error('Errore nell\'invio della notifica:', notificaData.error);
            }
        })
        .catch(error => console.error('Errore nell\'invio della notifica:', error));
}

/* Parte InvioID da reparto-infermiere */
// Funzione inviaID.js (FunzioniDinamiche.js)
function inviaID(ID) {
    const Id_Data = new FormData();
    Id_Data.append('id_reparto', ID);

    fetch("diplay_letti.php", {
        method: "POST",
        body: Id_Data
    })
    .then(response => response.json())
    .then(Id_Data => {
        if (Id_Data.success) {
            console.log('ID inviato con successo');
        } else {
            console.error('Errore nell\'invio dell\'ID:', Id_Data.error);
        }
    })
    .catch(error => {
        console.error('Errore nella richiesta:', error);
    });
}


