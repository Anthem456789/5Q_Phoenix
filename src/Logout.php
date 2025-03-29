<!-- file in cui ci sarà come logout funzionerà -->
<?php
 session_start();

 session_unset();
 session_destroy();

 header("Location: Login.php");
 ?>