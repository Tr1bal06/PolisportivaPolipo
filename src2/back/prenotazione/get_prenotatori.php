<?
    include "../connessione.php";
    include '../function.php';

    header('Content-Type: application/json');

    session_start();
                
    $permessi = ['Consigliere', 'Socio', 'Atleta', 'Allenatore', 'admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }


    // Query SQL con placeholders
    $query = "SELECT U.Email, PE.Nome , PE.Cognome
        FROM PRENOTANTE P
	LEFT JOIN SOCIO S ON P.Codice = S.CodicePrenotante
    LEFT JOIN ALLENATORE A ON P.Codice = A.CodicePrenotante
    JOIN NOMINA N ON N.CodiceCarica = S.Codice OR N.CodiceCarica = A.Codice
    JOIN PERSONA PE ON PE.CF = N.Persona
    JOIN UTENTE U ON U.Persona = PE.CF";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500); 
        exit;
    }

    $campi = $result->fetch_all(MYSQLI_ASSOC);

    

    // Output
    echo json_encode($campi);
    http_response_code(200);
        

    $conn->close();
?>
