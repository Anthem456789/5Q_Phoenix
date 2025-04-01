<?php
/* Si mette il tipo di contenuto, non più come "html response", per le risposte Json */
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $orarioInput = $_POST["orario"];
    
  
       $orarioInput .= ":00";

       // Verifica e formatta l'orario
       $dateTime = DateTime::createFromFormat('H:i:s', $orarioInput);
       
       if (!$dateTime) {
           echo json_encode(['error' => 'Formato orario non valido. Utilizzare HH:MM.']);
           exit;
       }
   
       $orario = $dateTime->format('H:i:s');
   
       // Controllo campi obbligatori
       if (empty($codiceFiscale) || empty($reparto) || empty($orario)) {
           echo json_encode(['error' => 'Errore: Campi obbligatori mancanti!']);
           exit;
       }
   
       // Controlla se il paziente ha già una prenotazione in quel reparto
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
   
       $stmt->close();
   
       // Controlla se il dottore è disponibile
       $sql = "SELECT orario_inizio, orario_fine FROM Dottore WHERE id_reparto = ?";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("i", $reparto);
       $stmt->execute();
       $result = $stmt->get_result();
   
       if ($result->num_rows === 0) {
           echo json_encode(['error' => 'Nessun dottore disponibile per questo reparto.']);
           $stmt->close();
           exit;
       }
   
       $row = $result->fetch_assoc();
       $orario_inizio = $row['orario_inizio'];
       $orario_fine = $row['orario_fine'];
   
       if ($orario < $orario_inizio || $orario > $orario_fine) {
           echo json_encode(['error' => 'Il dottore non è disponibile in questo orario.']);
           $stmt->close();
           exit;
       }
   
       $stmt->close();
   
       // Inserisce la prenotazione
       $insertQuery = "INSERT INTO prenotazioni (id_reparto, codiceFiscale, data_ora) VALUES (?, ?, ?)";
       $stmt = $conn->prepare($insertQuery);
       $stmt->bind_param("iss", $reparto, $codiceFiscale, $orario);
   
       if ($stmt->execute()) {
           echo json_encode(['success' => 'Prenotazione inserita con successo!']);
       } else {
           echo json_encode(['error' => 'Errore durante l\'inserimento: ' . $stmt->error]);
       }
   
       $stmt->close();
   }
   
   $conn->close();
?> 