<?php
$numero=3;
include "../navbar.php";
include "../../back/connessione.php";
include "../../back/function.php";
//FILE DEPRECATO
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../../css/navbar.css">
  <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario Prenotazioni</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
      color: white;
      display: flex;
      overflow:hidden ;
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
      overflow:auto ;
    }

    .input-style {
      padding: 0.5rem;
      border: none;
      border-radius: 5px;
      margin-top: 0.5rem;
      font-family: inherit;
      font-size: 1rem;
    }

    label {
      flex: 1 1 calc(50% - 1rem);
      display: flex;
      flex-direction: column;
      color: #fff;
    }

    .container {
      width: 80%;
      padding: 2rem;
      overflow: auto;
    }

    h1, h2 { margin-bottom: 1rem; }
    h1 {
      font-size: 2.5rem;
      border-bottom: 2px solid #88bde9;
      padding-bottom: 0.5rem;
    }

    .slot.pending {
      background: #f4a261;
      cursor: not-allowed;
    }

    .sports-nav {
      display: flex;
      justify-content: space-around;
      margin-bottom: 1rem;
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


    .sports-nav button {
      flex: 1;
      padding: 0.75rem;
      margin: 0 0.5rem;
      background: rgba(255,255,255,0.1);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .sports-nav button.active,
    .sports-nav button:hover {
      background: #5c7ae3;
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

    .date-scroller {
      position: relative;
      overflow: hidden;
      margin-bottom: 1rem;
    }
    .date-list {
      display: flex;
      overflow-x: auto;
      scroll-behavior: smooth;
      padding: 0.5rem;
    }
    .date-item {
      flex: 0 0 auto;
      padding: 0.75rem 1rem;
      margin: 0 .25rem;
      background: rgba(255,255,255,0.1);
      border-radius: 6px;
      text-align: center;
      cursor: pointer;
    }
    .date-item.active {
      background: #aadfff;
      color: #3a4a7d;
    }
    .scroll-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255,255,255,0.2);
      border: none;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 50%;
      font-size: 1.2rem;
    }
    .scroll-btn.prev { left: 0.5rem; }
    .scroll-btn.next { right: 0.5rem; }

    .fields {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .field-row {
      display: grid;
      grid-template-columns: 200px 1fr;
      align-items: center;
      column-gap: 1rem;
    }
    .slots-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
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

    .input-style {
  width: 100%;
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  margin-bottom: 12px;
  font-size: 14px;
}

.form-group {
  margin-bottom: 16px;
}


    .field-name {
      padding: 0.5rem;
      font-weight: bold;
    }
    .slot {
      padding: 0.5rem;
      border-radius: 4px;
      text-align: center;
      cursor: pointer;
      background: #88bde9;
      color: #3a4a7d;
      min-width: 50px;
    }
    .slot.booked {
      background: #ff8b94;
      cursor: not-allowed;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.8);
      justify-content: center;
      align-items: center;
      z-index: 100;
      overflow: auto;
    }
    .modal.active {
      display: flex;
    }
    
    .modal-content {
      background: #3a4a7d;
      padding: 2rem;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      color: white;
    }
    .modal-content h2 {
      color: #aadfff;
    }
    .modal-content label {
      display: block;
      margin-top: 1rem;
    }
    .modal-content .actions {
      margin-top: 2rem;
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
    }
    .actions{
      justify-content: space-evenly!important;
    }

    .legend-tooltip {
  position: absolute;
  background: white;
  color: #333;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-size: 0.9rem;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  z-index: 1000;
  display: none;
  flex-direction: column;
  gap: 0.5rem;
  transform: translateY(5px);
}

.legend-tooltip .legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
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

.legend-tooltip .color-box {
  width: 15px;
  height: 15px;
  border-radius: 3px;
}

.legend-tooltip .available {
  background: #88bde9;
}

.legend-tooltip .pending {
  background: #f4a261;
}

.legend-tooltip .booked {
  background: #ff8b94;
}

.legend-tooltip .arrow {
  position: absolute;
  top: -8px;
  left: 10px;
  width: 0;
  height: 0;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 8px solid white;
}
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

   

    @media (max-width: 768px) {
      .field-row {
        grid-template-columns: 1fr;
      }
      .sports-nav {
        flex-wrap: wrap;
      }
      .blocco {
        display: none;
      }
    }

    @media (max-width: 600px) {
      .popup-content {
        width: 95%;
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
<div class="blocco"></div>
<div class="container">
  <h1>Prenota il tuo sport   <i class="fa-solid fa-circle-info" id="legendIcon" style="margin-left: 0.5rem; cursor: pointer;"></i></h1>
  
  <div id="legendTooltip" class="legend-tooltip">
  <div class="arrow"></div>
  <div class="legend-item"><span class="color-box available"></span> Disponibile</div>
  <div class="legend-item"><span class="color-box pending"></span> In Conferma</div>
  <div class="legend-item"><span class="color-box booked"></span> Prenotato</div>
</div>

  <div class="sports-nav" id="sportsNav">
    <button data-sport="volley" class="active">Volley</button>
    <button data-sport="basket">Basket</button>
    <button data-sport="calcio">Calcio</button>
    <button data-sport="tennis">Tennis</button>
  </div>

  <div class="date-scroller">
    <button class="scroll-btn prev" id="prevBtn">&lt;</button>
    <div class="date-list" id="dateList"></div>
    <button class="scroll-btn next" id="nextBtn">&gt;</button>
  </div>

  <div class="fields" id="fieldsContainer"></div>

  <div class="modal" id="recapModal">
    <div class="modal-content">
      <h2>Conferma Prenotazione</h2>
      <form action="../../back/prenotazione/handler_prenotazione.php" enctype="multipart/form-data" method="post" id="recapForm">
        <label>Sport:<input type="text" name="sport" class="input-style" readonly></label>
        <label>Data Prenotazione:<input type="date" name="data" class="input-style" readonly></label>
        <label>Ora Inizio:
          <input type="time" name="oraInizio" class="input-style" step="3600" min="07:00" max="22:00" readonly>
        </label>

        <label>Ora Fine:
          <select name="oraFine" id="oraFineSelect" class="input-style" required></select>

        </label>
        <label>Campo:<input type="text" name="campo" class="input-style" readonly></label>
        <? //  TODO AGGIUNGERE CAMPO per inserire l'identificatore del convocatore chiedi a jin cosa vuole ?>
        
        <!-- Selezione Attività -->
         <div>
         <div class="form-group">
              <label for="attivitaSelect">Attività (Se torneo la prenotazione è per tutto il giorno)</label>
              <select name="attivita" id="attivitaSelect" class="input-style" required>
                <option value="">-- Seleziona attività --</option>
                <option value="Torneo">Torneo</option>
                <option value="Allenamento">Allenamento</option>
                <option value="Partita ufficiale">Partita ufficiale</option>
                <option value="Evento speciale">Evento speciale</option>
                <option value="Riunione tecnica">Riunione tecnica</option>
              </select>
            </div>

            <!-- Sezioni dinamiche -->
            <div id="torneoFields" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="nomeTorneo">Nome Torneo</label>
                <input type="text" name="nomeTorneo" class="input-style">
              </div>
              <div class="form-group">
              <label>
                Regolamento PDF:
              </label>
              <input type="file" name="file_pdf" accept=".pdf" >
              </div>

            </div>

            <div id="allenamentoFields" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="tipo">Tipo Allenamento</label>
                <input type="text" name="tipo" class="input-style">
              </div>
            </div>

            <div id="partitaFields" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="arbitro">Arbitro</label>
                <input type="text" name="arbitro" class="input-style">
              </div>
            </div>

            <div id="eventoFields" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="causaleEvento">Causale Evento</label>
                <input type="text" name="causale" class="input-style" >
              </div>
            </div>
    
            <div id="riunioneFields" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="causaleRiunione">Causale Riunione</label>
                <input type="text" name="causale" class="input-style" id="causaleRiunione">
              </div>
            </div>
         </div>
        <div class="actions" style=" width: 100%;" >
          <button type="button"  id="cancelBtn">Annulla</button>
          <button type="submit">Conferma</button>
        </div>
      </form>
      

      <? if(isset($_SESSION['error_message'])){
          echo $_SESSION['error_message'];
          $_SESSION['error_message'] = NULL ;
         } 
         

         if(isset($_SESSION['success_message'])){
          echo $_SESSION['success_message'];
          $_SESSION['success_message'] = NULL ;
         }
      ?>
      
    </div>
  </div>
  
  <h1 style="margin-top: 50px;">Visualizza le Prenotazioni  </h1>

  <form id="filtroForm">
      <label>
        Periodo:
        <select name="periodo" id="periodoSelect" class="input-style" >
                <option value="giornaliero" selected>Giornaliere</option>
                <option value="settimanale">Settimanali</option>
                <option value="mensile">Mensile</option>
            </select>
      </label>
      <label>
        Cerca per Data:
        <input type="date" id="data" name="data" min="" value="" required>

      </label>
      <label>
        Cerca per Tipo Attività :
        <select name="attivita" id="filtroAttivita" class="input-style" >
                <option value="">-- Seleziona attività --</option>
                <option value="Torneo">Torneo</option>
                <option value="Allenamento">Allenamento</option>
                <option value="Partita ufficiale">Partita ufficiale</option>
                <option value="Evento speciale">Evento speciale</option>
                <option value="Riunione tecnica">Riunione tecnica</option>
        </select>
      </label>
      <button type="submit">Filtra</button>
    </form>

    <div class="table-container">
      <table id="tabellaAtti">
        <thead>
          <tr>
          
            
            <th>Data
              
            </th>
            <th>Ora Inizio</th>
            <th>Ora Fine</th>
            <th>Campo</th>
            <th>Tipo Attivita</th>
            <th>Stato</th>
            <th>modifica</th>
            
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS -->
        </tbody>
      </table>
    </div>
    <!-- Popup Modifica -->
  <div id="popupModifica" style="display:none;" class="popup-overlay">
    <div class="popup-content">
      <button class="close-popup" onclick="chiudiPopup()">✕</button>
      <h2>Modifica Persona</h2>
      <form action="../../back/prenotazione/handler_prenotazione_modifica.php" enctype="multipart/form-data" method="post" id="recapForm">
        <label>Sport:<input type="text" name="sport" id="popup-sport" class="input-style" readonly></label>
        <label>Data Prenotazione:<input type="date" id="popup-data"  name="data" class="input-style" readonly></label>
        <label>Ora Inizio:
          <input type="time" name="oraInizio" id="popup-oraI" class="input-style" step="3600" min="07:00" max="22:00" readonly>
        </label>

        <label>Ora Fine:
          <select name="oraFine" id="oraFineSelect"   class="input-style" required></select>

        </label>
        <label>Campo:<input type="text"id="popup-campo" name="campo" class="input-style" readonly></label>
        <? //  TODO AGGIUNGERE CAMPO per inserire l'identificatore del convocatore chiedi a jin cosa vuole ?>
        
        <!-- Selezione Attività -->
         <div>
         <div class="form-group">
              <label for="attivitaSelect">Attività</label>
              <select name="attivita" id="attivitaSelect2" class="input-style" required >
                <option value="">-- Seleziona attività --</option>
                <option value="Torneo">Torneo</option>
                <option value="Allenamento">Allenamento</option>
                <option value="Partita ufficiale">Partita ufficiale</option>
                <option value="Evento speciale">Evento speciale</option>
                <option value="Riunione tecnica">Riunione tecnica</option>
              </select>
            </div>

            <?
              include "../../back/connessione.php";
              include "../../back/function.php";
              session_start();
              $allenatore = 'Allenatore';
              $atleta = 'Atleta';
              if(!controllo($_SESSION['ruolo'], $allenatore)) {
                if(controllo($_SESSION['ruolo'], $atleta)) { ?>
                  <div class="form-group">
                    <label for="emailPrenotante" id="popup-emailPrenotante">Email Confermatore</label>
                    <input type="text" name="emailprenotante" class="input-style">
                  </div>
                <?}
              }
            ?>

            <!-- Sezioni dinamiche -->
            <div id="torneoFields2" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="nomeTorneo" id="popup-NomeTorneo">Nome Torneo</label>
                <input type="text" name="nomeTorneo" class="input-style">
              </div>
              <div class="form-group">
              <label>
                Regolamento PDF:
              </label>
              <input type="file" name="file_pdf" accept=".pdf" required>
              </div>

            </div>

            <div id="allenamentoFields2" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="tipo">Tipo Allenamento</label>
                <input type="text" name="tipo" id="popup-TipoAllenamento" class="input-style">
              </div>
            </div>

            <div id="partitaFields2" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="arbitro">Arbitro</label>
                <input type="text"  id="popup-Arbitro" name="arbitro" class="input-style">
              </div>
            </div>

            <div id="eventoFields2" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="causaleEvento">Causale Evento</label>
                <input type="text" name="causale" class="input-style" id="causaleEvento">
              </div>
            </div>
    
            <div id="riunioneFields2" class="activity-field" style="display:none">
              <div class="form-group">
                <label for="causaleRiunione">Causale Riunione</label>
                <input type="text" name="causale" class="input-style" id="causaleRiunione">
              </div>
            </div>
         </div>
        
      </form>
      <div style="display: flex; justify-content: space-around;">
        <button type="submit" form="forModifica" style="background-color:#4c5c96" class="btn-modifica">Modifica</button>
        <form class="logout" action="../../back/gestione_utenti/elimina_persona.php" method="POST">
          <input type="hidden" id="BottoneElimina" name="cf" value=""><button class="bottoniElimina" type="submit">elimina</button>
        </form>
      </div>

    </div>
  </div>
</div>
 
<script>
  
  const oggi = new Date().toISOString().split('T')[0];
  const inputData = document.getElementById('data');
  inputData.value = oggi;
  inputData.min = oggi;

const DAYS_TO_SHOW = 31, SLOT_START = 8, SLOT_END = 22;
let FIELDS = {}, BOOKINGS = [];
let selectedSport = 'volley', selectedDate = null;

const sportsNav = document.getElementById('sportsNav'),
      dateList = document.getElementById('dateList'),
      fieldsContainer = document.getElementById('fieldsContainer');
const prevBtn = document.getElementById('prevBtn'),
      nextBtn = document.getElementById('nextBtn');
const modal = document.getElementById('recapModal'),
      form = document.getElementById('recapForm');

init();

async function init() {
  try {
    await fetchFields();
    await fetchBookings();
    renderDates();
    renderFields();
    attachEvents();
  } catch (err) {
    console.error("Errore inizializzazione:", err);
  }
}

async function fetchFields() {
  const res = await fetch('../../back/prenotazione/get_campi.php');
  const data = await res.json();
  FIELDS = {};
  for (const key in data) {
    FIELDS[key.toLowerCase()] = data[key];
  }
}

async function fetchBookings() {
  const res = await fetch('../../back/prenotazione/get_prenotazioni.php');
  BOOKINGS = await res.json();
}

function renderDates() {
  dateList.innerHTML = '';
  const today = new Date();
  for (let i = 0; i < DAYS_TO_SHOW; i++) {
    const d = new Date(today.getTime() + i * 86400000);
    const day = d.toLocaleDateString('it-IT', { weekday: 'short' });
    const md = ("0"+d.getDate()).slice(-2) + "/" + ("0"+(d.getMonth()+1)).slice(-2);
    const el = document.createElement('div');
    el.className = 'date-item' + (i === 0 ? ' active' : '');
    el.dataset.date = d.toISOString().split('T')[0];
    el.innerHTML = `<div>${day}</div><div>${md}</div>`;
    el.onclick = () => selectDate(el);
    dateList.append(el);
    if (i === 0) selectedDate = el.dataset.date;
  }
}

function selectDate(el) {
  dateList.querySelectorAll('.date-item').forEach(e => e.classList.remove('active'));
  el.classList.add('active');
  selectedDate = el.dataset.date;
  renderFields();
}

function getSlotStatus(campo, date, hour) {
  for (const { NomeCampo, DataTimeInizio, DataTimeFine, Stato } of BOOKINGS) {
    if (NomeCampo !== campo) continue;
    const start = new Date(DataTimeInizio);
    const end = new Date(DataTimeFine);
    const slotTime = new Date(date + 'T' + String(hour).padStart(2, '0') + ':00:00');

    if (slotTime >= start && slotTime < end) {
      if (Stato === 'NonConfermato') return 'pending';
      return 'booked';
    }
  }
  return null;
}


function renderFields() {
  fieldsContainer.innerHTML = '';
  if (!FIELDS[selectedSport]) return;

  FIELDS[selectedSport].forEach(name => {
    const row = document.createElement('div');
    row.className = 'field-row';

    const title = document.createElement('div');
    title.className = 'field-name';
    title.textContent = name;
    row.appendChild(title);

    const slotWrapper = document.createElement('div');
    slotWrapper.className = 'slots-wrapper';

    for (let h = SLOT_START; h < SLOT_END; h++) {
      const slot = document.createElement('div');
      slot.className = 'slot';
      slot.textContent = `${h}:00`;

      const status = getSlotStatus(name, selectedDate, h);
      if (status === 'booked') {
        slot.classList.add('booked');
      } else if (status === 'pending') {
        slot.classList.add('pending');
      } else {
        slot.onclick = () => openModal(name, h);

      }


      slotWrapper.appendChild(slot);
    }

    row.appendChild(slotWrapper);
    fieldsContainer.appendChild(row);
  });
}

function openModal(campo, oraInizio) {
  const formatHour = h => h.toString().padStart(2, '0') + ':00';

  modal.classList.add('active');
  form.sport.value = selectedSport;
  form.data.value = selectedDate;
  form.campo.value = campo;
  form.oraInizio.value = formatHour(oraInizio);

  // Pulisci e genera opzioni oraFine valide
  const oraFineSelect = document.getElementById('oraFineSelect');
  oraFineSelect.innerHTML = '';

  const maxHour = Math.min(oraInizio + 3, 22); // max 3 ore o max 22
  for (let h = oraInizio + 1; h <= maxHour; h++) {
    const opt = document.createElement('option');
    opt.value = formatHour(h);
    opt.textContent = formatHour(h);
    oraFineSelect.appendChild(opt);
  }
}



function attachEvents() {
  sportsNav.querySelectorAll('button').forEach(btn => btn.onclick = () => {
    sportsNav.querySelectorAll('button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedSport = btn.dataset.sport;
    renderFields();
  });
  prevBtn.onclick = () => dateList.scrollBy({left: -200, behavior: 'smooth'});
  nextBtn.onclick = () => dateList.scrollBy({left: 200, behavior: 'smooth'});
  document.getElementById('cancelBtn').onclick = () => modal.classList.remove('active');
  
  window.onclick = e => { if (e.target === modal) modal.classList.remove('active'); };
}


const legendIcon = document.getElementById('legendIcon');
const legendTooltip = document.getElementById('legendTooltip');

legendIcon.addEventListener('mouseenter', () => {
  const rect = legendIcon.getBoundingClientRect();
  legendTooltip.style.top = `${rect.bottom + window.scrollY + 5}px`;
  legendTooltip.style.left = `${rect.left + window.scrollX}px`;
  legendTooltip.style.display = 'flex';
});

legendIcon.addEventListener('mouseleave', () => {
  setTimeout(() => {
    if (!legendTooltip.matches(':hover')) {
      legendTooltip.style.display = 'none';
    }
  }, 200);
});

legendTooltip.addEventListener('mouseleave', () => {
  legendTooltip.style.display = 'none';
});

document.getElementById('attivitaSelect').addEventListener('change', function () {
  const value = this.value;
  
  // Nasconde tutto
  const sections = ['torneoFields', 'allenamentoFields', 'partitaFields', 'eventoFields', 'riunioneFields'];
  sections.forEach(id => document.getElementById(id).style.display = 'none');

  // Mostra quello corretto
  switch (value) {
    case 'Torneo':
      document.getElementById('torneoFields').style.display = 'block';
      break;
    case 'Allenamento':
      document.getElementById('allenamentoFields').style.display = 'block';
      break;
    case 'Partita ufficiale':
      document.getElementById('partitaFields').style.display = 'block';
      break;
    case 'Evento speciale':
      document.getElementById('eventoFields').style.display = 'block';
      break;
    case 'Riunione tecnica':
      document.getElementById('riunioneFields').style.display = 'block';
      break;
  }
});

document.getElementById('attivitaSelect2').addEventListener('change', function () {
  const value = this.value;
  
  // Nasconde tutto
  const sections = ['torneoFields2', 'allenamentoFields2', 'partitaFields2', 'eventoFields2', 'riunioneFields2'];
  sections.forEach(id => document.getElementById(id).style.display = 'none');

  // Mostra quello corretto
  switch (value) {
    case 'Torneo':
      document.getElementById('torneoFields2').style.display = 'block';
      break;
    case 'Allenamento':
      document.getElementById('allenamentoFields2').style.display = 'block';
      break;
    case 'Partita ufficiale':
      document.getElementById('partitaFields2').style.display = 'block';
      break;
    case 'Evento speciale':
      document.getElementById('eventoFields2').style.display = 'block';
      break;
    case 'Riunione tecnica':
      document.getElementById('riunioneFields2').style.display = 'block';
      break;
  }
});


let ordineData = 'DESC'; // stato iniziale per l'ordinamento

  document.addEventListener('DOMContentLoaded', function () {
    // Inizializza caricamento dati al primo avvio
    caricaDati();

    // Evento filtro form
    document.getElementById('filtroForm').addEventListener('submit', function (e) {
      e.preventDefault();
      caricaDati();
    });

    

    // Gestione resize
    window.addEventListener('resize', function () {
      if (window.innerWidth > 768) {
        window.scrollTo(0, 0);
      }
    });
  });

  function caricaDati() {
  const periodo = document.getElementById('periodoSelect').value;
  const data = document.getElementById('data').value;
  const attivita = document.getElementById('filtroAttivita').value;

  const params = new URLSearchParams({
    periodo: periodo,
    data: data,
    attivita: attivita,
  });

  fetch(`../../back/prenotazione/get_prenotazione_filtro.php?${params.toString()}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#tabellaAtti tbody');
      tbody.innerHTML = '';
        

      data.forEach(prenotazione => {
        tipologia = prenotazione.TIPO_ATTIVITA
        
        if(tipologia == "Torneo"){
            colore = "red" 
        }else if(tipologia == "Allenamento"){
          colore = "green" 
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
            <td>
                  <button type="button" id="bottone${prenotazione.NomeCampo+prenotazione.DataTimeInizio}" class="" onclick='apriPopup(${JSON.stringify(prenotazione)})'>Modifica</button>
                </td> 
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

function apriPopup(persona) {
  console.log(persona);
  document.getElementById('popup-sport').value = persona.TipoCampo;           // Sport
  document.getElementById('popup-data').value = formatData(persona.DataTimeInizio);             // Data Prenotazione
  document.getElementById('popup-oraI').value = formatOra(persona.DataTimeInizio);          // Ora Inizio
  //  document.getElementById('oraFineSelect').value =  formatOra(persona.DataTimeFine);       // Ora Fine (select)
  document.getElementById('popup-campo').value = persona.NomeCampo;      // Campo
  document.getElementById('attivitaSelect2').value = persona;       // Attività (select)
  document.getElementById('popup-NomeTorneo');                 // Etichetta Nome Torneo (non un input)
  document.getElementById('popup-TipoAllenamento').value = persona; // Tipo Allenamento
  document.getElementById('popup-Arbitro').value = persona;         // Arbitro
  document.getElementById('causaleEvento').value = persona;         // Causale Evento
  document.getElementById('causaleRiunione').value = persona;       // Causale Riunione

      document.getElementById('popupModifica').style.display = 'flex';
}

function chiudiPopup() {
  document.getElementById('popupModifica').style.display = 'none';
}



</script>
</body>
</html> 
