
function caricapagina(pg) {
    var richiesta = new XMLHttpRequest();
    richiesta.open('GET', pg, true);
    richiesta.onreadystatechange = function() {
        if (richiesta.readyState == 4 && richiesta.status == 200) {
            document.getElementById('content').innerHTML = richiesta.responseText;
        }
    };
    richiesta.send();
}


function aggiorna(idLetto, newStatus) {
    fetch('updateLetto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_letto=' + idLetto + '&isTaken=' + newStatus
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        location.reload(); // Ricarica la pagina per aggiornare i pulsanti
    })
    .catch(error => console.error('Errore:', error));
}


