

<?php // FILE DEPRECATO TRASFRITO IN SESSIONE NEL LOGIN

include "../connessione.php";
include '../function.php';

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
            
$permessi = [ 'admin'];

//consigliere può vedere, modificare e aggiungere atti
//socio e allenatore possono vedere gli atti
//admin può fare tutto

if(!controllo($_SESSION['ruolo'], $permessi)) { // continua a buttarmi fuori 
    header("location: ../../front/404.php");
    exit();
}

// DEPRECATO AGGIUNTO IN SESSIONE 

/*
$CF = $_SESSION['cf'];
// Query SQL con placeholders
$query = "SELECT C.NomeCarica , N.CodiceCarica
          FROM NOMINA N
            JOIN CARICA C on N.CodiceCarica=C.Codice
          WHERE N.Persona = '$CF'";

$result = $conn->query($query);

if (!$result) {
    echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
    http_response_code(500);
    exit;
}

$persone = [];
while ($row = $result->fetch_assoc()) {
    $persone[] = $row;
}

// Output
echo json_encode($persone);


$conn->close();
?>
*/