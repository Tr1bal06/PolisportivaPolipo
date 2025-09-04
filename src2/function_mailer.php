<?php

$baseDir = __DIR__; // cartella di questo file
require $baseDir . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
use Dotenv\Dotenv;

// Avvia sessione se non esiste già
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carico variabili dal file .env
$dotenv = Dotenv::createImmutable($baseDir);
$dotenv->load();

/**
 * Funzione per l'invio della mail con PHPMailer + OAuth2
 */
function inviaMail($partecipanti, $titolo, $body) {
    $mail = new PHPMailer(true);

    try {
        // Configurazione SMTP base
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->Port       = $_ENV['SMTP_PORT'];
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
        $mail->SMTPAuth   = true;
        $mail->AuthType   = 'XOAUTH2';

        // Provider Google OAuth2
        $provider = new Google([
            'clientId'     => $_ENV['OAUTH_CLIENT_ID'],
            'clientSecret' => $_ENV['OAUTH_CLIENT_SECRET'],
        ]);

        // Configurazione OAuth
        $mail->setOAuth(new OAuth([
            'provider'     => $provider,
            'clientId'     => $_ENV['OAUTH_CLIENT_ID'],
            'clientSecret' => $_ENV['OAUTH_CLIENT_SECRET'],
            'refreshToken' => $_ENV['OAUTH_REFRESH_TOKEN'],
            'userName'     => $_ENV['SMTP_EMAIL'], // l’account Gmail
        ]));

        // Mittente
        $mail->setFrom($_ENV['SMTP_EMAIL'], $_ENV['SMTP_FROM_NAME']);

        // Aggiungo i destinatari
        foreach ($partecipanti as $email) {
            $mail->addAddress($email);
        }

        // Contenuto
        $mail->isHTML(true);
        $mail->Subject = $titolo;
        $mail->Body    = $body;

        // DEBUG opzionale
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';

        $mail->send();

    } catch (Exception $e) {
        echo "Errore durante l'invio: {$mail->ErrorInfo}";
    }
}
