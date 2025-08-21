<?php

    include '../connessione.php';
    include '../function.php';

    require '../../vendor/autoload.php';  
    session_start();

    use Google\Client;
    use Google\Service\Drive;

    $permessi = ['admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    // solo sponsor e altro_personale non funzionano, mancano i dati di Alberto!

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $CF = htmlentities($_POST['cf']);
        $nomeCarica = htmlentities($_POST['carica']);
        $tipoCarica = htmlentities($_POST['tipo']);
        $tipoattivita = htmlentities($_POST['attivita']);
        $nome = htmlentities($_POST['nome']);

        $conn->begin_transaction();

        try {

            $oggi = date("Y-m-d");
            $stmt8 = $conn->prepare("SELECT DataFine , Autenticazione
                                     FROM NOMINA
                                     WHERE Persona = ?");
            $stmt8->bind_param("s", $CF);
            $stmt8->execute();
            $result = $stmt8->get_result();
            $cariche = $result->fetch_all(MYSQLI_ASSOC);

            foreach($cariche as $value) {
                $parti = explode("_", $value['Autenticazione']);
                $carica = $nomeCarica;
                if($nomeCarica == 'Presidente') {
                    $carica = 'Consigliere';
                }

                if($parti[1] == strtolower($carica)) {
                    
                    if($value['DataFine']> $oggi) {
                        error('../../front/persone/persone.php', 'Carica già presente');
                    }
                }
            }

            $stmt1 = $conn->prepare("INSERT INTO CARICA (NomeCarica) VALUES (?)");
            $stmt1->bind_param("s", $nomeCarica);
            $stmt1->execute();
            $codiceCarica = $conn->insert_id;
            
            $codiceConvocatore = 1;

            // Step 1: Cerchiamo un codice convocatore già associato a qualsiasi carica di tipo convocatore
            $stmt9 = $conn->prepare("
                SELECT CodiceConvocatore 
                FROM (
                    SELECT C1.CodiceConvocatore
                    FROM NOMINA N
                    JOIN CONSIGLIERE C1 ON N.CodiceCarica = C1.Codice
                    WHERE N.Persona = ?
                    UNION
                    SELECT A.CodiceConvocatore
                    FROM NOMINA N
                    JOIN ALLENATORE A ON N.CodiceCarica = A.Codice
                    WHERE N.Persona = ?
                    UNION
                    SELECT S.CodiceConvocatore
                    FROM NOMINA N
                    JOIN SOCIO S ON N.CodiceCarica = S.Codice
                    WHERE N.Persona = ?
                ) AS ConvocatoriPersona
                LIMIT 1
            ");
            $stmt9->bind_param("sss", $CF, $CF, $CF);
            $stmt9->execute();
            $result = $stmt9->get_result();
            $row = $result->fetch_assoc();
            
            if ($row && !empty($row['CodiceConvocatore'])) {
                $codiceConvocatore = $row['CodiceConvocatore'];
            } else {
                // Step 2: Se non c'è, generiamo un nuovo codice convocatore
                $stmt5 = $conn->prepare("SELECT MAX(Codice) as MaxCodice FROM CONVOCATORE");
                $stmt5->execute();
                $result = $stmt5->get_result();
                $row = $result->fetch_assoc();
                $codiceConvocatore = ($row && $row['MaxCodice']) ? $row['MaxCodice'] + 1 : 1;
            
                // Step 3: Inseriamo il nuovo codice nella tabella CONVOCATORE
                $stmt6 = $conn->prepare("INSERT INTO CONVOCATORE (Codice) VALUES (?)");
                $stmt6->bind_param("i", $codiceConvocatore);
                $stmt6->execute();
            }
            $codicePrenotante = 0;

            // Cerchiamo se la persona ha già un codice prenotante da qualsiasi carica
            $stmt10 = $conn->prepare("
                SELECT CodicePrenotante FROM (
                    SELECT A.CodicePrenotante
                    FROM NOMINA N
                    JOIN CARICA C ON N.CodiceCarica = C.Codice
                    JOIN ALLENATORE A ON C.Codice = A.Codice
                    WHERE N.Persona = ?
                    UNION
                    SELECT S.CodicePrenotante
                    FROM NOMINA N
                    JOIN CARICA C ON N.CodiceCarica = C.Codice
                    JOIN SOCIO S ON C.Codice = S.Codice
                    WHERE N.Persona = ?
                ) AS Prenotanti
                LIMIT 1
            ");

            $stmt10->bind_param("ss", $CF, $CF);
            $stmt10->execute();
            $result = $stmt10->get_result();
            $row = $result->fetch_assoc();

            if ($row && !empty($row['CodicePrenotante'])) {
                $codicePrenotante = $row['CodicePrenotante'];
            } else {
                // Se non c'è, lo creiamo
                $stmtMax = $conn->prepare("SELECT MAX(Codice) as MaxCodice FROM PRENOTANTE");
                $stmtMax->execute();
                $resMax = $stmtMax->get_result();
                $rowMax = $resMax->fetch_assoc();
                $codicePrenotante = ($rowMax && $rowMax['MaxCodice']) ? $rowMax['MaxCodice'] + 1 : 1;

                // Inseriamo il nuovo codice
                $stmtInsert = $conn->prepare("INSERT INTO PRENOTANTE (Codice) VALUES (?)");
                $stmtInsert->bind_param("i", $codicePrenotante);
                $stmtInsert->execute();
            }

            // 2. Inserisci nella tabella specifica della carica
            switch (strtolower($nomeCarica)) {
                case 'presidente':
                case 'consigliere':
                    $stmt = $conn->prepare("INSERT INTO CONSIGLIERE (Codice, Tipo, CodiceConvocatore) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $codiceCarica, $nomeCarica, $codiceConvocatore);
                    break;

                case 'allenatore':
                    $stmt = $conn->prepare("INSERT INTO ALLENATORE (Codice, CodiceConvocatore, CodicePrenotante) VALUES (?, ?, ?)");
                    $stmt->bind_param("iii", $codiceCarica, $codiceConvocatore , $codicePrenotante);
                    break;

                case 'atleta':
                    $stmt = $conn->prepare("INSERT INTO ATLETA (Codice) VALUES (?)");
                    $stmt->bind_param("i", $codiceCarica);
                    break;

                case 'medico':
                    $stmt = $conn->prepare("INSERT INTO MEDICO (Codice) VALUES (?)");
                    $stmt->bind_param("i", $codiceCarica);
                    break;

                case 'socio':
                    $stmt = $conn->prepare("INSERT INTO SOCIO (Codice, CodiceConvocatore, CodicePrenotante) VALUES (?, ?, ?)");
                    $stmt->bind_param("iii", $codiceCarica, $codiceConvocatore , $codicePrenotante);
                    break;

                case 'altro_personale':
                    
                    $stmt = $conn->prepare("INSERT INTO ALTRO_PERSONALE (Codice, TipoCarica) VALUES (?, ?)");
                    $stmt->bind_param("is", $codiceCarica, $tipoCarica);
                    break;

                case 'sponsor':
                    $stmt = $conn->prepare("INSERT INTO SPONSOR (Codice, Nome, TipoAttivita) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $codiceCarica, $nome, $tipoattivita);
                    break;

                default:
                    throw new Exception("Tipo di carica non riconosciuto: $nomeCarica");
            }

            $stmt->execute();

            if($nomeCarica == 'Presidente') {
                $stmt7 =  $conn->prepare("SELECT NumProtocollo
                                          FROM ATTO
                                          WHERE OrdineDelGiorno = ? OR OrdineDelGiorno = 'Consigliere'");
                $stmt7->bind_param("s", $nomeCarica);
                $stmt7->execute();
            } else {
                $stmt7 =  $conn->prepare("SELECT NumProtocollo
                                          FROM ATTO
                                          WHERE OrdineDelGiorno = ?");
                $stmt7->bind_param("s", $nomeCarica);
                $stmt7->execute();   
            }
                               
            $result = $stmt7->get_result();
            $prefissi = $result->fetch_all(MYSQLI_ASSOC);
            $basi = [];
            foreach ($prefissi as $item) {
                $parts = explode('_', $item['NumProtocollo']);
                $basi[] = $parts[0]; 
            }

            if (empty($basi)) {
                switch (strtolower($nomeCarica)) {
                    case 'presidente':
                    case 'consigliere': 
                        $numero = '111'; 
                        break;
                    case 'allenatore': 
                        $numero = '331'; 
                        break;
                    case 'atleta':    
                         $numero = '441'; 
                         break;
                    case 'socio':      
                        $numero = '221';
                        break;
                    case 'medico':     
                        $numero = '661'; 
                        break;
                    case 'altro_personale': 
                        $numero = '551'; 
                        break;
                    case 'sponsor':    
                        $numero = '771'; 
                        break;
                }
            } else {
                $last = max($basi);
                $fissi = substr($last, 0, 2);
                $variabile = intval(substr($last, 2));
                $variabile = $variabile + 1;
                $numero = $fissi . $variabile;
            }

            // 3. Crea un ATTO + ATTO_NOMINA
            switch (strtolower($nomeCarica)) {
                case 'presidente':
                case 'consigliere':

                    $numProtocollo = $numero ."_consigliere";
                    break;
                case 'allenatore':

                    $numProtocollo = $numero ."_allenatore";
                    break;

                case 'atleta':

                    $numProtocollo = $numero ."_atleta";
                    break;

                case 'medico':


                    $numProtocollo = $numero ."_medico";
                    break;

                case 'socio':

                    $numProtocollo = $numero ."_socio";
                    break;

                case 'altro_personale':

                    $numProtocollo = $numero ."_altro_personale";
                    break;

                case 'sponsor':

                    $numProtocollo = $numero ."_sponsor";
                    break;
            }
            $anno = date("Y"); 
            $numProtocollo = $numProtocollo . "_" . $anno;
            $path = "/";//controllare se è possibile creare un file pdf da caricare sul db
            $ordineGiorno = $nomeCarica;
            $oggetto = "Nomina di $CF a $nomeCarica";
             
            
            $testo = "In data $oggi la persona $CF e' stata nominata $nomeCarica";

            // Struttura base di un file PDF (semplificata)
            $pdfContent = <<<EOD
            %PDF-1.4
            1 0 obj
            << /Type /Catalog /Pages 2 0 R >>
            endobj
            2 0 obj
            << /Type /Pages /Kids [3 0 R] /Count 1 >>
            endobj
            3 0 obj
            << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << >> >>
            endobj
            4 0 obj
            << /Length 75 >>
            stream
            BT
            /F1 12 Tf
            100 750 Td
            ($testo) Tj
            ET
            endstream
            endobj
            xref
            0 5
            0000000000 65535 f 
            0000000010 00000 n 
            0000000062 00000 n 
            0000000117 00000 n 
            0000000240 00000 n 
            trailer
            << /Root 1 0 R /Size 5 >>
            startxref
            355
            %%EOF
            EOD;

            // 2. Scrivi su file temporaneo
            $tempPdfPath = sys_get_temp_dir() . '/nomina_semplice.pdf';
            file_put_contents($tempPdfPath, $pdfContent);

            // 3. Configura Google Client
            $client = new Client();
            $client->setAuthConfig('../credenziali.json');
            $client->addScope(Drive::DRIVE_FILE);

            // 4. Inizializza servizio Drive
            $service = new Drive($client);

            // 5. Carica su Google Drive
            $fileMetadata = new Drive\DriveFile([
                'name' => 'nomina_semplice_' . time() . '.pdf',
                'parents' => ['147w5dFleAqbQrONHsDbXJicIhmAC0NR_']
            ]);

            $content = file_get_contents($tempPdfPath);

            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            // 6. Permessi pubblici
            $fileId = $file->id;
            $permission = new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader'
            ]);
            $service->permissions->create($fileId, $permission);

            $fileUrl = "https://drive.google.com/file/d/$fileId/view";

            echo "PDF caricato senza librerie! Link: $fileUrl";


            $stmt2 = $conn->prepare("INSERT INTO ATTO (NumProtocollo, Data, OrdineDelGiorno, PathTesto, CodiceRedatore, Oggetto) VALUES (?, ?, ?, ?, 1, ?)");
            $stmt2->bind_param("sssss", $numProtocollo, $oggi, $ordineGiorno, $fileUrl, $oggetto);
            $stmt2->execute();

            $stmt3 = $conn->prepare("INSERT INTO ATTO_NOMINA (NumProtocollo) VALUES (?)");
            $stmt3->bind_param("s", $numProtocollo);
            $stmt3->execute();


            $dataNomina = $oggi;
            $dataOggi = new DateTime($oggi);
            $dataFutura = $dataOggi->add(new DateInterval('P1Y'));
            $dataFine = $dataFutura->format('Y-m-d');
            // 4. Inserisci in NOMINA
            $stmt4 = $conn->prepare("INSERT INTO NOMINA (Persona, CodiceCarica, DataDiNomina, DataFine, Autenticazione) VALUES (?, ?, ?, ?, ?)");
            $stmt4->bind_param("sisss", $CF, $codiceCarica, $dataNomina, $dataFine, $numProtocollo);
            $stmt4->execute();

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            
            error('../../front/persone/persone.php', 'Assegnazione fallita!');
        }
        $_SESSION['ruoli'][] = $nomeCarica;
        success('../../front/persone/persone.php', 'Assegnazione carica avvenuta con successo!');
    } else {
        error("location: ../../front/404.php", "Metodo sbagliato");
    }
?>