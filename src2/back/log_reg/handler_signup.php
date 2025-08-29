<?php

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Salvo le variabili che mi interessano
        $CF = htmlentities($_POST['cf']);
        $nome = htmlentities($_POST['nome']);
        $cognome = htmlentities($_POST['cognome']);
        $mail = htmlentities($_POST['email']);
        $numTel = htmlentities($_POST['telefono']);
        $primapass = htmlentities($_POST['password1']);
        $secondapass = htmlentities($_POST['password2']);

        if(strlen($CF) != 16) {
            error('../../front/signup.php', 'Codice fiscale incoretto!');
        }

        if(strlen($numTel) != 10) {
            error('../../front/signup.php', 'Numero di telefono incoretto!');
        }

        if(!isset($CF) || !isset($nome) || !isset($cognome) || !isset($mail) || !isset($numTel) || !isset($primapass) || !isset($secondapass) ) {
            error('../../front/signup.php', 'Compilare tutti i campi grazie!');
        }

        if(strlen($primapass) < 6) {
            error('../../front/signup.php', 'Password troppo corta, lunghezza minima:6');
        }

        if($primapass != $secondapass) {
            error('../../front/signup.php', 'Password diverse!');
        } else {
            $pass = $primapass;
        }

    }
    else {
        error('../../front/404.php', 'Metodo errato');
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

        //terza insert in UTENTE
        $stmt3 = $conn->prepare("INSERT INTO TELEFONO VALUES (?,?)");
        $stmt3->bind_param("ss",$numTel, $CF); 
        $stmt3->execute();

        $conn->commit();

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();

        error('../../front/signup.php', 'Registrazione fallita!');
    }
    
    header('location: ../../front/login.php');
    exit();

    // fatto da jin
    // Un utente appena registrato non possiede nessuna carica, essa verrà assegnata dall'admin
?>
