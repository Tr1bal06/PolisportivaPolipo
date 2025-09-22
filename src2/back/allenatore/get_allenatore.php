<?php
    /** 
     * File: 
     * Auth: 
     * Desc: 
    */
    include "../connessione.php";
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
    //Preparo il sistema a ricevere una risposta di tipo JSON
    header('Content-Type: application/json');

    //controllo l'utente sia autorizzato ad eseguire le seguenti operazioni
    $permessi = ['Allenatore', 'admin'];
    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }

    $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];

    //effettuo una query preparata per evitare attacchi di tipo SQL injection
    $stmt1 = $conn->prepare("SELECT NomeSport
                                    FROM INSEGNA
                                    WHERE CodiceAllenatore = ?");
            $stmt1->bind_param("i", $codiceAllenatore);
            $stmt1->execute();
            $result = $stmt1->get_result();

    //controllo se sono presenti richieste di eliminazione in sospeso
    $stmt2 = $conn->prepare("SELECT Sport
                            FROM RICHIESTE_ALL
                            WHERE Codice=? AND Stato='NonConfermato' AND Tipo='Eliminazione'"); 
    $stmt2->bind_param("i", $codiceAllenatore);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $richiesteInSospeso = $result2->fetch_all(MYSQLI_ASSOC);
    
    //controllo i risultati della query 
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    //output
    $port = $result->fetch_all(MYSQLI_ASSOC);
    $richiesteInSospeso = array_column($richiesteInSospeso,"Sport");
    
    foreach($port as $key => $value) {
         $port[$key]['inSospeso'] = in_array($value['NomeSport'], $richiesteInSospeso);    
    }

    echo json_encode($port);

    //chiudo stmt e conn
    $stmt1->close();
    $conn->close();
?>
