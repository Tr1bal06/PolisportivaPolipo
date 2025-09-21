<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Tabellone Dinamico - Mirror Right (FIX)</title>
<style>
  :root{
    --bg:#061639;
    --accent:#f6c84c;
    --node:#c93b3b;
    --line:#ffffff;
    --text:#e8f0ff;
    --muted:rgba(232,240,255,0.6);
    --node-h:42px;
    --node-w:170px;
    --col-w:220px;
    --gap-vertical:18px;
    font-family:Inter, "Helvetica Neue", Arial, sans-serif;
  }
  html,body{height:100%;margin:0;background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);color:var(--text);}
  .page{padding:18px;}
  h1{margin:0 0 12px 0;color:var(--accent);font-size:18px;}
  .bracket-wrap{position:relative;padding:12px;border-radius:12px;overflow:hidden;}
  .bracket{
    display:flex;
    gap:26px;
    align-items:flex-start;
    justify-content:center;
    position:relative;
    padding:28px 12px;
    min-height:560px;
  }
  .round{flex:0 0 var(--col-w);min-width:150px;position:relative;z-index:2;}
  .round .title{color:var(--muted);font-size:12px;margin-bottom:8px;text-transform:uppercase;font-weight:600;}
  .col-body{position:relative;min-height:420px;}
  .team-node{
    position:absolute;height:var(--node-h);width:var(--node-w);display:flex;align-items:center;gap:10px;padding:6px 10px;
    box-sizing:border-box;border-radius:8px;background:linear-gradient(180deg, #26385d 0%, #364e73 100%);
    color:var(--text);font-weight:700;font-size:13px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;cursor:pointer;
    transition:transform .12s ease,box-shadow .12s ease;
  }
  .team-node .left-bar{width:6px;height:100%;border-radius:6px;background:var(--node);flex-shrink:0;}
  .team-node .label{padding-left:8px;}
  .team-node:hover{transform:translateY(-4px);box-shadow:0 10px 26px rgba(0,0,0,0.55);}
  .center-col{width:240px;display:flex;align-items:center;justify-content:center;flex-direction:column;z-index:3;position:relative;}
  .trophy-wrap{width:120px;height:120px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,rgba(246,200,76,0.12),rgba(246,200,76,0.04));box-shadow:inset 0 3px 12px rgba(246,200,76,0.06);}
  .trophy-wrap svg{width:160px;height:160px;}
  .winner-wrap{display:flex;flex-direction:column;align-items:center;margin-bottom:12px;}
  .winner-text{font-size:16px;font-weight:800;color:var(--accent);display:none;}
  .winner-team{font-size:18px;font-weight:900;color:#fff;display:none;}
  .center-finalists{display:flex;gap:12px;align-items:center;justify-content:center;margin-top:12px;z-index:4;}
  .final-team{width:150px;height:34px;border-radius:8px;display:flex;align-items:center;padding:6px;box-sizing:border-box;background:linear-gradient(180deg, #26385d 0%, #364e73 100%);font-weight:800;cursor:pointer;}
  .svg-lines{position:absolute;left:0;top:0;right:0;bottom:0;z-index:1;pointer-events:none;}
  .modal-backdrop{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(2,6,23,0.6);z-index:9999;}
  .modal{width:420px;background-color: #3a4a7d;padding:16px;border-radius:12px;color:var(--text);box-shadow:0 18px 40px rgba(0,0,0,0.6);}
  .modal h3{margin:0 0 8px 0;color:var(--accent);}
  .teams{display:flex;gap:10px;margin-bottom:10px;}
  .team{flex:1;padding:10px;background:rgba(255,255,255,0.03);border-radius:8px;text-align:center;font-weight:800;}
  .score-form{display:flex;gap:8px;margin-bottom:10px;align-items:center;}
  .score-form input[type="number"]{width:80px;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:var(--text);}
  .actions{display:flex;gap:8px;justify-content:flex-end;}
  .btn{padding:8px 12px;border-radius:8px;cursor:pointer;font-weight:700;border:0;}
  .btn.primary{background:var(--accent);color:#08102a;}
  .btn.ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted);}
  @media (max-width:1100px){:root{--node-w:150px;--col-w:160px;} .round{flex:0 0 var(--col-w);}}
  @media (max-width:720px){.bracket{flex-direction:column;align-items:center;} .center-col{order:-1;margin-bottom:14px;}}
</style>
</head>
<body>
  <div class="page">
    <h1>Tabellone Torneo</h1>
    <div class="bracket-wrap">
      <div id="bracket" class="bracket"></div>
      <svg id="svgLines" class="svg-lines"></svg>

      <!-- modal -->
      <div id="modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal" role="document">
          <h3>Inserisci risultato</h3>
          <div id="modalMatchLabel" style="color:var(--muted);font-size:13px;margin-bottom:8px"></div>
          <div class="teams">
            <div id="teamA" class="team">Team A</div>
            <div id="teamB" class="team">Team B</div>
          </div>
          <form id="scoreForm">
            <div class="score-form">
              <input id="scoreA" type="number" min="0" placeholder="0" required />
              <span style="color:var(--muted);">-</span>
              <input id="scoreB" type="number" min="0" placeholder="0" required />
            </div>
            <div class="actions">
              <button type="button" id="closeModal" class="btn ghost">Annulla</button>
              <button type="submit" class="btn primary">Salva</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

<script>
/* ================= CONFIGURAZIONE DI PROVA =================
   Cambia SLOTS_COUNT (4/8/16) e teamSlots per testare.
===========================================================*/
const SLOTS_COUNT = 4; // 4,8 o 16

const teamSlots = {
  1: "Paris",
  2: "Liverpool",
  3: "Club Brugge",
  4: "Aston Villa",
  5: "Real Madrid",
  6: "Atleti",
  7: "PSV",
  8: "Arsenal",
  9: "Benfica",
 10: "Barcellona",
 11: "B. Dortmund",
 12: "Lille",
 13: "Bayern",
 14: "Leverkusen",
 15: "Feyenoord",
 16: "Inter"
};

let layoutLeft = [];

const bracketEl = document.getElementById('bracket');
const svg = document.getElementById('svgLines');
const modal = document.getElementById('modal');
const teamAEl = document.getElementById('teamA');
const teamBEl = document.getElementById('teamB');
const scoreA = document.getElementById('scoreA');
const scoreB = document.getElementById('scoreB');
const modalMatchLabel = document.getElementById('modalMatchLabel');

const nodeH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--node-h')) || 42;
const nodeW = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--node-w')) || 170;
const colW = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--col-w')) || 220;
const gap = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--gap-vertical')) || 18;

function clearAll(){
  bracketEl.innerHTML = '';
  while(svg.firstChild) svg.removeChild(svg.firstChild);
}

function createRound(title){
  const r = document.createElement('div'); r.className='round';
  const t = document.createElement('div'); t.className='title'; t.textContent = title;
  const body = document.createElement('div'); body.className='col-body';
  r.appendChild(t); r.appendChild(body);
  bracketEl.appendChild(r);
  return body;
}

function build(slots, slotsMap){
  if (![4,8,16].includes(slots)) throw new Error("Slots must be 4,8 or 16");
  clearAll();

  const half = Math.floor(slots/2);
  if(!layoutLeft || layoutLeft.length !== half){
    layoutLeft = [];
    for(let i=1;i<=half;i++) layoutLeft.push(i);
  }

  const rounds = Math.log2(slots); // per lato

  // left columns
  const leftCols = [];
  for(let r=0;r<rounds;r++) leftCols.push(createRound( `Round ${r+1}`));

  // center column (trofeo + winner badge + finalists small boxes)
  const centerCol = document.createElement('div'); centerCol.className='center-col';
  const winnerWrap = document.createElement('div'); winnerWrap.className='winner-wrap';
  const winnerText = document.createElement('div'); winnerText.className='winner-text'; winnerText.textContent = 'WINNER!';
  const winnerTeam = document.createElement('div'); winnerTeam.className='winner-team'; winnerTeam.id = 'winnerTeam';
  winnerWrap.appendChild(winnerText); winnerWrap.appendChild(winnerTeam);
  centerCol.appendChild(winnerWrap);

  const trophy = document.createElement('div'); trophy.className='trophy-wrap';
  trophy.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="300.000000pt" height="450.000000pt" viewBox="0 0 300.000000 450.000000" preserveAspectRatio="xMidYMid meet">
<metadata>
Created by potrace 1.10, written by Peter Selinger 2001-2011
</metadata>
<g transform="translate(0.000000,450.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
<path d="M501 3790 c-98 -21 -199 -107 -255 -219 -76 -149 -76 -406 -1 -666 97 -334 297 -691 529 -943 45 -48 90 -110 110 -150 48 -95 142 -233 220 -322 49 -57 64 -80 60 -97 -7 -27 6 -51 61 -113 49 -56 52 -67 30 -111 -24 -45 -90 -87 -184 -116 -126 -38 -143 -61 -167 -234 l-6 -46 49 -6 c119 -16 515 -28 693 -22 245 8 441 22 453 34 5 5 1 49 -9 102 -21 116 -39 138 -145 169 -98 29 -139 51 -177 95 -43 49 -41 74 13 135 55 62 68 86 61 113 -3 15 12 40 60 96 79 91 173 230 219 321 20 39 66 102 110 150 431 469 689 1195 553 1557 -63 169 -194 276 -338 277 -82 0 -132 -17 -182 -62 -109 -98 -114 -242 -13 -360 57 -67 59 -98 10 -140 -32 -28 -117 -54 -131 -39 -3 3 15 28 40 56 51 57 59 96 27 125 -12 11 -59 23 -132 33 -165 25 -941 25 -1108 0 -62 -9 -124 -23 -137 -32 -39 -25 -32 -67 21 -124 25 -26 45 -51 45 -55 0 -23 -109 6 -144 39 -40 37 -35 73 19 137 172 201 7 472 -254 418z m110 -40 c72 -14 123 -51 154 -113 41 -84 28 -154 -46 -246 -40 -50 -49 -68 -49 -100 0 -71 85 -140 173 -141 54 0 43 -24 -13 -28 -85 -6 -187 54 -210 124 -17 50 -6 92 39 152 47 62 65 112 57 156 -13 69 -81 103 -168 85 -65 -14 -118 -65 -160 -155 -30 -64 -33 -78 -36 -199 -3 -75 1 -166 8 -215 40 -271 243 -768 395 -965 17 -22 39 -60 48 -84 l17 -45 -31 30 c-17 16 -63 72 -103 124 -192 255 -351 578 -422 864 -32 124 -43 332 -24 428 33 170 142 302 271 327 24 5 45 9 45 10 1 1 25 -4 55 -9z m1935 -20 c83 -36 152 -118 195 -229 96 -253 -21 -729 -287 -1167 -82 -135 -248 -354 -268 -354 -11 0 40 99 74 145 79 105 177 304 264 536 152 406 171 711 53 885 -57 86 -166 122 -244 81 -58 -31 -66 -120 -17 -192 13 -19 35 -51 49 -71 55 -81 16 -173 -95 -223 -53 -24 -150 -29 -150 -7 0 9 18 15 51 19 62 8 107 32 136 77 37 53 30 87 -31 167 -56 75 -71 122 -60 184 27 145 182 214 330 149z m-1891 -147 c30 -30 15 -100 -35 -167 -54 -72 -65 -138 -31 -203 38 -76 136 -133 228 -133 18 0 33 -3 33 -7 -1 -5 -26 -28 -57 -53 -119 -95 -111 -83 -123 -204 -15 -146 5 -357 50 -530 11 -44 18 -81 16 -83 -8 -8 -114 201 -164 322 -125 300 -171 473 -179 676 -6 148 3 211 42 289 23 45 53 78 90 98 32 18 111 15 130 -5z m1826 2 c79 -41 130 -172 130 -332 -1 -192 -35 -343 -143 -628 -52 -137 -185 -415 -199 -415 -4 0 -1 22 6 48 20 66 52 259 60 362 3 47 1 135 -5 195 -12 122 -2 105 -118 200 -34 28 -62 53 -62 58 0 4 15 7 33 7 68 0 137 29 188 80 75 75 80 160 14 251 -48 65 -66 115 -53 148 5 14 15 29 21 33 21 13 97 9 128 -7z m-721 -195 c176 -8 305 -21 383 -36 30 -6 37 -34 13 -53 -11 -9 -29 -10 -71 -2 -189 35 -915 40 -1140 7 -104 -15 -114 -12 -115 26 0 10 11 19 28 22 66 14 229 29 382 36 247 10 307 11 520 0z m122 -110 c70 -5 149 -12 177 -16 l50 -6 -14 -29 c-21 -39 -19 -103 4 -147 21 -41 98 -117 148 -147 40 -23 43 -40 43 -234 0 -440 -172 -915 -435 -1199 l-67 -72 -288 0 -288 0 -67 73 c-130 141 -233 318 -311 534 -46 126 -100 344 -114 453 -11 89 -14 370 -4 406 3 11 20 29 37 39 54 32 129 108 148 150 23 49 24 106 4 145 l-15 29 55 6 c188 24 706 32 937 15z m-82 -1898 c0 -10 -17 -35 -38 -55 l-38 -37 -227 1 -226 2 -37 38 c-67 68 -65 69 271 69 277 0 295 -1 295 -18z m-105 -155 c-16 -84 65 -168 195 -202 l45 -12 -87 -8 c-97 -8 -471 -12 -644 -6 -121 4 -160 14 -98 26 124 23 224 131 194 210 l-10 25 206 0 205 0 -6 -33z m-205 -260 c201 0 397 4 435 10 80 12 92 9 101 -20 9 -27 33 -151 30 -154 -1 -1 -132 -8 -290 -14 -219 -9 -352 -9 -554 0 -147 7 -268 13 -270 15 -7 8 29 151 43 166 13 14 23 15 78 7 34 -6 226 -10 427 -10z"/>
<path d="M1035 3201 c-2 -2 -19 -7 -37 -11 -18 -4 46 -5 142 -3 232 6 274 18 64 18 -91 0 -167 -2 -169 -4z"/>
<path d="M1595 3200 c43 -10 161 -13 153 -5 -2 3 -47 7 -99 9 -67 3 -83 2 -54 -4z"/>
<path d="M1846 3166 c11 -8 26 -32 32 -53 6 -21 26 -53 44 -70 36 -34 104 -73 128 -73 8 0 -10 12 -40 26 -62 28 -100 65 -120 115 -15 40 -39 69 -54 69 -6 0 -1 -6 10 -14z"/>
<path d="M990 3105 c-7 -22 -18 -43 -24 -47 -8 -5 -7 -8 2 -8 15 0 45 65 39 83 -2 7 -10 -6 -17 -28z"/>
<path d="M1415 2994 c-238 -37 -432 -207 -501 -439 -13 -42 -18 -91 -17 -165 0 -127 31 -235 93 -328 30 -45 40 -69 40 -100 0 -32 7 -49 30 -76 37 -41 80 -57 146 -55 67 2 167 -36 189 -73 31 -54 116 -76 148 -40 23 25 21 41 -5 52 -19 7 -16 9 19 9 32 1 45 -4 52 -18 13 -24 71 -51 108 -51 36 0 89 32 98 60 11 34 -11 51 -56 44 -26 -4 -39 -3 -39 5 0 6 6 11 14 11 7 0 34 11 60 25 33 17 63 25 99 25 66 0 116 31 142 90 16 36 17 47 7 86 -11 39 -10 49 7 82 51 101 67 274 37 405 -59 254 -289 441 -555 452 -47 2 -99 1 -116 -1z m264 -69 c224 -71 377 -270 389 -505 5 -107 -7 -180 -42 -257 -18 -37 -31 -52 -48 -55 -19 -2 -24 -10 -28 -48 -4 -38 -9 -46 -29 -48 -15 -3 -33 6 -52 24 -33 32 -36 44 -11 44 28 0 81 37 102 72 24 39 26 104 5 155 -26 61 -20 82 25 100 46 18 52 43 18 67 -51 36 -137 4 -164 -61 -13 -30 -14 -47 -4 -102 11 -62 10 -67 -10 -83 -26 -21 -60 -11 -77 23 -20 38 -16 65 22 143 49 100 62 183 41 264 -28 112 -102 192 -208 228 -75 25 -196 16 -266 -20 -108 -55 -172 -157 -172 -274 0 -77 14 -121 55 -179 87 -119 92 -139 48 -185 -30 -31 -76 -37 -107 -15 -15 11 -17 22 -12 53 20 128 19 158 -6 194 -31 46 -93 68 -135 48 -15 -7 -29 -23 -31 -35 -2 -16 4 -24 32 -33 48 -15 52 -39 20 -102 -53 -102 -29 -202 63 -259 30 -19 52 -24 104 -24 l67 0 -14 -26 c-15 -31 -70 -79 -88 -79 -24 0 -47 36 -41 63 9 33 -24 54 -56 37 -27 -15 -39 -4 -77 72 -43 87 -62 167 -62 268 0 104 16 165 66 261 73 138 205 244 354 283 92 24 235 20 329 -9z m-34 -85 c147 -71 196 -272 105 -435 -36 -66 -43 -97 -30 -145 12 -46 42 -70 86 -70 55 0 67 26 59 123 -7 79 -6 85 16 110 29 33 75 48 109 35 l25 -10 -25 -10 c-71 -28 -79 -42 -60 -105 34 -119 23 -173 -43 -213 -43 -26 -65 -25 -110 6 -21 14 -40 24 -42 22 -2 -2 31 -40 74 -86 60 -63 83 -82 104 -82 38 0 69 34 65 72 -4 38 16 48 32 17 20 -36 6 -100 -29 -131 -44 -39 -90 -45 -146 -18 -44 21 -95 79 -95 109 0 18 -41 84 -47 77 -3 -3 2 -23 11 -46 21 -52 20 -81 -4 -158 -16 -50 -17 -67 -8 -87 11 -26 22 -30 71 -26 33 3 35 -12 5 -33 -47 -33 -118 -13 -143 41 -14 30 -15 43 -4 103 12 67 11 69 -19 134 -17 36 -33 66 -35 66 -2 0 -5 -21 -5 -46 -2 -52 -20 -99 -62 -165 -17 -25 -30 -58 -30 -72 0 -27 30 -67 50 -67 6 0 10 -4 10 -10 0 -5 -15 -10 -34 -10 -81 0 -116 81 -78 182 24 65 33 134 22 180 -4 18 -8 25 -9 14 -1 -23 -87 -161 -120 -193 -79 -76 -207 -69 -246 13 -19 40 -19 54 1 82 21 31 34 27 34 -10 0 -65 69 -90 125 -46 26 20 112 185 102 194 -2 3 -16 -11 -31 -31 -25 -33 -31 -35 -82 -35 -70 0 -115 19 -149 64 -40 52 -43 102 -11 171 14 32 26 69 26 83 0 30 -26 62 -51 62 -26 0 -33 18 -9 25 49 15 108 -21 126 -77 3 -11 -2 -53 -12 -94 -20 -86 -12 -114 37 -134 59 -25 124 8 149 74 14 39 2 74 -51 143 -51 66 -68 111 -69 187 0 83 17 131 68 187 53 59 84 77 165 93 72 15 150 6 212 -24z m-250 -920 c-15 -35 -24 -71 -22 -82 6 -22 4 -22 -54 -2 l-47 17 42 31 c22 18 55 55 71 83 39 66 45 37 10 -47z m200 -21 l-7 -89 -49 0 c-27 0 -49 3 -49 7 0 4 15 32 34 63 19 30 40 74 47 98 20 68 32 27 24 -79z m190 21 l28 -29 -48 -21 c-26 -11 -49 -19 -51 -17 -5 4 30 97 37 97 3 0 18 -13 34 -30z"/>
<path d="M1395 2820 c-3 -5 -3 -10 2 -10 19 -1 -24 -20 -45 -20 -13 0 -20 -4 -17 -10 16 -25 66 0 71 36 3 16 -2 18 -11 4z"/>
<path d="M1287 2733 c5 -12 2 -15 -10 -10 -12 5 -17 1 -17 -13 0 -23 10 -26 28 -8 14 14 16 48 2 48 -5 0 -6 -8 -3 -17z"/>
<path d="M1396 2669 c-34 -27 -27 -35 13 -15 19 10 41 14 57 10 20 -5 25 -3 22 7 -8 23 -61 22 -92 -2z"/>
<path d="M1657 2683 c-15 -14 -6 -23 18 -17 15 4 32 1 40 -6 20 -17 32 -5 15 15 -13 15 -61 21 -73 8z"/>
<path d="M1245 2620 c4 -11 2 -20 -4 -20 -6 0 -11 -4 -11 -10 0 -5 7 -10 15 -10 10 0 15 10 15 30 0 17 -5 30 -11 30 -6 0 -8 -9 -4 -20z"/>
<path d="M1424 2590 c-90 -36 -58 -194 39 -194 65 0 108 64 87 128 -20 62 -71 88 -126 66z m86 -40 c12 -12 20 -33 20 -55 0 -75 -83 -104 -120 -42 -25 41 -25 58 1 91 25 32 70 35 99 6z"/>
<path d="M1420 2520 c-14 -26 -5 -57 21 -71 27 -14 49 4 49 40 0 49 -49 70 -70 31z"/>
<path d="M1643 2585 c-57 -40 -46 -149 17 -175 33 -14 58 -4 89 36 61 78 -28 194 -106 139z m82 -30 c28 -27 31 -51 13 -90 -16 -36 -85 -49 -95 -18 -3 7 3 10 15 7 40 -11 57 57 19 80 -15 9 -21 7 -33 -8 -12 -17 -14 -18 -14 -3 0 21 35 57 55 57 9 0 27 -11 40 -25z"/>
<path d="M1520 2342 c0 -17 39 -44 65 -44 27 0 75 27 75 42 0 14 -8 13 -40 -7 -26 -15 -32 -15 -57 -3 -15 8 -31 16 -35 18 -5 2 -8 -1 -8 -6z"/>
<path d="M885 2931 c3 -6 -3 -14 -12 -20 -15 -9 -13 -10 13 -11 20 0 34 6 38 16 4 10 13 13 28 9 18 -5 20 -4 8 4 -19 13 -83 14 -75 2z"/>
<path d="M1890 2914 c25 -14 53 -33 63 -41 9 -9 17 -12 17 -6 0 5 -11 16 -25 23 -13 7 -22 18 -18 23 3 6 1 7 -5 3 -7 -4 -12 -3 -12 3 0 6 30 12 68 14 54 2 47 3 -33 5 l-100 2 45 -26z"/>
<path d="M1468 1493 c17 -2 47 -2 65 0 17 2 3 4 -33 4 -36 0 -50 -2 -32 -4z"/>
<path d="M1603 1130 c15 -25 32 -47 38 -49 7 -2 -1 14 -16 35 -44 59 -51 64 -22 14z"/>
<path d="M1392 1126 c-6 -13 -24 -39 -42 -59 -20 -23 -25 -35 -15 -33 16 2 45 26 31 26 -4 0 4 14 18 31 14 17 26 37 26 45 0 20 -5 17 -18 -10z"/>
<path d="M1070 906 c6 -6 59 -10 120 -11 60 0 106 3 102 7 -12 10 -233 15 -222 4z"/>
<path d="M833 803 c-12 -2 -26 -10 -32 -17 -15 -18 4 -33 54 -41 l40 -6 -32 15 c-38 17 -40 24 -15 42 9 7 15 13 12 12 -3 0 -15 -3 -27 -5z"/>
<path d="M2158 788 c19 -19 15 -24 -29 -37 -54 -16 -45 -23 15 -12 61 11 78 27 52 47 -25 18 -56 20 -38 2z"/>
<path d="M1248 703 c23 -2 61 -2 85 0 23 2 4 4 -43 4 -47 0 -66 -2 -42 -4z"/>
<path d="M1663 703 c26 -2 67 -2 90 0 23 2 2 3 -48 3 -49 0 -68 -1 -42 -3z"/>
</g>
</svg>`;
  centerCol.appendChild(trophy);

  // finalists small boxes (these are dynamic and clickable)
  const finalistsWrap = document.createElement('div'); finalistsWrap.className='center-finalists';
  const finalLeftBox = document.createElement('div'); finalLeftBox.className='final-team'; finalLeftBox.style.display='none';
  const finalRightBox = document.createElement('div'); finalRightBox.className='final-team'; finalRightBox.style.display='none';
  finalistsWrap.appendChild(finalLeftBox); finalistsWrap.appendChild(finalRightBox);
  centerCol.appendChild(finalistsWrap);

  bracketEl.appendChild(centerCol);

  // right columns (we will create them and append, but for the building logic we will use a reversed copy)
  const rightCols = [];
  for(let r=rounds;r>0;r--) rightCols.push(createRound( `Round ${r}`));

  // helper create node
  const nodesBySlot = {};
  function makeNode(slot, container, alignLeft){
    const el = document.createElement('div'); el.className='team-node';
    el.style.width = nodeW + 'px';
    el.style.left = alignLeft ? '0px' : (colW - nodeW) + 'px';
    el.innerHTML = `<div class="left-bar"></div><div class="label"></div>`;
    container.appendChild(el);
    return { el, slot, name:'', present:false, children:[] };
  }

  // layout first column left with layoutLeft order and compute top positions
  function layoutFirstLeft(body, order){
    const count = order.length;
    const totalHeight = count * nodeH + (count-1) * gap;
    body.style.height = (totalHeight + 20) + 'px';
    const arr = [];
    for(let i=0;i<count;i++){
      const slot = order[i];
      const n = makeNode(slot, body, true);
      const top = i * (nodeH + gap);
      n.el.style.top = top + 'px';
      arr.push(n);
      nodesBySlot[slot] = n;
    }
    return arr;
  }

  const leftLeafs = layoutFirstLeft(leftCols[0], layoutLeft);

  const colonnaTrofeo  = document.querySelector('.center-col');

  if(SLOTS_COUNT === 4) colonnaTrofeo.style.height = '180px';
  else if(SLOTS_COUNT === 8) colonnaTrofeo.style.height = '300px';
  else if(SLOTS_COUNT === 16) colonnaTrofeo.style.height = '540px';

  // RIGHT: we need to populate the column *più esterna* (farthest from center),
  // quindi usiamo una copia reversed di rightCols per il build.
  const rightColsForBuild = rightCols.slice().reverse();

  // Right ordering of slots (default half+1 .. slots)
  const layoutRight = [];
  for(let i=0;i<half;i++) layoutRight.push(half + 1 + i); // natural order (9..16 for 16 slots)

  function layoutFirstRight(body, rightOrder, leftPositions){
    const arr = [];
    // make sure body height equals left first body height (to align)
    const leftBodyHeight = leftCols[0].style.height || '';
    if(leftBodyHeight) body.style.height = leftBodyHeight;
    for(let i=0;i<rightOrder.length;i++){
      const slot = rightOrder[i];
      const n = makeNode(slot, body, false);
      // correggiamo: stabiliamo la stessa posizione verticale (top) della corrispondente foglia di sinistra
      // (non passiamo il round vicino al trofeo, ma il round piu' esterno grazie a rightColsForBuild)
      const leftNode = leftPositions[i]; // stessa riga verticale: top-to-top (specchia rispetto all'asse verticale)
      let top = 0;
      if(leftNode && leftNode.el && leftNode.el.style.top) top = parseFloat(leftNode.el.style.top);
      n.el.style.top = top + 'px';
      arr.push(n);
      nodesBySlot[slot] = n;
    }
    return arr;
  }

  // important: pass the *outermost* right column body (rightColsForBuild[0])
  const rightLeafs = layoutFirstRight(rightColsForBuild[0], layoutRight, leftLeafs);

  // populate names from teamSlots, hide absent
  Object.keys(nodesBySlot).forEach(k=>{
    const slot = parseInt(k,10);
    const name = slotsMap[slot] || '';
    const node = nodesBySlot[k];
    if(!name){ node.el.style.display='none'; node.present=false; }
    else { node.el.querySelector('.label').textContent = name; node.name = name; node.present=true; }
  });

  // build parent levels: for left (inward), for right we must use rightColsForBuild (outer->inner order)
  function buildParentLevels(cols, leafs, alignLeft){
    let current = leafs.slice();
    const levels = [];
    for(let r=1;r<rounds;r++){
      const parentArr = [];
      const body = cols[r]; // cols is expected to be ordered: [round1Body (parents of leaves), round2Body, ...]
      // set body height so connectors align
      const totalHeight = current.length * nodeH + (current.length-1)*gap;
      body.style.height = (totalHeight + 40) + 'px';
      for(let p=0;p<Math.ceil(current.length/2);p++){
        const el = document.createElement('div'); el.className='team-node';
        el.style.width = nodeW + 'px';
        el.style.left = alignLeft ? '0px' : (colW - nodeW) + 'px';
        el.innerHTML = `<div class="left-bar"></div><div class="label"></div>`;
        body.appendChild(el);
        const childA = current[p*2] || null;
        const childB = current[p*2+1] || null;
        const top = (childA && childB) ? ((parseFloat(childA.el.style.top) + parseFloat(childB.el.style.top))/2) : (childA ? parseFloat(childA.el.style.top) : (childB ? parseFloat(childB.el.style.top) : 0));
        el.style.top = top + 'px';
        const obj = { el, children:[childA, childB], present:false, name:'' };
        const nA = childA && childA.present ? childA.name : '';
        const nB = childB && childB.present ? childB.name : '';
        if(!nA && !nB){ obj.el.style.display='none'; obj.present=false; }
        else { obj.present=true; obj.name = nA ? nA : nB; obj.el.querySelector('.label').textContent = obj.name + (nA && nB ? '' : ' (bye)'); }
        parentArr.push(obj);
      }
      levels.push(parentArr);
      current = parentArr;
    }
    return levels;
  }

  // build left parents normally (cols: leftCols)
  const leftParents = buildParentLevels(leftCols, leftLeafs, true);
  // build right parents using rightColsForBuild (outermost->inner)
  const rightParents = buildParentLevels(rightColsForBuild, rightLeafs, false);

  // last-level source to feed center finalists
  const leftFinalSource = leftParents.length ? leftParents[leftParents.length-1][0] : leftLeafs[0];
  const rightFinalSource = rightParents.length ? rightParents[rightParents.length-1][0] : rightLeafs[0];

  // populate center small boxes (these are dynamic and clickable)
  if(leftFinalSource && leftFinalSource.present){ finalLeftBox.textContent = leftFinalSource.name; finalLeftBox.style.display='flex'; } else { finalLeftBox.style.display='none'; }
  if(rightFinalSource && rightFinalSource.present){ finalRightBox.textContent = rightFinalSource.name; finalRightBox.style.display='flex'; } else { finalRightBox.style.display='none'; }

  // collect matches for drawing and modal mapping
  function collectMatches(levels, leafs, sideName){
    const list = [];
    // leaf pairings
    for(let i=0;i<leafs.length;i+=2){
      const a = leafs[i] || null;
      const b = leafs[i+1] || null;
      const parent = levels[0] ? levels[0][Math.floor(i/2)] : null;
      list.push({ side:sideName, round:0, index:Math.floor(i/2), a,b,parent });
    }
    // higher rounds
    for(let r=1;r<levels.length;r++){
      const lvl = levels[r-1];
      for(let i=0;i<lvl.length;i+=2){
        const a = lvl[i] || null; const b = lvl[i+1] || null;
        const parent = levels[r] ? levels[r][Math.floor(i/2)] : null;
        list.push({ side:sideName, round:r, index:Math.floor(i/2), a,b,parent });
      }
    }
    return list;
  }

  const leftMatches = collectMatches(leftParents, leftLeafs, 'left');
  const rightMatches = collectMatches(rightParents, rightLeafs, 'right');

  // final center match object (uses small boxes as visible DOM)
  const finalObjA = { present: !!(leftFinalSource && leftFinalSource.present), name: leftFinalSource ? leftFinalSource.name : '', source: leftFinalSource };
  const finalObjB = { present: !!(rightFinalSource && rightFinalSource.present), name: rightFinalSource ? rightFinalSource.name : '', source: rightFinalSource };
  const finalMatch = { side:'center', round:rounds, index:0, a: finalObjA, b: finalObjB, parent:null };

  const allMatches = [...leftMatches, ...rightMatches, finalMatch];

  // helper: find match for clicked node element (including center small boxes)
  function findMatchForNode(el){
    for(const m of allMatches){
      if(m.side === 'center'){
        if(el === finalLeftBox || el === finalRightBox) return m;
      } else {
        if(m.a && m.a.el === el) return m;
        if(m.b && m.b.el === el) return m;
        if(m.a && m.a.children && m.a.children.some(ch => ch && ch.el === el)) return m;
        if(m.b && m.b.children && m.b.children.some(ch => ch && ch.el === el)) return m;
      }
    }
    return null;
  }

  // resolve competitors names for a match
  function resolveCompetitors(match){
    function rep(part){
      if(!part) return 'TBA';
      if(part.source) {
        if(part.source.present && part.source.name) return part.source.name;
        if(part.source.children && part.source.children.length){
          for(const ch of part.source.children){
            if(ch && ch.present && ch.name) return ch.name;
          }
        }
        return 'TBA';
      }
      if(part.children && part.children.length){
        for(const ch of part.children){
          if(!ch) continue;
          if(ch.present && ch.name) return ch.name;
          if(ch.children && ch.children.length){
            for(const ch2 of ch.children){ if(ch2 && ch2.present && ch2.name) return ch2.name; }
          }
        }
      }
      if(part.present && part.name) return part.name;
      return 'TBA';
    }
    return [rep(match.a), rep(match.b)];
  }

  // attach click handlers to leaves and parents
  const clickable = [];
  leftLeafs.forEach(n=>clickable.push(n));
  rightLeafs.forEach(n=>clickable.push(n));
  leftParents.forEach(lvl=>lvl.forEach(n=>clickable.push(n)));
  rightParents.forEach(lvl=>lvl.forEach(n=>clickable.push(n)));

  clickable.forEach(n=>{
    if(!n || !n.el) return;
    n.el.addEventListener('click', (ev)=>{
      ev.stopPropagation(); if(!n.present) return;
      const match = findMatchForNode(n.el); if(!match) return;
      const [a,b] = resolveCompetitors(match);
      modalMatchLabel.textContent = `Match ${match.side} r${match.round} i${match.index}`;
      teamAEl.textContent = a; teamBEl.textContent = b; scoreA.value=''; scoreB.value='';
      modal.style.display='flex';
      modal.dataset.match = JSON.stringify({side:match.side, round:match.round, index:match.index});
    });
  });

  // click handlers for center small boxes (finalists)
  finalLeftBox.addEventListener('click', (ev)=>{
    ev.stopPropagation();
    const match = findMatchForNode(finalLeftBox); if(!match) return;
    const [a,b] = resolveCompetitors(match);
    modalMatchLabel.textContent = `Finale`;
    teamAEl.textContent = a; teamBEl.textContent = b; scoreA.value=''; scoreB.value='';
    modal.style.display='flex';
    modal.dataset.match = JSON.stringify({side:match.side, round:match.round, index:match.index});
  });
  finalRightBox.addEventListener('click', (ev)=>{
    ev.stopPropagation();
    const match = findMatchForNode(finalRightBox); if(!match) return;
    const [a,b] = resolveCompetitors(match);
    modalMatchLabel.textContent = `Finale`;
    teamAEl.textContent = a; teamBEl.textContent = b; scoreA.value=''; scoreB.value='';
    modal.style.display='flex';
    modal.dataset.match = JSON.stringify({side:match.side, round:match.round, index:match.index});
  });

  document.getElementById('closeModal').addEventListener('click', ()=> modal.style.display='none');
  modal.addEventListener('click', (ev)=>{ if(ev.target === modal) modal.style.display='none'; });

  // submit scores
  document.getElementById('scoreForm').addEventListener('submit', (ev)=>{
    ev.preventDefault();
    const aVal = parseInt(scoreA.value || '0',10);
    const bVal = parseInt(scoreB.value || '0',10);
    if(isNaN(aVal) || isNaN(bVal)) return;
    const dat = JSON.parse(modal.dataset.match || 'null'); if(!dat && dat!==null) return;
    let match = null;
    for(const m of allMatches){
      if(m.side === 'center'){ match = m; break; } // center final case
      if(m.side === dat.side && m.round === dat.round && m.index === dat.index){ match = m; break; }
    }
    if(!match) return;
    const [nameA, nameB] = resolveCompetitors(match);
    let winner = null;
    if(aVal > bVal) winner = nameA;
    else if(bVal > aVal) winner = nameB;
    else { alert("Pareggio: inserisci punteggio vincente."); return; }

    // advance winner
    if(match.side === 'left' || match.side === 'right'){
      if(match.parent){
        match.parent.present = true;
        match.parent.name = winner;
        match.parent.el.style.display = '';
        match.parent.el.querySelector('.label').textContent = winner;
      } else {
        // final feeder: this is last side round -> put into center final boxes
        if(match.side === 'left'){
          finalObjA.present = true; finalObjA.name = winner; finalObjA.source = match.a || match.b || match;
          finalLeftBox.textContent = winner; finalLeftBox.style.display='flex';
        } else {
          finalObjB.present = true; finalObjB.name = winner; finalObjB.source = match.a || match.b || match;
          finalRightBox.textContent = winner; finalRightBox.style.display='flex';
        }
      }
    } else if(match.side === 'center'){
      // final decided -> mostra WINNER sopra il trofeo e la squadra
      winnerText.style.display = 'block';
      winnerTeam.style.display = 'block';
      winnerTeam.textContent = winner;
    }

    modal.style.display='none';
    drawLines();
  });

  // draw squarish white L-lines behind nodes
  function drawLines(){
    while(svg.firstChild) svg.removeChild(svg.firstChild);
    const br = bracketEl.getBoundingClientRect();
    svg.setAttribute('width', br.width);
    svg.setAttribute('height', br.height);

    function rect(el){ const r = el.getBoundingClientRect(); return {left:r.left-br.left, right:r.right-br.left, top:r.top-br.top, bottom:r.bottom-br.top, cx:r.left-br.left + r.width/2, cy:r.top-br.top + r.height/2}; }

    function drawList(list, isLeft){
      list.forEach(m=>{
        const a = m.a, b = m.b, parent = m.parent;
        if(!a || !b || !parent) return;
        const visible = (a.present || b.present || parent.present);
        [a,b].forEach(child=>{
          if(!child || !child.el || !parent.el) return;
          const c = rect(child.el);
          const p = rect(parent.el);
          const startX = isLeft ? c.right : c.left;
          const startY = c.cy;
          const endX = isLeft ? p.left : p.right;
          const endY = p.cy;
          const midX = (startX + endX)/2;
          const path = document.createElementNS("http://www.w3.org/2000/svg","path");
          const d = `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`;
          path.setAttribute('d', d);
          path.setAttribute('stroke', 'var(--line)');
          path.setAttribute('stroke-width', visible ? '3' : '1.4');
          path.setAttribute('fill','none');
          path.setAttribute('stroke-linecap','round');
          path.setAttribute('stroke-linejoin','round');
          path.style.opacity = visible ? '1' : '0.22';
          svg.appendChild(path);
        });
      });
    }

    drawList(leftMatches, true);
    drawList(rightMatches, false);

    // draw connections from last left/right sources to trophy
    const trophyEl = centerCol.querySelector('.trophy-wrap');
    if(leftFinalSource && leftFinalSource.el && trophyEl){
      const a = rect(leftFinalSource.el); const t = rect(trophyEl);
      const startX = a.right; const startY = a.cy;
      const endX = t.left + 8; const endY = t.cy;
      const midX = (startX + endX)/2;
      const path = document.createElementNS("http://www.w3.org/2000/svg","path");
      path.setAttribute('d', `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`);
      path.setAttribute('stroke','var(--line)'); path.setAttribute('stroke-width','3'); path.setAttribute('fill','none');
      svg.appendChild(path);
    }
    if(rightFinalSource && rightFinalSource.el && trophyEl){
      const b = rect(rightFinalSource.el); const t = rect(trophyEl);
      const startX = b.left; const startY = b.cy;
      const endX = t.right - 8; const endY = t.cy;
      const midX = (startX + endX)/2;
      const pathR = document.createElementNS("http://www.w3.org/2000/svg","path");
      pathR.setAttribute('d', `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`);
      pathR.setAttribute('stroke','var(--line)'); pathR.setAttribute('stroke-width','3'); pathR.setAttribute('fill','none');
      svg.appendChild(pathR);
    }
  }

  // initial draw
  setTimeout(()=>{ drawLines(); window.addEventListener('resize', drawLines); }, 80);
}

// run builder
build(SLOTS_COUNT, teamSlots);

</script>
</body>
</html>
