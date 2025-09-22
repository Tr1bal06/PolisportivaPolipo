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
    
    //controllo l'utente sia autorizzato ad eseguire le seguenti operazioni
    $permessi = ['admin','Allenatore'];
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }
    //controllo di aver ricevuto una richiesta post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //inizio la transazione
        $conn->begin_transaction();
        try{
                // Recupera il giorno selezionato
            $sport = htmlentities($_POST['sport']);
            $codiciCariche = $_SESSION['caricheCodici'];
            $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];
            $mot = htmlentities($_POST['motivazione']);//da aggiungere nel front
            
            $stmt1 = $conn->prepare("SELECT NomeSport
                                    FROM INSEGNA
                                    WHERE CodiceAllenatore = ?");                                   
            $stmt1->bind_param("i", $codiceAllenatore);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $port = $result->fetch_all(MYSQLI_ASSOC); 

            foreach($port as $value) {
                if($value['NomeSport'] == $sport) {
                    throw new Exception("Sport già presente!",11010); 
                    error('../../front/persone/utente.php', 'Sport già presente!');
                }
            }   

            $stmt = $conn->prepare("INSERT INTO RICHIESTE_ALL(Codice, Sport, Motivo, Stato, CodApprovante,Tipo)
            VALUES (?,?,?,'NonConfermato',NULL,'Insegnamento');");
            $stmt->bind_param("iss",$codiceAllenatore,$sport, $mot); 
            $stmt->execute();
        
            $conn->commit();
        }catch(Exception $e){
            $conn->rollback();
            $default = "Registrazione della richiesta fallita!";

            $codiciGestiti = [11010];

            if (in_array($e->getCode(), $codiciGestiti, true)) {
                $default = $e->getMessage();
            }
            error('../../front/persone/utente.php' , $default);

        }
        success('../../front/persone/utente.php', 'Registrazione della richiesta completata');
        
    }
?>