<?php

session_start();

session_destroy(); // Distrugge la sessione

header('Location: ../../front/login.php');

exit();

?>
