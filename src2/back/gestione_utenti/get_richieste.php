<?php
    include "../connessione.php";
    include '../function.php';

    header('Content-Type: application/json');

    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
    }

    error_log('RUOLO SESSIONE: ' . print_r($_SESSION['ruolo'], true)); 
    $permessi = [ 'user'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }
    $CF = $_SESSION['cf'];
    
    // Query SQL con placeholders
    $query = "SELECT R.Codice, R.Sport, P.Nome, P.Cognome,'-' as Livello, C.NomeCarica,R.Tipo,R.Motivo
              FROM RICHIESTE_ALL R JOIN ALLENATORE A ON R.Codice=A.Codice
                                JOIN CARICA C ON A.Codice=C.Codice 
                                JOIN NOMINA N ON C.Codice=N.CodiceCarica JOIN PERSONA P ON N.Persona=P.CF                   
             UNION                   
             SELECT R.Codice, R.Sport, P.Nome, P.Cognome, I.Tipo as Livello, C.NomeCarica,R.Tipo,R.Motivo
              FROM RICHIESTE_ATL R JOIN ATLETA A ON R.Codice=A.Codice
                                JOIN CARICA C ON A.Codice=C.Codice 
                                JOIN NOMINA N ON C.Codice=N.CodiceCarica JOIN PERSONA P ON N.Persona=P.CF
                                JOIN ISCRIZIONE I ON I.CodiceAtleta = A.Codice;           
            ";

    $result = $conn->query($query);


    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }

    $persona = [];
    while ($row = $result->fetch_assoc()) {
        $persona[] = $row;
    }

    // Output
    echo json_encode($persona);


    $conn->close();
?>