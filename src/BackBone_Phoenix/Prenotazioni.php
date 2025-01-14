<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Prenotazione Visite Mediche</title>
</head>
<body>
    <div class="container">

    <button id="ToggleBox">Crea Corso</button>
            <div id="popupBox" class="invisible">
                <form action="BackBone_Corsi/make-prenotazioni.php" method="POST">
                    <?php if (isset($_GET['error'])) { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) { ?>
                        <p class="success"><?php echo $_GET['success']; ?></p>
                    <?php } ?>
                    <input type="text" name="nomecorso" placeholder="Nome Corso (Obbligatorio)"><br>
                    <input type="text" name="cscorso" placeholder="Classe e Szn (Obbligatorio)"><br>
                    <button type="submit">Crea corso</button>
                </form>
    </div>
</body>
</html>
