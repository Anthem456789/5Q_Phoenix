<?php
session_start();

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . json_encode($output) . "' );</script>";
}



function checkLog(){
    if(isset($_SESSION["codiceFiscale"]) && isset($_SESSION["email"])){
        debug_to_console("bene");
    }else{
        header("Location: ../Login.php");
        exit();
    }
}


function myExecute($stmt){
    $stmt->execute();
    $result = $stmt->get_result();
    if($result==true){
        debug_to_console("good");
        //var_dump($result);
    }else{
        ob_start();
        var_dump($result);
        $output = ob_get_clean();
        echo "<script>console.log(" . json_encode($output) . ");</script>";
        //echo"good";
    }

}
