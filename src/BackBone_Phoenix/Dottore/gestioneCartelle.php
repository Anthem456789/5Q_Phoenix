<?php

$q = isset($_GET["stringa"]) ? $_GET["stringa"] : '';

if (!empty($q)) {
    $db = new mysqli("localhost", "root", "", "5q_ombrello_phoenix");
    if ($db->connect_errno) {
        die("Errore di connessione: " . $db->connect_error);
    }

    $sql = "SELECT d.* , p.id_malattia, t.nome
            FROM Documenti as d
            JOIN patologia_documenti as p ON d.id_documento = p.id_documento
            JOIN patologia as t ON t.id_malattia = p.id_malattia
            WHERE (d.contenuto LIKE ?)";

    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('Errore nella preparazione della query: ' . $db->error);
    }

    $like_q = "%$q%";
    $stmt->bind_param("s", $like_q);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($riga = $result->fetch_assoc()) {
            echo "ID Documento: ". $riga["id_documento"] . ", contenuto: " . $riga["contenuto"] .". Malattia del paziente: ".$riga["nome"]. ", Codice Fiscale:".$riga["codiceFiscale"]. "<br>";
        }
    } else {
        echo "Nessun documento trovato.";
    }
    $stmt->close();
    $db->close();
}
?>

