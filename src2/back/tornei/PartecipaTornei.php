<?php

    /**
     * File: PartecipaTorneo.php
     * Auth: Jin
     * Desc: Questo file serve per l'iscrizione delle squadre ad un torneo , 
     *       a controllare se il torneo è pieno per far partire il matchmatching , 
     *       e controllare se è tempo di passare al prossimo round 
     */
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
    //controllo i permessi
    
    $permessi  =['Allenatore', 'admin'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)){
        error('../../front/404.php', 'Accesso negato');
    }

    $conn->begin_transaction();

    try{
        if($_SERVER['REQUEST_METHOD']=='POST'){
        
            $codTorneo = htmlentities($_POST['CodiceTorneo']);
            $Anno = htmlentities($_POST['Anno']);
            $nomeSquadra = htmlentities($_POST['Squadra']);
        }

        $max = $conn->prepare("SELECT MaxSquadre
                                 FROM EDIZIONE_TORNEO
                                 WHERE CodiceTorneo = ? AND Anno= ?");
        $max->bind_param("is",$codTorneo,$Anno);
        $max->execute();
        $result = $max->get_result();
        $row = $result->fetch_assoc();

        $check = $conn->prepare("SELECT NomeSquadra
                                 FROM ISCRIZIONI_TORNEO
                                 WHERE EdizioneTorneo = ? AND AnnoTorneo = ?");
        $check->bind_param("is",$codTorneo,$Anno);
        $check->execute();
        $result = $check->get_result();
        $row = $result->fetch_assoc();

        
        
        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        $default = "";

        $codiciGestiti = [];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/tornei.php', $default);
    }
    //success('../../front/tornei/tornei.php', 'Iscrizione al torneo avvenuta con successo!'); ??
?>