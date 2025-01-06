<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


if (isset($_SESSION["codiceFiscale"])) {
    $codiceFiscale = $_SESSION["codiceFiscale"];

    if ($_SESSION['ruolo'] == "Infermiere") {
        $ruolo = $_SESSION['ruolo'];
        $TMessaggio = "Stato letto modificato!";
        $Descrizione = "Lo stato del letto è stato modificato da " . $codiceFiscale;

       
      
        $sql = "INSERT INTO Notifica (codiceFiscale, categoria, titolo, descrizione, visualizzato) 
                VALUES (?, ?, ?, ?, FALSE)";
                

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $codiceFiscale, $ruolo, $TMessaggio, $Descrizione);

           
            try {
                $stmt->execute();
                echo "Notifica inserita con successo!";
            } catch (mysqli_sql_exception $e) {
                echo "Errore nell'inserimento della notifica: " . $e->getMessage();
            }
            

            $stmt->close();
        } else { 
            echo "Errore nella preparazione della query: " . $conn->error;
        }
    } else {
        echo "Ruolo non valido per inserire notifiche.";
    }
} else {
    echo "Codice fiscale non trovato nella sessione.";
}

$conn->close();
?>
