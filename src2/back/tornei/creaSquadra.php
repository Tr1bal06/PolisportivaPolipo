<?php

    /**
     * File: creaSquadra
     * Auth: Jin
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

    //controllo i permessi
    $permessi  =['Allenatore', 'admin'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)){
        error('../../front/404.php', 'Accesso negato');
    }

    $conn->begin_transaction();

    try{
        if($_SERVER['REQUEST_METHOD']=='POST'){
        
            $nomeSquadra = htmlentities($_POST['nome_squadra']);
            $partecipanti = json_decode($_POST['codici_fiscali'], true);
            $sport = htmlentities($_POST['sport']);
            $sport = htmlentities($_POST['sport']); 
            $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];
        }
       
        $check = $conn->prepare("SELECT Nome
                                 FROM SQUADRA
                                 WHERE Nome = ?");
        $check->bind_param("s",$nomeSquadra);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            throw new Exception("Il nome della squadra esiste già!", 10010);
        }

        if (!(isset($_FILES['file_img']) && $_FILES['file_img']['error'] == UPLOAD_ERR_OK)) {
            throw new Exception("Immagine non presente!", 10011);
        }

        // Controlla che il file sia effettivamente un'immagine (png, jpg, jpeg, ico)
        $fileType = mime_content_type($_FILES['file_img']['tmp_name']);
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/x-icon']; // favicon .ico incluso

        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Formato immagine non valido!", 10012);
        }

        // Configura il client Google
        $client = new Client();
        $client->setAuthConfig('../credenziali.json');
        $client->addScope(Drive::DRIVE_FILE);

        // Inizializza il servizio Drive
        $service = new Drive($client);
        $fileTmpPath = $_FILES['file_img']['tmp_name'];
        $originalFileName = $_FILES['file_img']['name'];

        // Crea i metadati per il file
        $fileMetadata = new Drive\DriveFile([
            'name' => $originalFileName,
            'parents' => ['1wdnmO12q-tYfsnz7qG614odDRTCzPgyn'] // ID cartella
        ]);

        // Carica il file
        $content = file_get_contents($fileTmpPath);
        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $fileType, // <-- ora dinamico
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        // Crea il link pubblico al file
        $fileId = $file->id;

        // Rendi il file visibile a chiunque con il link
        $permission = new Drive\Permission([
            'type' => 'anyone',
            'role' => 'reader'
        ]);
        $service->permissions->create($fileId, $permission);

        // Link visualizzabile
        $path = "https://drive.google.com/uc?export=view&id=$fileId";


        $stmt=$conn->prepare("INSERT INTO SQUADRA(Nome,Logo,Allenatore,Sport)
                              VALUES (?,?,?,?)");
        $stmt->bind_param("ssis",$nomeSquadra,$path,$codiceAllenatore,$sport);
        $stmt->execute();

        $stmt1=$conn->prepare("SELECT N.CodiceCarica
                               FROM NOMINA N JOIN CARICA C ON N.CodiceCarica = C.Codice
                               WHERE N.Persona = ? AND C.NomeCarica = 'Atleta' ");

        $stmt2=$conn->prepare("INSERT INTO TESSERAMENTI(Atleta,NomeSquadra)
                              VALUES (?,?)");
        $stmt2->bind_param("is",$codiceAtleta , $nomeSquadra);
        foreach($partecipanti as $persona) {
            
            $persona = htmlentities($persona);
            $stmt1->bind_param("s",$persona);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $row = $result->fetch_assoc();
            $codiceAtleta = $row['CodiceCarica'];

            $stmt2->execute();
        }

        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();

        $default = "Creazione della squadra fallita!";

        $codiciGestiti = [10010, 10011, 10012];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/squadre.php', $default);
    }
    success('../../front/tornei/squadre.php', 'Creazione della squadra avvenuta con successo!');
    
?>