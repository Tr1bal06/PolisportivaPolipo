<?php
    include '../../back/connessione.php'; // Connessione al database
    include '../../back/function.php';

    if (session_status() == PHP_SESSION_NONE) {
        // Avvia la sessione
        session_start();
    } 

    $permessi = ['Allenatore', 'admin']; // Ruoli autorizzati ad accedere a questa pagina

    if (!controllo($_SESSION['ruolo'], $permessi)) {
        error("location: ../../front/404.php", "Permesso negato");
    }
    $numero = 5;
    include "../navbar.php"; // Inclusione della barra di navigazione
?>
<html>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/navbar.css">
    <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script>
    <title>Seleziona Sport e Livello</title>
    <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
      color: white;
      display: flex;
      overflow:hidden;
    }

    .input-style {
  padding: 0.5rem;
  border: none;
  border-radius: 5px;
  margin-top: 0.5rem;
  font-family: inherit;
  font-size: 1rem;
}

    .blocco{
      flex-shrink: 0;    
      color: white;
      width: 280px;
      height: 100vh;
      padding: 1rem;  
      display: flex;
      flex-direction: column;
      justify-content: space-between;

    } 
    .container {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden; /* evita sforamenti non desiderati */
  margin: 0 auto;
  padding: 2rem;
  box-sizing: border-box;
}


    h1,
    h2 {
      margin-bottom: 1rem;
    }

    h1 {
      font-size: 2.5rem;
      border-bottom: 2px solid #88bde9;
      padding-bottom: 0.5rem;
    }

    h2 {
      font-size: 1.5rem;
      color: #aadfff;
      margin-top: 2rem;
    }

    form:not(.logout) {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2rem !important;
      background-color: rgba(255, 255, 255, 0.05);
      padding: 1rem;
      border-radius: 10px;
    }

    label {
      align-self: start;
      margin-left: 5%;
    }

    input[type="text"],
    input[type="date"],
    input[type="file"] {
      padding: 0.5rem;
      border: none;
      border-radius: 5px;
      margin-top: 0.5rem;
    }

    .table-container {
      width: 70%;
      margin:auto;
      max-width: 100%;
      overflow-x: auto;
      background-color: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
      padding: 1rem;
      box-sizing: border-box;
    }


    table {
  width: 100%;
  min-width: 600px; /* oppure un valore maggiore se ci sono molte colonne */
  border-collapse: collapse;
  color: white;
  table-layout: auto; /* importante per il comportamento fluido */
}

    th,
    td {
      padding: 0.75rem;
      border-bottom: 1px solid #ffffff33;
      text-align: left;
    }

    th {
      background-color: #3a4a7d;
    }

    #formAssemblea {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  width: 70%;
  margin: auto;
}

#formAssemblea label {
  display: flex;
  flex-direction: column;
  margin-left: 0;
}
    button {
      background-color: #3a4a7d;
      color: white;
      padding: 0.7rem 1.5rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: bold;
    }

    button:hover {
      background-color: #5c7ae3;
    }
    #selectSport {
      padding: 0.6rem;
      border: none;
      border-radius: 5px;

    }
    #selectLivello {
      padding: 0.6rem;
      border: none;
      border-radius: 5px;
    }

    #formSport{
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 70%;
      margin:auto;
    }

    .table-container {
      overflow-x: auto;
      background-color: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
      padding: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      color: white;
    }

    th,
    td {
      padding: 0.75rem;
      border-bottom: 1px solid #ffffff33;
      text-align: left;
    }

    th {
      background-color: #3a4a7d;
    }
    
    .bottoniElimina {
      background-color: red;
      transition: background-color 0.3s ease;
    }
    .bottoniElimina:hover {
      background-color: darkred
    }

    @media (max-width: 768px) {
      form {
        flex-direction: column;
      }
      .blocco {
        display: none;
    }
    #formAssemblea {
    grid-template-columns: 1fr;
  }
    }
  </style>
</head>

<body>
    
    <div class="blocco"></div>
    <div class="container">
        <h1>
        Crea la tua squadra
        </h1>

        <!-- Inserisci Atto -->
        <h2>Crea la tua squadra</h2>
        <form action="../../back/tornei/creaSquadra.php" id="formAssemblea" method="POST" enctype="multipart/form-data">
        <label>
            Nome della squadra:
            <input type="text"  name="nome_squadra" required>
        </label>
        <label><!--TODO Alberto -> mi fai che questa sia una lista di persone che si possono aggiungere alla squadra
                                    un'idea carina sarebbe quella che già dal front il sistema sia in grado di capire se 
                                    il numero di persone sia maggiore di quella consentita 
                    la logica dietro la posso fare io però mi serve la struttura fatta 
                    la lista mostra solo nome e cognome invece nel post mi mandi la mail associata
                    Alberto le mail che mi passi devono essere in un array sennò il back non funziona
                    -JIN
                -->
            Partecipati:
            <input type="text" name="atleta" required>
        </label>
        <label> 
            Sport:
            <input type="text" name="sport" required>
        </label> 
        <label> 
            Logo:
            <input type="file" name="file_img" id="file_img" accept="image/png, image/jpeg, image/jpg, image/x-icon" required><br><br>
        </label><br>
       <button type="submit">Inserisci</button>
    </div>
        

        <? if(isset($_SESSION['error_message'])){
            echo $_SESSION['error_message'];
            $_SESSION['error_message'] = NULL ;
            } 
            

            if(isset($_SESSION['success_message'])){
            echo $_SESSION['success_message'];
            $_SESSION['success_message'] = NULL ;
            }
        ?>
        
        </form>
</body>
