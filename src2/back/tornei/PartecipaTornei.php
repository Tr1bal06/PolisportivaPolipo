<?php

    /**
     * File: PartecipaTorneo.php
     * Auth: Jin
     * Desc: Questo file serve per l'iscrizione delle squadre ad un torneo , 
     *       a controllare se il torneo è pieno per far partire il matchmatching
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
        //prendo la capacità massima
        $max = $conn->prepare("SELECT MaxSquadre
                                 FROM EDIZIONE_TORNEO
                                 WHERE CodiceTorneo = ? AND Anno= ?");
        $max->bind_param("is",$codTorneo,$Anno);
        $max->execute();
        $result = $max->get_result();
        $row = $result->fetch_assoc();
        $maxSquadre = $row['MaxSquadre'];

        $check = $conn->prepare("SELECT NomeSquadra
                                 FROM ISCRIZIONI_TORNEO
                                 WHERE EdizioneTorneo = ? AND AnnoTorneo = ?");
        $check->bind_param("is",$codTorneo,$Anno);
        $check->execute();
        $result = $check->get_result();

        $squadre = [];
        while ($row = $result->fetch_assoc()) {
            $squadre[] = $row['NomeSquadra'];
        }

        if(count($squadre)<$maxSquadre-1) {
            //iscrivo la squadra se non già iscritta
            if (!in_array($nomeSquadra, $squadre)) {
                $insert = $conn->prepare("INSERT INTO ISCRIZIONI_TORNEO (EdizioneTorneo, AnnoTorneo, NomeSquadra)
                                        VALUES (?, ?, ?)");
                $insert->bind_param("iss", $codTorneo, $Anno, $nomeSquadra);
                $insert->execute();
            } else {
                throw new Exception("La squadra è già iscritta!", 20010);
            }
        } else if(count($squadre)+1==$maxSquadre) {

            if (!in_array($nomeSquadra, $squadre)) {
                $insert = $conn->prepare("INSERT INTO ISCRIZIONI_TORNEO (EdizioneTorneo, AnnoTorneo, NomeSquadra)
                                        VALUES (?, ?, ?)");
                $insert->bind_param("iss", $codTorneo, $Anno, $nomeSquadra);
                $insert->execute();
            } else {
                throw new Exception("La squadra è già iscritta!", 20010);
            }
            $squadre[] = $nomeSquadra;
            $fine = count($squadre)-1;

            $count = 1;
            $gruppo = 1;
            $insert = $conn->prepare("INSERT INTO PARTITA_TORNEO (SquadraCasa,SquadraOspite,EdizioneTorneo,AnnoTorneo,ScoreCasa,ScoreOspite,Round,Gruppo)
                                        VALUES (?, ?, ?,?,?,?,?,?)");
            for($i=0 ; $i<count($squadre)/2;$i++ , $fine--) {
                $insert->bind_param("ssisiiis", $squadre[$i], $squadre[$fine], $codTorneo,$Anno,0,0,1,$gruppo);
                $insert->execute();
                if($count == 2) {
                    $count = 0;
                    $gruppo++;
                }
                $count++;
            }
        }
        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        $default = "Iscrizione al torneo fallita!";

        $codiciGestiti = [20010];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/tornei.php', $default);
    }
    success('../../front/tornei/tornei.php', 'Iscrizione al torneo avvenuta con successo!'); 
?>