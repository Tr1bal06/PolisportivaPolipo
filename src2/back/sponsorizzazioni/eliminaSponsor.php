<?php
    /**
     * File: 
     * Auth: Teox5
     * Desc: 
     */
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    $permessi = ['admin', 'Sponsor'];

    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try{

            $CodT =  intval($_POST['CodiceTorneo']);
            $Anno = htmlentities($_POST['Anno']);
            $CodS = $_SESSION["caricheCodici"]['Sponsor'];

            $stmt1 = $conn->prepare("DELETE FROM SPONSORIZZAZIONE WHERE CodiceTorneo = ? AND Anno = ? AND CodiceSponsor= ?");
            $stmt1->bind_param("iss", $CodT, $Anno, $CodS);
            $result = $stmt1->execute();
            if (! ($result && $stmt1->affected_rows === 1)) {
                error('../../front/sponsor/sponsorizzazioni.php', 'Eliminazione fallita!');
            } else {
                success('../../front/sponsor/sponsorizzazioni.php', 'Eliminazione avvenuta con successo!');
            }
        }catch(Exception $e){
            error('../../front/sponsor/sponsorizzazioni.php', 'Eliminazione fallita!');
        }
    }

?>
