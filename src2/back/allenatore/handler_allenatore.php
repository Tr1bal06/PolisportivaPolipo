<?php
    include '../connessione.php';
    include '../function.php';
    session_start();
    
    //controllo l'utente sia autorizzato ad eseguire le seguenti operazioni
    $permessi = ['admin','Allenatore'];
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }
    //controllo di aver ricevuto una richiesta post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //inizio la transazione
        try{
                // Recupera il giorno selezionato
            $sport = htmlentities($_POST['sport']);
            $codiciCariche = $_SESSION['caricheCodici'];
            $codiceAllenatore = $_SESSION['caricheCodici']['Allenatore'];

            $stmt1 = $conn->prepare("SELECT NomeSport
                                    FROM INSEGNA
                                    WHERE CodiceAllenatore = ?");
            $stmt1->bind_param("i", $codiceAllenatore);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $port = $result->fetch_all(MYSQLI_ASSOC);

            foreach($port as $value) {
                if($value['NomeSport'] == $sport) {
                    error('../../front/allenatore/allenatore.php', 'Sport già presente!');
                }
            }

            $stmt = $conn->prepare("INSERT INTO INSEGNA VALUES (?,?)");
            $stmt->bind_param("is",$codiceAllenatore,$sport); 
            if($stmt->execute()) {
                success('../../front/allenatore/allenatore.php', 'Registrazione dello sport insegnato completata');
            }else {
                error('../../front/allenatore/allenatore.php', 'Registrazione dello sport insegnato fallito!');
            }
        }catch(Exception $e){
            error('../../front/allenatore/allenatore.php', 'Registrazione dello sport insegnato fallito!');
        }
        
    }
?>