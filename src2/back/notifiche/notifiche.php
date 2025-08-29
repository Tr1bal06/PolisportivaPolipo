<?php

use PgSql\Lob;

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
} 

    $permessi = ['user'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }
    //$cod_prenotante = $_SESSION['pren']; 
    $oggi = date('Y-m-d H:i:s'); 

    $sqlAdmin = "";
    if(!in_array("admin", $_SESSION['ruolo'])){
        if(in_array("Atleta", $_SESSION['ruolo'])){
        
            $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];
            $sqlAdmin = "AND R.Atleta = $codiceAtleta";
        } else {
            $codicePrenotante = $_SESSION["pren"];
            $sqlAdmin = "AND P.Prenotante = $codicePrenotante";
        }
    }

    $result =  $conn->query("SELECT DISTINCT  P.DataTimeInizio , P.DataTimeFine , T_A.TIPO_ATTIVITA , C.NomeCampo , C.TipoCampo 
                             FROM PRENOTAZIONE P 
                             JOIN CAMPO C ON C.ID = P.IDCampo
                             JOIN TIPO_ATTIVITA T_A ON T_A.Codice = P.Attivita
                             LEFT JOIN RICHIESTA R ON R.IDCampo = P.IDCampo AND R.DataTimeInizio = P.DataTimeInizio
                             WHERE P.DataTimeInizio >= '$oggi' $sqlAdmin");

    $prenotazioni = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    
    
    $prenotazioniInScadenza = [];

    foreach ($prenotazioni as $p) {
        $dataPrenotazione = new DateTime($p['DataTimeInizio']);
        $adesso = new DateTime($oggi);

        // Calcola la differenza in giorni
        $diff = $adesso->diff($dataPrenotazione);

        // Se mancano 2 o meno giorni
        if ($dataPrenotazione > $adesso && $diff->days <= 2) {
            $prenotazioniInScadenza[] = $p;
        }
    }
    
    

    echo json_encode($prenotazioniInScadenza);

    http_response_code(200);
    

$conn->close();
        
?>
