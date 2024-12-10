<?php 
 session_start();

 $serverName = "localhost";
 $username = "root"; 
 $password = ""; 
 $db_name = "5q_ombrello_phoenix"; 



 // Creazione della connessione
 $conn = new mysqli($serverName, $username, $password, $db_name);

    if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['Re_password']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['data_nascita']) && isset($_POST['codiceFiscale'])){

     // funzione che rimuove tutte le "imperfezioni" dalla stringa
     function test_input($var){ 
        $var = trim($var); // rimuove gli spazi all'inizio e alla fine di una stringa 
        $var = stripcslashes($var); //rimuove gli backslash
        $var = htmlspecialchars($var, ENT_QUOTES, 'UTF-8');  // Converte sia i doppi apici che gli apici singoli in entità HTML.
         return $var;
       }

       function checkPass($var1){

        $numero = 0;
        $minuscole = 0;
        $maiuscole = 0;

        for($i = 0; $i<strlen($var1); $i++){
          //ord converte in codice ASCII
          $ascii = ord($var1[$i]);

         if($ascii >= ord('0') && $ascii <= ord('9')){$numero++;}
         if($ascii >= ord('a') && $ascii <= ord('z')){$minuscole++;}
         if($ascii >= ord('A') && $ascii <= ord('Z')){$maiuscole++;}
       }

         if(($numero>0) && ($maiuscole>0) && ($minuscole>0) && (strlen($var1)>6)){
          return true;
         }else{
          return false;
         }

       }

    $email = test_input($_POST["email"]);
    $pass = test_input($_POST["password"]);
    $Re_pass = test_input($_POST["Re_password"]);
    $nom = test_input($_POST["nome"]);
    $cognome = test_input($_POST["cognome"]);
    $data_nascita = test_input($_POST["data_nascita"]);
    $codiceFiscale = test_input($_POST["codiceFiscale"]);
    $categ = test_input($_POST["gender"]);

    $_SESSION["nome"] = $nom;
    $_SESSION["pass"] = $pass;
    $_SESSION["em"] = $email;
    $_SESSION["cognome"] = $cognome;
    $_SESSION["Re_password"] = $Re_pass;
    $_SESSION["data_nascita"] = $data_nascita;
    $_SESSION["codiceFiscale"] = $codiceFiscale;
    
    
    
     if (empty($nom)) {
        header("Location: ../SignUp.php?error=Nome Obbligatorio");
        exit();
       }else if(empty($cognome)) {
        header("Location: ../SignUp.php?error=Cognome Obbligatorio");
        exit();    
      }else if(empty($data_nascita)){
        header("Location: ../SignUp.php?error=Data di nascita Obbligatoria");
        exit();
      }else if(empty($codiceFiscale)){
        header("Location: ../SignUp.php?error=codice Fiscale Obbligatorio");
        exit();
      }else if(empty($email)) {
        header("Location: ../SignUp.php?error=Email obbligatoria");
        exit();    
      }else if(empty($pass)) {
        header("Location: ../SignUp.php?error=Password Obbligatoria");
        exit();    
      }else if(!checkPass($pass)){
        header("Location: ../SignUp.php?error=Password non conforme o troppo corta");
      }else if(empty($Re_pass)) {
        header("Location: ../SignUp.php?error=Ripeti la password");
        exit();    
      }else if($pass !== $Re_pass){ 
         header("Location: ../SignUp.php?error=Le due password non corrispondono!");
         exit();
      }else if(empty($categ)){
        header("Location: ../SignUp.php?error=Inserisci la tua categoria!");
        exit();
      } else{
      
      //funzione per trasformare la password in valori hash
       $pass = md5($pass);

      $sql = "SELECT * FROM utenti WHERE email='$email' ";
      //  restituirà un oggetto mysqli_result. Per altre domande/query riuscite, darà true, in caso di errore false. $conn è la variabile che rappresenta la connessione(si veda db_coonection.php)
      $result = mysqli_query($conn, $sql);  
      //verifica che non ci siano email uguali
      if(mysqli_num_rows($result) > 0){
         header("Location: ../SignUp.php?error= Email già esistente, inseriscine un'altra");
         exit();
      }else{
       session_unset();
       $sql2 = "INSERT INTO utenti(codiceFiscale, nome, cognome, data_nascita, email, password) VALUES('$codiceFiscale', '$nom', '$cognome', '$data_nascita', '$email', '$pass')";
       $result2 = mysqli_query($conn, $sql2);

       if($result2){

         $sql3 = "INSERT INTO utenti_ruoli(codiceFiscale, id_ruoli) VALUES('$codiceFiscale','$categ')";
         $result3 = mysqli_query($conn, $sql3);
         header("Location: ../Login.php?success= Il tuo account è stato creato con successo!");
         exit();
       }else{
         header("Location: ../Login.php?error= Un errore ha impedito la creazione dell'account");
         exit();
      }
   }
}    

    }else{
        header("Location: ../SignUp.php");
         exit();
    }
         
      
    ?>