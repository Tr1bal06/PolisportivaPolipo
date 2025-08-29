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

  if(!controllo($_SESSION['ruolo'], $permessi)) {
      header("location: ../404.php");
      exit();
  }

  $numero = 8;
  include "../navbar.php";
?>

<body>
    
    <div class="blocco"></div>
  <div class="container">
    <h1>
      Assemblea
    </h1>

    <!-- Inserisci Atto -->
    <h2>Inserisci Assemblea</h2>
     <form action="../../back/assemblee/handler_assemblea.php" id="formAssemblea" method="POST" enctype="multipart/form-data">
      <label>
        Data Assemblea:
        <input type="date" id="myDate" name="data" required>
      </label>
      <label>
        Oggetto:
        <input type="text" name="oggetto" required>
      </label>
      <label> 
        Ordine del Giorno:
        <input type="text" name="ordine_giorno" required>
      </label> 
       <div id="select Persona" >
      <label for="personInput">Aggiungi persona:</label>
  <input  style="width: 100%;" list="peopleList" id="personInput" class="input-style" placeholder="Cerca nome o codice fiscale">

  <datalist id="peopleList"></datalist>
  <input type="hidden" name="codici_fiscali" id="cfHidden"/>
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
    

<h2>Le Persone Selezionate Per L'assemblea:</h2>
<div id="tableContainer" class="table-container">
    <p id="noSelection">Nessuna Persona Selezionata</p>
    <table id="selectedTable" border="1" style="display: none;">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Cognome</th>
          <th>Codice Fiscale</th>
          <th>Elimina</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <div style="margin: auto; width:20%; padding: 10px;">
    <button form="formAssemblea" type="submit">Crea Assemblea</button><br>
    </div>
    
  </div>

  <h2>Le mie assemblee:</h2>
  <form id="filtroForm" style="width:70%; margin: auto; ">
      <input type="text" id="ricerca" placeholder="Cerca nelle mie prenotazioni" style="width:100%; margin-bottom: 10px; padding: 5px;">
    </form>
  <div class="table-container">
      <table id="tabellaAssemblee">
        <thead>
          <tr>
            <th>Data</th>
            <th>Ordine del Giorno</th>
            <th>Oggetto</th>
            <th>NomeConvocatore</th>
            <th>CognomeConvocatore</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  <script>
  document.addEventListener('DOMContentLoaded', async function () {
    const persone = await caricaPersone();  // aspetta i dati
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("myDate").setAttribute('min', today);

    const personInput = document.getElementById("personInput");
    const peopleList = document.getElementById("peopleList");
    const table = document.getElementById("selectedTable");
    const tbody = table.querySelector("tbody");
    const hiddenInput = document.getElementById("cfHidden");
    const noSelectionMsg = document.getElementById("noSelection");

    const selectedMap = new Map();

    persone.forEach(p => {
      const option = document.createElement("option");
      option.value = `${p.Nome} ${p.Cognome} (${p.CF})`;
      peopleList.appendChild(option);
    });

    personInput.addEventListener("change", () => {
      const val = personInput.value;
      const match = persone.find(p => val.includes(p.CF));
      if (match && !selectedMap.has(match.CF)) {
        selectedMap.set(match.CF, match);
        updateTable();
      }
      personInput.value = "";
    });

    function updateTable() {
      tbody.innerHTML = "";
      const values = Array.from(selectedMap.values());

      if (values.length === 0) {
        table.style.display = "none";
        noSelectionMsg.style.display = "block";
      } else {
        table.style.display = "table";
        noSelectionMsg.style.display = "none";

        values.forEach((p, index) => {
          const row = document.createElement("tr");

          row.innerHTML = `
            <td>${index + 1}</td>
            <td>${p.Nome}</td>
            <td>${p.Cognome}</td>
            <td>${p.CF}</td>
            <td><button type="button" class="bottoniElimina" data-cf="${p.CF}">X</button></td>
          `;

          row.querySelector("button").addEventListener("click", () => {
            selectedMap.delete(p.CF);
            updateTable();
          });

          tbody.appendChild(row);
        });
      }

      // Costruisce un oggetto numerato con i CF
      const cfArray = Array.from(selectedMap.keys());
      const cfObj = {};
      cfArray.forEach((cf, i) => cfObj[i] = cf);
      hiddenInput.value = JSON.stringify(cfObj);
    }
  });

  function caricaPersone() {
    return fetch(`../../back/gestione_utenti/get_persone.php`)
      .then(res => res.json())
      .catch(error => {
        console.error('Errore nel caricamento delle persone:', error);
        return [];
      });
  }

  document.addEventListener('DOMContentLoaded', function() {
      caricaDati();

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

    function caricaDati() {
      fetch(`../../back/assemblee/get_assemblea.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaAssemblee tbody');
          tbody.innerHTML = '';

          data.forEach(persona => {
            
            const row = `
              <tr>
                <td>${persona.Data}</td>
                <td>${persona.OrdineDelGiorno}</td>
                <td>${persona.Oggetto}</td>
                <td>${persona.NomeConvocatore}</td>
                <td>${persona.CognomeConvocatore}</td>
              
              </tr>`;
            tbody.innerHTML += row;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }

    document.getElementById('ricerca').addEventListener('input', function() {
      const ricerca = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tabellaAssemblee  tbody tr');

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

        row.style.display = match ? '' : 'none';
      });
    });
</script>

</body>
</html>
