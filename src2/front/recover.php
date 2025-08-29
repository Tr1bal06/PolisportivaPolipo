<? if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
// $_SESSION['error_message'] = "Si è verificato un errore!"; // Per test
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
$_SESSION['error_message'] = null;
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recupero Password</title>
    <style>
        /* Stili di base */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            /* Gradienti basati sulla palette con #4c5c96 */
            background: linear-gradient(135deg, #4c5c96, #3a4a7d);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 450px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        h1 {
            margin-bottom: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="email"] {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            padding: 0.75rem;
            margin: 0.5rem 0;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        /* Bottone Invia */
        button.send {
            background-color: #3a4a7d;
            color: #fff;
        }

        button.send:hover {
            background-color: #2e3a63;
        }

        /* Bottone Torna al Login */
        button.home {
            background-color: #5a6fa5;
            color: #fff;
        }

        button.home:hover {
            background-color: #4c5c96;
        }

        /* Media query per desktop */
        @media (max-width: 992px) {
            .container {
                max-width: 500px;
                padding: 3rem;
            }

            input,
            button {
                font-size: 1.2rem;
            }
        }

        /* Media query per tablet */
        @media (max-width: 870px) {
            .container {
                max-width: 300px;
                padding: 2.5rem;
            }

            .logo {
                top: -275px !important;
                left: 80px !important;
                width: 220px !important;
            }

            .logoReverse {
                display: none;
            }

            input,
            button {
                font-size: 1.1rem;
            }

            /* Nascondiamo le immagini su mobile */
            .side-image {
                display: none;
            }
        }

        /* Media query per schermi molto piccoli: 
       cambia la larghezza del container dal 90% all'80% */
        @media (max-width: 500px) {
            .container {
                width: 80%;
            }
        }

        @media (max-width: 375px) {
            .logo {
                position: absolute !important;
                top: -200px !important;
                left: 112px !important;
                width: 150px !important;
            }

            h1 {
                font-size: 30px;
            }
        }

        .logo {
            position: absolute;
            top: -200px;
            left: -150px;
            width: 220px;

        }

        .logoReverse {
            position: absolute;
            top: -200px;
            right: -150px;
            width: 220px;
        }

        /* Effetto onda */
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 0;
        }

        .wave svg {
            position: relative;
            display: block;
            width: calc(200% + 1.3px);
            height: 100px;
        }

        .wave .shape-fill {
            fill: #fff;
            /* colore della wave: contrasto con il blu */
            opacity: 0.2;
        }

        /* Posizionamento delle immagini laterali */
        .side-image {
            position: absolute;
            top: 50px;
            z-index: 0;
            width: 25%;
        }

        .left-image {
            left: 60px;
        }

        .right-image {
            right: 60px;
        }
    </style>
</head>

<body>
    <div>
        <h1>Recupera il tuo Polipo!</h1>
    </div>

    <!-- Immagini a sinistra e destra sopra il container -->



    <div class="container">
        <img src="../assets/polipoDetective.png" class="logo" alt="Left Image">
        <img src="../assets/polipoDetectiveReverse.png" class="logoReverse" alt="Right Image">
        <h2>Inserisci la tua email per il recupero della password</h2>
        <form action="../back/log_reg/handler_recupero_password.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit" class="send">Invia Richiesta</button>
        </form>
        <div class="bottoni">
            <button class="home" onclick="window.location.href='login.php'">Torna al Login</button>
        </div>
        <? if (isset($errorMessage)) { ?>
            <h3 style="color:red;"><?= $errorMessage ?></h3>
            <style>
                .container {
                    padding-bottom: 3px !important;
                }
            </style>
        <? } ?>
    </div>

    <!-- Effetto Wave nello sfondo -->
    <div class="wave">
        <svg viewBox="0 0 1200 100" preserveAspectRatio="none">
            <path d="M0,0 C300,100 900,-50 1200,0 L1200,100 L0,100 Z" class="shape-fill"></path>
        </svg>
    </div>
</body>

</html>