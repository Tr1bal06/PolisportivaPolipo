<?php

    /*
        file: rifiuta_richiesta.php
        desc: Rifiuta la richiesta di Inserimento o Eliminazione di un Atleta o Allenatore
        auth: Alberto Magrini
    */

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
        $path = '../../front/persone/persone.php';
        $cod = htmlspecialchars($_POST['codice']);
        
        $tab = $reach == 'Atleta' ? 'RICHIESTE_ATL' : 'RICHIESTE_ALL';
        $sql = "DELETE FROM $tab WHERE Codice = ? AND Sport=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $cod, $sport);
        $stmt->execute();
        
        if($stmt->affected_rows === 0) {
                error($path, 'Errore nel cambiare stato della richiesta!');
        } else {
                success($path, 'Richiesta rifiutata con successo!');
        }
    }

?>