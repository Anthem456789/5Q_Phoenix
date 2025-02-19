<?php
/* Si mette il tipo di contenuto, non più come "html response", per le risposte Json */
header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = '5q_ombrello_phoenix';


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connessione al database fallita: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $codiceFiscale = $_POST['Fiscale'];
    $reparto = $_POST['reparto'];
    $orario = $_POST['orario'];

    // Validate the input data
    if (empty($codiceFiscale) || empty($reparto) || empty($orario)) {
        echo json_encode(['error' => 'Errore: Campi obbligatori mancanti!']);
        exit;
    }

    /* Controllo per verificare se lo stesso codice fiscale è già presente nello stesso reparto */
    $checkQuery = "SELECT * FROM prenotazioni WHERE codiceFiscale = ? AND id_reparto = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $codiceFiscale, $reparto); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['error' => 'Errore: Hai già preso il numero massimo di prenotazioni in un reparto!']);
        $stmt->close();
        exit;
    }

    /* Se è una nuova prenotazione si esegue il codice sottostante */

    $sql = "SELECT orario_inizio, orario_fine FROM Dottore WHERE id_reparto = ?";
    $stmt->close();
    // Controlla se il dottore è disponibile
        $sql = "SELECT orario_inizio, orario_fine 
                FROM Dottore 
                WHERE id_reparto = ?";

   
    if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $reparto);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $orario_inizio = $row['orario_inizio'];
                    $orario_fine = $row['orario_fine'];
    
                    if ($orario >= $orario_inizio && $orario <= $orario_fine) { 
                        $stmt->close();
                        $stmt->bind_param("sis", $codiceFiscale, $reparto, $orario); 
                        // Inserisci la prenotazione
                        $sql = "INSERT INTO prenotazioni (id_reparto, codice_fiscale, data_ora)
                                VALUES (?, ?, ?)";

                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("sss", $reparto, $codiceFiscale, $orario);
                            if ($stmt->execute()) {
                                echo json_encode(['success' => 'Prenotazione inserita con successo!']);
                            } else {
                                echo json_encode(['error' => 'Errore durante l\'inserimento: ' . $stmt->error]);
                               
                            }
                             $stmt->close();
                        }
                    } else {
                        echo json_encode(['error' => 'Nessun dottore trovato per questo reparto.']);
                    } 
                } else {
                    echo json_encode(['error' => 'Il dottore non è disponibile in quest\'orario.']);
                }
            } else {
                echo json_encode(['error' => "Errore nella query: " . $conn->error]);
            }
        } 
    }
     $conn->close();

?>
