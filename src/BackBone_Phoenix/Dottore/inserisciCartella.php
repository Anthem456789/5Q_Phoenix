Migliorare la verifica dei cf

<?php 
include '../Generale/Db.php'; // Include database connection

if (isset($_POST["reparto"]) && isset($_POST["letto"]) && isset($_POST["codicefiscale"])) {

    $id_reparto = $_POST["reparto"];
    $id_letto = $_POST["letto"];
    $codiceFiscale = $_POST["codicefiscale"];
    
    // Update the bed with the patient's CF
    //Controllo codice fiscale duplicato in altri letti -> da fare 
    //isTaken = 1^isTaken cambia la value del boolean all'opposto -> 0 = 1 ; 1 = 0
    $stmt = $conn->prepare("UPDATE letto SET cf_paziente = ? , isTaken = 1^isTaken  WHERE id_letto = ?");
    $stmt->bind_param("si", $codiceFiscale, $id_letto);

    if ($stmt->execute()) {
        header("Location: Assegna_letto.php?success=Assegnazione completata"); 
    } else {
        //echo "Errore SQL: " . $stmt->error;
       header("Location: Assegna_letto.php?error=Errore nell'assegnazione"); 
    }

    $stmt->close();
} else {
    header("Location: Assegna_letto.php?error=Dati mancanti"); 
}
?>
