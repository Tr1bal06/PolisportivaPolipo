<?php

    include '../connessione.php';
    include '../function.php';
    session_start();

    $permessi = ['admin'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Salvo le variabili che mi interessano
        $CF = htmlentities($_POST['cf']);
        $nome = htmlentities($_POST['nome']);
        $cognome = htmlentities($_POST['cognome']);
        $mail = htmlentities($_POST['email']);
        $numTel = htmlentities($_POST['telefono']);
        $pass = htmlentities($_POST['password1']);

        if(strlen($CF) != 16) {
            error('../../front/persone/persone.php', 'Codice fiscale incoretto!');
        }

        if(strlen($numTel) != 10) {
            error('../../front/persone/persone.php', 'Numero di telefono incoretto!');
        }

        if(!isset($CF) || !isset($nome) || !isset($cognome) || !isset($mail) || !isset($numTel) || !isset($pass) ) {
            error('../../front/persone/persone.php', 'Compilare tutti i campi grazie!');
        }

        if(strlen($pass) < 6) {
            error('../../front/persone/persone.php', 'Password troppo corta, lunghezza minima:6');
        }

    }
    else {
        error('../../front/404.php', 'Metodo sbagliato');
    }
    //password hashata
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $conn->begin_transaction();//Rendo atomico le query 

    try {
        //primo insert in PERSONA
        $stmt1 = $conn->prepare("INSERT INTO PERSONA VALUES (?,?,?)");
        $stmt1->bind_param("sss",$CF,$nome, $cognome); 
        $stmt1->execute();

        //secondp insert in UTENTE
        $stmt2 = $conn->prepare("INSERT INTO UTENTE VALUES (?,?,?)");
        $stmt2->bind_param("sss",$mail,$hash, $CF); 
        $stmt2->execute();

        //terza insert in TELEFONO
        $stmt3 = $conn->prepare("INSERT INTO TELEFONO VALUES (?,?)");
        $stmt3->bind_param("ss",$numTel, $CF); 
        $stmt3->execute();

        $conn->commit();

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();

        error('../../front/persone/persone.php', 'Inserimento fallito!');
    }
    
    //header('../../front/persone/persone.php');
    //exit();
    success('../../front/persone/persone.php', 'Inserimento Avvenuto con successo!');
    // fatto da jin
    // Un utente appena registrato non possiede nessuna carica, essa verrà assegnata dall'admin
?>