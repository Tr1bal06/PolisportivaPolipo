<?php
    include "../connessione.php";
    include '../function.php';
    session_start();
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
            
    //controllo i risultati della query 
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    //output
    $port = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($port);

    //chiudo stmt e conn
    $stmt1->close();
    $conn->close();
?>
