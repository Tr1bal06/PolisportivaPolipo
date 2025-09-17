<?php


    /** 
     * File: 
     * Auth: 
     * Desc: 
    */
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
        $conn->begin_transaction();
        try{   
            $livello = htmlentities($_POST['livello']);
            $sport = htmlentities($_POST['sport']);
            $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];
            $mot = htmlentities($_POST['motivazione']);//da aggiungere nel front

            $stmt1 = $conn->prepare("SELECT NomeSport
                                    FROM ISCRIZIONE
                                    WHERE CodiceAtleta = ?");
            $stmt1->bind_param("i", $codiceAtleta);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $port = $result->fetch_all(MYSQLI_ASSOC);

            foreach($port as $value) {
                if($value['NomeSport'] == $sport) {
                    throw new Exception("Sport già praticato!",10090);
                }
            }

            $stmt = $conn->prepare("INSERT INTO RICHIESTE_ATL(Codice, Sport, TipoSport, Motivo, Stato, CodApprovante,Tipo)
            VALUES (?,?,?,?,'NonConfermato',NULL,'Iscrizione');");
            $stmt->bind_param("isss",$codiceAtleta,$sport,$livello,$mot);

            $stmt->execute();

            $conn->commit();
        }catch(Exception $e){
            $conn->rollback();
            $default = "Richiesta di iscrizione fallita!";

            $codiciGestiti = [10090];

            if (in_array($e->getCode(), $codiciGestiti, true)) {
                $default = $e->getMessage();
            }
            error('../../front/persone/utente.php' , $default);

        }
        success('../../front/persone/utente.php', 'Richiesta di iscrizione avvenuta con successo!');
    }
?>