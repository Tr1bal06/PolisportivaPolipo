<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin', 'segreteria'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sport =  htmlentities($_POST['sport']);
        $reach = htmlentities($_POST['source']);
        $codiciCariche = $_SESSION['caricheCodici'];
        
        if(empty($path)){
            error('../../front/404.php', 'Inserimento sport fallito!');
        }
        if($reach == 'atleta') {
            $codAtleta = $_SESSION['caricheCodici']['Atleta'];
            $stmt1 = $conn->prepare("INSERT INTO ISCRIZIONE(CodiceAtleta, NomeSport) VALUES (?, ?)");
            $stmt1->bind_param("is",$codAtleta , $sport); 
        } else {
            $codAllenatore = $_SESSION['caricheCodici']['Allenatore'];
            $stmt1 = $conn->prepare("INSERT INTO INSEGNA(CodiceAllenatore, NomeSport) VALUES (?,?)");
            $stmt1->bind_param("is",$codAllenatore , $sport); 
        }
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error($path, 'Inserimento sport fallito!');
        } else {
            success($path, 'Inserimento sport avvenuto con successo!');
        }     
    }
?>