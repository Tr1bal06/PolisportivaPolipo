<?php
    include '../connessione.php';
    include '../function.php';
    require '../../vendor/autoload.php';  
    session_start();

    $permessi = ['Consigliere', 'admin'];
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    //Acquisisco i dati dal form
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $numprotocollo = '';
            $data = htmlentities($_POST['data_atto']);
            $ordine= htmlentities($_POST['ordine_giorno']);
            $oggetto = htmlentities($_POST['oggetto']);
            $redatore = $_SESSION['caricheCodici']['Consigliere'] ?? $_SESSION['caricheCodici']['Presidente'] ?? null;
            $array_ruoli = $_SESSION['ruolo'];
    }

    // Controlla che sia stato caricato un file
    if (!(isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == UPLOAD_ERR_OK)) {
        error("../../front/atti/atti.php","Nessun file inserito");
    }

    // Controlla che il file sia effettivamente un PDF
    $fileType = mime_content_type($_FILES['file_pdf']['tmp_name']);
    if ($fileType != 'application/pdf') {
        error("../../front/atti/atti.php","Il file inserito non è un pdf");

    }

    use Google\Client;
    use Google\Service\Drive;
    // Configura il client Google
    $client = new Client();
    $client->setAuthConfig('../credenziali.json');
    $client->addScope(Drive::DRIVE_FILE);

    $conn->begin_transaction();//Inizio la transazione
    try {
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

        $fileUrl = "https://drive.google.com/file/d/$fileId/view";

        echo "<pre>";
    //genero dinamicamente il codice del protocollo
        $anno= date('Y');

        $query = "SELECT NumProtocollo
                    FROM `ATTO`
                    WHERE NumProtocollo LIKE 'ATTO-%'
                    AND SUBSTRING_INDEX(NumProtocollo, '-', -1) = YEAR(CURDATE())
                    ORDER BY NumProtocollo DESC
                    ";

        $result = $conn ->query($query);
        if($result) {
            $numero = intval(explode("-",(  $result->fetch_assoc())['NumProtocollo'])[1]);
            
        }else{
            throw new Exception("Errore");
        }
        
        $numero++;
        $numeroProgressivoFormattato = str_pad($numero, 6, '0', STR_PAD_LEFT);
        $numprotocollo= "ATTO-". $numeroProgressivoFormattato. "-". $anno;

        $sql="INSERT INTO ATTO(`NumProtocollo`, `Data`, `OrdineDelGiorno`, `PathTesto`, `CodiceRedatore`, `Oggetto`)
        VALUES (?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss",$numprotocollo, $data, $ordine, $fileUrl, $redatore, $oggetto);
        $stmt->execute();

        $conn->commit();

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        error("../../front/atti/atti.php","Inserimento fallito");
    }
    success("../../front/atti/atti.php","Atto inserito correttamente");
    //made by Tha_Losco
?>