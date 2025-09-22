<?php
    /** 
     * File: handler_controllo_password.php
     * Auth: Jin
     * Desc: File che controlla l'otp per il cambio password
    */
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $otp = htmlentities($_POST['OTP']); 
        $mail = $_SESSION['mail-recupero'];
        $primapass = htmlentities($_POST['password1']);
        $secondapass = htmlentities($_POST['password2']);

        if(strlen($primapass) < 6) {
            error('../../front/recovered.php', 'Password troppo corta, lunghezza minima:6');
        }

        if($primapass != $secondapass) {
            error('../../front/recovered.php', 'Password diverse!');
        }else {
            $pass = $primapass;
        }

    }
    else {
        header("location: ../../front/404.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT *
                             FROM RECUPERO
                             WHERE email = ?");
    $stmt->bind_param("s",$mail); 
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows ===0) {
        error('../../front/recovered.php','OTP non presente!');
    }else {
        $row = $result->fetch_assoc();
        $prova = $row['otp'];
    }

    if(password_verify($otp, $prova)) {

        $conn->begin_transaction();//Rendo atomico le query 

        try {

            $hash = password_hash($pass, PASSWORD_DEFAULT);

            //aggiorna la password
            $stmt1 = $conn->prepare("UPDATE UTENTE SET Password = ? WHERE Email = ?");
            $stmt1->bind_param("ss",$hash,$mail); 
            $stmt1->execute();
    
            //elimina il record in recupero
            $stmt2 = $conn->prepare("DELETE FROM RECUPERO WHERE email = ?");
            $stmt2->bind_param("s",$mail); 
            $stmt2->execute();
    
            $conn->commit();
    
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            error('../../front/recovered.php', 'Recupero password fallita!');
        }
        success('../../front/login.php', 'Recupero password avvenuta con successo!');
    }else {
        error('../../front/recovered.php','OTP sbagliato!');
    }
?>