<!-- file in cui ci sarà come Login funzionerà -->

<?php 
 session_start();
 
 $serverName = "localhost";
 $username = "root"; 
 $password = ""; 
 $db_name = "5q_ombrello_phoenix"; 



 $conn = mysqli_connect($serverName, $username, $password, $db_name);  // Ritorna un oggetto msql che rappresenta la connessione al server

   if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
      }
 


 /* Get è una richiesta o un invio dati fatta al server web o al pc stesso, dove i parametri sono incorporati dentro alla richiesta stessa. E' più sicuro rispetto al GET */
    if(isset($_POST['email']) && isset($_POST['password'])){

     // funzione che rimuove tutte le "imperfezioni" dalla stringa
     function test_input($var){ 
        $var = trim($var); // rimuove gli spazi all'inizio e alla fine di una stringa 
        $var = stripslashes($var); //rimuove gli backslash
        $var = htmlspecialchars($var);
         return $var;
       }

    $email = test_input($_POST["email"]);
    $pass = test_input($_POST["password"]);

      setcookie("user", $email, time() + 3600, '/' );
      setcookie("userpass", $pass, time() + 3600, '/' );

     if (empty($email)) {
        header("Location: ../Login.php?error=Email Obbligatoria");
        exit();
       }else if(empty($pass)) {
        header("Location: ../Login.php?error=Password Obbligatoria");
        exit();    
      }else{
      
      /* funzione per trasformare la password in valori hash */
      $pass = md5($pass);
      
      $sql = "SELECT * FROM utenti WHERE email='$email' AND password='$pass'";
      //  restituirà un oggetto mysqli_result. Per altre domande/query riuscite, darà true, in caso di errore false. $conn è la variabile che rappresenta la connessione(si veda db_coonection.php)

      $result = $conn->query($sql);
      if(mysqli_num_rows($result) === 1){
         //Restituisce un array di stringhe che rappresentano la riga recuperata. Se non ci sono più righe nel set di risultati darà NULL
         $row = mysqli_fetch_assoc($result); 
         if($row['email'] === $email && $row['password'] === $pass){
            $_SESSION["email"] = $row["email"];
            $_SESSION["nome"] = $row["nome"];
            $_SESSION["cognome"] = $row["cognome"];
            $_SESSION["codiceFiscale"] = $row["codiceFiscale"];
            $_SESSION["data_nascita"] = $row["data_nascita"];
            header("Location: ../Home.php");
            exit();
         }else{ 
            header("Location: ../Login.php?error=Email o Password non corrette!");
            exit();
         }
      }else{
         header("Location: ../Login.php?error=Email o Password non corrette!");
         exit();}
      }
    }else{
    header("Location: ../Login.php");
    exit();
    }

    ?>
