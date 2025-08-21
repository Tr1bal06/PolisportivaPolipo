<?php


session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SESSION['log'] ) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli("localhost", "uda5idpolisportiva", "", "my_uda5idpolisportiva");	
    $_SESSION['accesso_consentito']=false;
    
    if(!$conn)
    {
    	die("Connessione non riuscita" . $conn->connect_error);
    }
} else {
    header("location: ../front/404.php");
    exit();
}
?>