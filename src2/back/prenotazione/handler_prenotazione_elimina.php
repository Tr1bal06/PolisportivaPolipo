<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin','Allenatore','Atleta', 'Socio'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $campo =  htmlentities($_POST['nomeCampo']);
        $datai =  htmlentities($_POST['dataInizio']);

        $stmt = $conn->prepare("SELECT ID
                                FROM CAMPO
                                WHERE NomeCampo = ?");
        $stmt->bind_param("s", $campo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_all(MYSQLI_ASSOC);
        $idCampo = $row[0]['ID'];

        $stmt1 = $conn->prepare("DELETE FROM PRENOTAZIONE WHERE IDCampo = ? AND DataTimeInizio = ?");
        $stmt1->bind_param("is",$idCampo, $datai); 
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error('../../front/prenotanti/prenotazione_form.php', 'Eliminazione fallita!');
        } else {
            success('../../front/prenotanti/prenotazione_form.php', 'Eliminazione avvenuta con successo!');
        }
    }
?>