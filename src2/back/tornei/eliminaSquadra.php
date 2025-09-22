<?php
    /**
     * File: eliminaSquadre.php
     * Auth: Jin
     * Desc: Questo file serve per eliminare le proprie squadre
     */
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
        $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];
    }
    $conn->begin_transaction();

    try{
        $check = $conn->prepare("SELECT Allenatore
                                 FROM SQUADRA
                                 WHERE Nome = ?");
        $check->bind_param("s",$nomeSquadra);
        $check->execute();
        $result = $check->get_result();
        $row = $result->fetch_assoc();

        if($row['Allenatore']!= $codiceAllenatore ) {
            throw new Exception("Questa squadra non ti appartiene!", 60010);
        }

        $stmt=$conn->prepare("DELETE FROM TESSERAMENTI WHERE NomeSquadra = ? ");
        $stmt->bind_param('s', $nomeSquadra);
        $stmt->execute();

        $stmt1=$conn->prepare("DELETE FROM SQUADRA WHERE Nome = ? ");
        $stmt1->bind_param('s', $nomeSquadra);
        $stmt1->execute();

        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        $default = "Eliminazione della squadra fallita!";

        $codiciGestiti = [60010];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/squadre.php', $default);
    }
    success('../../front/tornei/squadre.php', 'Eliminazione della squadra avvenuta con successo!'); 
?>