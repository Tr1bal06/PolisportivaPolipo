<?php
    
    $baseDir = __DIR__; // __DIR__ punta sempre alla cartella di questo file

    require $baseDir . '/vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use Dotenv\Dotenv;

    // Avvia la sessione subito, prima di qualsiasi output
    if (session_status() === PHP_SESSION_NONE) {
        if (session_status() == PHP_SESSION_NONE) {
            // Avvia la sessione
            session_start();
        }    
    }
    

    // Carico variabili dal file .env
    $dotenv = Dotenv::createImmutable($baseDir);
    $dotenv->load();
    /**
     * Funzione per l'invio della mail
     * Da usare in un try
     */
    function inviaMail($partecipanti, $titolo , $body) {
        $mail = new PHPMailer(true);

        try {
            // Configurazione SMTP
            $mail->SMTPDebug = 4; 
            $mail->AuthType = 'XOAUTH2';
            $mail->Debugoutput = 'error_log';
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['SMTP_FROM_NAME']);//mittente

            // Aggiungo i destinatari (array di email)
            foreach ($partecipanti as $email) {
                $mail->addAddress($email);
            }

            // Contenuto della mail
            $mail->isHTML(true);
            $mail->Subject = $titolo;
            $mail->Body    = $body;

            $mail->send();
            
        } catch (Exception $e) {
            echo "Errore durante l'invio: {$mail->ErrorInfo}";
        }
        exit;
    }
?>