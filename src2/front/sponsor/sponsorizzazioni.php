<!DOCTYPE html>
<html lang="it"> <!-- Dichiarazione del tipo di documento e lingua italiana -->

<? $numero = 4 ?> <!-- (Errore) Uso di PHP breve senza `php`. Non consigliato e disattivato su molti server. -->

<head>
  <meta charset="UTF-8"> <!-- Set di caratteri UTF-8 per supporto internazionale -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design per dispositivi mobili -->
  <link rel="stylesheet" href="../../css/navbar.css"> <!-- Inclusione file CSS personalizzato -->
  <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script> <!-- Icone FontAwesome -->
  <title>Sponsorizzazione</title> <!-- Titolo della pagina -->

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
      color: white;
      display: flex
    }

    .blocco {
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
      width: 80%;
      margin-left: auto;
      padding: 2rem;
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
      flex: 1 1 200px;
      display: flex;
      flex-direction: column;
    }

    input[type="text"],
    input[type="date"],
    input[type="file"] {
      padding: 0.5rem;
      border: none;
      border-radius: 5px;
      margin-top: 0.5rem;
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
    }
  </style>
</head>

<?php
include '../../back/connessione.php'; // Connessione al database
include '../../back/function.php';

if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
} // Avvia la sessione per accedere a $_SESSION

$permessi = ['Sponsor', 'admin']; // Ruoli autorizzati ad accedere a questa pagina

if (!controllo($_SESSION['ruolo'], $permessi)) {
  header("location: ../404.php"); // Reindirizza alla pagina 404 se l'utente non ha i permessi, error
  exit(); // Termina l'esecuzione dello script
}

include "../navbar.php"; // Inclusione della barra di navigazione
?>

<body>
  <div class="blocco"></div>

  <div class="container"> <!-- Contenitore principale del contenuto -->
    <h1>SPONSORIZZAZIONE</h1> <!-- Titolo principale della pagina -->

    <h2>Le tue collaborazioni</h2> <!-- Sezione per sponsorizzazioni attive, questa parte deve inviare il codice dello sponsor alla pagina getSponsor.php -->

    <div class="table-container"> <!-- Contenitore della tabella -->
      <table id="tabellaMieSponsor"> <!-- Tabella sponsorizzazioni -->
        <thead>
          <tr>
            <th>Codice Torneo</th>
            <th>Nome Torneo</th>
            <th>Sport</th>
            <th>Data
              <button id="sortAnnoBtn">↕</button> <!-- Bottone per ordinare gli anni -->
            </th>
            <th>Regolamento</th>
            <th>Medico</th>
            <th>Elimina</th> <!-- Colonna per eliminare la collaborazione, invia i dati CodiceTorneo, anno, CodiceSponsor alla pagina eliminaSponsor.php -->
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS --> <!-- Sarà popolata tramite JavaScript -->
        </tbody>
      </table>
    </div>


    <h2>Attività a cui puoi collaborare</h2> <!-- Sezione per nuove sponsorizzazioni disponibili -->
    <!--questa parte invia al back i dati CodiceTorneo, anno, CodiceSponsor alla pagina sponsorizzazioni.php-->

    <div class="table-container">
      <table id="tabellaSponsor"> <!-- Tabella degli eventi disponibili alla sponsorizzazione -->
        <thead>
          <tr>
            <th>Codice Torneo</th>
            <th>Nome Torneo</th>
            <th>Sport</th>
            <th>Data
              <button id="sortAnnoBtn">↕</button> <!-- Bottone per ordinare gli anni -->
            </th>
            <th>Regolamento</th>
            <th>Medico</th>
            <th>Partecipa</th> <!-- Colonna per eliminare la collaborazione, invia i dati CodiceTorneo, anno, CodiceSponsor alla pagina eliminaSponsor.php -->
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS -->
        </tbody>
      </table>
    </div>
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
  </div>

  <script>
    let ordineData = 'DESC'; // Stato iniziale di ordinamento (decrescente)

    document.addEventListener('DOMContentLoaded', function() {
      caricaDati(); // Carica i dati appena la pagina è caricata
      caricaDati2();


      document.getElementById('sortAnnoBtn').addEventListener('click', function() {
        ordineData = ordineData === 'DESC' ? 'ASC' : 'DESC'; // Toggle ASC/DESC
        caricaDati(); // Ricarica i dati con il nuovo ordinamento
      }); 

      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          window.scrollTo(0, 0); // In caso di resize sopra 768px, torna all’inizio
        }
      });
    });

    function caricaDati() {


      fetch(`../../back/sponsorizzazioni/get_sponsor.php`) // Effettua chiamata GET al backend
        .then(res => res.json()) // Converte risposta in JSON
        .then(data => {
          const tbody = document.querySelector('#tabellaMieSponsor tbody');
          tbody.innerHTML = ''; // Svuota il corpo della tabella
          console.log(data);
          data.forEach(atto => {
            const row = `
            <tr>
              <td>${atto.CodiceTorneo}</td>
              <td>${atto.NomeTorneo}</td>
              <td>${atto.Sport}</td>
              <td>${atto.Anno}</td>
              <td><a href="${atto.Regolamento}" target="_blank">Visualizza Regolamento</a></td>
              <td>${atto.NomeMedico+' '+atto.CognomeMedico}</td>
              <td>
                <form class="logout" action="../../back/sponsorizzazioni/eliminaSponsor.php" method="POST">
                  <input type="hidden" id="BottoneElimina" name="CodiceTorneo" value="${atto.CodiceTorneo}">
                  <input type="hidden" id="BottoneElimina" name="Anno" value="${atto.Anno}">
                  <button id="btnElimina${atto.CodiceTorneo}" class="bottoniElimina" type="submit">Elimina  </button>
                </form>
              </td>
            </tr>`;
            tbody.innerHTML += row; // Aggiunge la riga alla tabella
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error); // Logga errori eventuali
        });
    }

    function caricaDati2() {
      fetch(`../../back/sponsorizzazioni/get_tornei_non_sponsorizzati.php`) // Effettua chiamata GET al backend
        .then(res => res.json()) // Converte risposta in JSON
        .then(data => {
          const tbody = document.querySelector('#tabellaSponsor tbody');
          tbody.innerHTML = ''; // Svuota il corpo della tabella
          console.log(data);
          data.forEach(atto => {
            const row = `
            <tr>
              <td>${atto.CodiceTorneo}</td>
              <td>${atto.NomeTorneo}</td>
              <td>${atto.Sport}</td>
              <td>${atto.Anno}</td>
              <td><a href="${atto.Regolamento}" target="_blank">Visualizza Regolamento</a></td>
              <td>${atto.NomeMedico+' '+atto.CognomeMedico}</td>
              <td>
                <form class="logout" action="../../back/sponsorizzazioni/sponsorizzazione.php" method="POST">
                  <input type="hidden" id="BottoneElimina" name="CodiceTorneo" value="${atto.CodiceTorneo}">
                  <input type="hidden" id="BottoneElimina" name="Anno" value="${atto.Anno}">
                  <button id="btnElimina${atto.CodiceTorneo}" class="" type="submit">Sponsorizza</button>
                </form>
              </td>
            </tr>`;
            tbody.innerHTML += row; // Aggiunge la riga alla tabella
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error); // Logga errori eventuali
        });
    }
  </script>
</body>

</html>