
function gestisciLetti(idLetto, newStatus) {
    const CodiceFiscale = document.querySelector('meta[name="codiceFiscale"]').getAttribute('content');
    const categoria = document.querySelector('meta[name="ruolo-utente"]').getAttribute('content');
    const titolo = "Stato modificato con successo!";
    const descrizione = "Lo stato del letto è stato modificato con successo!";
    
    let cf_paziente = "";
    if (newStatus == 1) {
        cf_paziente = document.getElementById('cf_paziente').value;
    }

    fetch('updateLetto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_letto=' + idLetto + '&isTaken=' + newStatus + '&codiceFiscale=' + CodiceFiscale +
              '&categoria=' + categoria + '&titolo=' + titolo + '&descrizione=' + descrizione +
              '&cf_paziente=' + cf_paziente
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('Stato aggiornato correttamente!')) {
            const button = document.getElementById(`btn-${idLetto}`);
            if (button) {
                if (newStatus == 1) {
                    button.textContent = "Rilascia";
                    button.setAttribute('onclick', `gestisciLetti('${idLetto}', 0)`);
                } else {
                    button.textContent = "Assegna";
                    button.removeAttribute('onclick');
                }
                inviaNotificaInfermiere(CodiceFiscale, categoria, titolo, descrizione);
            }

        
            document.getElementById('popup_rilascia').style.display = 'none';
        } else {
            console.error('Errore:', data);
            alert('Si è verificato un errore. Riprova.');
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('Si è verificato un errore. Riprova.');
    });
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