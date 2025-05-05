<?php
include '../Generale/Db.php'; // Assicurati che la connessione al DB sia inclusa
header('Content-Type: application/json');

// Verifica che sia stato passato l'ID del reparto
if (isset($_GET['reparto_id']) && is_numeric($_GET['reparto_id'])) {
    $reparto_id = $_GET['reparto_id'];

    // Prepara la query per recuperare i letti disponibili per il reparto selezionato
    $query = "SELECT l.id_letto FROM letto l
                JOIN reparto_letto rl ON rl.id_letto = l.id_letto
                JOIN reparto r ON r.id_reparto = rl.id_reparto
                    WHERE rl.id_reparto = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Lega il parametro e esegui la query
        $stmt->bind_param('i', $reparto_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Crea un array per i letti
        $letti = array();
        while ($row = $result->fetch_assoc()) {
            $letti[] = array(
                'id' => $row['id_letto'],
                
            );
        }

        //echo json_encode(var_dump($letti));
        // Restituisci i letti in formato JSON
        echo json_encode($letti);

        // Chiudi la dichiarazione
        $stmt->close();
    } else {
        // In caso di errore nella query
        echo json_encode("frocio");
    }
} else {
    // Se non viene fornito un ID valido per il reparto
    echo json_encode([]);
}
?>
