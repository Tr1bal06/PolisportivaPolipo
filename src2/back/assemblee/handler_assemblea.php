<?php  
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
    
    //controllo che l'utente abbia i giusti permessi
    $permessi = ['Consigliere', 'Socio', 'Allenatore', 'admin'];
     if(!controllo($_SESSION['ruolo'], $permessi)) {
        error("../../front/404.php", "Permesso negato");
        exit();
    }

    //acquisisco i dati 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $codConv = htmlentities($_SESSION['conv']);
        $Data = htmlentities($_POST['data']);
        $Ordine = htmlentities($_POST['ordine_giorno']);
        $Oggetto = htmlentities($_POST['oggetto']);
        $cf_list = json_decode($_POST['codici_fiscali'], true); //trasformo l'input in array associativo
    }
    //Inizio la transazione
    $conn->begin_transaction();
    try{
        
        //inserisco il record nella tabella Assemblea
        $stmt = $conn->prepare("INSERT INTO ASSEMBLEA (CodiceConvocatore, Data, OrdineDelGiorno, Oggetto)
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $codConv, $Data, $Ordine, $Oggetto);
        $stmt->execute();
        $stmt->close();
        
        //inserisco i record nella tabella intervento
        foreach($cf_list as $cf){
        $stmt = $conn->prepare("INSERT INTO INTERVENTO (CodiceConvocatore, DataAssemblea, Persona)
                                VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $codConv, $Data, $cf);
        $stmt->execute();
        $stmt->close();
        }

        //ciclo per tutte le email
        foreach ($cf_list as $cf) {
            
            //controllo che le email siano presenti nel database e le salvo in una variabile
            $stmt = $conn->prepare("SELECT Email
                                    FROM UTENTE
                                    WHERE Persona = ? ");
            $stmt->bind_param("s", $cf); 
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows === 0) {
                error('../../front/convocatori/assemblea.php','Email non presente!');
                
            }
            $mail = $result->fetch_assoc();


            //compongo ed invio la mail
            $to = $mail['Email'];
            $subject = 'Invito ad un assemblea';
            $message = 'Ciao, sei stato invitato alla seguente assemblea: '.$Oggetto.' in data '.$Data;
            $headers = "From: polipopolisportiva5id@altervista.org\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            //Controllo se la mail sia usabile
            if (!mail($to, $subject, $message, $headers)){
                
                error('../../front/convocatori/assemblea.php','Email non valida!');
            }
        }
        $conn->commit();
    }
    catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        error('../../front/convocatori/assemblea.php' , 'email errate / assemblea già presente');
        
    }
    success('../../front/convocatori/assemblea.php' , 'Assemblea creata con successo');
    
    //made by Tha_Losco
?>