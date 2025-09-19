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

    if($_SERVER['REQUEST_METHOD']=='POST'){
        
            $scoreCasa = htmlentities($_POST['scoreCasa']);
            $scoreOspite = htmlentities($_POST['scoreOspite']);
            $codTorneo = htmlentities($_POST['CodiceTorneo']);
            $Anno = htmlentities($_POST['Anno']);
            $casa = htmlentities($_POST['casa']);
            $ospite = htmlentities($_POST['ospite']);
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

        $insert = $conn->prepare("UPDATE PARTITA_TORNEO (ScoreCasa, ScoreOspite, is_updated) VALUES (?, ? , 1)");
        $insert->bind_param("ii", $scoreCasa, $scoreOspite);
        $insert->execute();

        //manca il controllo degli is_updated , controlla tutto per EdizioneTorneo = ? AND AnnoTorneo = ?
        //tutte le partite precedenti perforza saranno a 1 senno siamo all'inizio , check if all == 1
        // query --> is_updated , gruppo , round++ 

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
        if($allUpdated) {
            // Raggruppo per Gruppo
            $gruppi = [];
            foreach ($rows as $r) {
                $gruppi[$r['Gruppo']][] = $r;
            }
            // Per ogni gruppo trovo il Round più alto e il vincitore
            $winners = [];

            foreach ($gruppi as $gruppo => $partite) {
                // Ordino le partite del gruppo per Round discendente
                usort($partite, function($a, $b) {
                    return $b['Round'] <=> $a['Round'];
                });

                // Prendo la partita col Round più alto
                $match = $partite[0];

                // Determino il vincitore
                if ($match['ScoreCasa'] > $match['ScoreOspite']) {
                    $winner = $match['SquadraCasa'];
                } elseif ($match['ScoreCasa'] < $match['ScoreOspite']) {
                    $winner = $match['SquadraOspite'];
                } else {
                    $winner = null; // pareggio, da gestire come preferisci
                }

                if ($winner !== null) {
                    $winners[$gruppo] = [
                        'Round'   => $match['Round'],
                        'Gruppo'  => $gruppo,
                        'Vincitore' => $winner
                    ];
                }
            }//da capire
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
        $default = "Aggiornamento score fallito fallita!";

        $codiciGestiti = [20010];

        if (in_array($e->getCode(), $codiciGestiti, true)) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/tornei.php', $default);
    }
    success('../../front/tornei/tornei.php', 'Score aggiornato con successo!'); 
?>