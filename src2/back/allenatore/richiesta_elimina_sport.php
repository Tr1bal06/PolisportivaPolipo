<?php
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin','Allenatore','Atleta'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $path = '../../front/persone/utente.php';
        $sport =  htmlentities($_POST['sport']);
        $reach = htmlentities($_POST['source']);
        $codiciCariche = $_SESSION['caricheCodici'];
        $mot = htmlentities($_POST['motivazione']);

        
        if($reach == 'atleta') {
            $codAtleta = $_SESSION['caricheCodici']['Atleta'];
            $tipo = htmlentities($_POST['livello']);
            
            $stmt2= $conn->prepare("SELECT Codice, Sport, TipoSport
                                    FROM RICHIESTE_ATL
                                    WHERE Codice=? AND Sport=? AND TipoSport=?");
            $stmt2->bind_param("iss",$codAtleta, $sport, $tipo);
            $stmt2->execute();
            $result = $stmt2->get_result();
            
            if($result->num_rows === 0) {

            $stmt2->close();
            $stmt1 = $conn->prepare("INSERT INTO RICHIESTE_ATL(Codice, Sport, TipoSport, Motivo, Stato, CodApprovante,Tipo)
            VALUES (?,?,?,?,'NonConfermato', NULL,'Eliminazione');"); 
            $stmt1->bind_param("isss",$codAtleta, $sport, $tipo, $mot); 

            } else {
                error($path, 'Richiesta eliminazione sport già inviata!');
            }
            
        } else {
            $codAllenatore = $_SESSION['caricheCodici']['Allenatore'];

            $stmt2= $conn->prepare("SELECT Codice, Sport
                                    FROM RICHIESTE_ALL
                                    WHERE Codice=? AND Sport=?");
            $stmt2->bind_param("is",$codAllenatore, $sport);
            $stmt2->execute();
            $result = $stmt2->get_result();
            if($result->num_rows === 0) {

            $stmt2->close();
            $stmt1 = $conn->prepare ("INSERT INTO RICHIESTE_ALL (Codice, Sport, Motivo, Stato, CodApprovante,Tipo)
            VALUES (?,?,?,'NonConfermato', NULL,'Eliminazione');"); 
            $stmt1->bind_param("iss",$codAllenatore, $sport, $mot); 

            } else {
                error($path, 'Richiesta eliminazione sport già inviata!');
            }
            
        }
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error($path, 'Richiesta eliminazione sport fallita!');
        } else {
            success($path, 'Richiesta eliminazione sport avvenuta con successo!');
        }
    }
?>