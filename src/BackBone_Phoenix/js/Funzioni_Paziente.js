document.addEventListener("DOMContentLoaded", function () {

    // Prendi il ruolo dal meta tag nell'header
    const ruoloUtente = "Paziente";
     gestisciPrenotazionePaziente(ruoloUtente);

});

// Funzione per la gestione delle prenotazioni del paziente
function gestisciPrenotazionePaziente(ruoloUtente) {
    const form = document.getElementById('prenotazioniForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevenire il comportamento predefinito del form

        const codiceFiscale = document.getElementById('codiceFiscale').value.trim();
        const reparto = document.getElementById('reparto').value.trim();
        const orario = document.getElementById('orario').value.trim();
        /* Non prende il contenuto */
        const categoria = ruoloUtente;
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
                inviaNotificaPaziente(codiceFiscale, titolo, descrizione, categoria );
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
function inviaNotificaPaziente(codiceFiscale, titolo, descrizione, categoria) {

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
