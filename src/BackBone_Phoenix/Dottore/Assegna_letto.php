<?php
include "../Infermiere/display_reparto.php"; //aggiunto di recente

$conn = new mysqli("localhost", "root", "", "5q_ombrello_phoenix");
?>

<!DOCTYPE html>
<html>

<body>
<a href="Cartelle.php">Torna alla pagina precedente</a>

<?php for($i=0; $i<sizeof($reparti); $i++) : ?>
    <div class='reparto-box' onclick="window.location.href='/src/BackBone_Phoenix/Infermiere/reparto-infermiere.php?id_reparto=<?= htmlspecialchars($reparti[$i]) ?>';">
        Reparto N°<?= htmlspecialchars($reparti[$i]) ?>
    </div>
<?php endfor; ?>
    
<p><b>Doc da cercare:</b></p>
<form>
    nome: <input type="text" onkeyup="mostra(this.value)">
</form>

<p>Doc suggeriti: 
    <div class="box" id="elenco"></div>
</p>

<!-- Primo select (Reparto) -->
<form action = "inserisciCartella.php" method="post">

<select name="reparto" id="campo1" required>
    <option value="">Seleziona un reparto</option>
    <?php 
    $malattie = $conn->query("SELECT * FROM reparto");
    while($row = $malattie->fetch_assoc()): ?>
        <option value="<?= $row['id_reparto'] ?>"><?= $row['nome_reparto'] ?></option>
    <?php endwhile; ?>
</select>

<!-- Secondo select (Letto) -->
<select name="letto" id="campo2" required disabled>
    <option value="">Seleziona un letto</option>
</select>

<!-- terzo input (fisco) -->
<input name="codicefiscale" id="campo3" placeholder="Codice Fiscale" required disabled></input>

<button>Inserisci</button>

</form>

<?php if(isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <?php
 if(isset($_GET['success'])) { ?>
        <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?>
        
<script type="text/javascript">
function mostra(str) {
    if (str.length == 0) {
        document.getElementById("elenco").innerHTML = "";
        return;
    }

    let ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            document.getElementById("elenco").innerHTML = ajax.responseText;
        }
    };
    ajax.open("GET", "get_letto.php?stringa=" + encodeURIComponent(str), true);
    ajax.send();
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
    // Quando cambia la scelta del reparto
    $('#campo1').change(function() {
        var id_reparto = $(this).val();

        // Se è stato selezionato un reparto
        if (id_reparto != "") {
            // Abilita il secondo select (Letto)
            $('#campo2').prop('disabled', false);

            // Fai una richiesta AJAX per ottenere i letti disponibili
            $.ajax({
                url: 'get_letto.php', // Il file che restituirà i letti per il reparto selezionato
                method: 'GET',
                data: { reparto_id: id_reparto },
                success: function(data) {
                console.log('Risposta del server:', data);

                // Verifica la risposta ricevuta
                if (typeof data === 'string') {
                    try {
                        // Tenta di fare il parsing se è una stringa
                        data = JSON.parse(data);
                        } catch (e) {
                            console.error('Errore nel parsing del JSON:', e);
                        }
                }

                if (Array.isArray(data) && data.length > 0 ) {
                    // Svuota il campo2 prima di aggiungere le nuove opzioni
                    $('#campo2').html('<option value="">Seleziona un letto</option>');

                    // Itera sui letti ricevuti
                    $.each(data, function(index, letto) {
                         $('#campo2').append('<option value="' + letto.id + '">' + "Letto" + letto.id + '</option>');
                     });

                     $('#campo2').change(function() {
                         var id_letto = $(this).val();

                     if (id_letto != ""){
                        $('#campo3').prop('disabled', false);
                    }else {
                        $('#campo3').prop('disabled', true);
                    } 
                     })
                    
                } else {
                    $('#campo2').html('<option value="">Nessun letto disponibile</option>');
                }
            },
                error: function() {
                    alert("Si è verificato un errore nel recupero dei letti.");
                }
            });
        } else {
            // Disabilita il secondo select se non c'è un reparto selezionato
            $('#campo2').prop('disabled', true);
            $('#campo2').html('<option value=""> Seleziona un letto </option>');
        }
    });
});
</script>

</body>
</html>
