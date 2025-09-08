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
       
        $check = $conn->prepare("SELECT Nome
                                 FROM SQUADRA
                                 WHERE Nome = ?");
        $check->bind_param("s",$nomeSquadra);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            throw new Exception("Il nome della squadra esiste già!", 1001);
        }

        $stmt=$conn->prepare("INSERT INTO SQUADRA(Nome,Logo,Allenatore,Sport)
                              VALUES (?,?,?,?)");
        $stmt->bind_param("ssis",$nomeSquadra,$logo,$codiceAllenatore,$sport);
        $stmt->execute();
        foreach($partecipanti as $persona) {
            //da terminare
        }
        $conn->commit();
    }catch(Exception $e){
        $conn->rollback();

        $default = "Creazione della squadra fallita!";

        if($e->getCode() === 1001) {
            $default = $e->getMessage();
        }

        error('../../front/tornei/squadre.php', $default);
    }
    success('../../front/tornei/squadre.php', 'Creazione della squadra avvenuta con successo!');
    
?>