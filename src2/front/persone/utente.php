<!DOCTYPE html>
<html lang="it">
<? $numero = -1;

?>
<?php

include '../../back/connessione.php';
include '../../back/function.php';
if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

$permessi = ['user'];

//consigliere può vedere, modificare e aggiungere atti
//socio e allenatore possono vedere gli atti
//admin può fare tutto
//user utente semplice solo se è per tutti

if (!controllo($_SESSION['ruolo'], $permessi)) {
  header("location: ../404.php");
  exit();
}

?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/navbar.css">
  <link rel="stylesheet" href="../../css/toast.css">
  <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script>
  <title>Utente</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
      color: white;
      display: flex;
    }

    #selectRuoli {
      padding: 0.6rem;
      border: none;
      border-radius: 5px;

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

    .bottoniElimina {
      background-color: red;
      transition: background-color 0.3s ease;
    }

    .bottoniElimina:hover {
      background-color: darkred
    }

    .tag {
      display: inline-block;
      background-color: #FFFFFF;
      /* Bianco per un buon contrasto */
      color: #3a4a7d;
      /* Colore testo blu scuro che si abbina al background */
      border-radius: 12px;
      padding: 6px 12px;
      font-size: 14px;
      text-align: center;
      width: 75%;
      font-weight: 600;
      margin: 5px;
      margin-left: 10%;

      transition: background-color 0.3s, transform 0.3s;
    }

    .tag:hover {
      background-color: #88bde9;
      /* Giallo chiaro al passaggio del mouse */
      color: #FFFFFF;
      /* Cambia il colore del testo quando passa sopra */
      transform: translateY(-3px);
      /* Effetto leggero di movimento */
    }

    .blocco {
      flex-shrink: 0;
      color: white;
      width: 300px;
      height: 100vh;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .container {
      width: 80%;
      padding: 2rem;
      flex-direction: column;
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
      margin-bottom: 2rem;
      background-color: rgba(255, 255, 255, 0.05);
      padding: 1rem;
      border-radius: 10px;
    }

    label {
      flex: 1 1 calc(50% - 1rem);
      display: flex;
      flex-direction: column;
      color: #fff;
    }

    input[type="text"],
    input[type="date"],
    input[type="email"],
    input[type="tel"],
    input[type="password"],
    input[type="file"] {
      padding: 0.6rem;
      border: none;
      border-radius: 5px;
      margin-top: 0.3rem;
      background-color: #ffffffcc;
      color: #000;
      font-size: 1rem;
    }

    .super-container{
      margin: auto;
      display: flex;
      flex-direction: column;
    }

    input::placeholder {
      color: #666;
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



    /* MODALE */
    .popup-overlay {
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: space-evenly;
      z-index: 9999;
      padding: 1rem;
    }

    .popup-content {
      background-color: #3a4a7d;
      color: white;
      padding: 2rem;
      border-radius: 10px;
      width: 100%;
      max-width: 500px;
    }

    .popup-content2 {
      background-color: #3a4a7d;
      color: white;
      border-radius: 10px;
      width: 30%;
      max-width: 500px;
      margin-left: 40px;
      padding-bottom: 20px;
      height: fit-content;

    }

    .popup-content h2 {
      margin-top: 0;
      color: #aadfff;
    }

    .close-popup {
      position: absolute;
      top: 10px;
      right: 10px;
      background: transparent;
      border: none;
      font-size: 1.5rem;
      color: white;
      cursor: pointer;
    }


    @media (max-width: 1268px) {
      .popup-overlay {
        flex-direction: column;
      }

      .popup-content {
        margin: auto !important;
        width: 85% !important;
      }

      .popup-content2 {
        margin: auto !important;
        margin-top: 32px !important;
        width: 100% !important;
        padding: 32px;

      }
    }

    @media (max-width: 970px) {
      .container {
        padding-left: 0px;
      }

      .popup-content {
        width: 85% !important;
      }

      .popup-content2 {
        width: 85% !important;
      }
    }

    @media (max-width: 768px) {

      .container {
        padding-left: 32px;
      }

      .blocco {
        display: none;
      }

      form:not(.logout) {
        flex-direction: column;
      }

      label {
        width: 100%;
      }
    }

    @media (max-width: 600px) {
      .popup-content {
        width: 95%;
        padding: 1.5rem;
      }

      .popup-content2 {
        width: 95%;
        padding: 1.5rem;
      }
    }

    @media (max-width: 500) {}
  </style>
</head>
<? include "../navbar.php"; ?>

<body>
  
  <div class="blocco"></div>

  <div class="super-container">
  <div class="container">
    <h1 style="text-align:center">UTENTE : <?= $nome . ' ' . $cognome ?></h1>

    <div id="popupModifica" class="popup-overlay">
      <div class="popup-content">

        <h2 style="text-align: center;"> Info Account </h2>
        <form action="../../back/gestione_utenti/modifica_persona.php" method="POST" id="forModifica">
          <label>Codice Fiscale:
            <input type="text" name="cf" id="popup-cf"  value =""readonly>
          </label>
          <label>Nome:
            <input type="text" name="nome" id="popup-nome" required>
          </label>
          <label>Cognome:
            <input type="text" name="cognome" id="popup-cognome" required>
          </label>
          <label>Email:
            <input type="email" name="email" id="popup-email" required>
          </label>
          <label>Telefono:
            <input type="tel" name="telefono" id="popup-telefono">
          </label>
          <input type="hidden" name="path" value="../../front/persone/utente.php">

        </form>
        <div style="display: flex; justify-content: space-around;">
          <button type="submit" form="forModifica" style="background-color:#4c5c96" class="btn-modifica">Modifica</button>
        </div>

      

      </div>
      <div class="popup-content2">
        <h2 style="text-align: center;"> Ruoli Account </h2>
        <div>
          <!-- TODO AGGIUNGERE I TAGGHINI CARINI CON  I RUOLI -->
          <? foreach ($_SESSION['ruolo'] as $ruolo) { ?>
            <div class="tag"><?= $ruolo ?> </div>
          <? } ?>
        </div>
      </div>
    </div>

    <? if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
    $_SESSION['error_message'] = null;
    ?>
    <? if (isset($errorMessage)) { ?>
      <h3 style="color:red;"><?= $errorMessage ?></h3>
      <style>
        .container {
          padding-bottom: 3px !important;
        }
      </style>
    <? } ?>
  </div>
    <div class="container">
      <h1>Gli Sport che insegno </h1>
      <h2>Seleziona e insegna un nuovo sport!</h2>
        <form action="../../back/allenatore/handler_allenatore.php" method="post" id="formSport1">
            <select name="sport" id="selectSport" class="input-style" required style=" margin-top: 0.3rem; background-color: #ffffffcc; color: #000; font-size: 1rem; width:90%">
                <option value="Basket">Basket</option>
                <option value="Calcio">Calcio</option>
                <option value="Volley">Volley</option>
                <option value="Tennis">Tennis</option>
        </select>
            <button style="width:150px; margin: auto;" type="submit">Invia</button>
        </form>
        <? 
          if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
        
      ?>
      <? if (isset($_SESSION['error_message'])){ ?>
          <div id="toast" class="toast">
              <div class="toast-icon">🐙</div>
              <div class="toast-message"><?php echo $_SESSION['error_message']; ?></div>
              <button class="toast-close">&times;</button>
          </div>
          <?php unset($_SESSION['error_message']); ?>
      <?php } ?>
      
      <?php if (isset($_SESSION['success_message'])){ ?>
          <div id="toast" class="toast">
              <div class="toast-icon">🐙</div>
              <div class="toast-message"><?php echo $_SESSION['success_message']; ?></div>
              <button class="toast-close">&times;</button>
          </div>
          <?php unset($_SESSION['success_message']); ?>
      <?php } ?>
        <h2>I miei sport</h2>
        <div class="table-container">
      <table id="tabellaSport1">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Elimina</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    </div>
    <div class="container">
      <h1>Gli Sport che pratico! </h1>
      <h2>Seleziona lo sport e il livello per iniziare a praticarlo!</h2>
        <form action="../../back/atleta/handler_atleta.php" method="post" id="formSport">
        <label>Sport: </label>
            <select name="sport" id="selectSport" class="input-style" required style="  background-color: #ffffffcc; color: #000; font-size: 1rem; width:90%">
                <option value="Basket">Basket</option>
                <option value="Calcio">Calcio</option>
                <option value="Volley">Volley</option>
                <option value="Tennis">Tennis</option>
            </select>
            <label>Livello: </label>
            <select name="livello" id="selectLivello" class="input-style" required style="  background-color: #ffffffcc; color: #000; font-size: 1rem; width:90%">
                <option value="Amatoriale">Amatoriale</option>
                <option value="Agonistico">Agonistico</option>
            </select>
            <button style="width:150px; margin: auto;" type="submit">Invia</button>
    
        </form>
        <? if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
         if(isset($_SESSION['error_message'])){
          echo $_SESSION['error_message'];
          $_SESSION['error_message'] = NULL ;
         } 

         if(isset($_SESSION['success_message'])){
          echo $_SESSION['success_message'];
          $_SESSION['success_message'] = NULL ;
         }
      ?>
        <h2>I miei sport</h2>
        <div class="table-container">
      <table id="tabellaSport">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Livello</th>
            <th>Elimina</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    </div>
  
</body>
<script>
        document.addEventListener('DOMContentLoaded', function() {
      console.log('Dati caricati ');
          caricaDati3();

      document.getElementById('filtroForm').addEventListener('submit', function(e) {
        e.preventDefault();
        caricaDati3();
      });

          window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
              window.scrollTo(0, 0);
            }
          });
        });

      function caricaDati3() {
      fetch(`../../back/gestione_utenti/get_utente.php`)
        .then(res => res.json())
        .then(data => {
          
          //console.log(document.getElementById('popup-cf'));
          const persona = data[0]; // Puoi anche scegliere un altro elemento se è presente più di un oggetto nell'array
          
          
          document.getElementById('popup-cf').value = persona.CF || '';
          document.getElementById('popup-nome').value = persona.Nome || '';
          document.getElementById('popup-cognome').value = persona.Cognome || '';
          document.getElementById('popup-email').value = persona.Email || '';
          document.getElementById('popup-telefono').value = persona.Numero || '';
          document.getElementById('BottoneElimina').value = persona.CF || '';
          
          
        })
        .catch(error => {
          console.error('Errore nel caricamento dei dati:', error);
        });

    }
   
        document.addEventListener('DOMContentLoaded', function() {
          caricaDati1();
          caricaDati4();

          window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
              window.scrollTo(0, 0);
            }
          });
        });
      function caricaDati1() {
      fetch(`../../back/allenatore/get_allenatore.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaSport1 tbody');
          tbody.innerHTML = '';
          let count = 1;
          data.forEach(sport => {
            
            const row = `
              <tr>
                <td>${count+"°"}</td>
                <td>${sport.NomeSport}</td>
                <td>
                  <form action = '../../back/allenatore/elimina_sport.php' method = 'POST'  class="logout">
                    <input type="hidden" name="path" value="../../front/persone/utente.php">
                    <input type="hidden" name="sport" value="${sport.NomeSport}">
                    <input type="hidden" name="source" value="allenatore">
                    <button type="submit" style="padding: 0.4rem 1.2rem;" id="bottone${sport.Nome}" class="bottoniElimina" >Elimina</button>
                  </form>
                </td>
              </tr>`;
            tbody.innerHTML += row;
            count++;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }
     function caricaDati4() {
      fetch(`../../back/atleta/get_atleta.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaSport tbody');
          tbody.innerHTML = '';
          let count = 1;
          data.forEach(sport => {
            
            const row = `
              <tr>
                <td>${count+"°"}</td>
                <td>${sport.NomeSport}</td>
                <td>${sport.Tipo}</td>
                <td>
                  <form action = '../../back/allenatore/elimina_sport.php' method = 'POST'  class="logout">
                    <input type="hidden" name="path" value="../../front/persone/utente.php">
                    <input type="hidden" name="sport" value="${sport.NomeSport}">
                    <input type="hidden" name="source" value="atleta">
                    <button type="submit" style="padding: 0.4rem 1.2rem;" id="bottone${sport.Nome}" class="bottoniElimina" >Elimina</button>
                  </form>
                </td>
              </tr>`;
            tbody.innerHTML += row;
            count++;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }
        </script>
        <script src="../../js/toast.js"></script>
</html>