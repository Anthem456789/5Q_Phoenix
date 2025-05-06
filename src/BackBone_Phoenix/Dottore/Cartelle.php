<?php

include "../Infermiere/display_reparto.php"; //aggiunto di recente 

$conn = new mysqli("localhost", "root", "", "5q_ombrello_phoenix");

// CREAZIONE CARTELLA CLINICA
if(isset($_POST['crea_cartella']) && $_SESSION['user']['ruolo'] === 'dottore') {
    $paziente_cf = sanitize($_POST['paziente_cf']);
    $contenuto = sanitize($_POST['contenuto']);
    $id_malattia = (int) $_POST['malattia']; // Convertito in intero per sicurezza
    $id_dottore = $_SESSION['user']['id_dottore'];

    if(empty($paziente_cf) || empty($contenuto) || !$id_malattia) {
        echo "<p style='color:red;'>Dati mancanti o errati.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO Documenti (codiceFiscale, contenuto, id_dottore) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $paziente_cf, $contenuto, $id_dottore);
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            $id_documento = $conn->insert_id;
            $stmt = $conn->prepare("INSERT INTO Patologia_Documenti (id_documento, id_malattia, codiceFiscale) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id_documento, $id_malattia, $paziente_cf);
            $stmt->execute();
            echo "<p style='color:green;'>Cartella creata con successo.</p>";
        } else {
            echo "<p style='color:red;'>Errore nella creazione della cartella.</p>";
        }
    }
}



// FUNZIONI DI BASE
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}


// GESTIONE LOGIN
if(isset($_POST['login'])) {
    $cf = sanitize($_POST['codiceFiscale']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE codiceFiscale = ?");
    $stmt->bind_param("s", $cf);
    $stmt->execute();
    
    $user = $stmt->get_result()->fetch_assoc();
 /*   
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'cf' => $user['codiceFiscale'],
            'ruolo' => 'dottore',
            'id_dottore' => $user['id_doc'] ?? null
        ];
    }*/
    $_SESSION['user'] = [
        'cf' => $user['codiceFiscale'],
        'ruolo' => 'dottore',
        'id_dottore' => $user['id_doc'] ?? null
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestione Cartelle Cliniche</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        form { margin: 20px 0; background: #f5f5f5; padding: 20px; }
        input, textarea { width: 100%; margin: 5px 0; padding: 8px; }
        .search-container { margin: 20px 0; position: relative; }
        .search-input { width: calc(100% - 100px); padding: 10px; margin-right: 10px; }
        .search-button { width: 80px; padding: 10px; background-color: #007bff; color: white; 
                         border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        #loading { display: none; color: #007bff; margin-top: 10px; }
        #error { color: red; display: none; margin-top: 10px; }
    </style>
</head>
<body>

<?php if(!isset($_SESSION['user'])): ?>
    <!-- FORM DI LOGIN -->
    <form method="post">
        <h2>Login</h2>
        <input type="text" name="codiceFiscale" placeholder="Codice Fiscale" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Accedi</button>
    </form>


<?php else: ?>
    <!-- INTERFACCIA PRINCIPALE -->
    <header>
        <h1>Benvenuto, <?= $_SESSION['user']['cf'] ?></h1>
        <a href="../../Home.php">Torna indietro</a>
    </header>

    <?php if($_SESSION['user']['ruolo'] === 'dottore'): ?>
        <!-- FORM NUOVA CARTELLA -->
        <form method="post">
            <h2>Nuova Cartella Clinica</h2>
            <input type="text" name="paziente_cf" placeholder="CF Paziente" required>
            <select name="malattia" required>
                <?php 
                $malattie = $conn->query("SELECT * FROM Patologia");
                while($row = $malattie->fetch_assoc()): ?>
                    <option value="<?= $row['id_malattia'] ?>"><?= $row['nome'] ?></option>
                <?php endwhile; ?>
            </select>
            <textarea name="contenuto" rows="6" placeholder="Contenuto cartella..." required></textarea>
            <button type="submit" name="crea_cartella">Salva Cartella</button>
        </form>

    <a href="Assegna_letto.php">Assegna Letto</a>
    <?php endif; ?>

    </script>

<?php endif; ?>

<script TYPE="text/javascript">
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
    ajax.open("GET", "gestioneCartelle.php?stringa=" + encodeURIComponent(str), true);
    ajax.send();
}

</script>
 <P><B>Località da cercare:</B></P>
 <FORM> nome: <INPUT TYPE="text" onkeyup="mostra(this.value)"></FORM>
 <P>Doc suggeriti: <div class = "box" id="elenco"></div></p>

</body>
</html>



