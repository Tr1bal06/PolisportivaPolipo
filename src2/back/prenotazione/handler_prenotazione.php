<?php
    
    include '../connessione.php';
    include '../function.php';
    require '../../vendor/autoload.php';  
    session_start();

    use Google\Client;
    use Google\Service\Drive;

    $permessi = ['Atleta', 'Socio', 'Allenatore','admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Salvo le variabili che mi interessano
        $ruolo = $_SESSION['ruolo'];// Per il controllo se si neccessità di richiesta
        $cod_prenotante = $_SESSION['pren']; // prenotante
        $data = htmlentities($_POST['data']);
        $ora_inizio = htmlentities($_POST['oraInizio']);
        $ora_fine = htmlentities($_POST['oraFine']);
        $data_inizio = $data . ' ' . $ora_inizio. ':00';
        $data_fine = $data . ' ' . $ora_fine. ':00';
        $data_prenotazione = date('Y-m-d H:i:s', time());
        $campo = htmlentities($_POST['campo']);
        $attivita = htmlentities($_POST['attivita']);
        
    }
    else {
       error('../../front/404.php', 'Metodo errato');
    }

    $stmt0 = $conn->prepare("SELECT ID
                             FROM CAMPO
                             WHERE NomeCampo = ?");
    $stmt0->bind_param("s",$campo); 
    $stmt0->execute();
    $result = $stmt0->get_result();
    $row = $result->fetch_assoc();
    $idCampo = $row['ID']; 
    
    $stmt11 = $conn->prepare("SELECT 1 
                              FROM PRENOTAZIONE 
                              WHERE IDCampo = ? 
                              AND NOT (? >= DataTimeFine OR ? <= DataTimeInizio)");
    $stmt11->bind_param("iss", $idCampo, $data_inizio, $data_fine);
    $stmt11->execute();
    $result = $stmt11->get_result();
    if ($result->num_rows > 0) {
        error('../../front/prenotanti/prenotazione_form.php' , 'Campo già prenotato!');
    }

    $check = false;
    $allenatore = ['Allenatore'];
    $atleta = ['Atleta'];
    if(!controllo($_SESSION['ruolo'], $allenatore)) {
      if(controllo($_SESSION['ruolo'], $atleta)) { 
        $check = true;
      }
    }

    $conn->begin_transaction();//Rendo atomico le query

    try {
        if(strtoupper($attivita) =='TORNEO') {

            $nomeTorneo = htmlentities($_POST['nomeTorneo']);
            //controllo se il torneo esiste già
            $stmt1 = $conn->prepare("SELECT CodiceAttivita 
                                     FROM TORNEO 
                                     WHERE LOWER(Nome) = LOWER(?)");
            $stmt1->bind_param("s", $nomeTorneo);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $torneo = $result->fetch_assoc();


            $anno = $data;

            
            if (!(isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == UPLOAD_ERR_OK)) {
                error('../../front/prenotanti/prenotazione_form.php' , 'File non presente!');
            }            
            // Controlla che il file sia effettivamente un PDF
            $fileType = mime_content_type($_FILES['file_pdf']['tmp_name']);
            if ($fileType != 'application/pdf') {
                error('../../front/prenotanti/prenotazione_form.php' , 'Formato sbagliato!');
            }                
            // Configura il client Google
            $client = new Client();
            $client->setAuthConfig('../credenziali.json');
            $client->addScope(Drive::DRIVE_FILE);        

            // Inizializza il servizio Drive
            $service = new Drive($client);
            $fileTmpPath = $_FILES['file_pdf']['tmp_name'];
            $originalFileName = $_FILES['file_pdf']['name'];
        
            // Crea i metadati per il file
            $fileMetadata = new Drive\DriveFile(array(
                'name' => $originalFileName,
                'parents' => array('147w5dFleAqbQrONHsDbXJicIhmAC0NR_') 
            ));
            
            // Carica il file
            $content = file_get_contents($fileTmpPath);
            $file = $service->files->create($fileMetadata, array(
               'data' => $content,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',                        
                'fields' => 'id'
            ));
            
            // Crea il link pubblico al file
            $fileId = $file->id;
        
            // Rendi il file visibile a chiunque con il link
            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader'
            ]);
            $service->permissions->create($fileId, $permission);
            
            $path = "https://drive.google.com/file/d/$fileId/view";

      

            $giorniEnum = ['Domenica','Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato'];
            $numeroGiorno = date('w', strtotime($data_prenotazione));
            $giornoDisponibilita = $giorniEnum[$numeroGiorno]; // es: "Sabato"ciao

            

            // Step 2: Query SQL per trovare un medico disponibile quel giorno
            $stmt12 = $conn->prepare("SELECT CodiceMedico 
                                      FROM DISPONIBILITA 
                                      WHERE GiornoSettimanale = ?");
            $stmt12->bind_param("s", $giornoDisponibilita);
            $stmt12->execute();
            $result = $stmt12->get_result();
            $medici_disponibili = $result->fetch_all(MYSQLI_ASSOC);
            
            if(empty($medici_disponibili)) {
                error('../../front/prenotanti/prenotazione_form.php','Torneo non prenotabile nessun medico disponibile!');
            }

            $stmt13 = $conn->prepare("SELECT DISTINCT ET.CodiceMedico 
                                          FROM PRENOTAZIONE P JOIN EDIZIONE_TORNEO ET ON P.Attivita = ET.CodiceTorneo
                                          WHERE P.DataTimeInizio >= ?");
            $stmt13->bind_param("s", $data_inizio);
            $stmt13->execute();
            $result = $stmt13->get_result();
            $medici_occupati = $result->fetch_all(MYSQLI_ASSOC);

            $codiceMedico = null;

            foreach ($medici_disponibili as $medico_disp) {
                $trovato = false;
                foreach ($medici_occupati as $medico_occ) {
                    if ($medico_disp['CodiceMedico'] == $medico_occ['CodiceMedico']) {
                        $trovato = true;
                        break;
                    }
                }
                if (!$trovato) {
                    $codiceMedico = $medico_disp['CodiceMedico'];
                    break; // esci appena trovi il primo libero
                }
            }

          

            if(is_null($codiceMedico)) {
                error('../../front/prenotanti/prenotazione_form.php' , 'Nessun Medico disponibile!');
            }
            
            if ($torneo) {
                
                $codiceAttivita = $torneo['CodiceAttivita'];
                

                $stmt = $conn->prepare("INSERT INTO EDIZIONE_TORNEO (CodiceTorneo,Anno,Regolamento,CodiceMedico) VALUES (?, ? , ?, ?)");
                $stmt->bind_param("issi", $codiceAttivita, $anno, $path , $codiceMedico);
                $stmt->execute();
            } else {
                
                $sport =  htmlentities($_POST['sport']);

                $stmt = $conn->prepare("INSERT INTO TIPO_ATTIVITA (TIPO_ATTIVITA) VALUES (?)");
                $stmt->bind_param("s", $attivita);
                $stmt->execute();
                $codiceAttivita = $conn->insert_id;
           

                $stmt5 = $conn->prepare("INSERT INTO TORNEO  VALUES (?, ? , ?)");
                $stmt5->bind_param("iss", $codiceAttivita, $nomeTorneo, $sport );
                $stmt5->execute();

                $stmt6 = $conn->prepare("INSERT INTO EDIZIONE_TORNEO (CodiceTorneo,Anno,Regolamento,CodiceMedico) VALUES (?, ? , ?, ?)");
                $stmt6->bind_param("issi", $codiceAttivita, $anno, $path , $codiceMedico);
                $stmt6->execute();
            }
            $data_inizio  = $data . ' 8:00:00';
            $data_fine = $data . ' 22:00:00';

            $stmt14 = $conn->prepare("SELECT *
                          FROM PRENOTAZIONE 
                          WHERE NOT (DataTimeFine <= ? OR DataTimeInizio >= ?) AND IDCampo = ?");
            $stmt14->bind_param("ssi", $data_inizio, $data_fine ,$idCampo );
            $stmt14->execute();
            $result = $stmt14->get_result();

            if ($result->num_rows > 0) {
                error('../../front/prenotanti/prenotazione_form.php',"Torneo non prenotabile: giornata intera non disponibile!");
            }

        }else {
            $stmt = $conn->prepare("INSERT INTO TIPO_ATTIVITA (TIPO_ATTIVITA) VALUES (?)");
            $stmt->bind_param("s", $attivita);
            $stmt->execute();
            $codiceAttivita = $conn->insert_id;
           
            // 2. Switch-case per attività specifiche
            switch (strtoupper($attivita)) {

                case "ALLENAMENTO":

                    $tipo = htmlentities($_POST['tipo']);

                    $stmt = $conn->prepare("INSERT INTO ALLENAMENTO (CodiceAttivita, Tipo) VALUES (?, ?)");
                    $stmt->bind_param("is", $codiceAttivita, $tipo);
                    
                    $stmt->execute();
                    break;

                case "PARTITA UFFICIALE":

                    $arbitro = htmlentities($_POST['arbitro']);

                    $stmt = $conn->prepare("INSERT INTO PARTITA_UFFICIALE (CodiceAttivita, Arbitro) VALUES (?, ?)");
                    $stmt->bind_param("is", $codiceAttivita, $arbitro);
                    $stmt->execute();
                    break;

                case "EVENTO SPECIALE":

                    $causale = htmlentities($_POST['causaleEvento']);

                    $stmt = $conn->prepare("INSERT INTO EVENTO_SPECIALE (CodiceAttivita, Causale) VALUES (?, ?)");
                    $stmt->bind_param("is", $codiceAttivita, $causale);
                    $stmt->execute();
                    break;

                case "RIUNIONE TECNICA":

                    $causale = htmlentities($_POST['causaleRiunione']);

                    $stmt = $conn->prepare("INSERT INTO RIUNIONE_TECNICA (CodiceAttivita, Causale) VALUES (?, ?)");
                    $stmt->bind_param("is", $codiceAttivita, $causale);
                    $stmt->execute();
                    break;

                default:
                    error('../../front/prenotanti/prenotazione_form.php' , 'Attività non valida!');
            }
        }
        if($check) {

            $codiciCariche = $_SESSION['caricheCodici'];
            $codAtleta = $_SESSION['caricheCodici']['Atleta'];

            $emailPrenotante = htmlentities($_POST['emailprenotante']);

            

            // 1. Ottieni tutti i CodiciCarica associati all'utente
            $stmt7 = $conn->prepare("SELECT N.CodiceCarica
                                    FROM UTENTE U 
                                    JOIN NOMINA N ON U.Persona = N.Persona
                                    WHERE U.Email = ?");
            $stmt7->bind_param("s", $emailPrenotante); 
            $stmt7->execute();
            $result = $stmt7->get_result();
            $caricheUtente = $result->fetch_all(MYSQLI_ASSOC);
            
            
            // Salva i codici in un array
            $codiciCaricaUtente = [];
            foreach ($caricheUtente as $value) {
                $codiciCaricaUtente[] = $value['CodiceCarica'];
            }
            
            // 2. Ottieni tutti i codici e nomi delle cariche
            $stmt8 = $conn->prepare("SELECT Codice, NomeCarica 
                                    FROM CARICA");
            $stmt8->execute();
            $result = $stmt8->get_result();
            $tutteCariche = $result->fetch_all(MYSQLI_ASSOC);
            
            $codicePrenotante = null;
            
            // Scorri tutti i codici carica dell'utente
            foreach ($tutteCariche as $carica) {
                if (in_array($carica['Codice'], $codiciCaricaUtente)) {
                    if ($carica['NomeCarica'] === "Allenatore") {
                        // Query su ALLENATORE
                        $stmt9 = $conn->prepare("SELECT CodicePrenotante 
                                                FROM ALLENATORE 
                                                WHERE Codice = ?");
                        $stmt9->bind_param("i", $carica['Codice']);
                        $stmt9->execute();
                        $result = $stmt9->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $codicePrenotante = $row['CodicePrenotante'];
                            break; // trovato, esci dal ciclo
                        }
                        
                    } elseif ($carica['NomeCarica'] === "Socio") {
                        // Query su SOCIO
                        $stmt10 = $conn->prepare("SELECT CodicePrenotante 
                                                FROM SOCIO 
                                                WHERE Codice = ?");
                        $stmt10->bind_param("i", $carica['Codice']);
                        $stmt10->execute();
                        $result = $stmt10->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $codicePrenotante = $row['CodicePrenotante'];
                            break; // trovato, esci dal ciclo
                        }
                    }
                }
            }
        }

        //TODO da finire!
        if (isset($_POST['ripetizione']) && $_POST['ripetizione'] === 'ripetizione') {
            // Recupera il numero delle ripetizioni
            if (isset($_POST['numeroPrenotazioni'])) {
                $numeroPrenotazioni = (int) $_POST['numeroPrenotazioni'];
    
                // Verifica che il numero sia tra 1 e 4 (ulteriore sicurezza lato server)
                if ($numeroPrenotazioni >= 2 && $numeroPrenotazioni <= 4) {
                    $new_data_inizio = $data_inizio;
                    $new_data_fine = $data_fine;
                    for($i =0 ; $i <$numeroPrenotazioni ; $i++) {

                        if($check){
                            $stmt3 = $conn->prepare("INSERT INTO PRENOTAZIONE (IDCampo ,DataTimeInizio,DataTimeFine,Prenotante,Attivita) VALUES (?,?,?,?,?)");
                            $stmt3->bind_param("issii",$idCampo,$new_data_inizio, $new_data_fine ,$codicePrenotante,$codiceAttivita); 
                            $stmt3->execute();
                            
                            //secondo insert in RICHIESTA
                            $stmt4 = $conn->prepare("INSERT INTO RICHIESTA VALUES (?,?,?,?,'NonConfermato')");
                            $stmt4->bind_param("isii",$idCampo,$new_data_inizio,$codicePrenotante,$codAtleta); 
                            $stmt4->execute();
                            
                            $to = $emailPrenotante;
                            $subject = 'Richiesta di conferma prenotazione';
                            $message = 'Ciao, la persona : '. $_SESSION['nome'] .' '.$_SESSION['cognome'].
                                    ' ha richiesto la sua conferma per la prenotazione del campo : ' . $campo . ' il giorno ' . $new_data_inizio ;
                            $headers = "From: polipopolisportiva5id@altervista.org\r\n";
                            $headers .= "X-Mailer: PHP/" . phpversion();
            
                            //Controllo se la mail sia usabile
                            if (!mail($to, $subject, $message, $headers)) {
                                error('../../front/prenotanti/prenotazione_form.php','Email non valido per la conferma!');
                            }
                        }else {//logica prenotante
                            
                            $stmt2 = $conn->prepare("INSERT INTO PRENOTAZIONE (IDCampo ,DataTimeInizio,DataTimeFine,Prenotante,Attivita) VALUES (?,?,?,?,?)");
                            $stmt2->bind_param("issii",$idCampo,$new_data_inizio, $new_data_fine ,$cod_prenotante,$codiceAttivita); 
                            $stmt2->execute();
                            
                        }
                        // Aggiungi 7 giorni alla data di inizio
                        $new_data_inizio = date('Y-m-d H:i:s', strtotime($new_data_inizio . ' +1 week'));

                        // Aggiungi 7 giorni alla data di fine
                        $new_data_fine = date('Y-m-d H:i:s', strtotime($new_data_fine . ' +1 week'));
                    }
                }
            }   
        } else {
            if($check) {

                $stmt3 = $conn->prepare("INSERT INTO PRENOTAZIONE (IDCampo ,DataTimeInizio,DataTimeFine,Prenotante,Attivita) VALUES (?,?,?,?,?)");
                $stmt3->bind_param("issii",$idCampo,$data_inizio, $data_fine ,$codicePrenotante,$codiceAttivita); 
                $stmt3->execute();

                //secondo insert in RICHIESTA
                $stmt4 = $conn->prepare("INSERT INTO RICHIESTA VALUES (?,?,?,?,'NonConfermato')");
                $stmt4->bind_param("isii",$idCampo,$data_inizio,$codicePrenotante,$codAtleta); 
                $stmt4->execute();

                $to = $emailPrenotante;
                $subject = 'Richiesta di conferma prenotazione';
                $message = 'Ciao, la persona : '. $_SESSION['nome'] .' '.$_SESSION['cognome'].
                        ' ha richiesto la sua conferma per la prenotazione del campo : ' . $campo . ' il giorno ' . $data_inizio ;
                $headers = "From: polipopolisportiva5id@altervista.org\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                //Controllo se la mail sia usabile
                if (!mail($to, $subject, $message, $headers)) {
                    error('../../front/prenotanti/prenotazione_form.php','Email non valido per la conferma!');
                }
            }else {//logica prenotante
                $stmt2 = $conn->prepare("INSERT INTO PRENOTAZIONE (IDCampo ,DataTimeInizio,DataTimeFine,Prenotante,Attivita) VALUES (?,?,?,?,?)");
                $stmt2->bind_param("issii",$idCampo,$data_inizio, $data_fine ,$cod_prenotante,$codiceAttivita); 
                $stmt2->execute();    
            }
        }
        $conn->commit();

    }catch (mysqli_sql_exception $exception) {
        $conn->rollback();

        error('../../front/prenotanti/prenotazione_form.php', 'Prenotazione fallita!');
    }
    success('../../front/prenotanti/prenotazione_form.php' , 'Prenotazione Avvenuta con successo!');

    
?>