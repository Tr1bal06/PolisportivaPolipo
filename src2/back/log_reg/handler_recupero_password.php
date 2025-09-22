<?php
    /** 
     * File: handler_recupero_password.php
     * Auth: Jin
     * Desc: file per la creazione dell'OTP
    */
    include '../connessione.php';
    include '../function.php';
    include '../../function_mailer.php';
    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Salvo le variabili che mi interessano
        $mail = htmlentities($_POST['email']);
        $_SESSION['mail-recupero'] = $mail;
    }
    else {
        error('../../front/404.php', 'Metodo errato');
    }

    //Controllo se la mail sia prensente nel database
    $stmt = $conn->prepare("SELECT Email
                             FROM UTENTE
                             WHERE Email = ?");
    $stmt->bind_param("s",$mail); 
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows ===0) {
        error('../../front/recover.php','Email non presente!');
    }

    $stmt1 = $conn->prepare("SELECT *
                             FROM RECUPERO
                             WHERE email = ?");
    $stmt1->bind_param("s",$mail); 
    $stmt1->execute();
    $result = $stmt1->get_result();

    if($result->num_rows >0) {
        error('../../front/recover.php','Email già presente!');
    }

    $otp = rand(100000 , 900000)+ rand(10,1000)-rand(10,100);
    $to[] = $mail;

    $titolo = "Recupero della password ";
    $contenuto = 'Ciao, inserire questa OTP: '. $otp .' per il recupero della password';

    
    $conn->begin_transaction();
    try{
        inviaMail($to, $titolo, $contenuto);
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        
        error('../../front/recover.php','mail inesistente!');
        
    }   
    $hash = password_hash($otp, PASSWORD_DEFAULT);

    //Insert in recupero
    $stmt2 = $conn->prepare("INSERT INTO RECUPERO VALUES (?,?)");
    $stmt2->bind_param("ss",$mail,$hash); 
    if($stmt2->execute()) {
        header("location: ../../front/recovered.php");
        exit();
    }else {
        error('../../front/recover.php','Recupero fallito!');
    }

        
?>