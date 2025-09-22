<?php
    /** 
     * File: 
     * Auth: 
     * Desc: 
    */
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
    $query = "SELECT U.Email , P.CF ,P.Nome , P.Cognome , T.Numero
            FROM UTENTE U JOIN PERSONA P 
                                ON U.Persona = P.CF
                            JOIN TELEFONO T 
                                ON T.Persona = P.CF 
            WHERE P.CF = '$CF'";



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
