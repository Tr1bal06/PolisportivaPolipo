<?php
    include "../connessione.php";
    include '../function.php';

    header('Content-Type: application/json');

    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
                
    $permessi = ['Consigliere', 'Socio', 'Allenatore', 'admin'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }

    // Recupera parametri con fallback vuoto
    $oggettoFiltro = '%' . htmlspecialchars($_GET['oggetto'] ?? '') . '%';
    $dataFiltro = '%' . htmlspecialchars($_GET['data'] ?? '') . '%';
    $ordineFiltro = '%' . htmlspecialchars($_GET['ordine'] ?? '') . '%';

    // Valida direzione ordine (solo ASC o DESC)
    $direzioneOrdine = 'DESC';
    if (isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC'])) {
        $direzioneOrdine = strtoupper($_GET['order']);
    }

    // Query SQL con placeholders
    $query = "SELECT NumProtocollo, Data, OrdineDelGiorno, PathTesto AS file_path, Oggetto , CodiceRedatore
            FROM ATTO 
            WHERE Oggetto LIKE ? AND OrdineDelGiorno LIKE ? AND Data LIKE ?
            ORDER BY Data $direzioneOrdine";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['error' => 'Errore nella preparazione: ' . $conn->error]);
        http_response_code(500);
        exit;
    }

    // Bind dei parametri corretti
    $stmt->bind_param("sss", $oggettoFiltro, $ordineFiltro, $dataFiltro);

    // Esecuzione
    $stmt->execute();
    $result = $stmt->get_result();

    // Costruzione dell'array JSON
    $atti = [];
    while ($row = $result->fetch_assoc()) {
        $atti[] = $row;
    }

    // Output
    echo json_encode($atti);

    // Pulizia
    $stmt->close();
    $conn->close();
?>
