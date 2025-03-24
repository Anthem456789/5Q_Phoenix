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

    <!-- RICERCA -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cerca per contenuto o patologia..." class="search-input">
        <button onclick="cercaCartelle()" class="search-button">Cerca</button>
        <div id="loading">Caricamento...</div>
        <div id="error"></div>
    </div>

    <!-- LISTA CARTELLE -->
    <h2>Cartelle Cliniche</h2>
    <div id="cartelle">
        <?php foreach($cartelle as $cartella): ?>
            <div class="card">
                <h3><?= date('d/m/Y H:i', strtotime($cartella['data_creazione'])) ?></h3>
                <p>Patologia: <?= $cartella['malattia'] ?></p>
                <div class="content"><?= nl2br($cartella['contenuto']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script TYPE="text/javascript">
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');

    function handleSearchInput() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(cercaCartelle, 500);
    }

    searchInput.addEventListener('input', handleSearchInput);
    searchInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') cercaCartelle();
    });

    async function cercaCartelle() {
        const query = searchInput.value.trim();
        const container = document.getElementById('cartelle');
        const loading = document.getElementById('loading');
        const errorDiv = document.getElementById('error');

        try {
            loading.style.display = 'block';
            errorDiv.style.display = 'none';

            const response = await fetch(`?ajax=1&search=${encodeURIComponent(query)}`);
            
            if(!response.ok) throw new Error('Errore nella ricerca');
            
            const cartelle = await response.json();
            
            container.innerHTML = cartelle.map(c => `
                <div class="card">
                    <h3>${new Date(c.data_creazione).toLocaleDateString('it-IT', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</h3>
                    <p>Patologia: ${c.malattia}</p>
                    <div class="content">${c.contenuto.replace(/\n/g, '<br>')}</div>
                </div>
            `).join('');

            if(cartelle.length === 0) {
                errorDiv.textContent = 'Nessun risultato trovato';
                errorDiv.style.display = 'block';
            }

        } catch(error) {
            errorDiv.textContent = error.message;
            errorDiv.style.display = 'block';
        } finally {
            loading.style.display = 'none';
        }
    }
    </script>

<?php endif; ?>

<?php
// GESTIONE AJAX
if(isset($_GET['ajax']) && isset($_GET['search'])) {
    header('Content-Type: application/json');
    
    try {
        $searchTerm = $conn->real_escape_string($_GET['search']);
        $search = "%$searchTerm%";
        $codiceFiscale = $_SESSION['user']['cf'];

        $stmt = $conn->prepare("
            SELECT d.*, p.nome as malattia 
            FROM Documenti d
            JOIN Patologia_Documenti pd ON d.id_documento = pd.id_documento
            JOIN Patologia p ON pd.id_malattia = p.id_malattia
            WHERE (d.contenuto LIKE ? OR p.nome LIKE ?) AND d.codiceFiscale = ?
        ");
        $stmt->bind_param("sss", $search, $search, $codiceFiscale);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $cartelle = $result->fetch_all(MYSQLI_ASSOC);
        
        echo json_encode($cartelle);
        
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>
</body>
</html>



