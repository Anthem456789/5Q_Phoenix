/* Funzioni generali */
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



