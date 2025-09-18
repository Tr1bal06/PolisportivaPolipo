<?php
    include "../connessione.php";
    
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    //Comunico al sistema che riceverà una risposta JSON
    header('Content-Type: application/json');

    $permessi = ['user', 'admin'];


    ob_start();
    include "get_squadra.php"; // esegue il PHP e cattura l’output
    $json = ob_get_clean();


    // Decodifica il JSON in array associativo PHP
    $squadre = json_decode($json, true);


    $sport = array_map(function($squadra) {
        return $squadra['Sport'];
    }, $squadre);

    
    
    $sport = array_unique($sport);
    
    //controllo i permessi 
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error("./front/404.php","Permesso negato!");
    }

    $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];

    // Query SQL con placeholders per ricavare i dati di maggior importanza, in modo sicuro, per ogni torneo 
    // query modificata per includere solo i tornei degli sport delle squadre dell'allenatore esclusi quelli a cui si partecipa di già
    $query = "SELECT E_D.CodiceTorneo, E_D.Anno, E_D.Regolamento, T.Nome AS NomeTorneo, T.Sport
                FROM EDIZIONE_TORNEO E_D
                JOIN TORNEO T ON T.CodiceAttivita = E_D.CodiceTorneo
                WHERE E_D.Anno >= CURRENT_DATE AND T.Sport IN ('" . implode("','", $sport) . "') 
                AND NOT EXISTS (
                    
                    SELECT E_D.CodiceTorneo, E_D.Anno, E_D.Regolamento, T.Nome AS NomeTorneo, T.Sport
                    FROM ISCRIZIONI_TORNEO I 
                        JOIN SQUADRA S  ON S.Nome = I.NomeSquadra
                        JOIN EDIZIONE_TORNEO E_D ON E_D.CodiceTorneo = I.EdizioneTorneo AND E_D.Anno = I.AnnoTorneo
                        JOIN TORNEO T ON T.CodiceAttivita = E_D.CodiceTorneo 
                        WHERE S.Allenatore = $codiceAllenatore AND I.AnnoTorneo >= CURRENT_DATE
                )
                ORDER BY E_D.Anno DESC;";
                
    $result = $conn->query($query);

    if ($result === false) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }

    


    $tornei = $result->fetch_all(MYSQLI_ASSOC);

    // Costruisco una mappa dei tornei per codice + anno
    $torneiMappa = [];
    foreach ($tornei as $numero => $torneo) {
        $chiave = $torneo['CodiceTorneo'] . '-' . $torneo['Anno'];
        $tornei[$numero]['Sponsor'] = []; // Inizializzo sponsor vuoti
        $torneiMappa[$chiave] = &$tornei[$numero];
    }
    
    // Query per prendere tutti gli sponsor di tutti i tornei in una volta sola
    $sql = "SELECT S.CodiceTorneo, S.Anno, SP.Nome
            FROM SPONSORIZZAZIONE S
            JOIN SPONSOR SP ON SP.Codice = S.CodiceSponsor";
    
    $risultato = $conn->query($sql);
    
    if ($risultato) {
        $sponsorTornei = $risultato->fetch_all(MYSQLI_ASSOC);
    
        foreach ($sponsorTornei as $sponsor) {
            $chiave = $sponsor['CodiceTorneo'] . '-' . $sponsor['Anno'];
            if (isset($torneiMappa[$chiave])) {
                $torneiMappa[$chiave]['Sponsor'][] = ['Nome' => $sponsor['Nome']];
            }
        }
    } else {
        echo json_encode(['error' => 'Errore nella query sponsor: ' . $conn->error]);
        http_response_code(500);
        exit;
    }

    

    
    // Output
    echo json_encode($tornei);
    http_response_code(200);

  
    $conn->close();
    //made by Tha_Losco
?>