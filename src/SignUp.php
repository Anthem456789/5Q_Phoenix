
<!DOCTYPE HTML>  
<html>
  <head> 
   <title> REGISTRAZIONE </title>
   <link rel="stylesheet" type="text/css" href="BackBone_Phoenix/css/GraficaForm.css?<?php echo time(); ?>">
  </head>
 
<body>
 

<form action="BackBone_Phoenix/Generale/CheckSignUp.php" method="POST">
<h2>Benvenuto</h2>
<?php if(isset($_GET['error'])) { ?>
  <p class="error"><?php echo $_GET['error']; ?></p>
<?php } ?>

<label>Nome</label>
  <input type= "text" name= "nome" placeholder="Nome" value = 
  <?php 
  if (!empty($_COOKIE['nome'])) {
    echo $_COOKIE['nome'];
  }
    ?>><br>

<label>Cognome</label>
<input type= "text" name= "cognome" placeholder="Cognome" value = 
  <?php 
  if (!empty($_COOKIE['cognome'])) {
    echo  $_COOKIE['cognome'];
  }
    ?>><br>

<label>Data Di Nascita</label>
<input type= "text" name= "data_nascita" placeholder="Data di nascita" value = 
  <?php 
  if (!empty($_COOKIE['data'])) {
    echo  $_COOKIE['data'];
  }
    ?>><br>

<label>Codice Fiscale</label>
<input type= "text" name= "codiceFiscale" placeholder="Codice Fiscale" value = 
  <?php 
  if (!empty($_COOKIE['codiceFiscale'])) {
    echo  $_COOKIE['codiceFiscale'];
  }
    ?>><br>

<label>Email</label>
<input type= "text" name= "email" placeholder="Email" value = 
  <?php 
  if (!empty( $_COOKIE['email'])) {
    echo   $_COOKIE['email'];
  }
    ?>><br>

<label>Password</label>
<input type= "password" name= "password" placeholder="Password(Inserisci almeno un numero, una maiuscola) - Min: 7 caratteri" value = 
  <?php 
  if (!empty($_COOKIE['userpass'])) {
   echo  $_COOKIE['userpass'];
  }
    ?>><br>

<label>Ripeti Password</label>
<input type= "password" name= "Re_password" placeholder="Ripeti Password" value = 
  <?php 
  if (!empty($_COOKIE['Re_password'])) {
    echo  $_COOKIE['Re_password'];
  }
    ?>><br>

  <div class ="CategoriaBox">

   <label id="lab">categoria</label>
   <p>Medico</p><input  id="Cat" type="radio" name="professione" value="1">
   <p>Infermiere</p><input id="Cat" type="radio" name="professione" value="2">
   <p>Paziente</p><input id="Cat" type="radio" name="professione" value="3">

  </div>
  
<button type="submit">Registrati</button><br>
<a class="register" href="Login.php">Hai già un account?</a>  
  


  </form>

  <?php 

   ?>

</body>
</html>
