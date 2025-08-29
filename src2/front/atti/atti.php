<!DOCTYPE html>
<html lang="it">
<? $numero = 2

?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/navbar.css">
  <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script>
  <title>ATTI</title>
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

include '../../back/connessione.php';
include '../../back/function.php';
if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

$permessi = ['Consigliere', 'Socio', 'Allenatore', 'admin'];

//consigliere può vedere, modificare e aggiungere atti
//socio e allenatore possono vedere gli atti
//admin può fare tutto

if (!controllo($_SESSION['ruolo'], $permessi)) {
  header("location: ../404.php");
  exit();
}
include "../navbar.php";
?>

<body>
  <? include "../"; ?>
  <div class="blocco"></div>
  <div class="container">
    <h1>ATTI</h1>
    <? if(isset($_SESSION['error_message'])){
          echo $_SESSION['error_message'];
          $_SESSION['error_message'] = NULL ;
         } 
         

         if(isset($_SESSION['success_message'])){
          echo $_SESSION['success_message'];
          $_SESSION['success_message'] = NULL ;
         }
      ?>

    <!-- Inserisci Atto -->
    <div id = 'inserisci'>
      <h2>Inserisci Atto</h2>
      <form action="../../back/atti/handler_aggiunta_atti.php" method="POST" enctype="multipart/form-data">
        <label>
          Data Atto:
          <input type="date" name="data_atto" required>
        </label>
        <label>
          Oggetto:
          <input type="text" name="oggetto" required>
        </label>
        <label>
          Ordine del Giorno:
          <input type="text" name="ordine_giorno" required>
        </label>
        <label>
          File PDF:
          <input type="file" name="file_pdf" accept=".pdf" required>
        </label>
        <button type="submit">Inserisci</button><br>
        
      </form>
    </div>

    <!-- Visualizza Atti -->
    <h2>Visualizza Atti</h2>
    <form id="filtroForm">
      <label>
        Cerca per Oggetto:
        <input type="text" name="oggetto" id="oggetto">
      </label>
      <label>
        Cerca per Data:
        <input type="date" name="data" id="data">
      </label>
      <label>
        Cerca per Ordine del Giorno :
        <input type="text" name="ordine" id="ordine">
      </label>
      <button type="submit">Filtra</button>
    </form>

    <div class="table-container">
      <table id="tabellaAtti">
        <thead>
          <tr>
            <th>Data
              <button id="sortDataBtn">↕</button> <!-- La freccia toggle -->
            </th>
            <th>N°Prot</th>
            <th>Oggetto</th>
            <th>Ordine del Giorno</th>
            <th>Documento</th>
            <th>Elimina</th>
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS -->
        </tbody>
      </table>
    </div>
  </div>
  <script>
    const utenteCorrente = <?= json_encode($_SESSION['caricheCodici']['Consigliere'] ?? '0') ?>;
    if(utenteCorrente ==0 ) {
      document.getElementById('inserisci').style.display = 'none';
    }
    let ordineData = 'DESC'; // stato iniziale per l'ordinamento

    document.addEventListener('DOMContentLoaded', function() {
      // Inizializza caricamento dati al primo avvio
      caricaDati();

      // Evento filtro form
      document.getElementById('filtroForm').addEventListener('submit', function(e) {
        e.preventDefault();
        caricaDati();
      });

      // Evento ordinamento colonna data
      document.getElementById('sortDataBtn').addEventListener('click', function() {
        ordineData = ordineData === 'DESC' ? 'ASC' : 'DESC';
        caricaDati();
      });

      // Gestione resize
      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          window.scrollTo(0, 0);
        }
      });
    });

    function caricaDati() {
      // Raccogli i valori dei filtri
      const oggetto = document.getElementById('oggetto').value;
      const data = document.getElementById('data').value;
      const ordine = document.getElementById('ordine').value;

      // Costruzione dei parametri per la query string
      const params = new URLSearchParams({
        oggetto: oggetto,
        data: data,
        ordine: ordine,
        order: ordineData
      });

      // Richiesta al backend
      fetch(`../../back/atti/get_atti.php?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaAtti tbody');
          tbody.innerHTML = '';

          data.forEach(atto => {
            const row = `
            <tr>
             <td>${atto.Data}</td>
              <td>${atto.NumProtocollo}</td>
              <td>${atto.Oggetto}</td>
              <td>${atto.OrdineDelGiorno}</td>
              <td><a href="${atto.file_path}" target="_blank">Visualizza PDF</a></td>
              <td>
                ${atto.CodiceRedatore === utenteCorrente ? `
                  <form class="logout" action="../../back/atti/rimuovi_atto.php" method="POST">
                    <input type="hidden" name="numProtocollo" value="${atto.NumProtocollo}">
                    <button class="bottoniElimina" type="submit">Elimina</button>
                  </form>` : 'Non eliminabile!'}
              </td>
            </tr>`;
            
            tbody.innerHTML += row;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }
  </script>

</body>

</html>