<?php
    include "../connessione.php";
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    //Comunico al sistema che riceverà una risposta JSON
    header('Content-Type: application/json');

    $permessi = ['user', 'admin'];

    //controllo i permessi 
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error("./front/404.php","Permesso negato!");
    }

    $cfPersona = $_SESSION['cf'];
    // Query SQL con placeholders per ricavare i dati di maggior importanza, in modo sicuro, per ogni torneo
    $query = "SELECT E_D.CodiceTorneo, E_D.Anno, E_D.Regolamento, T.Nome AS NomeTorneo, T.Sport
                FROM EDIZIONE_TORNEO E_D
                JOIN TORNEO T ON T.CodiceAttivita = E_D.CodiceTorneo
                WHERE E_D.Anno >= CURRENT_DATE
                AND NOT EXISTS (
                        SELECT 1
                        FROM PARTECIPAZIONE P1
                        WHERE P1.CodiceTorneo = E_D.CodiceTorneo
                        AND P1.Anno = E_D.Anno
                        AND P1.CFPartecipante = '$cfPersona'
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