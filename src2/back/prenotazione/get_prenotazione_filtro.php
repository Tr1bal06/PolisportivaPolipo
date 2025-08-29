<? 
    include "../connessione.php";
    include '../function.php';

    header('Content-Type: application/json');

    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
            
    $permessi = [ 'user', 'admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }
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



    $periodo =  htmlspecialchars($_GET['periodo'] ?? '') ;
    $dataFiltro =   htmlspecialchars($_GET['data'] ?? '') ;
    $dataFiltro = $dataFiltro . " 00:00:00";
    $attivita = '%' . htmlspecialchars($_GET['attivita'] ?? '') . '%';

    $mapping = [
        "giornaliero" => 1,
        "settimanale" => 7,
    ];

    $periodoSQL = $mapping[$periodo] ?? 31;



    // Query SQL con placeholders



    $query = "SELECT  P.IDCampo ,C.TipoCampo, C.NomeCampo, P.DataTimeInizio, P.DataTimeFine, R.Stato, T.TIPO_ATTIVITA , T_O.Nome as NomeTorneo, AL.Tipo , P_U.Arbitro , E_S.Causale as CausaleEvento , R_T.Causale as CausaleRiunione 
            FROM PRENOTAZIONE P 
            JOIN CAMPO C ON P.IDCampo = C.ID
            LEFT JOIN RICHIESTA R ON R.IDCampo = P.IDCampo AND R.DataTimeInizio = P.DataTimeInizio 
            JOIN TIPO_ATTIVITA T ON T.Codice = P.Attivita
            LEFT JOIN ALLENAMENTO AL ON AL.CodiceAttivita = T.TIPO_ATTIVITA
            LEFT JOIN PARTITA_UFFICIALE P_U ON P_U.CodiceAttivita = T.Codice
            LEFT JOIN TORNEO T_O ON T_O.CodiceAttivita = T.Codice
            LEFT JOIN EVENTO_SPECIALE E_S ON E_S.CodiceAttivita = T.Codice
            LEFT JOIN RIUNIONE_TECNICA R_T ON R_T.CodiceAttivita = T.Codice
            WHERE DATE(P.DataTimeInizio) BETWEEN ? AND DATE_ADD(?, INTERVAL $periodoSQL - 1 DAY)
                AND T.TIPO_ATTIVITA LIKE ? $sqlAdmin
            ORDER BY P.DataTimeInizio";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }

    // Bind dei parametri corretti
    $stmt->bind_param("sss", $dataFiltro, $dataFiltro , $attivita);

    // Esecuzione
    $stmt->execute();
    $result = $stmt->get_result();

    $arrayPrenotazioniFiltrate = $result->fetch_all(MYSQLI_ASSOC);

    // Output
    echo json_encode($arrayPrenotazioniFiltrate);

    // Pulizia
    $stmt->close();
    $conn->close();
?>