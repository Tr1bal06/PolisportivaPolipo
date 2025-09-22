<?php
    /*
    file: get_atleta.php
    desc: Recupera le informazioni di uno specifico atleta dal database.
    Auth: Alberto Magrini
    */


    include "../connessione.php";
    include '../function.php';
    
    header('Content-Type: application/json');

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
                
    $permessi = ['Atleta','Allenatore', 'admin', 'user'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }

    $permessi = ['Atleta','Allenatore', 'admin'];

    $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];
    if(!controllo($_SESSION['ruolo'], $permessi)) { 
         header('Location: ' .'../../front/persone/utente.php');
    }

    $stmt1 = $conn->prepare("SELECT NomeSport , Tipo
                FROM ISCRIZIONE
                WHERE CodiceAtleta = ?");
                
            $stmt1->bind_param("i", $codiceAtleta);
            $stmt1->execute();
            $result = $stmt1->get_result();
    
    //controllo se sono presenti richieste di eliminazione in sospeso
    $stmt2 = $conn->prepare("SELECT Sport
                            FROM RICHIESTE_ATL
                            WHERE Codice=? AND Stato='NonConfermato' AND Tipo='Eliminazione'"); 
    $stmt2->bind_param("i", $codiceAtleta);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $richiesteInSospeso = $result2->fetch_all(MYSQLI_ASSOC);
      
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    $port = $result->fetch_all(MYSQLI_ASSOC);
    $richiesteInSospeso = array_column($richiesteInSospeso,"Sport");
    
    foreach($port as $key => $value) {
        
    $port[$key]['inSospeso'] = in_array($value['NomeSport'], $richiesteInSospeso);
        
    }


    
    echo json_encode($port);


    $conn->close();
?>