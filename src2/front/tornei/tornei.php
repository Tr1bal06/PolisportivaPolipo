<!DOCTYPE html>
<html lang="it"> <!-- Dichiarazione del tipo di documento e lingua italiana -->

<? $numero = 9 ?>

<head>
  <meta charset="UTF-8"> <!-- Set di caratteri UTF-8 per supporto internazionale -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design per dispositivi mobili -->
  <link rel="stylesheet" href="../../css/navbar.css"> <!-- Inclusione file CSS personalizzato -->
  <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script> <!-- Icone FontAwesome -->
  <title>Tornei</title> <!-- Titolo della pagina -->

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
      color: white;
      display: flex
    }

    .sponsor-tag {
  display: inline-block;
  background-color: #88bde9;
  color: #0a2a4e;
  padding: 0.2rem 0.6rem;
  margin: 0.2rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: bold;
  white-space: nowrap;
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

$permessi = ['user', 'admin']; // Ruoli autorizzati ad accedere a questa pagina

if (!controllo($_SESSION['ruolo'], $permessi)) {
    error("location: ../../front/404.php", "Permesso negato");
}

include "../navbar.php"; // Inclusione della barra di navigazione
?>

<body>
  <div class="blocco"></div>

  <div class="container"> <!-- Contenitore principale del contenuto -->
    <h1>TORNEI</h1> <!-- Titolo principale della pagina -->
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

    <h2>I tuoi tornei</h2> <!-- Sezione per tornei attivi, questa parte deve inviare il codice dello sponsor alla pagina getSponsor.php -->

    <div class="table-container"> <!-- Contenitore della tabella -->
      <table id="tabellaMieiTornei"> <!-- Tabella tornei -->
        <thead>
          <tr>
            <th>Codice Torneo</th>
            <th>Nome Torneo</th>
            <th>Sport</th>
            <th>Data <!-- Bottone per ordinare gli anni -->
            </th>
            <th>Regolamento</th>
            <th>Sponsor Torneo</th>
            <th>Elimina</th> <!-- Colonna per eliminare la partecipazione, invia i dati CodiceTorneo, anno, CFPartecipante alla pagina EliminaTornei.php -->
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS --> <!-- Sarà popolata tramite JavaScript -->
        </tbody>
      </table>
    </div>

    <h2>Tornei a cui puoi partecipare</h2> <!-- Sezione per nuovi tornei disponibili -->
    <!--questa parte invia al back i dati CodiceTorneo, anno, CodiceSponsor alla pagina tornei.php-->

    <div class="table-container">
      <table id="tabellaTornei"> <!-- Tabella dei tornei disponibili -->
        <thead>
          <tr>
            <th>Codice Torneo</th>
            <th>Nome Torneo</th>
            <th>Sport</th>
            <th>Data
            </th>
            <th>Regolamento</th>
            <th>Sponsor Torneo</th><!--mostra i posti disponibili, colore verde se ancora aperte, colore rosso se chiuse-->
            <th>Partecipa</th> <!-- Colonna per partecipare al torneo, invia i dati CodiceTorneo, anno alla pagina PartecipaTornei.php -->
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    let ordineData = 'DESC'; // Stato iniziale di ordinamento (decrescente)

    document.addEventListener('DOMContentLoaded', function() {
      caricaDati(); // Carica i dati appena la pagina è caricata
      caricaDati2();

     

      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          window.scrollTo(0, 0); // In caso di resize sopra 768px, torna all’inizio
        }
      });
    });

    function caricaDati() {
  fetch(`../../back/tornei/get_tornei.php`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#tabellaTornei tbody');
      tbody.innerHTML = '';
      console.log(data);
      data.forEach(atto => {
        const sponsorId = `codice${atto.CodiceTorneo}+${atto.Anno}`;
        const row = `
          <tr>
            <td>${atto.CodiceTorneo}</td>
            <td>${atto.NomeTorneo}</td>
            <td>${atto.Sport}</td>
            <td>${atto.Anno}</td>
            <td><a href="${atto.Regolamento}" target="_blank">Visualizza Regolamento</a></td>
            <td id="${sponsorId}"></td>
            <td>
              <form class="logout" action="../../back/tornei/PartecipaTornei.php" method="POST">
                <input type="hidden" name="CodiceTorneo" value="${atto.CodiceTorneo}">
                <input type="hidden" name="Anno" value="${atto.Anno}">
                <button type="submit">Partecipa</button>
              </form>
            </td>
          </tr>`;
        tbody.innerHTML += row;

        // Genera i tag sponsor
        const sponsorCell = document.getElementById(sponsorId);
        if (atto.Sponsor && atto.Sponsor.length > 0) {
          atto.Sponsor.forEach(s => {
            const tag = document.createElement('span');
            tag.classList.add('sponsor-tag');
            tag.innerText = s.Nome;
            sponsorCell.appendChild(tag);
          });
        } else {
          sponsorCell.innerHTML = '<span style="color: #ccc;">Nessuno</span>';
        }
      });
    })
    .catch(error => {
      console.error('Errore nel caricamento degli atti:', error);
    });
}


    function caricaDati2() {
        fetch(`../../back/tornei/get_tornei_partecipati.php`) // Effettua chiamata GET al backend
        .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#tabellaMieiTornei tbody');
      tbody.innerHTML = '';
      console.log(data);
      data.forEach(atto => {
        const sponsorId = `codice${atto.CodiceTorneo}+${atto.Anno}`;
        const row = `
          <tr>
            <td>${atto.CodiceTorneo}</td>
            <td>${atto.NomeTorneo}</td>
            <td>${atto.Sport}</td>
            <td>${atto.Anno}</td>
            <td><a href="${atto.Regolamento}" target="_blank">Visualizza Regolamento</a></td>
            <td id="${sponsorId}"></td>
            <td>
              <form class="logout" action="../../back/tornei/EliminaTornei.php" method="POST">
                <input type="hidden" name="CodiceTorneo" value="${atto.CodiceTorneo}">
                <input type="hidden" name="Anno" value="${atto.Anno}">
                <button type="submit" class="bottoniElimina"bt>Elimina</button>
              </form>
            </td>
          </tr>`;
        tbody.innerHTML += row;

        // Genera i tag sponsor
        const sponsorCell = document.getElementById(sponsorId);
        if (atto.Sponsor && atto.Sponsor.length > 0) {
          atto.Sponsor.forEach(s => {
            const tag = document.createElement('span');
            tag.classList.add('sponsor-tag');
            tag.innerText = s.Nome;
            sponsorCell.appendChild(tag);
          });
        } else {
          sponsorCell.innerHTML = '<span style="color: #ccc;">Nessuno</span>';
        }
      });
    })
    .catch(error => {
      console.error('Errore nel caricamento degli atti:', error);
    });

        
    }
  </script>
</body>

</html>