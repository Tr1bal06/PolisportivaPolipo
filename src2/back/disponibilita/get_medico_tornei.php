<?
  include "../connessione.php";
  include '../function.php';

  header('Content-Type: application/json');

  if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
              
  $permessi = ['Medico', 'admin'];

  if(!controllo($_SESSION['ruolo'], $permessi)) { 
      error('../../front/404.php', 'Accesso negato');
  }

  $codiceMedico = $_SESSION["caricheCodici"]["Medico"];

  // Query SQL con placeholders
  $query = "SELECT  E_D.Anno , T.Nome , T.Sport , R.Stato , C.NomeCampo
            FROM EDIZIONE_TORNEO E_D
              JOIN TORNEO T ON T.CodiceAttivita = E_D.CodiceTorneo
              JOIN PRENOTAZIONE P ON T.CodiceAttivita = P.Attivita
              JOIN CAMPO C ON P.IDCampo = C.ID
              LEFT JOIN 	RICHIESTA R ON P.DataTimeInizio = R.DataTimeInizio AND P.IDCampo = R.IDCampo
            WHERE CodiceMedico = $codiceMedico AND CURRENT_DATE <= E_D.Anno";


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