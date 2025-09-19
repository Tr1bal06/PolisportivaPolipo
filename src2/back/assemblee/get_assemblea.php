<?php
    /** 
     * File: get_assemblea.php
     * Auth: Teox5
     * Desc: questo file mostra le assemblee a cui l'utente è stato invitato
    */

    include "../connessione.php";
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
   
    //comunico al sistema che riceverà una risposta
    header('Content-Type: application/json');
                
    $permessi = ['user'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error("../../front/convocatori/assemblee.php", "errore");
        exit();
    }

    $convocatore = htmlentities($_SESSION['cf']);

    // Query SQL con placeholders
        $query = "SELECT DISTINCT A.Data , A.OrdineDelGiorno , A.Oggetto , P.Nome as NomeConvocatore , P.Cognome as CognomeConvocatore
                    FROM ASSEMBLEA A
                        JOIN INTERVENTO I ON I.CodiceConvocatore  = A.CodiceConvocatore
                        JOIN PERSONA P ON P.CF = I.Persona
                    WHERE I.Persona = '$convocatore' AND CURRENT_DATE <= A.Data";

    $result = $conn->query($query);
    if (!$result) {
        echo json_encode(['error' => 'Errore nella query: ' . $conn->error]);
        http_response_code(500);
        exit();
    }

    $assemblee = []; 
    while ($row = $result->fetch_assoc()) {
        
        $assemblee[] = $row;
    }

    // Output
    echo json_encode($assemblee);

    $conn->close();
?>
