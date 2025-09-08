<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
    //controllo l'utente sia autorizzato ad eseguire le seguenti operazioni
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
            error('../../front/404.php', 'Eliminazione sport fallita!');
        }

        if($reach == 'atleta') {
            $codAtleta = $_SESSION['caricheCodici']['Atleta'];
            $stmt1 = $conn->prepare("DELETE FROM ISCRIZIONE WHERE CodiceAtleta = ? AND NomeSport = ?");
            $stmt1->bind_param("is",$codAtleta , $sport); 
            
        } else {
            $codAllenatore = $_SESSION['caricheCodici']['Allenatore'];
            $stmt1 = $conn->prepare("DELETE FROM INSEGNA WHERE CodiceAllenatore = ? AND NomeSport = ?");
            $stmt1->bind_param("is",$codAllenatore , $sport); 
            
        }
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error($path, 'Eliminazione sport fallita!');
        } else {
            success($path, 'Eliminazione sport avvenuta con successo!');
        }
            
    }
?>