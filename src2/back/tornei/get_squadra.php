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

    $permessi = ['Atleta','admin','Allenatore'];

    
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
    $stmt1->close();

    //controllo i risultati della query 
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    //output
    $squadre = $result->fetch_all(MYSQLI_ASSOC);

    $stmt2 = $conn->prepare(" SELECT P.Nome, P.Cognome
                                    FROM Atleta as A
                                        JOIN TESSERAMENTI T ON A.Codice = T.Atleta
                                        JOIN NOMINA N ON N.CodiceCarica = A.Codice
                                        JOIN PERSONA P ON P.CF = N.Persona
                                    WHERE T.NomeSquadra = ? ");
                                                               
    foreach($squadre as $index => $squadra) {
        
        $stmt2->bind_param("s", $squadra['Nome']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if (!$result2) {
            echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
            http_response_code(500);
            exit;
        }

        $atleti = $result2->fetch_all(MYSQLI_ASSOC);
        $squadre[$index]['Atleti'] = $atleti;
    }
    
    echo json_encode($squadre);


?>