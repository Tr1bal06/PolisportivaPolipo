<?php
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    //controllo il ruolo
    $permessi =['Allenatore', 'admin'];
    if(!controllo($_SESSION['ruolo'], $permessi)){
        error("./front/404.php","Permesso negato!");
    }
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $nomeSquadra = htmlentities($_POST['nomeSquadra']);
    }
    $conn->begin_transaction();

    try{
        $stmt=$conn->prepare("DELETE FROM TESSERAMENTI WHERE NomeSquadra = ? ");
        $stmt->bind_param('s', $nomeSquadra);
        $stmt->execute();

        $stmt1=$conn->prepare("DELETE FROM SQUADRA WHERE NomeSquadra = ? ");
        $stmt1->bind_param('s', $nomeSquadra);
        $stmt1->execute();

        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        $default = "Eliminazione della squadra fallita!";

        $codiciGestiti = [];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/squadre.php', $default);
    }
    success('../../front/tornei/squadre.php', 'Eliminazione della squadra avvenuta con successo!'); 
?>