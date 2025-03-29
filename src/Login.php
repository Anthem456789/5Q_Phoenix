
<!DOCTYPE HTML>
<html>

<head>
    <title> Login </title>
    <link rel="stylesheet" type="text/css" href="BackBone_Phoenix/css/GraficaForm.css?<?php echo time(); ?>">
</head>

<body>
    <div class="container" id="container">
    <form action="BackBone_Phoenix/Generale/CheckLogin.php" method="POST">
        <h2>Bentornato</h2>
        <!-- Get è una richiesta o un invio dati fatta al server web o al pc stesso, dove i parametri sono inviati tramite URL  -->
        <?php if(isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <?php
 if(isset($_GET['success'])) { ?>
        <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?>

        <label>Email</label>
        <input type="text" name="email" placeholder="Email" value=<?php 
  if (!empty($_COOKIE['user'])) {
    echo $_COOKIE['user'];
  }
    ?>><br>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password" value=<?php 
  if (!empty($_COOKIE['userpass'])) {
    echo $_COOKIE['userpass'];
  }
    ?>><br>

        <a class="register" href="SignUP.php">Registrati</a>

        <button type="submit">Login</button>

    </form>

    </div>

</body>

</html>
