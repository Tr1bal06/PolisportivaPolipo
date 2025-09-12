<?php
    
    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin', 'segreteria'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sport =  htmlentities($_POST['sport']);
        $reach = htmlentities($_POST['source']);
        $codiciCariche = $_SESSION['caricheCodici'];
        $path = '../../front/persone/persone.php';
        $cod = htmlspecialchars($_POST['codice']);

        die();
        if($reach == 'Atleta') {
            if($_POST['tipologia']=='Eliminazione'){
                //TODO bisogna fare la logica per eliminare lo sport


            }else if($_POST['tipologia']=='Inserimento'){
                
                $stmt1 = $conn->prepare("INSERT INTO ISCRIZIONE(CodiceAtleta, NomeSport) VALUES (?, ?)");
                $stmt1->bind_param("is",$codAtleta , $sport);

                //TODO bisogna cambiare lo stato della richiesta accettata TRANSAZIONE
                $sql = "UPDATE RICHIESTE_ATL SET Stato='Confermato' WHERE CodicePersona=? AND Sport=?";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bind_param("is", $cod, $sport);
                $stmt2->execute();

                if($stmt2->affected_rows === 0) {
                    error($path, 'Errore nel cambiare stato della richiesta!');
                }

            }else{
                error($path, 'Errore nel inserimento sport!');
            }
                
            
        } else if($reach == 'Allenatore') {
            if($_POST['tipologia']=='Eliminazione'){
                //TODO bisogna fare la logica per eliminare lo sport
            }else if($_POST['tipologia']=='Inserimento'){
                $stmt1 = $conn->prepare("INSERT INTO INSEGNA(CodiceAllenatore, NomeSport) VALUES (?,?)");
                $stmt1->bind_param("is",$cod, $sport); 
                
                //TODO bisogna cambiare lo stato della richiesta accettata TRANSAZIONE

            }else{
                error($path, 'Errore nel inserimento sport!');
            }
            
        }else {
            error($path, 'Inserimento sport fallito!');
        }
        $stmt1->execute();
        if($stmt1->affected_rows === 0) {
            error($path, 'Inserimento sport fallito!');
        } else {
            success($path, 'Inserimento sport avvenuto con successo!');
        }     
    }
?>