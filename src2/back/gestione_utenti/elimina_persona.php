<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
       error('../../front/404.php', 'Accesso negato');
    }

    

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $CF = htmlentities($_POST['cf']);
        
        if(empty($CF)){
            error('../../front/persone/persone.php', 'errore nella eliminazione del account');
        }

        if($_SESSION['cf'] == $CF) {
            error('../../front/persone/persone.php', 'Non ti puoi autocancellare!');
        }

        $conn->begin_transaction();//Rendo atomico le query 

        try {
            //primo insert in PERSONA
            
            $stmt5 = $conn->prepare("SELECT Autenticazione
                                     FROM NOMINA 
                                     WHERE Persona = ?");
            $stmt5->bind_param("s",$CF);
            $stmt5->execute();
            $result = $stmt5->get_result();
            $atti = $result->fetch_all(MYSQLI_ASSOC);
            
            foreach($atti as $atto) {

                $stmt4 = $conn->prepare("DELETE FROM NOMINA WHERE Persona = ? AND Autenticazione = ?");
                $stmt4->bind_param("ss",$CF,$atto['Autenticazione']); 
                $stmt4->execute();

                $stmt6 = $conn->prepare("DELETE FROM ATTO_NOMINA WHERE NumProtocollo = ?");
                $stmt6->bind_param("s",$atto['Autenticazione']); 
                $stmt6->execute();
               
                $stmt7 = $conn->prepare("DELETE FROM ATTO WHERE NumProtocollo = ?");
                $stmt7->bind_param("s",$atto['Autenticazione']); 
                $stmt7->execute();
            }
            
            $stmt9 = $conn->prepare("DELETE FROM INTERVENTO WHERE Persona = ?");
            $stmt9->bind_param("s",$CF ); 
            $stmt9->execute();
            

            //secondp insert in UTENTE
            $stmt2 = $conn->prepare("DELETE FROM UTENTE WHERE Persona = ?");
            $stmt2->bind_param("s",$CF); 
            $stmt2->execute();
            
            //terza insert in UTENTE
            $stmt3 = $conn->prepare("DELETE FROM TELEFONO WHERE Persona = ?");
            $stmt3->bind_param("s",$CF); 
            $stmt3->execute();
            
            $stmt1 = $conn->prepare("DELETE FROM PERSONA WHERE CF = ?");
            $stmt1->bind_param("s",$CF); 
            $stmt1->execute();
            
            $conn->commit();
    
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();

            error('../../front/persone/persone.php', 'Eliminazione fallita!');
        }
        
        success('../../front/persone/persone.php', 'Eliminazione avvenuta con successo!');
    }
    else {
        error('../../front/404.php', 'Metodo errato');
    }

?>