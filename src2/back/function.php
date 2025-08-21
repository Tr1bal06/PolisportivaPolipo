<?php
    //TODO fare una funzione success che fa come error per i colori più belli :D
   session_start();
    function success($path , $success) {
        session_start();
        $_SESSION['success_message'] = '<div style="color:#00ff00;"><h3>'.$success.'</h3></div>';
        header('location: '. $path);
        exit;
    }
    //funzione per gli errori
    function error($path , $errore) {
        session_start();
        $_SESSION['error_message'] = '<div style="color:red;"><h3>'.$errore.'</h3></div>';
        header('location: '. $path);
        exit;
    }

    //funzione per controllo pagine

    /** 
     * Input: $ruoli-> array degli ruoli che possiede , $permessi-> array degli ruoli che possono accedere alla pagina 
     * Output: true se almeno un permesso è stato soddisfatto : false se nessun permesso sia stato soddisfato
     
     */
    function controllo($ruoli , $permessi) {

        foreach($permessi as $permesso) {

            foreach($ruoli as $ruolo) {
                if($permesso == $ruolo) {// alla prima carica che l'utente possiede sia presente tra i permessi neccessari la funzione termina dando true
                   return true;
                }
            }
    
        }
        return false;

    }
?>