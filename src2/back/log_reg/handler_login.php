<?php
    /** 
     * File: 
     * Auth: 
     * Desc: 
    */
    include "../connessione.php";
    include "../function.php";
    if(session_status() === PHP_SESSION_NONE) {
        if(session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
            session_start();
        }    
    }

    //Sanificazione input
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = htmlentities($_POST['email']);
        $pass = htmlentities($_POST['password']);

    } else {
        error('../../front/404.php', 'Accesso negato');
    }

    //Utilizzo una query preparata e la eseguo 
    $stmt = $conn->prepare("SELECT U.Password , P.Nome , P.Cognome , P.CF
                                FROM UTENTE U JOIN PERSONA P ON U.Persona = P.CF
                                WHERE U.Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $array_query = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if($result->num_rows == 0) {
        error("../../front/login.php", "email errata");
    }

    $ruolo = ['user'];
    //valorizzo le variabili e controllo la password
    $hash = $array_query[0]['Password'];
    
    if (!password_verify($pass, $hash)) {
        error("../../front/login.php", "password errata");
    } 
    
    $CF = $array_query[0]['CF'];
    $_SESSION['cf'] = $array_query[0]['CF'];
    $_SESSION['nome'] = $array_query[0]['Nome'];
    $_SESSION['cognome'] = $array_query[0]['Cognome'];

    $stmt = $conn->prepare("SELECT C.NomeCarica , N.CodiceCarica
                                FROM NOMINA N
                                    JOIN CARICA C on N.CodiceCarica=C.Codice
                                WHERE N.Persona = ?");
    $stmt->bind_param("s", $CF);
    $stmt->execute();
    $result = $stmt->get_result();
    $array_query = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($array_query as $carica) {
        $ruolo[] = $carica["NomeCarica"];
    }

    
    $index = array_search("Altro_Personale", $ruolo);

    if ($index !== false) {
        // cerco l’indice di Altro_Personale in array_query
        $idxQuery = array_search("Altro_Personale", array_column($array_query, "NomeCarica"));

        if ($idxQuery !== false) {
            $codiceTrovato = $array_query[$idxQuery]["CodiceCarica"];

        $sql = "SELECT AP.TipoCarica
                FROM ALTRO_PERSONALE AP
                WHERE Codice = $codiceTrovato";

        $result = $conn->query($sql);
        $altro_personale_nome_carica = $result->fetch_assoc();
        
            // sostituisco altro_personale col valore preso dal DB
            if ($altro_personale_nome_carica) {
        $ruolo[$index] = $altro_personale_nome_carica["TipoCarica"];
    }
        }
    }
  
     // Inizializza l'array associativo per le cariche
    $caricheCodici = [];

    // Popola l'array associativo con NomeCarica => CodiceCarica
    foreach ($array_query as $carica) {
        $caricheCodici[$carica["NomeCarica"]] = $carica["CodiceCarica"];
    }
    
    //Eseguo query per ottenere il codice Prenotante
    if (in_array('Socio', $ruolo) || in_array('Allenatore', $ruolo)) {
        $data_oggi = date('Y-m-d');
        if (in_array('Socio', $ruolo)) {
            $sql = "SELECT S.CodicePrenotante
                    FROM NOMINA N
                        JOIN CARICA C on N.CodiceCarica=C.Codice
                        JOIN SOCIO S on C.Codice=S.Codice
                    WHERE N.Persona = ? AND N.DataFine > ?";
        }
        if (in_array('Allenatore', $ruolo)) {
            $sql = "SELECT A.CodicePrenotante
                    FROM NOMINA N
                        JOIN CARICA C on N.CodiceCarica=C.Codice
                        JOIN ALLENATORE A on C.Codice=A.Codice
                    WHERE N.Persona = ? AND N.DataFine > ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $CF, $data_oggi );
        $stmt->execute();
        $result = $stmt->get_result();
        $array_query = $result->fetch_all(MYSQLI_ASSOC);

        $_SESSION['pren'] = $array_query[0]['CodicePrenotante'];
    }
    //Eseguo query per ottenere il codice Convocatore
    if (in_array('Socio', $ruolo) || in_array('Allenatore', $ruolo)||in_array('Consigliere', $ruolo)||in_array('Presidente', $ruolo)) {
        $data_oggi = date('Y-m-d');
        if (in_array('Socio', $ruolo)) {
            $sql = "SELECT S.CodiceConvocatore
                    FROM NOMINA N
                        JOIN CARICA C on N.CodiceCarica=C.Codice
                        JOIN SOCIO S on C.Codice=S.Codice
                    WHERE N.Persona = ? AND N.DataFine > ?";
        }
        if (in_array('Allenatore', $ruolo)) {
            $sql = "SELECT A.CodiceConvocatore
                    FROM NOMINA N
                        JOIN CARICA C on N.CodiceCarica=C.Codice
                        JOIN ALLENATORE A on C.Codice=A.Codice
                    WHERE N.Persona = ? AND N.DataFine > ?";
        }
        if (in_array('Consigliere', $ruolo)||in_array('Presidente', $ruolo)) {
            $sql = "SELECT C.CodiceConvocatore
                    FROM NOMINA N
                        JOIN CARICA CA on N.CodiceCarica=CA.Codice
                        JOIN CONSIGLIERE C on CA.Codice=C.Codice
                    WHERE N.Persona = ? AND N.DataFine > ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $CF, $data_oggi );
        $stmt->execute();
        $result = $stmt->get_result();
        $array_query = $result->fetch_all(MYSQLI_ASSOC);
        if(in_array('admin', $ruolo)){
            $_SESSION['conv'] = $array_query[1]['CodiceConvocatore'];
        }else {
            $_SESSION['conv'] = $array_query[0]['CodiceConvocatore'];
        }
        
    }
    /* DeprecatoF
    //Eseguo query per ottenere il codice CONSIGLIERE
    if (in_array('Consigliere', $ruolo)||in_array('Presidente', $ruolo)||in_array('admin', $ruolo)) {
            $sql = "SELECT C.Codice
                    FROM NOMINA N
                        JOIN CARICA CA on N.CodiceCarica=CA.Codice
                        JOIN CONSIGLIERE C on CA.Codice=C.Codice
                    WHERE N.Persona = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $CF);
        $stmt->execute();
        $result = $stmt->get_result();
        $array_query = $result->fetch_all(MYSQLI_ASSOC);
        $_SESSION['cons'] = $array_query[0]['Codice'];
    }*/
    //Variabili di Sessione
    $_SESSION['ruolo'] = $ruolo;
    $_SESSION['caricheCodici'] = $caricheCodici; 
    //chiudo la connessione
    //$stmt->close();
    $conn->close();
    $_SESSION['log'] = true;
    header('Location: ../../front/home.php');
    exit();
?>
