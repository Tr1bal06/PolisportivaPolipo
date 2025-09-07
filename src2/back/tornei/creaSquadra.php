<?php

    /**
     * File: creaSquadra
     * Auth: Jin
     * 
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
    try{
        if($_SERVER['REQUEST_METHOD']=='POST'){
        
            $nomeSquadra = htmlentities($_POST['nome_squadra']);
            $logo = htmlentities($_POST['logo']);
            $partecipanti = htmlentities($_POST['email_atleta']);
            $sport = htmlentities($_POST['sport']);
            $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];
        }
        //mettere un controllo se la squadra esiste già
        $stmt=$conn->prepare("INSERT INTO SQUADRA(Nome,Logo,Allenatore,Sport)
                              VALUES (?,?,?,?)");
        $stmt->bind_param("ssis",$nomeSquadra,$logo,$codiceAllenatore,$sport);
        $stmt->execute();
        //manca il ciclo per l'aggiunta alla squadra con attesa?
        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();
        error('../../front/tornei/squadre.php', 'Creazione della squadra fallita!');
    }
    success('../../front/tornei/squadre.php', 'Creazione della squadra avvenuta con successo!');
    
?>