<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin','Allenatore','Atleta'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $path =  htmlentities($_POST['path']);
        $sport =  htmlentities($_POST['sport']);
        $reach = htmlentities($_POST['source']);
        $codiciCariche = $_SESSION['caricheCodici'];
      

        if(empty($path)){
            error('../../front/404.php', 'Richiesta eliminazione sport fallita!');
        }

        if($reach == 'atleta') {
            $codAtleta = $_SESSION['caricheCodici']['Atleta'];
            $stmt1 = $conn->prepare("INSERT RICHIESTE_ATL(Codice, Sport, TipoSport, Motivo, Stato, CodApprovante)
            VALUES (?,?,?,?,'NonConfermato', NULL)"); 
            $stmt1->bind_param("isss",$codAtleta, $sport, $tipo, $mot); 
            
        } else {
            $codAllenatore = $_SESSION['caricheCodici']['Allenatore'];
            $stmt1 = $conn->prepare ("INSERT INTO RICHIESTE_ALL (Codice, Sport, Motivo, Stato, CodApprovante)
            VALUES (?,?,?,'NonConfermato', NULL)"); 
            $stmt1->bind_param("iss",$codAllenatore, $sport, $mot); 
            
        }
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error($path, 'Richiesta eliminazione sport fallita!');
        } else {
            success($path, 'Richiesta eliminazione sport avvenuta con successo!');
        }
    }
?>