<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin','Atleta'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try{
                // Recupera il giorno selezionato
            $livello = htmlentities($_POST['livello']);
            $sport = htmlentities($_POST['sport']);
            $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];
            $mot = htmlentities($_POST['motivo']);//da aggiungere nel front
            

            $stmt1 = $conn->prepare("SELECT NomeSport
                                    FROM ISCRIZIONE
                                    WHERE CodiceAtleta = ?");
            $stmt1->bind_param("i", $codiceAtleta);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $port = $result->fetch_all(MYSQLI_ASSOC);

            foreach($port as $value) {
                if($value['NomeSport'] == $sport) {
                    error('../../front/persone/utente.php', 'Sport già praticato!');
                }
            }

            $stmt = $conn->prepare("INSERT INTO RICHIESTE_ATL(Codice, Sport, TipoSport, Motivo, Stato, CodApprovante)
             VALUES (?,?,?,?,'NonConfermato',NULL)");
            $stmt->bind_param("isss",$codiceAtleta,$sport,$livello,$mot); 
            if($stmt->execute()) {
                success('../../front/persone/utente.php', 'Richiesta di iscrizione avvenuta con successo!');
            }else {
                error('../../front/persone/utente.php', 'Richiesta di iscrizione fallita!');
            }
        }catch(Exception $e){
            error('../../front/persone/utente.php', 'Richiesta di iscrizione fallita!');
        }
        
    }
?>