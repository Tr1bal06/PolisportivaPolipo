<?php
    include '../connessione.php';
    include '../function.php';
    session_start();

    //controllo il ruolo
    $permessi =['user', 'admin'];
    if(!controllo($_SESSION['ruolo'], $permessi)){
        error("./front/404.php","Permesso negato!");
    }

    //salvo le variabili di sessione
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $codTorneo = $_POST['CodiceTorneo'];
        $Anno = $_POST['Anno'];
        $CF = $_SESSION['cf'];
    }

    //preparo la query e la eseguo
    $stmt=$conn->prepare("DELETE FROM PARTECIPAZIONE
                         WHERE CodiceTorneo = ? AND Anno = ? AND CFPartecipante = ?");
    $stmt->bind_param('sss', $codTorneo, $Anno, $CF);
    
    try{
        if ($stmt->execute()) {
            success('../../front/tornei/tornei.php', 'Eliminazione avvenuta con successo!');
        } else {
            error('../../front/tornei/tornei.php', 'Eliminazione fallita!');
        }

    }catch(Exception $e){
        error('../../front/tornei/tornei.php', 'Eliminazione fallita!');
    }
    //Verifico che la query abbia avuto successo
    
    
    $stmt->close();
    $conn->close();
    //made by Tha_Losco
?>