<?php
session_start();
function checkLog(){
    if(isset($_SESSION["codiceFiscale"]) && isset($_SESSION["email"])){
        consol.log("bene");
    }else{
        header("Location: ../Login.php");
        exit();
    }
}

