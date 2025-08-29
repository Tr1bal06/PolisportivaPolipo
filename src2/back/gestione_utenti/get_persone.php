<?php
    include "../connessione.php";
    include '../function.php';

    header('Content-Type: application/json');

    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
                
    $permessi = ['Consigliere', 'admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }

    // Query SQL con placeholders
    $query = "SELECT U.Email , P.CF ,P.Nome , P.Cognome , T.Numero
            FROM UTENTE U JOIN PERSONA P 
                                ON U.Persona = P.CF
                            JOIN TELEFONO T 
                                ON T.Persona = P.CF ;";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }

    $persone = [];
    while ($row = $result->fetch_assoc()) {
        $persone[] = $row;
    }

    // Output
    echo json_encode($persone);


    $conn->close();
?>
