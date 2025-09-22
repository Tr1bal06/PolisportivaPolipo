<?php
    /** 
     * File: 
     * Auth: 
     * Desc: 
    */
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    $permessi = ['admin','Consigliere'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $numProtocollo =  htmlentities($_POST['numProtocollo']);

        $stmt1 = $conn->prepare("DELETE FROM ATTO WHERE NumProtocollo = ?");
        $stmt1->bind_param("s",$numProtocollo); 
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error('../../front/atti/atti.php', 'Eliminazione fallita!');
        } else {
            success('../../front/atti/atti.php', 'Eliminazione avvenuta con successo!');
        }
    }
?>