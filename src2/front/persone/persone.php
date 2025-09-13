<!DOCTYPE html>
<html lang="it">
<? $numero = 1 ?>
<?php

  include '../../back/connessione.php';
  include '../../back/function.php';
  if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

  $permessi = ['admin'];

  //consigliere può vedere, modificare e aggiungere atti
  //socio e allenatore possono vedere gli atti
  //admin può fare tutto

  if(!controllo($_SESSION['ruolo'], $permessi)) {
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
  <title>PERSONE</title>
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

    .bottoniElimina {
      background-color: red;
      transition: background-color 0.3s ease;
    }

    .bottoniElimina:hover {
      background-color: darkred
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

    .btn-modifica {
      height: fit-content;
      background-color: #4c5c96;
      transition: background-color 0.3s ease;
    }

    .btn-modifica:hover {
      background-color: #5c7ae3;
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

    @media (max-width: 768px) {
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

    #forRichiesta{
      display: flex;
      flex-direction: column;
    }

    /* MODALE */
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
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
      position: relative;
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

    @media (max-width: 600px) {
      .popup-content {
        width: 95%;
        padding: 1.5rem;
      }
    }
  </style>
</head>
<? include "../navbar.php"; ?>

<body>
  
  <div class="blocco"></div>

  <div class="container">
    <h1>PERSONE</h1>
<? if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
     if (isset($_SESSION['error_message'])){ ?>
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

    <h2>Gestione Persone</h2>
    <form action="../../back/gestione_utenti/aggiungi_persona.php" method="POST" enctype="multipart/form-data">
      <label>Codice Fiscale:
        <input type="text" name="cf" placeholder="Codice Fiscale" maxlength="16" minlength="16" required>
      </label>
      <label>Email:
        <input type="email" name="email" placeholder="Email" required>
      </label>
      <label>Nome:
        <input type="text" name="nome" placeholder="Nome" required>
      </label>
      <label>Cognome:
        <input type="text" name="cognome" placeholder="Cognome" required>
      </label>
      <label>Telefono:
        <input type="tel" name="telefono" id="telefono" placeholder="Numero di Telefono" maxlength="10" minlength="10">
      </label>
      <label>Password:
        <input type="password" name="password1" id="password1" placeholder="Password" required>
      </label>
      <button type="submit" style="margin:auto;">Inserisci</button>
    </form>

    <h1>ASSEGNA CARICA</h1>
    <h2>Assegna carica</h2>
    <form action="../../back/gestione_utenti/assegna_carica.php" method="POST" enctype="multipart/form-data">
      <label>Codice Fiscale:
        <input type="text" name="cf" placeholder="Codice Fiscale" maxlength="16" minlength="16" required>
      </label>
      <label>Tipo carica:
        <select name="carica" id="selectRuoli" class="input-style" required style=" margin-top: 0.3rem;
      background-color: #ffffffcc;
      color: #000;
      font-size: 1rem;">
          <option value="">Seleziona Carica</option>
          <option value="Presidente">Presidente</option>
          <option value="Consigliere">Consigliere</option>
          <option value="Allenatore">Allenatore</option>
          <option value="Atleta">Atleta</option>
          <option value="Medico">Medico</option>
          <option value="Socio">Socio</option>
          <option value="Altro_Personale">Altro_Personale</option>
          <option value="Sponsor">Sponsor</option>
        </select>
        <div id="sponsorFields" style="display:none; margin-top: 1rem;">
  <label for="nomeSponsor">Nome Sponsor</label>
  <input type="text" id="nomeSponsor" name="nome" class="input-style" />

  <label for="tipoAttivitaSponsor" style="margin-top:5px">Tipo Attività Sponsor</label>
  <input type="text" id="tipoAttivitaSponsor" name="attivita" class="input-style" />
</div>

<div id="altroPersonaleField" style="display:none; margin-top: 1rem;">
  <label for="tipoCarica">Tipo di Carica</label>
  <input type="text" id="tipoCarica" name="tipo" class="input-style" />
</div>
      </label>
      <button type="submit" style="margin:auto;">Inserisci</button>
    </form>

    <h2>Visualizza Persone</h2>
    <form id="filtroForm">
      <input type="text" id="ricerca" placeholder="Cerca nelle Persone" style="width:100%; margin-bottom: 10px; padding: 5px;">
    </form>

    <div class="table-container">
      <table id="tabellaAtti">
        <thead>
          <tr>
            <th>CF</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Elimina</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

  <!-- Popup Modifica -->
  <div id="popupModifica" style="display:none;" class="popup-overlay">
    <div class="popup-content">
      <button class="close-popup" onclick="chiudiPopup()">✕</button>
      <h2>Modifica Persona</h2>
      <form action="../../back/gestione_utenti/modifica_persona.php" method="POST" id="forModifica">
        <label>Codice Fiscale:
          <input type="text" name="cf" id="popup-cf" readonly>
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
        <input type="hidden" name="path" value="../../front/persone/persone.php">

      </form>
      <div style="display: flex; justify-content: space-around;">
        <button type="submit" form="forModifica"  class="btn-modifica">Modifica</button>
        <form class="logout" action="../../back/gestione_utenti/elimina_persona.php" method="POST">
          <input type="hidden" id="BottoneElimina" name="cf" value=""><button class="bottoniElimina" type="submit">elimina</button>
        </form>
      </div>

    </div>
  </div>
  <h1>Gestisci Richieste</h1>
      <h2>Gestisci Richieste</h2>
    <form id="filtroForm1">
      <input type="text" id="ricerca1" placeholder="Cerca nelle Persone" style="width:100%; margin-bottom: 10px; padding: 5px;">
    </form>
    <div class="table-container">
      <table id="tabellaRichieste">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Ruolo</th>
            <th>Sport</th>
            <th>Livello</th>
            <th>TipoRichiesta</th>
            <th>Specifiche</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <!-- Popup Richieste -->
  <div id="popupRichiesta" style="display:none;" class="popup-overlay">
    <div class="popup-content">
      <button class="close-popup" onclick="chiudiPopupRichiesta()">✕</button>
      <h2>Informazioni Richiesta</h2>
      <form action="../../back/gestione_utenti/aggiungi_sport.php" method="POST" id="forRichiesta">
        <label>Tipo Richiesta:
          <input type="text" name="tipologia" id="popup-Tipo" readonly>
        </label>
        <label>Motivazione:
          <input type="text" name="motivazione" id="popup-Motivazione" readonly>
        </label>
        <input type="hidden" name="livello" id="popup-livello" value="">
        <input type="hidden" name="codice" id="popup-codice" value="">
        <input type="hidden" name="sport" id="popup-sport" value="">
        <input type="hidden" name="source" id="popup-source" value="">
      </form>
      <div style="display: flex; justify-content: space-around;">
          <button type="submit" form="forRichiesta"  class="btn-modifica">Accetta</button>
        <form class="logout" action="../../back/gestione_utenti/rifiuta_richiesta.php" method="POST">
          <input type="hidden" id="BottoneElimina" name="ident" value=""><button class="bottoniElimina" type="submit">Rifiuta</button>
          <input type="hidden" name="codice" id="popup-codice2" value="">
          <input type="hidden" name="sport" id="popup-sport2" value="">
          <input type="hidden" name="source" id="popup-source2" value="">
        </form>
      </div>
    </div>
  </div>
   </div>
  <script>
    let ordineData = 'DESC';

    document.addEventListener('DOMContentLoaded', function() {
      caricaDati();
      caricaRichieste();

      document.getElementById('filtroForm').addEventListener('submit', function(e) {
        e.preventDefault();
        caricaDati();
      });

      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          window.scrollTo(0, 0);
        }
      });
    });

    function caricaRichieste() {
      fetch(`../../back/gestione_utenti/get_richieste.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaRichieste tbody');
          tbody.innerHTML = '';

          if(data.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="6" style="padding:16px; text-align:center;">
                    🐙 Nessun poli-richiesta trovata 🐙
                  </td>
                </tr>`;
              return;
          }

          data.forEach(persona => {
            
            const row = `
              <tr>
                <td>${persona.Nome}</td>
                <td>${persona.Cognome}</td>
                <td>${persona.NomeCarica}</td>
                <td>${persona.Sport}</td>
                <td>${persona.Livello}</td>
                <td>${persona.Tipo}</td>
                <td>
                  <button type="button" id="bottone${persona.Codice+persona.Sport}" class="" onclick='apriPopupRichiesta(${JSON.stringify(persona)})'>Visualizza</button>
                </td>
              </tr>`;
            tbody.innerHTML += row;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }

    function caricaDati() {
      fetch(`../../back/gestione_utenti/get_persone.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaAtti tbody');
          tbody.innerHTML = '';

          if(data.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="6" style="padding:16px; text-align:center;">
                    🐙 Nessun poli-membro trovato 🐙
                  </td>
                </tr>`;
              return;
          }

          data.forEach(persona => {
            
            const row = `
              <tr>
                <td>${persona.CF}</td>
                <td>${persona.Nome}</td>
                <td>${persona.Cognome}</td>
                <td>${persona.Email}</td>
                <td>${persona.Numero}</td>
                <td>
                  <button type="button" id="bottone${persona.CF}" class="" onclick='apriPopup(${JSON.stringify(persona)})'>Modifica</button>
                </td>
              </tr>`;
            tbody.innerHTML += row;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }

    function apriPopup(persona) {
      document.getElementById('popup-cf').value = persona.CF;
      document.getElementById('popup-nome').value = persona.Nome;
      document.getElementById('popup-cognome').value = persona.Cognome;
      document.getElementById('popup-email').value = persona.Email;
      document.getElementById('BottoneElimina').value = persona.CF;
      document.getElementById('popup-telefono').value = persona.Numero || '';
      document.getElementById('popupModifica').style.display = 'flex';
    }

    function chiudiPopup() {
      document.getElementById('popupModifica').style.display = 'none';
    }

    function apriPopupRichiesta(persona) {
      console.log(persona);
      document.getElementById('popup-Tipo').value = persona.Tipo || '';
      document.getElementById('popup-Motivazione').value = persona.Motivo || '';
      document.getElementById('popup-livello').value = persona.Livello || '';
      document.getElementById('popup-sport').value = persona.Sport || '';
      document.getElementById('popup-source').value = persona.NomeCarica || '';
      document.getElementById('popup-codice').value = persona.Codice || '';
      document.getElementById('popup-sport2').value = persona.Sport || '';
      document.getElementById('popup-source2').value = persona.NomeCarica || '';
      document.getElementById('popup-codice2').value = persona.Codice || '';
      document.getElementById('popupRichiesta').style.display = 'flex';
    }

    function chiudiPopupRichiesta() {
      document.getElementById('popupRichiesta').style.display = 'none';
    }


    document.getElementById('ricerca').addEventListener('input', function() {
      const ricerca = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tabellaAtti tbody tr');

      count = 0;
      rows.forEach(row => {
        let testoRiga = row.textContent.toLowerCase();
        if (ricerca === '') {
          row.innerHTML = row.innerHTML.replace(/<mark>(.*?)<\/mark>/g, '$1');
          row.style.display = '';
          return;
        }

        let match = false;

        row.querySelectorAll('td').forEach((td, index) => {
          if (index === row.cells.length - 1) return;
          const text = td.textContent;
          const lowerText = text.toLowerCase();

          if (lowerText.includes(ricerca)) {
            match = true;
            const regex = new RegExp(`(${ricerca})`, 'gi');
            td.innerHTML = text.replace(regex, `<mark>$1</mark>`);
          } else {
            td.innerHTML = text;
          }
        });

        if (match) {
          row.style.display = '';
        } else {
          count++;
          row.style.display = 'none';
        }
      });

      if (count === rows.length) {
        document.querySelector('#tabellaAtti tbody').innerHTML = `
          <tr>
            <td colspan="6" style="padding:16px; text-align:center;">
              🐙 Nessun poli-membro trovato 🐙
            </td>
          </tr>`;
      }

    });

    const selectRuoli = document.getElementById('selectRuoli');
  const sponsorFields = document.getElementById('sponsorFields');
  const altroPersonaleField = document.getElementById('altroPersonaleField');

  selectRuoli.addEventListener('change', function () {
    const selected = this.value;

    sponsorFields.style.display = selected === 'Sponsor' ? 'block' : 'none';
    altroPersonaleField.style.display = selected === 'Altro_Personale' ? 'block' : 'none';
  }); 
  </script>
  <script src="../../js/toast.js"></script>
</body>

</html>