<?php
// Avvia la sessione subito, prima di qualsiasi output
    if (session_status() === PHP_SESSION_NONE) {
        if (session_status() == PHP_SESSION_NONE) {
            // Avvia la sessione
            session_start();
        }    
    }

// Funzione per messaggi di successo
function success($path, $success) {
    $_SESSION['success_message'] = '<div style="color:#00ff00;"><h3>'.$success.'</h3></div>';
    header('Location: ' . $path);
    exit;
}

// Funzione per gli errori
function error($path, $errore) {
    $_SESSION['error_message'] = '<div style="color:red;"><h3>'.$errore.'</h3></div>';
    header('Location: ' . $path);
    exit;
}

// Funzione per controllo pagine
/**
 * Input: $ruoli -> array dei ruoli posseduti
 *        $permessi -> array dei ruoli ammessi
 * Output: true se almeno un permesso è soddisfatto, false altrimenti
 */
function controllo($ruoli, $permessi) {
    foreach ($permessi as $permesso) {
        foreach ($ruoli as $ruolo) {
            if ($permesso === $ruolo) {
                // trovato un match → l’utente ha il permesso
                return true;
            }
        }
    }
    return false;
}
