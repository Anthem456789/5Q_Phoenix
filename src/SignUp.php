
<!DOCTYPE HTML>  
<html>
  <head> 
   <title> REGISTRAZIONE </title>
   <link rel="stylesheet" type="text/css" href="Grafica/style.css?<?php echo time(); ?>">
  </head>
 
<body>
 

<form action="BackBone_Phoenix/CheckSignUp.php" method="POST">
<h2>Benvenuto</h2>
<?php if(isset($_GET['error'])) { ?>
  <p class="error"><?php echo $_GET['error']; ?></p>
<?php } ?>

<label>Nome</label>
  <input type= "text" name= "nome" placeholder="Nome" value = 
  <?php 
  if (!empty($_SESSION['nome'])) {
    echo $_SESSION['nome'];
  }
    ?>><br>

<label>Cognome</label>
<input type= "text" name= "cognome" placeholder="Cognome" value = 
  <?php 
  if (!empty($_SESSION["cognome"])) {
    echo $_SESSION["cognome"];
  }
    ?>><br>

<label>Data Di Nascita</label>
<input type= "text" name= "data_nascita" placeholder="Data di nascita" value = 
  <?php 
  if (!empty($_SESSION["data_nascita"])) {
    echo $_SESSION["data_nascita"];
  }
    ?>><br>

<label>Codice Fiscale</label>
<input type= "text" name= "codiceFiscale" placeholder="Codice Fiscale" value = 
  <?php 
  if (!empty($_SESSION["codiceFiscale"])) {
    echo $_SESSION["codiceFiscale"];
  }
    ?>><br>

<label>Email</label>
<input type= "text" name= "email" placeholder="Email" value = 
  <?php 
  if (!empty( $_SESSION["em"])) {
    echo  $_SESSION["em"];
  }
    ?>><br>

<label>Password</label>
<input type= "password" name= "password" placeholder="Password(Inserisci almeno un numero, una maiuscola) - Min: 7 caratteri" value = 
  <?php 
  if (!empty($_SESSION["pass"])) {
   echo $_SESSION["pass"];
  }
    ?>><br>

<label>Ripeti Password</label>
<input type= "password" name= "Re_password" placeholder="Ripeti Password" value = 
  <?php 
  if (!empty($_SESSION["Re_password"])) {
    echo $_SESSION["Re_password"];
  }
    ?>><br>

  <div class ="CategoriaBox">

   <label id="lab">categoria</label>
   <input  id="Cat" type="radio" name="professione" value="1"><p>Medico</p>
   <input id="Cat" type="radio" name="professione" value="2"><p>Infermiere</p>
   <input id="Cat" type="radio" name="professione" value="3"><p>Paziente</p>

  </div>

<a class="register" href="Login.php">Hai già un account?</a>  
  
<button type="submit">Registrati</button>

  </form>

  <?php 

   ?>

</body>
</html>
