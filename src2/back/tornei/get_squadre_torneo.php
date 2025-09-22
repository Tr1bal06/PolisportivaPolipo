<?php
    /** 
     * File: get_squadra.php
     * Auth: Jin
     * Desc: getter delle squadre che l'allenatore gestisce e degli atleti . dati--> Nome , Logo , Sport , [atleti]
    */
    include "../connessione.php"; 
    include '../function.php'; 
    if (session_status() == PHP_SESSION_NONE) { 
        // Avvia la sessione 
        session_start(); 
    } 
     
    
    //session_start();
    //Comunico al sistema che riceverà una risposta JSON
    header('Content-Type: application/json');

    $permessi = ['admin','Allenatore'];

    //controllo i permessi 
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error("./front/404.php","Permesso negato!");
    }

    $codice = intval($_POST['EdizioneTorneo']);
    $anno = htmlspecialchars($_POST['AnnoTorneo']);
    

    $stmt1 = $conn->prepare("SELECT *
                             FROM PARTITA_TORNEO
                             WHERE EdizioneTorneo = ? AND AnnoTorneo = ?
                             ORDER BY Round , Gruppo");
    $stmt1->bind_param("is", $codice,$anno );
    $stmt1->execute();
    $result = $stmt1->get_result();
    $partite = $result->fetch_all(MYSQLI_ASSOC);
    $stmt1->close();

    //controllo i risultati della query 
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    
    echo json_encode($partite);


?>