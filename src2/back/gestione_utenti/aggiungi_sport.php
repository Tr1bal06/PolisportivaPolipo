<?php
    /**
     * File: aggiungi_sport.php
     * Desc: Script per accettare o rifiutare la richiesta di aggiunta di uno sport ad un atleta o allenatore
     * Auth: Teox5
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
        $path = '../../front/persone/persone.php';
        $cod = htmlspecialchars($_POST['codice']);

        $tab = $reach == 'Atleta' ? 'RICHIESTE_ATL' : 'RICHIESTE_ALL';
        $tab1 = $reach == 'Atleta' ? 'ISCRIZIONE' : 'INSEGNA';
        $tab2 = $reach == 'Atleta' ? 'CodiceAtleta' : 'CodiceAllenatore';

        $sqlD= "DELETE FROM $tab WHERE Codice=? AND Sport=?";
        $conn->begin_transaction();
        try {
              
            $stmt2 = $conn->prepare($sqlD);
            $stmt2->bind_param("is", $cod, $sport);
            $stmt2->execute();
            

            if($stmt2->affected_rows === 0) {
                 throw new Exception("Eliminazione sport fallita!", 10052);
            }
            $stmt2->close();

            if($_POST['tipologia']=='Iscrizione'||$_POST['tipologia']=='Insegnamento'){            

                if($reach == 'Atleta') {
                    $tipo = htmlentities($_POST['livello']);
                    $stmt1 = $conn->prepare("INSERT INTO ISCRIZIONE (CodiceAtleta, NomeSport, Tipo) VALUES (?,?,?)");
                    $stmt1->bind_param("iss", $cod, $sport, $tipo);
                }

                else if($reach == 'Allenatore') {
                    $stmt1 = $conn->prepare("INSERT INTO INSEGNA (CodiceAllenatore, NomeSport) VALUES (?,?)");
                    $stmt1->bind_param("is", $cod, $sport);
                }
                 
            } else if($_POST['tipologia']=='Eliminazione'){
                
                $stmt1 = $conn->prepare("DELETE FROM $tab1 WHERE $tab2 = ? AND NomeSport = ?");
                $stmt1->bind_param("is", $cod, $sport);
            }
            $stmt1->execute();
            if($stmt1->affected_rows === 0) {
                    throw new Exception("Inserimento sport fallito!", 10051);
            }
            $stmt1->close();
            $conn->commit();

            } catch(Exception $e){
                var_dump($e->getMessage());
                die();
                $conn->rollback();
                $default = "Errore nell'operazione!";

                $codiciGestiti = [10051, 10052];

                if (in_array($e->getCode(), $codiciGestiti, true)) {
                    $default = $e->getMessage();
                }

                error($path, $default);
                }
        success($path, 'Operazione avvenuta con successo!');
    }
?>