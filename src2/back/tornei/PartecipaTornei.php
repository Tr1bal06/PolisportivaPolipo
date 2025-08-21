<?php
    include '../connessione.php';
    include '../function.php';
    session_start();
    //controllo i permessi
    
    $permessi  =['user', 'admin'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)){
        error('../../front/404.php', 'Accesso negato');
    }

    try{
        if($_SERVER['REQUEST_METHOD']=='POST'){
        
            $codTorneo = $_POST['CodiceTorneo'];
            $Anno = $_POST['Anno'];
            $CF = $_SESSION['cf'];
        }
        
        //utilizzo una query preparata per evitare sql injection
        $stmt=$conn->prepare("INSERT INTO PARTECIPAZIONE(CodiceTorneo, Anno, CFPartecipante)
                              VALUES (?,?,?)");
        $stmt->bind_param("iss",$codTorneo,$Anno,$CF);
    
        //controllo il risultato
        if (($stmt->execute())) {
            success('../../front/tornei/tornei.php', 'Iscrizione al torneo avvenuta con successo!');
        } else {
            
            error('../../front/tornei/tornei.php', 'Iscrizione al torneo fallita!');
        }
        
        $stmt->close();
        $conn->close();
    }catch(Exception $e){
        error('../../front/tornei/tornei.php', 'Iscrizione al torneo fallita!');
    }
    //salvo le variabili di sessione
  
    //made by Tha_Losco
?>