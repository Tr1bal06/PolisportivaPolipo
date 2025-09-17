<?php
    /** 
     * File: 
     * Auth: 
     * Desc: 
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

    if(!controllo($_SESSION['ruolo'], $permessi)) { // continua a buttarmi fuori 
        error("../../front/convocatori/assemblee.php", "errore");
        exit();
    }

    $covocatore = $_SESSION['cf'];
    $oggi = date('Y-m-d');

    // Query SQL con placeholders
        $query = "SELECT A.Data , A.OrdineDelGiorno , A.Oggetto , P.Nome as NomeConvocatore , P.Cognome as CognomeConvocatore
                    FROM ASSEMBLEA A
                        JOIN INTERVENTO I ON I.CodiceConvocatore  = A.CodiceConvocatore
                        JOIN PERSONA P ON P.CF = I.Persona
                    WHERE I.Persona = '$covocatore' AND CURRENT_DATE <= A.Data";

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
