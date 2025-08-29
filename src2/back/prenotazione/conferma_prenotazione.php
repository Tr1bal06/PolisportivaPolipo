<?php
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin','Allenatore','Socio'];
 
    
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idcampo = htmlentities($_POST['Idcampo']);
        $data = htmlentities($_POST['DataTimeInizio']);

        $stmt1 = $conn->prepare("UPDATE RICHIESTA SET Stato = 'Confermato' WHERE IDCampo = ? AND DataTimeInizio = ?");
        $stmt1->bind_param("is", $idcampo , $data);
        $stmt1->execute();
        if ($stmt1->affected_rows > 0) {
            success('../../front/prenotanti/prenotazione_form.php','Prenotazione confermata con successo');
        } else {
            error('../../front/prenotanti/prenotazione_form.php','Non esiste la prenotazione');
        }

    } else{
        error('../../front/404.php', 'Metodo errato!');
    }

?>