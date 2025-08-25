<?php
    include '../connessione.php';
    include '../function.php';
    session_start();
    //controllo i permessi
    
    $permessi  =['Allenatore', 'admin'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)){
        error('../../front/404.php', 'Accesso negato');
    }
    
?>