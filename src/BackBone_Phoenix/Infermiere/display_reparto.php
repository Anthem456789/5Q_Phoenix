<?php
/* Codice Business per la gestione dei dati dei reparti (collegata logicamnete con Infermiere.php) */

include_once "../Generale/Db.php";

$sql2 = "SELECT id_reparto
FROM reparto"; 

if($stmt2 = $conn->prepare($sql2)){
$stmt2->execute();
$result2 = $stmt2->get_result();

$reparti = [];

while($row2 = $result2->fetch_assoc()){
    
    $reparti[] = $row2['id_reparto'];
}

$stmt2->close();
  //var_dump($reparto); debug
}