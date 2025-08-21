<?php

    include '../connessione.php';
    include '../function.php';
    session_start();

    $permessi = ['admin','Medico'];
 
    
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try{
            // Recupera il giorno selezionato
            $giorno = htmlentities($_POST['giorni']);
            $giorniArray = explode(',', $giorno);
            $codiciCariche = $_SESSION['caricheCodici'];
            $codiceMedico = $_SESSION['caricheCodici']['Medico'];


            $stmt1 = $conn->prepare("SELECT GiornoSettimanale
                                    FROM DISPONIBILITA
                                    WHERE CodiceMedico = ?");
            $stmt1->bind_param("i", $codiceMedico);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $mieDisponibilita = [];

            if ($result->num_rows > 0) {
                $mieDisponibilita = $result->fetch_all(MYSQLI_ASSOC);
            }


            // Prima creiamo due array "puliti" per lavorare meglio
            $giorniDB = array_map(function($riga) {
                return $riga['GiornoSettimanale'];
            }, $mieDisponibilita);

            // Ora gestiamo aggiunte e cancellazioni
            foreach ($giorniArray as $giornoNuovo) {
                if (!in_array($giornoNuovo, $giorniDB)) {
                    // Se il nuovo giorno non è già nel DB, lo inseriamo
                    $stmtInserisci = $conn->prepare("INSERT INTO DISPONIBILITA (CodiceMedico, GiornoSettimanale) VALUES (?, ?)");
                    $stmtInserisci->bind_param("is", $codiceMedico, $giornoNuovo);
                    $stmtInserisci->execute();
                }
            }

            // Ora vediamo se ci sono giorni da cancellare
            foreach ($giorniDB as $giornoPresenteDB) {
                if (!in_array($giornoPresenteDB, $giorniArray)) {
                    // Se il giorno nel DB non è più nei nuovi, lo eliminiamo
                    $stmtElimina = $conn->prepare("DELETE FROM DISPONIBILITA WHERE CodiceMedico = ? AND GiornoSettimanale = ?");
                    $stmtElimina->bind_param("is", $codiceMedico, $giornoPresenteDB);
                    $stmtElimina->execute();
                }
            }
            success('../../front/disponibilita/disponibilita_medico.php', 'Disponibilità registrata con successo!');
        }catch(Exception $e){
            error('../../front/disponibilita/disponibilita_medico.php', 'Errore nel inserimento!');
        }
       
    }
?>