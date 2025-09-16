<?php  

    /**
     * File: handler_assemblea.php
     * Auth: Jin
     * Desc: questo file ha il compito di creare l'assemblea e inviare le mail 
     */
    include '../connessione.php';
    include '../function.php';
    include '../../function_mailer.php';
    // Avvia la sessione
    if (session_status() == PHP_SESSION_NONE) {     
        session_start();
    }
    
    //controllo che l'utente abbia i giusti permessi
    $permessi = ['Consigliere', 'Socio', 'Allenatore', 'admin'];
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error("../../front/404.php", "Permesso negato");
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
        $stmt1 = $conn->prepare("INSERT INTO ASSEMBLEA (CodiceConvocatore, Data, OrdineDelGiorno, Oggetto)
                                VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("isss", $codConv, $Data, $Ordine, $Oggetto);
        $stmt1->execute();

        $stmt2 = $conn->prepare("INSERT INTO INTERVENTO (CodiceConvocatore, DataAssemblea, Persona)
                                    VALUES (?, ?, ?)");
        //inserisco i record nella tabella intervento
        foreach($cf_list as $cf){
            
            $stmt2->bind_param("iss", $codConv, $Data, $cf);
            $stmt2->execute();

        }
        $mail = [];

        $stmt3 = $conn->prepare("SELECT Email
                                    FROM UTENTE
                                    WHERE Persona = ? ");
        //carico le mail dei partecipanti
        foreach ($cf_list as $cf) {
            
            //controllo che le email siano presenti nel database e le salvo in una variabile
            
            $stmt3->bind_param("s", $cf); 
            $stmt3->execute();
            $result = $stmt3->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("Email non presente!",10070);    
            } else {
                // salvo l'email nell'array
                $row = $result->fetch_assoc();
                $mail[] = $row['Email'];
            }
            
        }
        $titolo = "Convocazione riunione: ";
        $contenuto = "<html>
                        <head>
                     <meta charset='UTF-8'>
                    <title>$Ordine</title>
                    </head><h2>Convocazione Riunione</h2>

                        <p>Ciao, ". $_SESSION['nome'] ." ". $_SESSION['cognome']. ";</p>
                        <p>sei invitato a partecipare alla seguente riunione della <b>Polisportiva Polipo</b>:</p>

                        <ul>
                            <li><b>Titolo:</b> $Oggetto</li>
                            <li><b>Data:</b> $Data</li>
                            <li><b>Ora:</b> 18:30</li>
                            <li><b>Luogo:</b> Sala Conferenze, Polisportiva Polipo</li>
                        </ul>

                        <p>La tua presenza è importante per condividere aggiornamenti, discutere le attività in corso e pianificare i prossimi impegni sportivi e organizzativi.</p>

                        <hr>

                        <small>Questa è una comunicazione automatica della Polisportiva Polipo.<br>
                        Per qualsiasi informazione puoi contattarci all’indirizzo: info@polisportivapolipo.it</small>
                        ";
        inviaMail($mail,$titolo,$contenuto);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $default = "email errate / assemblea già presente";

        $codiciGestiti = [10070];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }
        error('../../front/convocatori/assemblea.php' , $default);
        
    }
    success('../../front/convocatori/assemblea.php' , 'Assemblea creata con successo');
    
?>