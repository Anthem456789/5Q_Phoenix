
function caricapagina(pg) {

    const content = document.getElementById('content');
    var richiesta = new XMLHttpRequest();
    richiesta.open('GET', pg, true);
    richiesta.onreadystatechange = function() {
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
        const categoria = document.querySelector('meta[name="ruolo-utente"]').getAttribute('content');
        const titolo = "Prenotazione si!";
        const descrizione = "pute!";

        // Verifica che entrambi i campi siano compilati
        if (!codiceFiscale || !reparto) {
            alert("Entrambi i campi sono obbligatori!");
            return;
        }

        const formData = new FormData();
        formData.append('Fiscale', codiceFiscale);
        formData.append('reparto', reparto);
        formData.append('categoria', categoria);
        formData.append('titolo', titolo);
        formData.append('descrizione', descrizione);
       
     
        fetch('make-prenotazioni.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())  
        .then(data => {
            console.log('Dati ricevuti:', data); // debug
            if (data.success) {
                document.getElementById('resultPrenotazioni').innerHTML = data.success;
            } else if (data.error) {
                document.getElementById('resultPrenotazioni').innerHTML = data.error;
            } else {
                document.getElementById('resultPrenotazioni').innerHTML = 'Errore sconosciuto.';
            }
            form.reset();

            inviaNotificaPaziente(codiceFiscale,reparto,titolo,descrizione,categoria);
        })
        .catch(error => {
            console.error('Errore durante la richiesta:', error);
            document.getElementById('resultPrenotazioni').innerHTML = 'Si è verificato un errore.';
        });
    });
}

// Funzione per inviare la notifica al paziente
function inviaNotificaPaziente(codiceFiscale, reparto, titolo, descrizione, categoria) {

    const notificaData = new FormData();

    notificaData.append('codiceFiscale', codiceFiscale);
    notificaData.append('categoria',categoria);
    notificaData.append('titolo', titolo);
    notificaData.append('descrizione', descrizione);

    console.log(codiceFiscale);
    
    fetch("Notifiche.php", {
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
    


        fetch('BackBone_Phoenix/updateLetto.php', {
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
                        button.setAttribute('onclick', `aggiorna('${idLetto}', 0)`);
                        inviaNotificaInfermiere(CodiceFiscale,categoria,titolo,descrizione);
                    } else {
                        button.textContent = "Assegna";
                        button.setAttribute('onclick', `aggiorna('${idLetto}', 1)`);
                        inviaNotificaInfermiere(CodiceFiscale,categoria,titolo,descrizione);
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
    notificaData.append('categoria',categoria);
    notificaData.append('titolo', titolo);
    notificaData.append('descrizione', descrizione);
    
    /* Siccome Infermiere.php è stato chiamato dinamicamente bisogna mettere un percoso assoluto */
    fetch("/5Q_Phoenix/src/BackBone_Phoenix/Notifiche.php", {
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
