<?php
session_start();

if (!isset($_SESSION["codiceFiscale"])) {
    die("Utente non loggato.");
}

$codiceFiscale = $_SESSION["codiceFiscale"];

$host = "localhost";
$username = "root";
$password = "";
$dbname = "5q_ombrello_phoenix";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}