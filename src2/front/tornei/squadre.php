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
    <link rel="stylesheet" href="../../css/toast.css">
    <link rel="stylesheet" href="../../css/card.css">
    <link rel="stylesheet" href="../../css/button.css">
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

.bottoneTogli {
      background-color: red;
      transition: background-color 0.3s ease;
    }
    .bottoneTogli:hover {
      background-color: darkred
    }
    #selectSport {
      padding: 0.6rem;
      border: none;
      border-radius: 5px;
      margin-top: 0.5rem;
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

    #formSquadra {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  width: 70%;
  margin: auto;
}

#formSquadra label {
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

    .atleti-list{
      margin: 5 10px
    }

    .animated-button{
      margin: 5px;
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
  position: absolute;
  bottom: 10px;
  right: 10px;
  background-color: red;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
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
    #formSquadra {
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
        <form action="../../back/tornei/creaSquadra.php" id="formSquadra" method="POST" enctype="multipart/form-data">
        <label>
            Nome della squadra:
            <input type="text"  name="nome_squadra" required>
        </label>
        <label>
            Partecipati:
            <input list="atletiList" id="atletiInput" class="input-style" placeholder="Cerca nome o codice fiscale">
            <datalist id="atletiList"></datalist>
            <input type="hidden" name="codici_fiscali" id="cfHidden"/>
        </label>
        <label> 
           Sport:
          <select name="sport" id="selectSport" required>
            <option value="Basket" selected>Basket</option>
            <option value="Volley">Volley</option>
            <option value="Calcio">Calcio</option>
            <option value="Tennis">Tennis</option>
          </select>

        </label> 
        <label> 
            Logo:
            <input type="file" name="file_img" id="file_img" accept="image/png, image/jpeg, image/jpg, image/x-icon" required><br><br>
        </label><br>
        </form>
            <h2>Le Persone Selezionate Per L'assemblea:</h2>
<div id="tableContainer" class="table-container">
    <p id="noSelection">Nessun Atleta Selezionato</p>
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
    <button  form="formSquadra" type="submit">Crea Squadra</button><br>
    </div>
    
  </div>
    
        
<?php 
        
       
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
    <h1>Le mie squadre:</h1>  
    <!-- Le mie squadre-->
      <div id="squadreContainer"></div>
      
        
        </form>
      <script>
        // per far partire l'evento change allo start
        document.addEventListener("DOMContentLoaded", () => {
          sport.dispatchEvent(new Event("change"));

          caricaSquadre();
        }); 

        const sport = document.getElementById("selectSport");
        const atletiInput = document.getElementById("atletiInput");
        const atletiList = document.getElementById("atletiList");
        const table = document.getElementById("selectedTable");
        const tbody = table.querySelector("tbody");
        const hiddenInput = document.getElementById("cfHidden");
        const noSelectionMsg = document.getElementById("noSelection");  
        
        const selectedMap = new Map();
        let atleti = [];

        // Listener che parte solo una volta
        atletiInput.addEventListener("input", () => {
          const val = atletiInput.value;
          const match = atleti.find(p => val.includes(p.CF));
          if (match && !selectedMap.has(match.CF)) {
            selectedMap.set(match.CF, match);
            updateTable();
          }
          atletiInput.value = "";
        });

        sport.addEventListener("change", async function () {
          atleti = await caricaAtleti(sport.value);  // aspetta i dati

          // Reset lista e tabella
          atletiList.innerHTML = "";
          tbody.innerHTML = "";
          selectedMap.clear();
          updateTable();

          // Popola la datalist
          atleti.forEach(atleta => {
            const option = document.createElement("option");
            option.value = `${atleta.Nome} ${atleta.Cognome} (${atleta.CF})`;
            atletiList.appendChild(option);
          });
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
                <td><button type="button" class="bottoneTogli" data-cf="${p.CF}">X</button></td>
              `;

              row.querySelector("button").addEventListener("click", () => {
                selectedMap.delete(p.CF);
                updateTable();
              });

              tbody.appendChild(row);
            });
          }

          // Costruisce un oggetto numerato con i CF
          const cfObj = {};
          Array.from(selectedMap.keys()).forEach((cf, i) => cfObj[i] = cf);
          hiddenInput.value = JSON.stringify(cfObj);
        }

        function caricaAtleti(sport) {
          return fetch(`../../back/atleta/get_atleti.php?sport=${encodeURIComponent(sport)}`)
            .then(res => res.json())
            .catch(error => {
              console.error("Errore nel caricamento delle persone:", error);
              return [];
            });
        }

        function caricaSquadre() {
  fetch('../../back/tornei/get_squadra.php')
    .then(response => response.json())
    .then(data => {
      const squadreContainer = document.getElementById('squadreContainer');
      squadreContainer.innerHTML = ""; // reset

      data.forEach(squadra => {
        let url = squadra.Logo;
        const params = new URL(url).searchParams;
        const fileId = params.get("id");
        let Sport = squadra.Sport;
        let Nome = squadra.Nome;

        const card = document.createElement('div');
        card.className = 'carta';

        let stringaAtleti = "";
        squadra.Atleti.forEach((atleta, index) => {
          stringaAtleti += `
            <button class="animated-button">
              <span><c>${index + 1}. ${atleta.Nome} ${atleta.Cognome}</c></span>
              <span></span>
            </button>`;
        });

        card.innerHTML = `
          <div class="carta-inner">
            <div id="logo">
              <img src="../../back/proxyImmagini.php?id=${fileId}" alt="Immagine da Drive">
            </div>
            <div class="content">
              <p class="heading">
                Squadra: <span style="color:#afedf7f6;">${Nome}</span>
                
                <button type="button" class="btn" style="background-color:#0d6efd;">${Sport}</button>
              </p>
              <p class="para">
                ATLETI:
                <div id="atletiList${Nome}" class="atleti-list">
                  ${stringaAtleti}
                </div>
              </p>
            </div>
          </div>
          <form action="../../back/tornei/eliminaSquadra.php" method="POST" class="logout" style="display:inline;">
                  <input type="hidden" name="nomeSquadra" value="${Nome}">
                 <button type="submit" class="bottoniElimina">Elimina</button>
          </form>
        `;

        squadreContainer.appendChild(card);
      });
    });
}

      </script>
      <script src="../../js/toast.js"></script>
</body>
</html>