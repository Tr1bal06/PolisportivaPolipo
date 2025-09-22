<?php 
    /** 
     * File: modifica_persona.php
     * Auth: Jin
     * Desc: modifica l'anagrafica dell'utente
    */
    include "../connessione.php";
    include '../function.php';

    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
                
    $permessi = ['user'];

    if(!controllo($_SESSION['ruolo'], $permessi)) { 
        error('../../front/404.php', 'Accesso negato');
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $CF = htmlentities($_POST['cf']);
        $nome = htmlentities($_POST['nome']);
        $cognome = htmlentities($_POST['cognome']); 
        $email = htmlentities($_POST['email']);
        $telefono = htmlentities($_POST['telefono']);
        $path = htmlentities($_POST['path']);

        $stmt1 = $conn->prepare("SELECT P.Nome , P.Cognome , U.Email , T.Numero
                                 FROM PERSONA P JOIN UTENTE U ON P.CF = U.Persona
                                      JOIN TELEFONO T ON P.CF = T.Persona
                                 WHERE P.CF = ?");
        $stmt1->bind_param("s", $CF);
        $stmt1->execute();
        $result = $stmt1->get_result();
        $valori_vecchi = $result->fetch_all(MYSQLI_ASSOC);

        if ($valori_vecchi) {

            $datiDiversi = false;
    
            if ($valori_vecchi[0]['Nome'] !== $nome ||
                $valori_vecchi[0]['Cognome'] !== $cognome ||
                $valori_vecchi[0]['Email'] !== $email ||
                $valori_vecchi[0]['Numero'] !== $telefono) {
                $datiDiversi = true;
            }
    
            if ($datiDiversi) {
                // Inizia la transazione
                $conn->begin_transaction();
    
                try {
                    $stmt2 = $conn->prepare("UPDATE PERSONA SET Nome = ?, Cognome = ? WHERE CF = ?");
                    $stmt2->bind_param("sss", $nome, $cognome, $CF);
                    $stmt2->execute();
    
                    $stmt3 = $conn->prepare("UPDATE UTENTE SET Email = ? WHERE Persona = ?");
                    $stmt3->bind_param("ss", $email, $CF);
                    $stmt3->execute();
    
                    $stmt4 = $conn->prepare("UPDATE TELEFONO SET Numero = ? WHERE Persona = ?");
                    $stmt4->bind_param("ss", $telefono, $CF);
                    $stmt4->execute();
                    
                    // Conferma la transazione
                    $conn->commit();
    
                } catch (Exception $e) {
                    // Se qualcosa va storto, annulla la transazione
                    $conn->rollback();
                    error($path, 'Modifica fallita!');
                }
                if($path == '../../front/persone/utente.php') {
                    $_SESSION['nome'] = $nome;
                    $_SESSION['cognome'] = $cognome;
                    $_SESSION['email'] = $email;
                    $_SESSION['telefono'] = $telefono;
                }
                success($path, 'Modifica avvenuta con successo!');
            }
            success($path, 'Modifica avvenuta con successo!');
        } else {
            error($path, 'Errore nei dati forniti!');
        }
    } else {
        error('../../front/404.php', 'Metodo errato');
    }
?>