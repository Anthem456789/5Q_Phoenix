<?php
include "../../functionLog.php";
include_once "../Generale/Db.php";

// Imposta l'intestazione per la risposta JSON
header('Content-Type: application/json');

if (isset($_POST['id_letto'])) {
    $id_letto = $_POST['id_letto'];
    $sql = "SELECT t.id_letto, t.cf_Paziente, y.id_documento, y.contenuto
            FROM letto AS t
            JOIN documenti AS y ON y.codiceFiscale = t.cf_Paziente
            WHERE t.id_letto = ?";

    if ($stm = $conn->prepare($sql)) {
        $stm->bind_param('i', $id_letto);
        $stm->execute();
        $result = $stm->get_result();

        if ($result->num_rows > 0) {
            $documents = [];
            $cf_paziente = null;

            // Aggiungi ogni documento nella risposta
            while ($row = $result->fetch_assoc()) {
                
                $documents[] = [
                    'id_documento' => $row['id_documento'],
                    'contenuto' => $row['contenuto']
                ];

                if (isset($row['cf_Paziente']) && $row['cf_Paziente'] !== null) {
                    $cf_paziente = $row['cf_Paziente']; 
                }
            }

    
            echo json_encode([
                'success' => true,
                'cf_Paziente' => $cf_paziente, 
                'documents' => $documents
            ]);
        } else {
            echo json_encode([
                'error' => 'Nessun dato trovato per il letto.'
            ]);
        }
        $stm->close();
    } else {
        echo json_encode([
            'error' => 'Errore nella preparazione della query: ' . $conn->error
        ]);
    }
} else {
    echo json_encode([
        'error' => 'Errore: ID letto non fornito.'
    ]);
}
?>