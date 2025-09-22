<?php

    /**
     * File: PartecipaTorneo.php
     * Auth: Jin
     * Desc: Questo file serve per gestire il matchmatching delle partite successive
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

    if($_SERVER['REQUEST_METHOD']=='POST'){
        
        $scoreCasa = htmlentities($_POST['scoreA']);
        $scoreOspite = htmlentities($_POST['scoreB']);
        $codTorneo = htmlentities($_POST['EdizioneTorneo']);
        $Anno = htmlentities($_POST['AnnoTorneo']);
        $casa = htmlentities($_POST['teamA']);
        $ospite = htmlentities($_POST['teamB']);
    }

    $conn->begin_transaction();

    try{
        $check = $conn->prepare("SELECT is_updated ,Round
                                 FROM PARTITA_TORNEO
                                 WHERE EdizioneTorneo = ? AND AnnoTorneo = ? AND SquadraCasa = ? AND SquadraOspite = ?");
        $check->bind_param("isss",$codTorneo,$Anno,$casa,$ospite);
        $check->execute();
        $result = $check->get_result();
        $row = $result->fetch_assoc();
        $controllo = $row['is_updated'];

        if($controllo == 1) {
            throw new Exception("Modifica illegale!",20020);
        }
        $max = $conn->prepare("SELECT MaxSquadre
                                 FROM EDIZIONE_TORNEO
                                 WHERE CodiceTorneo = ? AND Anno= ?");
        $max->bind_param("is",$codTorneo,$Anno);
        $max->execute();
        $result = $max->get_result();
        $row = $result->fetch_assoc();
        $maxSquadre = $row['MaxSquadre'];

        $update = $conn->prepare("UPDATE PARTITA_TORNEO
                                  SET ScoreCasa = ?, ScoreOspite = ?, is_updated = 1
                                  WHERE EdizioneTorneo = ? AND AnnoTorneo = ? AND SquadraCasa = ? AND SquadraOspite = ?");
        if($maxSquadre == 16) {
            $check->bind_param("isss",$codTorneo,$Anno,$ospite,$casa);
            $check->execute();
            $result = $check->get_result();
            $row = $result->fetch_assoc();
            $round = $row['Round'];
            if($round == 4) {
                $update->bind_param("iiisss", $scoreCasa, $scoreOspite, $codTorneo, $Anno, $ospite, $casa);
                $update->execute();
            }
            
        }
        if($maxSquadre == 8) {
            $check->bind_param("isss",$codTorneo,$Anno,$ospite,$casa);
            $check->execute();
            $result = $check->get_result();
            $row = $result->fetch_assoc();
            $round = $row['Round'];
            if($round == 3) {
                $update->bind_param("iiisss", $scoreCasa, $scoreOspite, $codTorneo, $Anno, $ospite, $casa);
                $update->execute();
            }
            
        }
        if($maxSquadre == 4) {
            $check->bind_param("isss",$codTorneo,$Anno,$ospite,$casa);
            $check->execute();
            $result = $check->get_result();
            $row = $result->fetch_assoc();
            $round = $row['Round'];
            if($round == 2) {
                $update->bind_param("iiisss", $scoreCasa, $scoreOspite, $codTorneo, $Anno, $ospite, $casa);
                $update->execute();
            }
            
        }
        $update->bind_param("iiisss", $scoreCasa, $scoreOspite, $codTorneo, $Anno, $casa, $ospite);
        $update->execute();

        $stmt = $conn->prepare("SELECT is_updated , Round , Gruppo , SquadraCasa , SquadraOspite , ScoreCasa, ScoreOspite
                                 FROM PARTITA_TORNEO
                                 WHERE EdizioneTorneo = ? AND AnnoTorneo = ? 
                                 ORDER BY Gruppo ");
        $stmt->bind_param("is",$codTorneo,$Anno);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $allUpdated = !in_array(0, array_column($rows, 'is_updated'));
        if ($allUpdated) {
            // Raggruppo per Gruppo
            $gruppi = [];
            foreach ($rows as $r) {
                $gruppi[$r['Gruppo']][] = $r;
            }
            
            $maxRound = 0;

            foreach ($gruppi as $gruppo) {
                foreach ($gruppo as $match) {
                    if ($match['Round'] > $maxRound) {
                        $maxRound = $match['Round'];
                    }
                }
            }
            // Trovo i vincitori di ciascun gruppo dell’ultimo round
            $topTeams = [];
            foreach ($gruppi as $gruppo => $partite) {

                 $ultimoRound = [];

                // Calcolo il round massimo
                foreach ($partite as $match) {
                    if ($match["Round"] == $maxRound) {
                        $ultimoRound[] = $match;
                    }
                }

                foreach ($ultimoRound as $match) {
                    if ($match['ScoreCasa'] >= $match['ScoreOspite']) {
                        $topTeams[] = $match['SquadraCasa'];
                    } else {
                        $topTeams[] = $match['SquadraOspite'];
                    }
                }
            }
           
            // Ora genero il nuovo round
            $nextRound = $maxRound + 1;
            $insert = $conn->prepare("INSERT INTO PARTITA_TORNEO(SquadraCasa, SquadraOspite, EdizioneTorneo, AnnoTorneo, ScoreCasa, ScoreOspite, Round, Gruppo) 
                                        VALUES (?, ?, ?, ?, 0, 0, ?, ?)");

            // Ora ho i gruppi del prossimo round, che ripartono da 1
            $count = 1;
            $gruppo = 1;
            for ($i = 0; $i < count($topTeams); $i += 2) {
                
                if (isset($topTeams[$i+1])) {
                    $teamA = $topTeams[$i];
                    $teamB = $topTeams[$i+1];
                    
                    $insert->bind_param("ssisis", $teamA,$teamB,$codTorneo,$Anno,$nextRound,$gruppo); 
                    $insert->execute();
                    if($count == 2) {
                        $count = 0;
                        $gruppo++;
                    }
                    $count++;
                }
            }
            
        }

        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        $default = "Aggiornamento score fallita!";

        $codiciGestiti = [20020];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/tornei.php', $default);
    }
    success('../../front/tornei/tornei.php', 'Score aggiornato con successo!'); 
?>