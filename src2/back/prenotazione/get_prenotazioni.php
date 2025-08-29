<?php 

  include "../connessione.php";
  include '../function.php';

  header('Content-Type: application/json');

  if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
              
  $permessi = ['user', 'admin'];

  if(!controllo($_SESSION['ruolo'], $permessi)) { 
      error('../../front/404.php', 'Accesso negato');
  }



  if($_GET['prenotante']){
    $sqlProprie = "";
    if(!in_array("admin", $_SESSION['ruolo'])){
        if(in_array("Atleta", $_SESSION['ruolo'])){
        
            $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];
            $sqlProprie = "AND R.Atleta = $codiceAtleta";
        } else {
            $codicePrenotante = $_SESSION["pren"];
            $sqlProprie = "AND P.Prenotante = $codicePrenotante";
        }
    }
  }

  // Query SQL con placeholders
  $query = "SELECT C.TipoCampo, C.NomeCampo, P.DataTimeInizio, P.DataTimeFine, R.Stato, T.TIPO_ATTIVITA , T_O.Nome as NomeTorneo, AL.Tipo , P_U.Arbitro , E_S.Causale as CausaleEvento , R_T.Causale as CausaleRiunione 
            FROM PRENOTAZIONE P JOIN CAMPO C ON P.IDCampo = C.ID
                                JOIN TIPO_ATTIVITA T_A ON T_A.Codice = P.Attivita
                                JOIN TIPO_ATTIVITA T ON T.Codice = P.Attivita
            LEFT JOIN ALLENAMENTO AL ON AL.CodiceAttivita = T.TIPO_ATTIVITA
            LEFT JOIN PARTITA_UFFICIALE P_U ON P_U.CodiceAttivita = T.Codice
            LEFT JOIN TORNEO T_O ON T_O.CodiceAttivita = T.Codice
            LEFT JOIN EVENTO_SPECIALE E_S ON E_S.CodiceAttivita = T.Codice
            LEFT JOIN RIUNIONE_TECNICA R_T ON R_T.CodiceAttivita = T.Codice
                                LEFT JOIN RICHIESTA R ON R.IDCampo = P.IDCampo AND R.DataTimeInizio = P.DataTimeInizio 
            WHERE P.DataTimeInizio BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 31 DAY  $sqlProprie
            ORDER BY DataTimeInizio;";



  $result = $conn->query($query);

  if (!$result) {
      echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
      http_response_code(500);
      exit;
  }

  $prenotazioni = $result->fetch_all(MYSQLI_ASSOC);

  // Output
  echo json_encode($prenotazioni);
  http_response_code(200);
      

  $conn->close();
?>
