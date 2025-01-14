<?php
session_start();

$serverName = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";

if(isset($_POST['codiceFiscale']) && isset($_POST['email'])){

  function test_input($var){ 
     $var = trim($var); 
     $var = stripslashes($var); 
     $var = htmlspecialchars($var);
     return $var;
  }

  $conn = mysqli_connect($serverName, $username, $password, $dbname);

  if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
  }


  $CodiceFiscale = test_input($_POST['codiceFiscale']);
  $email = test_input($_POST['email']);

  if (empty($CodiceFiscale)) {
    header("Location: ../Home.php?error=Codice Fiscale obbligatorio");
    exit();
  } else if (empty($email)) {
    header("Location: ../Home.php?error=email obbligatoria");
    exit();    
  } else {

    // Unisci nome corso e sezione per creare un identificativo unico per la tabella
    $nomecorso_con_sez = $nomecorso . "_" . $cscorso;

    // Verifica se esiste già una tabella con questo nome
    $check_table = "SHOW TABLES LIKE '$nomecorso_con_sez'";
    $result = mysqli_query($conn, $check_table);

    if(mysqli_num_rows($result) == 0) {
      // La tabella non esiste, quindi puoi crearla
      $sql = "CREATE TABLE $nomecorso_con_sez (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        cognome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        SezClass VARCHAR(255) NOT NULL,
        NomeCorso VARCHAR(255) NOT NULL
      )";


      if(mysqli_query($conn, $sql)) {
       
        $inserisci = "INSERT INTO $nomecorso_con_sez (nome, cognome, email, SezClass, NomeCorso) 
                      VALUES ('$nome', '$cognome', '$email', '$cscorso', '$nomecorso')";
        if (mysqli_query($conn, $inserisci)) {
          header("Location: ../home.php?success=Corso creato!");
        } else {
          header("Location: ../home.php?error=Errore nell'inserimento dati: " . mysqli_error($conn));
        }
      } else {
        header("Location: ../home.php?error=Errore nella creazione del corso: " . mysqli_error($conn));
      }
    } else {
      // La tabella esiste già
      header("Location: ../home.php?error=Il corso con questa sezione esiste già.");
    }

    mysqli_close($conn);
    exit();
  }
}
?>
