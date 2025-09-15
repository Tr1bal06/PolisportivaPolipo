<?php
 /*
        file: rifiuta_richiesta.php
        desc: Elimina l'insegnamento o la pratica di un Atleta o Allenatore
        auth: Teox5
    */

    include '../connessione.php';
    include '../function.php';
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

    $permessi = ['admin','Allenatore','Atleta'];

    if(!controllo($_SESSION['ruolo'], $permessi)) {
        error('../../front/404.php', 'Accesso negato');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $path = '../../front/persone/utente.php';
        $sport =  htmlentities($_POST['sport']);
        $reach = htmlentities($_POST['source']);
        $mot = htmlentities($_POST['motivazione']);

        $cod = $reach == 'Atleta' ? $_SESSION['caricheCodici']['Atleta'] : $_SESSION['caricheCodici']['Allenatore'];
        $tab2 = $reach == 'Atleta' ? 'ISCRIZIONE' : 'INSEGNA';
        $tab3 = $reach == 'Atleta' ? 'CodiceAtleta' : 'CodiceAllenatore';
        $tab = $reach == 'Atleta' ? 'RICHIESTE_ATL' : 'RICHIESTE_ALL';

        $sqlS="SELECT $tab3, NomeSport FROM $tab2 WHERE $tab3=? AND NomeSport=?";

        $stmt= $conn->prepare($sqlS);
        $stmt->bind_param("is", $cod, $sport);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows === 0) {
            error($path, 'Non puoi richiedere l\'eliminazione di uno sport che non pratichi/insegni!');
        }

        $sqlS1="SELECT Codice, Sport FROM $tab WHERE Codice=? AND Sport=? AND Stato='NonConfermato'";
        $stmt1= $conn->prepare($sqlS1);
        $stmt1->bind_param("is", $cod, $sport);
        $stmt1->execute();
        $result = $stmt1->get_result();
        $stmt1->close();

        if($result->num_rows >=1) {
            error($path, 'Richiesta già effettuata!');
        }
        
        if($reach == 'Atleta') {

                $tipo = htmlentities($_POST['livello']);

                $stmt2 = $conn->prepare("INSERT INTO RICHIESTE_ATL(Codice, Sport, TipoSport, Motivo, Stato, CodApprovante,Tipo)
                VALUES (?,?,?,?,'NonConfermato', NULL,'Eliminazione')"); 
                $stmt2->bind_param("isss",$cod, $sport, $tipo, $mot); 
                
        } else if($reach == 'Allenatore') {
            
            $stmt2 = $conn->prepare ("INSERT INTO RICHIESTE_ALL (Codice, Sport, Motivo, Stato, CodApprovante,Tipo)
            VALUES (?,?,?,'NonConfermato', NULL,'Eliminazione');"); 
            $stmt2->bind_param("iss",$cod, $sport, $mot); 
        }

        $stmt2->execute();
        if($stmt2->affected_rows === 0) {
            error($path, 'Richiesta eliminazione sport fallita!');
        } else {
            success($path, 'Richiesta eliminazione sport avvenuta con successo!');
        }
    }
?>