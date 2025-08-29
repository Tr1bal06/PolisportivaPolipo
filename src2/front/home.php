<?php
include "../back/connessione.php";
include "../back/function.php";
  
// Controlla se la sessione non è già avviata
if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
}


$numero = 0;
        

        $permessi = ['user'];

          if(!controllo($_SESSION['ruolo'], $permessi)) {
              header("location: 404.php");
              exit();
          }

?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script>
  <title>Home</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
      color: white;
      display: flex
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
      width: 80%;
      margin-left: auto;
      padding: 2rem;
    }

     
    .benvenuto {
      border: none;
      font-family: 'Butcherman', cursive;
      color: #a5f3fc;
      font-size: 2.8rem;
      text-align: center;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
      display: flex;
      align-items: center;
      gap: 1rem;
      
    }

    .mascotte {
      font-size: 3rem;
      filter: drop-shadow(0 0 4px rgba(165, 243, 252, 0.4));
    }

    @keyframes wave {
      0%, 100% {
        transform: translateX(0);
      }
      50% {
        transform: translateX(10px);
      }
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

    .notifiche-dinamiche {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  max-height: 300px;
  overflow-y: auto;
  background-color: rgba(255, 255, 255, 0.05);
  padding: 1rem;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  scrollbar-width: thin;
  scrollbar-color: #a5f3fc #1e293b;
  width:70%;
  margin:auto;
}

.notifica-singola {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  background: linear-gradient(to right, #475569 0%, #334155 100%);
  border-radius: 12px;
  border-left: 5px solid #38bdf8;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  transition: transform 0.2s ease, background 0.3s ease;
}

.notifica-singola:hover {
  transform: scale(1.01);
  background: linear-gradient(to right, #3b82f6, #1e3a8a);
}

.notifica-icon {
  font-size: 2rem;
  color: #a5f3fc;
  flex-shrink: 0;
}

.notifica-contenuto {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  color: white;
}

.notifica-tipo {
  font-size: 1.2rem;
  font-weight: bold;
  color: #a5f3fc;
  margin-bottom: 0.3rem;
}

.notifica-info {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  font-size: 0.95rem;
  color: #e2e8f0;
  margin-top: 0.3rem;
}

.notifica-info span {
  background-color: rgba(165, 243, 252, 0.15);
  padding: 0.4rem 0.8rem;
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 0 5px rgba(255, 255, 255, 0.05);
  min-width: 120px;
}


@media (max-width: 768px) {
  .notifica-singola {
    flex-direction: column;
    align-items: flex-start;
  }

  .notifica-icon {
    font-size: 1.6rem;
  }

  .notifica-contenuto {
    align-items: flex-start;
  }
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
      overflow-x: auto;
      background-color: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
      padding: 1rem;
      width: 70%;
      margin: auto;
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
    }
  </style>
</head>

<body>
    <?php
        //includo la connesione e apro la sessione
        include "navbar.php";
        
        $ruolo = ['Allenatore' , 'Atleta' ,'Socio' , 'admin'];
        $check = false;
        if(controllo($_SESSION['ruolo'], $ruolo)) {
          $check = true;
        }
      

        
         //controllo che l'utente sia loggato
        if(!isset($_SESSION['log'])){
            header('Location: login.php');
            exit();
    
        }
    ?>  

    
    
    <div class="blocco"></div>
    <div class="container">
        <div>
           
            <h1 class="benvenuto">Benvenuto <?php echo $_SESSION['nome']. ' '; echo $_SESSION['cognome'];?> nella polisportiva </h1>
            
          </div>

        <div>
            <h1>Notifiche</h1>
            <div class="notifiche-dinamiche">
            <!-- Queste notifiche verranno caricate dinamicamente -->

          </div>

        </div>
        <div id='prenotazioni'>
          <h1>Le mie prenotazioni</h1>
          <div class="table-container">
            <table id="tabella-Notifiche">
              <thead>
                <tr>
                
                  
                  <th>Data</th>
                  <th>Ora Inizio</th>
                  <th>Ora Fine</th>
                  <th>Campo</th>
                  <th>Tipo Attivita</th>
                  <th>Stato</th>
                  
                </tr>
              </thead>
              <tbody>
                <!-- I record verranno caricati via JS -->
              </tbody>
            </table>
        </div>

        </div>
        <div>
          <h1>Le mie Assemblee</h1>
        <div class="table-container">
            <table id="tabellaAssemblee">
              <thead>
                <tr>
                  <th>Data</th>
                  <th>Ordine del giorno</th>
                  <th>Oggetto</th>
                  <th>Nome Convocatore</th>
                  <th>Cognome Convocatore</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
    </div>
</body>
<script>

    const check = <?= $check ? 'true' : 'false' ?>;

  if (!check) {
    const tabella = document.getElementById('prenotazioni');
    if (tabella) {
      tabella.style.display = 'none';
    }
  }

   document.addEventListener('DOMContentLoaded', function() {
          caricaNotifiche();
          caricaPrenotazioni();
          caricaNotifichePrenotatore();

          window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
              window.scrollTo(0, 0);
            }
          });
        });

   function caricaNotifiche() {
  fetch(`../back/notifiche/notifiche.php`)
    .then(res => res.json())
    .then(data => {
      const container = document.querySelector('.notifiche-dinamiche');
      container.innerHTML = '';

      if (data.length === 0) {
        container.innerHTML = '<p>Nessuna notifica disponibile al momento.</p>';
        return;
      }

      data.forEach(notifica => {
        const div = document.createElement('div');
        div.classList.add('notifica-singola');

        const icon = document.createElement('div');
        icon.classList.add('notifica-icon');
        icon.innerHTML = '<i class="fa-solid fa-bell"></i>';

        const contenuto = document.createElement('div');
        contenuto.classList.add('notifica-contenuto');

        const tipo = document.createElement('div');
        tipo.classList.add('notifica-tipo');
        tipo.textContent = `${notifica.TIPO_ATTIVITA} di ${notifica.TipoCampo}`;

        const dettagli = document.createElement('div');
        dettagli.classList.add('notifica-info');
        dettagli.innerHTML = `
          <strong>Campo:</strong> ${notifica.NomeCampo}<br>
          <strong>Inizio:</strong> ${formattaData(notifica.DataTimeInizio)}<br>
          <strong>Fine:</strong> ${formattaData(notifica.DataTimeFine)}
        `;

        contenuto.appendChild(tipo);
        contenuto.appendChild(dettagli);
        div.appendChild(icon);
        div.appendChild(contenuto);
        container.appendChild(div);
      });
    })
    .catch(error => {
      console.error('Errore nel caricamento delle notifiche:', error);
    });
}

function caricaNotifichePrenotatore() {
  fetch(`../back/notifiche/notifiche_richieste.php`)
      .then(res => res.json())
    .then(data => {
      const container = document.querySelector('.notifiche-dinamiche');


      data.forEach(notifica => {
        const div = document.createElement('div');
        div.classList.add('notifica-singola');

        const icon = document.createElement('div');
        icon.classList.add('notifica-icon');
        icon.innerHTML = '<i class="fa-solid fa-bell"></i>';

        const contenuto = document.createElement('div');
        contenuto.classList.add('notifica-contenuto');

        const tipo = document.createElement('div');
        tipo.classList.add('notifica-tipo');
        tipo.textContent = `Conferma Prenotazione ${notifica.TIPO_ATTIVITA} di ${notifica.NomeCampo} in data ${formattaData(notifica.DataTimeInizio)}`;

          const dettagli = document.createElement('div');
          dettagli.classList.add('notifica-info');
          dettagli.innerHTML = `

            <strong>Inizio:</strong> ${formattaData(notifica.DataTimeInizio)}<br>
            <strong>Fine:</strong> ${formattaData(notifica.DataTimeFine)}
          `;

        contenuto.appendChild(tipo);
        contenuto.appendChild(dettagli);
        div.appendChild(icon);
        div.appendChild(contenuto);
        container.appendChild(div);
      });
    })
    .catch(error => {
      console.error('Errore nel caricamento delle notifiche:', error);
    });
}

function formattaData(dateString) {
  const date = new Date(dateString);
  return date.toLocaleString('it-IT', {
    weekday: 'short',
    day: '2-digit',
    month: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  });
}


function caricaPrenotazioni() {
      fetch(`../back/prenotazione/get_prenotazioni.php?prenotante=true`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabella-Notifiche tbody');
         tbody.innerHTML = '';
        

      data.forEach(prenotazione => {
        tipologia = prenotazione.TIPO_ATTIVITA
        
        if(tipologia == "Torneo"){
            colore = "red" 
        }else if(tipologia == "Allenamento"){ 
          colore = "#00ff00" 
        }else if(tipologia == "Partita ufficiale"){
          colore = "yellow" 
        }else if(tipologia == "Evento speciale"){
          colore = "blue" 
        }else if(tipologia == "Riunione tecnica"){
          colore = "orange" 
        }

        const row = `
          <tr>
          
            <td>${formatData(prenotazione.DataTimeInizio)}</td>
            <td>${formatOra(prenotazione.DataTimeInizio)}</td>
            <td>${formatOra(prenotazione.DataTimeFine)}</td>
            <td>${prenotazione.NomeCampo}</td>
            <td style="color:${colore}" >${prenotazione.TIPO_ATTIVITA}</td>
            <td>${prenotazione.Stato ?? 'Confermata'}</td>
            
          </tr>`;
        tbody.innerHTML += row;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }
      
    function formatData(datetime) {
      const d = new Date(datetime);
      return d.toLocaleDateString('it-IT');
    }

 function formatOra(datetime) {
            const d = new Date(datetime);
            return d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
        }
 document.addEventListener('DOMContentLoaded', function() {
      caricaDati();

      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          window.scrollTo(0, 0);
        }
      });
    });

  function caricaDati() {
    fetch(`/ProgettoUDA/back/assemblee/get_assemblea.php`)
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

</script>
</html>