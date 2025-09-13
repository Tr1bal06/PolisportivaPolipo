<?php  

    /**
     * File: handler_assemblea.php
     * Auth: 
     */
    //require __DIR__ . '/mailer.php';
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

        $mail = [];

        //carico le mail dei partecipanti
        foreach ($cf_list as $cf) {
            
            //controllo che le email siano presenti nel database e le salvo in una variabile
            $stmt = $conn->prepare("SELECT Email
                                    FROM UTENTE
                                    WHERE Persona = ? ");
            $stmt->bind_param("s", $cf); 
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                error('../../front/convocatori/assemblea.php','Email non presente!');
            } else {
                // salvo l'email nell'array
                $row = $result->fetch_assoc();
                $mail[] = $row['Email'];
            }
            
        }

        $titolo = "Convocazione riunione: " . $Ordine;
        $contenuto = "<h2>Convocazione Riunione</h2>

                        <p>Ciao,</p>
                        <p>sei invitato a partecipare alla seguente riunione della <b>Polisportiva Polipo</b>:</p>

                        <ul>
                            <li><b>Titolo:</b> Assemblea Generale</li>
                            <li><b>Data:</b> 10 Settembre 2025</li>
                            <li><b>Ora:</b> 18:30</li>
                            <li><b>Luogo:</b> Sala Conferenze, Polisportiva Polipo</li>
                        </ul>

                        <p>La tua presenza è importante per condividere aggiornamenti, discutere le attività in corso e pianificare i prossimi impegni sportivi e organizzativi.</p>

                        <hr>

                        <small>Questa è una comunicazione automatica della Polisportiva Polipo.<br>
                        Per qualsiasi informazione puoi contattarci all’indirizzo: info@polisportivapolipo.it</small>
                        ";
        //il contenuto è da decidere come strutturarlo
        
        inviaMail($mail,$titolo,$contenuto);
        $conn->commit();
    }
    catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        error('../../front/convocatori/assemblea.php' , 'email errate / assemblea già presente');
        
    }
    success('../../front/convocatori/assemblea.php' , 'Assemblea creata con successo');
    
?>