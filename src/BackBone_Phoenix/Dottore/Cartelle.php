<?php

session_start();
$conn = new mysqli("localhost", "root", "", "5q_ombrello_phoenix");

// CREAZIONE CARTELLA CLINICA
if(isset($_POST['crea_cartella'])) {
    if($_SESSION['user']['ruolo'] === 'dottore') {
        $stmt = $conn->prepare("INSERT INTO Documenti 
            (codiceFiscale, contenuto, id_dottore) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", 
            $_POST['paziente_cf'],
            $_POST['contenuto'],
            $_SESSION['user']['id_dottore']
        );
        $stmt->execute();
        
        $id_documento = $conn->insert_id;
        
        $stmt = $conn->prepare("INSERT INTO Patologia_Documenti 
            (id_documento, id_malattia, codiceFiscale) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_documento, $_POST['malattia'], $_POST['paziente_cf']);
        $stmt->execute();
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
        <a href="?logout">Logout</a>
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
    <?php endif; ?>

    </script>

<?php endif; ?>

</body>
</html>



