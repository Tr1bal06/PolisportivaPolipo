<?php

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
    $cod_prenotante = $_SESSION['pren']; 
    $oggi = date('Y-m-d H:i:s'); 

    $result =    $conn->query("SELECT DISTINCT  P.DataTimeInizio , P.DataTimeFine , T_A.TIPO_ATTIVITA , C.NomeCampo , C.TipoCampo 
                             FROM PRENOTAZIONE P 
                             JOIN RICHIESTA R ON P.IDCampo = R.IDCampo AND P.DataTimeInizio = R.DataTimeInizio
                             JOIN CAMPO C ON C.ID = P.IDCampo
                             JOIN TIPO_ATTIVITA T_A ON T_A.Codice = P.Attivita
                             WHERE P.Prenotante = $cod_prenotante AND P.DataTimeInizio >= '$oggi' AND R.Stato = 'NonConfermato' ");

    $richieste = $result ? $result->fetch_all(MYSQLI_ASSOC) : []; 

    echo json_encode($richieste);

    http_response_code(200);
    

$conn->close();
        
?>