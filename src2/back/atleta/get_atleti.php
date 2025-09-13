<?php
    /*
    file: get_atleti.php
    desc: Recupera le informazioni di tutti gli atleti dal database.
    Auth: Alberto Magrini
    */

    include "../connessione.php";
    include '../function.php';
    
    header('Content-Type: application/json');

    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
                
    $permessi = ['Atleta', 'admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }
    
    $result = $conn->query("SELECT  A.Codice , I.NomeSport , P.CF , P.Nome , P.Cognome , U.Email
                                    FROM ATLETA A
                                        JOIN ISCRIZIONE I ON I.CodiceAtleta = A.Codice
                                        JOIN NOMINA N ON N.CodiceCarica = A.Codice
                                        JOIN PERSONA P ON P.CF = N.Persona
                                        JOIN UTENTE U ON U.Persona = P.CF;
                ");

    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    $port = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($port);


    $conn->close();
?>