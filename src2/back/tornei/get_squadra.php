<?php
    /** 
     * File: get_squadra.php
     * Auth: Jin
     * Desc: getter delle squadre che l'allenatore gestisce. dati--> Nome , Logo , Sport
    */
    include "../connessione.php";
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    //Comunico al sistema che riceverà una risposta JSON
    header('Content-Type: application/json');

    $permessi = ['Atleta', 'admin','Allenatore'];

    //controllo i permessi 
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error("./front/404.php","Permesso negato!");
    }

    $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];
    $stmt1 = $conn->prepare("SELECT Nome ,Logo, Sport
                             FROM SQUADRA
                             WHERE Allenatore = ?");
    $stmt1->bind_param("i", $codiceAllenatore);
    $stmt1->execute();
    $result = $stmt1->get_result();
    
    //controllo i risultati della query 
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    //output
    $squadre = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($squadre);


?>