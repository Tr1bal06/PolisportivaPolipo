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
        $check = $conn->prepare("SELECT is_updated
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

        $update = $conn->prepare("UPDATE PARTITA_TORNEO
                                  SET ScoreCasa = ?, ScoreOspite = ?, is_updated = 1
                                  WHERE EdizioneTorneo = ? AND AnnoTorneo = ? AND SquadraCasa = ? AND SquadraOspite = ?");
        $update->bind_param("iiisss", $scoreCasa, $scoreOspite, $codTorneo, $Anno, $casa, $ospite);
        $update->execute();


        $stmt = $conn->prepare("SELECT is_updated , Round , Gruppo , SquadraCasa , SquadraOspite , ScoreCasa, ScoreOspite
                                 FROM PARTITA_TORNEO
                                 WHERE EdizioneTorneo = ? AND AnnoTorneo = ?");
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

            // Trovo i vincitori per ogni gruppo e round massimo
            $winners = [];
            foreach ($gruppi as $gruppo => $partite) {
                // Prendo tutte le partite dell'ultimo round
                $maxRound = max(array_column($partite, 'Round'));
                $ultimoRound = array_filter($partite, fn($p) => $p['Round'] == $maxRound);

                foreach ($ultimoRound as $match) {
                    if ($match['ScoreCasa'] > $match['ScoreOspite']) {
                        $winner = $match['SquadraCasa'];
                    } elseif ($match['ScoreCasa'] < $match['ScoreOspite']) {
                        $winner = $match['SquadraOspite'];
                    } else {
                        $winner = $match['SquadraCasa']; // pareggio -> passa Casa
                    }
                    $winners[$gruppo][] = $winner;
                }
            }

            // Ora genero il nuovo round
            $nextRound = $maxRound + 1;
            $insert = $conn->prepare("INSERT INTO PARTITA_TORNEO (SquadraCasa, SquadraOspite, EdizioneTorneo, AnnoTorneo, ScoreCasa, ScoreOspite, Round, Gruppo) VALUES (?, ?, ?, ?, 0, 0, ?, ?)");

            foreach ($winners as $gruppo => $squadre) {
                for ($i = 0; $i < count($squadre); $i += 2) {
                    if (isset($squadre[$i+1])) {
                        $insert->bind_param("ssisis",$squadre[$i], $squadre[$i+1], $codTorneo, $Anno, $nextRound, $gruppo);
                        $insert->execute();
                    }
                }
            }
        }

        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        $default = "Aggiornamento score fallito fallita!";

        $codiciGestiti = [20020];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/tornei.php', $default);
    }
    success('../../front/tornei/tornei.php', 'Score aggiornato con successo!'); 
?>