<?php

if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

session_destroy(); // Distrugge la sessione

header('Location: ../../front/login.php');

exit();

?>
