<?php
    /**
     * File: aggiungi_sport.php
     * Auth: Teox5
     * Desc: Script per l'aggiunta o l'eliminazione di uno sport ad un atleta o allenatore
     */
    
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
    
        $tab = $reach == 'Atleta' ? 'RICHIESTE_ATL' : 'RICHIESTE_ALL';
        $tab2 = $reach == 'Atleta' ? 'ISCRIZIONE' : 'INSEGNA';
        $tab3 = $reach == 'Atleta' ? 'CodiceAtleta' : 'CodiceAllenatore';

        $sqlD= "DELETE FROM $tab2 WHERE $tab3=? AND NomeSport=?";
        $sqlI= "INSERT INTO $tab2($tab3, NomeSport) VALUES (?, ?)";
        $sqlU= "UPDATE $tab SET Stato='Confermato' WHERE CodicePersona=? AND Sport=?";
        
            if($_POST['tipologia']=='Eliminazione'){
                //TODO bisogna fare la logica per eliminare lo sport
                $conn->begin_transaction();
                try {

                    $stmt1 = $conn->prepare($sqlD);
                    $stmt1->bind_param("is",$cod , $sport);
                    $stmt1->execute();

                    $stmt2 = $conn->prepare($sqlU);
                    $stmt2->bind_param("is",$cod , $sport);
                    $stmt2->execute();

                    if($stmt1->affected_rows === 0 || $stmt2->affected_rows === 0) {
                        throw new Exception("Eliminazione sport fallita!", 10051);
                    }

                    $conn->commit();
                } catch (Exception $e) {
                    $conn->rollback();
                    //error("../../front/prenotanti/prenotazione_form.php", "Modifica fallita!");
                }
            }else if($_POST['tipologia']=='Inserimento'){
                
                $conn->begin_transaction();
                try {

                    $stmt1 = $conn->prepare($sqlI);
                    $stmt1->bind_param("is",$codAtleta , $sport);
                    $stmt1->execute();

                    $stmt2 = $conn->prepare($sqlU);
                    $stmt2->bind_param("is",$codAtleta , $sport);
                    $stmt2->execute();

                    if($stmt1->affected_rows === 0 || $stmt2->affected_rows === 0) {
                        throw new Exception("Eliminazione sport fallita!", 10051);
                    }

                    $conn->commit();
                } catch (Exception $e) {
                    $conn->rollback();
                    //error("../../front/prenotanti/prenotazione_form.php", "Modifica fallita!");
            }
        }
        success("../../front/persone/persone.php", "Modifica avvenuta con successo.");
    }
?>