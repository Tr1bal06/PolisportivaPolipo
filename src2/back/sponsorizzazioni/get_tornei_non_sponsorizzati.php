<?php
    /**
     * File: 
     * Auth: Teox5
     * Desc: 
     */
    include "../connessione.php";
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    //comunico che il risultato sarà un json
    header('Content-Type: application/json');

    //controllo che l'utente abbia i permessi corretti   
    $permessi = ['Sponsor', 'admin'];
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error("../../front/404.php", "Permesso negato");
    }

    $Sponsor = $_SESSION["caricheCodici"]['Sponsor'];
    // Query SQL con placeholders per ricavare i dati di maggior importanza, in modo sicuro, per ogni sponsor
    $stmt = $conn->prepare("SELECT E_D.CodiceTorneo , E_D.Anno, E_D.Regolamento , T.Nome AS NomeTorneo , T.Sport , P.Nome AS NomeMedico , P.Cognome As CognomeMedico
                            FROM  EDIZIONE_TORNEO E_D 
                                LEFT JOIN TORNEO T ON T.CodiceAttivita = E_D.CodiceTorneo
                                LEFT JOIN NOMINA N ON N.CodiceCarica = E_D.CodiceMedico
                                LEFT JOIN PERSONA P ON P.CF = N.Persona
                            WHERE NOT EXISTS (
                            SELECT *
                            FROM SPONSORIZZAZIONE S
                            WHERE S.CodiceTorneo = E_D.CodiceTorneo AND E_D.Anno = S.Anno    AND	S.CodiceSponsor = ?
                            ) AND E_D.Anno >= CURRENT_DATE   
                            ORDER BY E_D.Anno DESC");
    $stmt->bind_param("s", $Sponsor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit();
    }

    $sponsor = $result->fetch_all(MYSQLI_ASSOC);

    // Output 
    echo json_encode($sponsor);
    http_response_code(200);

    $stmt->close();
    $conn->close();

?>