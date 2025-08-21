<?php

    include '../connessione.php';
    include '../function.php';
    session_start();

    $permessi = ['admin','Atleta'];
    
    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try{
                // Recupera il giorno selezionato
            $livello = htmlentities($_POST['livello']);
            $sport = htmlentities($_POST['sport']);
            $codiceAtleta = $_SESSION['caricheCodici']['Atleta'];
            

            $stmt1 = $conn->prepare("SELECT NomeSport
                                    FROM ISCRIZIONE
                                    WHERE CodiceAtleta = ?");
            $stmt1->bind_param("i", $codiceAtleta);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $port = $result->fetch_all(MYSQLI_ASSOC);

            foreach($port as $value) {
                if($value['NomeSport'] == $sport) {
                    error('../../front/atleti/atleta.php', 'Sport già praticato!');
                }
            }

            $stmt = $conn->prepare("INSERT INTO ISCRIZIONE VALUES (?,?,?)");
            $stmt->bind_param("iss",$codiceAtleta,$sport,$livello); 
            if($stmt->execute()) {
                success('../../front/atleti/atleta.php', 'Iscrizione avvenuta con successo!');
            }else {
                error('../../front/atleti/atleta.php', 'Iscrizione fallita!');
            }
        }catch(Exception $e){
            error('../../front/atleti/atleta.php', 'Iscrizione fallita!');
        }
        
    }
?>