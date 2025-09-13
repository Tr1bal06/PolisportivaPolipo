<?php

    /**
     * File: handler_prenotazione_modifica.php
     * Auth: Jin
     * Desc: Questo file hai il compito di modificare le prenotazioni
     * 
     */
    include '../connessione.php';
    include '../function.php';
    require '../../vendor/autoload.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    use Google\Client;
    use Google\Service\Drive;

    $permessi = ['Atleta', 'Socio', 'Allenatore','admin'];
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        error('../../front/404.php', 'Metodo errato');
    }

    $data = htmlentities($_POST['data']);
    $oraInizio = htmlentities($_POST['oraInizio']);
    $oraFine = htmlentities($_POST['oraFine']);
    $dataInizio = $data . ' ' . $oraInizio . ':00';
    $dataFine = $data . ' ' . $oraFine . ':00';
    $campo = htmlentities($_POST['campo']);

    $stmt0 = $conn->prepare("SELECT ID
                             FROM CAMPO
                             WHERE NomeCampo = ?");
    $stmt0->bind_param("s",$campo); 
    $stmt0->execute();
    $result = $stmt0->get_result();
    $row = $result->fetch_assoc();
    $idCampo = $row['ID'];

    $conn->begin_transaction();

    try {
        // 1. Ottieni la prenotazione
        $stmt = $conn->prepare("SELECT P.*, TA.TIPO_ATTIVITA 
                                FROM PRENOTAZIONE P
                                JOIN TIPO_ATTIVITA TA ON P.Attivita = TA.Codice
                                WHERE P.IDcampo = ? AND P.DataTimeInizio = ?");
        $stmt->bind_param("is", $idCampo , $dataInizio);
        $stmt->execute();
        $prenotazione = $stmt->get_result()->fetch_assoc();

        if (!$prenotazione) {
            throw new Exception("Prenotazione non trovata.",10030);
        }

        $tipoAttivita = strtoupper($prenotazione['TIPO_ATTIVITA']);
        $modificheEffettuate = false;

        // 2. Verifica modifiche alla prenotazione principale
        if ($dataFine != $prenotazione['DataTimeFine']) {

            $stmt = $conn->prepare("UPDATE PRENOTAZIONE 
                                    SET DataTimeFine = ?
                                    WHERE IDcampo = ? AND DataTimeInizio = ?");
            $stmt->bind_param("sis", $dataFine,$campo, $dataInizio);
            $stmt->execute();
            $modificheEffettuate = true;
        }

        // 3. Verifica modifiche specifiche per tipo attività
        switch ($tipoAttivita) {
            case 'ALLENAMENTO':
                $nuovoTipo = htmlentities($_POST['tipo']);
                $stmt = $conn->prepare("SELECT Tipo 
                                        FROM ALLENAMENTO 
                                        WHERE CodiceAttivita = ?");
                $stmt->bind_param("i", $prenotazione['Attivita']);
                $stmt->execute();
                $vecchioTipo = $stmt->get_result()->fetch_assoc()['Tipo'];
                if ($vecchioTipo != $nuovoTipo) {
                    $stmt = $conn->prepare("UPDATE ALLENAMENTO SET Tipo = ? WHERE CodiceAttivita = ?");
                    $stmt->bind_param("si", $nuovoTipo, $prenotazione['Attivita']);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }
                break;

            case 'PARTITA UFFICIALE':
                $nuovoArbitro = htmlentities($_POST['arbitro']);
                $stmt = $conn->prepare("SELECT Arbitro 
                                        FROM PARTITA_UFFICIALE 
                                        WHERE CodiceAttivita = ?");
                $stmt->bind_param("i", $prenotazione['Attivita']);
                $stmt->execute();
                $vecchioArbitro = $stmt->get_result()->fetch_assoc()['Arbitro'];
                if ($vecchioArbitro != $nuovoArbitro) {
                    $stmt = $conn->prepare("UPDATE PARTITA_UFFICIALE SET Arbitro = ? WHERE CodiceAttivita = ?");
                    $stmt->bind_param("si", $nuovoArbitro, $prenotazione['Attivita']);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }
                break;

            case 'EVENTO SPECIALE':
                $nuovaCausale = htmlentities($_POST['causaleEvento']);
                $stmt = $conn->prepare("SELECT Causale 
                                        FROM EVENTO_SPECIALE 
                                        WHERE CodiceAttivita = ?");
                $stmt->bind_param("i", $prenotazione['Attivita']);
                $stmt->execute();
                $vecchiaCausale = $stmt->get_result()->fetch_assoc()['Causale'];
                if ($vecchiaCausale != $nuovaCausale) {
                    $stmt = $conn->prepare("UPDATE EVENTO_SPECIALE SET Causale = ? WHERE CodiceAttivita = ?");
                    $stmt->bind_param("si", $nuovaCausale, $prenotazione['Attivita']);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }
                break;

            case 'RIUNIONE TECNICA':
                $nuovaCausale = htmlentities($_POST['causaleRiunione']);
                $stmt = $conn->prepare("SELECT Causale 
                                        FROM RIUNIONE_TECNICA 
                                        WHERE CodiceAttivita = ?");
                $stmt->bind_param("i", $prenotazione['Attivita']);
                $stmt->execute();
                $vecchiaCausale = $stmt->get_result()->fetch_assoc()['Causale'];
                if ($vecchiaCausale != $nuovaCausale) {
                    $stmt = $conn->prepare("UPDATE RIUNIONE_TECNICA SET Causale = ? WHERE CodiceAttivita = ?");
                    $stmt->bind_param("si", $nuovaCausale, $prenotazione['Attivita']);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }
                break;

            case 'TORNEO':
                $nuovoNome = htmlentities($_POST['nomeTorneo']);
                $orarioTorneo = htmlentities($_POST['data']);
                
                $stmt = $conn->prepare("SELECT Nome 
                                        FROM TORNEO 
                                        WHERE CodiceAttivita = ?");
                $stmt->bind_param("i", $prenotazione['Attivita']);
                $stmt->execute();
                $vecchioNome = $stmt->get_result()->fetch_assoc()['Nome'];
                if ($vecchioNome != $nuovoNome) {
                    
                    $stmt = $conn->prepare("UPDATE TORNEO SET Nome = ? WHERE CodiceAttivita = ? ");
                    $stmt->bind_param("si", $nuovoNome, $prenotazione['Attivita']);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }

                // Gestione PDF
                if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == UPLOAD_ERR_OK) {
                    $fileType = mime_content_type($_FILES['file_pdf']['tmp_name']);
                    if ($fileType != 'application/pdf') {
                        throw new Exception("Formato sbagliato!",10031);    
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

                    $stmt = $conn->prepare("UPDATE EDIZIONE_TORNEO SET Regolamento = ? WHERE CodiceTorneo = ? AND Anno = ?");
                    $stmt->bind_param("sis", $path, $prenotazione['Attivita'],$orarioTorneo);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }
                $stmt = $conn->prepare("SELECT MaxSquadre
                                        FROM EDIZIONE_TORNEO 
                                        WHERE CodiceTorneo = ? AND Anno = ?");
                $stmt->bind_param("is", $prenotazione['Attivita'],$orarioTorneo );
                $stmt->execute();
                $vecchioLimite = $stmt->get_result()->fetch_assoc()['MaxSquadre'];
                $nuovoLimite = htmlentities($_POST['max_squadre']);
                if($vecchioLimite != $nuovoLimite) {
                    $stmt = $conn->prepare("UPDATE EDIZIONE_TORNEO SET MaxSquadre = ? WHERE CodiceTorneo = ? AND Anno = ?");
                    $stmt->bind_param("sis", $nuovoLimite, $prenotazione['Attivita'],$orarioTorneo);
                    $stmt->execute();
                    $modificheEffettuate = true;
                }
            break;
        }

        $conn->commit();

        if ($modificheEffettuate) {
            success("../../front/prenotanti/prenotazione_form.php", "Modifica avvenuta con successo.");
        } else {
            success("../../front/prenotanti/prenotazione_form.php", "Nessuna modifica rilevata.");
        }

    } catch (Exception $e) {
        $conn->rollback();
        $default = "Modifica fallita!";

        $codiciGestiti = [10030, 10031];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/prenotanti/prenotazione_form.php', $default);
    }
?>