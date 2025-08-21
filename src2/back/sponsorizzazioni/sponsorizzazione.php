<?php
    //
    include '../connessione.php';
    include '../function.php';
    session_start();
    $permessi = ['Sponsor', 'admin'];
    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    //Acquisisco i dati dal form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try{

            $CodiceTorneo = intval($_POST['CodiceTorneo']);
            $Anno = htmlspecialchars($_POST['Anno']);
            $CodiceSponsor = $_SESSION["caricheCodici"]['Sponsor'];

            $stmt = $conn->prepare("INSERT INTO SPONSORIZZAZIONE(CodiceTorneo, Anno, CodiceSponsor) 
                        VALUES ( ?, ?, ?);");
            $stmt->bind_param("iss", $CodiceTorneo, $Anno, $CodiceSponsor);

            if ( $stmt->execute()   && ($stmt->affected_rows === 1) ) {
                success("../../front/sponsor/sponsorizzazioni.php", "sponsorizzazione inserita correttamente");
            } else {
                error("../../front/sponsor/sponsorizzazioni.php", "errore nella sponsorizzazione");
            }
            $stmt->close();
            $conn->close();

        }catch(Exception $e){
            error("../../front/sponsor/sponsorizzazioni.php", "errore nella sponsorizzazione");
        }
    }   


    //made by Tha_Losco
?>