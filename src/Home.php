<?php
session_start();

echo "Il mio nome è ". $_SESSION["nome"]. " " . $_SESSION["cognome"] . " " . $_SESSION["data_nascita"];

?>
