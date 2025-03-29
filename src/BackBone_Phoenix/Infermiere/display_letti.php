<?php

use SebastianBergmann\Environment\Console;

/* parte di display letti occupati */
include_once "../Generale/Db.php";

if (isset($_POST['id_reparto'])) {
    $id_reparto = $_POST['id_reparto'];
            $id_letti = [];
            $Disponibile = [];
    if ($id_reparto > 0) {

        $sql3 = "SELECT DISTINCT T.id_letto, Y.isTaken
                FROM reparto_letto AS T
                JOIN letto AS Y 
                ON T.id_letto = Y.id_letto
                WHERE T.id_reparto = ?";

        if ($stmt3 = $conn->prepare($sql3)) {
            $stmt3->bind_param('i', $id_reparto);
            $stmt3->execute();
            $result3 = $stmt3->get_result();

            // Inizializza gli array per i letti
          
            
            while ($row3 = $result3->fetch_assoc()) {
                $id_letti[] = $row3['id_letto'];
                $Disponibile[] = $row3['isTaken'];
            }
            $stmt3->close();
        }
    }
}
?>
