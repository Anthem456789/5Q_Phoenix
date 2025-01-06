
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

function aggiorna(idLetto, newStatus) {
    fetch('BackBone_Phoenix/updateLetto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_letto=' + idLetto + '&isTaken=' + newStatus
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('Stato aggiornato correttamente!')) {
            const button = document.getElementById(`btn-${idLetto}`);
            if (button) {
                if (newStatus == 1) {
                    button.textContent = "Rilascia";
                    button.setAttribute('onclick', `aggiorna('${idLetto}', 0)`);
                } else {
                    button.textContent = "Assegna";
                    button.setAttribute('onclick', `aggiorna('${idLetto}', 1)`);
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
}
