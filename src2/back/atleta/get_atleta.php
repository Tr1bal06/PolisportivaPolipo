<?php
    include "../connessione.php";
    include '../function.php';

    header('Content-Type: application/json');

    session_start();
                
    $permessi = ['Atleta', 'admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }
    $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];

    $stmt1 = $conn->prepare("SELECT NomeSport , Tipo
                FROM ISCRIZIONE
                WHERE CodiceAtleta = ?");
            $stmt1->bind_param("i", $codiceAtleta);
            $stmt1->execute();
            $result = $stmt1->get_result();
            
    $ciao = "prova";

    echo $ciao;
    
    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }
    $port = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($port);


    $conn->close();
?>