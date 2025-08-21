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
    $query = "SELECT NomeCampo , TipoCampo
            FROM CAMPO
            ORDER BY TipoCampo";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500); 
        exit;
    }

    $campi = $result->fetch_all(MYSQLI_ASSOC);

    $campiJson  = [];

    foreach($campi as $campo){
        $campiJson[$campo['TipoCampo']][]  = $campo["NomeCampo"];
    }

    // Output
    echo json_encode($campiJson);
    http_response_code(200);
        

    $conn->close();
?>
