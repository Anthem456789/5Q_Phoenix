<?php

$q = isset($_GET["stringa"]) ? sanitize($_GET["stringa"]) : '';

if (!empty($q)) {
    $db = new mysqli("localhost", "root", "", "5q_ombrello_phoenix");
    if ($db->connect_errno) {
        die("Errore di connessione: " . $db->connect_error);
    }

    $sql = "SELECT d.*, p.nome as malattia 
            FROM Documenti d
            JOIN Patologia_Documenti pd ON d.id_documento = pd.id_documento
            JOIN Patologia p ON pd.id_malattia = p.id_malattia
            WHERE (d.contenuto LIKE ? OR p.nome LIKE ?)";

    $like_q = "%$q%";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $like_q, $like_q);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($riga = $result->fetch_assoc()) {
            echo $riga["id_documento"] . "," . $riga["contenuto"] . "," . $riga["malattia"] . "<br>";
        }
    } else {
        echo "Nessun documento trovato.";
    }
    $db->close();
}
?>

