<?php
/* File: torneo.php (modificato per mostrare i nodi "Da definire") */
?>
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
  /* stile per i nodi "Da definire" (più tenui) */
  .team-node.muted{ opacity: 0.6; box-shadow: none; border: 1px dashed rgba(255,255,255,0.06); }
  .team-node.muted .label { color: var(--muted); font-weight:700; }
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
  @media (max-width:1100px){:root{--node-w:150px;--col-w:160px;} .round{flex:0 0 var(--col-w);} }
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
/* ================= CONFIG =================
   Variabili server-side (PHP) iniettate nel JS
   SLOTS_COUNT: 4,8 o 16
   EdizioneTorneo, AnnoTorneo
===========================================*/
const SLOTS_COUNT = <?= intval($_POST['MaxSquadre']); ?>; // 4,8 o 16
const EdizioneTorneo = <?= $_POST['Codice'] ?>;
const AnnoTorneo = '<?= $_POST['Anno'] ?>';

// =================== INIT fetch squads ===================
init(EdizioneTorneo, AnnoTorneo).then(teamSlots => {
  console.log("Mappa completa:", teamSlots);
  build(SLOTS_COUNT, teamSlots);
});

async function init(EdizioneTorneo, AnnoTorneo) {
  try {
    const res = await fetch("../../back/tornei/get_squadre_torneo.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `EdizioneTorneo=${EdizioneTorneo}&AnnoTorneo=${AnnoTorneo}`
    });

    const data = await res.json();

    let teamSlots = {};

    data.forEach((match, index) => {
      const slotHome = index * 2 + 1; // slot dispari
      const slotAway = index * 2 + 2; // slot pari

      teamSlots[slotHome] = match.SquadraCasa;
      teamSlots[slotAway] = match.SquadraOspite;
    });

    // fill remaining internal slots with placeholder 'Da definire'
    const totalSlots = SLOTS_COUNT * 2 - 1;
    for(let i=1;i<=totalSlots;i++){
      if(typeof teamSlots[i] === 'undefined' || teamSlots[i] === null) teamSlots[i] = 'Da definire';
    }

    return teamSlots;
  } catch (err) {
    console.error("Errore durante init:", err);
    return {};
  }
}

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
<!-- SVG TRONCATED FOR BREVITY (kept in original file) -->
</g>
</svg>`;
  centerCol.appendChild(trophy);

  // finalists small boxes (these are dynamic and clickable)
  const finalistsWrap = document.createElement('div'); finalistsWrap.className='center-finalists';
  const finalLeftBox = document.createElement('div'); finalLeftBox.className='final-team';
  const finalRightBox = document.createElement('div'); finalRightBox.className='final-team';
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
      // stabiliamo la stessa posizione verticale (top) della corrispondente foglia di sinistra
      const leftNode = leftPositions[i];
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

  // populate names from slotsMap for leafs (do NOT hide "Da definire" — show them muted)
  Object.keys(nodesBySlot).forEach(k=>{
    const slot = parseInt(k,10);
    const node = nodesBySlot[k];
    // take name from provided slotsMap; fallback to 'Da definire' if missing
    const name = (slotsMap && typeof slotsMap[slot] !== 'undefined') ? slotsMap[slot] : 'Da definire';
    node.el.querySelector('.label').textContent = name;
    node.name = (name && name !== 'Da definire') ? name : '';
    node.present = (name && name !== 'Da definire');
    node.el.classList.toggle('muted', name === 'Da definire');
  });

  // build parent levels: for left (inward), for right we must use rightColsForBuild (outer->inner order)
  function buildParentLevels(cols, leafs, alignLeft, slotsMap, startSlot){
    let current = leafs.slice();
    const levels = [];
    let slotId = startSlot; // starting slot id for parent nodes

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

        // position vertically between children if possible
        const top = (childA && childB) ? ((parseFloat(childA.el.style.top) + parseFloat(childB.el.style.top))/2) : (childA ? parseFloat(childA.el.style.top) : (childB ? parseFloat(childB.el.style.top) : 0));
        el.style.top = top + 'px';

        // label taken directly from the provided slotsMap (fallback 'Da definire')
        const name = (slotsMap && typeof slotsMap[slotId] !== 'undefined') ? slotsMap[slotId] : 'Da definire';
        el.querySelector('.label').textContent = name;

        const obj = { el, slot: slotId, children:[childA, childB], present: (name && name !== 'Da definire'), name: (name && name !== 'Da definire') ? name : '' };

        // show node even if "Da definire" — just style muted
        el.classList.toggle('muted', name === 'Da definire');

        // store reference by slot id for later lookup
        nodesBySlot[slotId] = obj;

        parentArr.push(obj);
        slotId++;
      }

      levels.push(parentArr);
      current = parentArr;
    }

    return { levels, nextSlotId: slotId };
  }

  // build left parents normally (cols: leftCols)
  const leftRes = buildParentLevels(leftCols, leftLeafs, true, slotsMap, slots+1);
  const leftParents = leftRes.levels;
  let nextSlot = leftRes.nextSlotId;
  // build right parents using rightColsForBuild (outermost->inner)
  const rightRes = buildParentLevels(rightColsForBuild, rightLeafs, false, slotsMap, nextSlot);
  const rightParents = rightRes.levels;

  // last-level source to feed center finalists
  const leftFinalSource = leftParents.length ? leftParents[leftParents.length-1][0] : leftLeafs[0];
  const rightFinalSource = rightParents.length ? rightParents[rightParents.length-1][0] : rightLeafs[0];

  // populate center small boxes (these are dynamic and clickable)
  // always show the finalist boxes, even if "Da definire"
  const leftFinalName = (leftFinalSource && leftFinalSource.name) ? leftFinalSource.name : (leftFinalSource && slotsMap && typeof slotsMap[leftFinalSource.slot] !== 'undefined' ? slotsMap[leftFinalSource.slot] : 'Da definire');
  const rightFinalName = (rightFinalSource && rightFinalSource.name) ? rightFinalSource.name : (rightFinalSource && slotsMap && typeof slotsMap[rightFinalSource.slot] !== 'undefined' ? slotsMap[rightFinalSource.slot] : 'Da definire');

  finalLeftBox.textContent = leftFinalName;
  finalLeftBox.style.display = 'flex';
  finalLeftBox.classList.toggle('muted', leftFinalName === 'Da definire');

  finalRightBox.textContent = rightFinalName;
  finalRightBox.style.display = 'flex';
  finalRightBox.classList.toggle('muted', rightFinalName === 'Da definire');

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
  const finalObjA = { present: !!(leftFinalSource && leftFinalSource.present), name: leftFinalName === 'Da definire' ? '' : leftFinalName, source: leftFinalSource };
  const finalObjB = { present: !!(rightFinalSource && rightFinalSource.present), name: rightFinalName === 'Da definire' ? '' : rightFinalName, source: rightFinalSource };
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
      ev.stopPropagation(); if(!n.present) return; // non aprire modal se nodo non ha partecipanti definiti
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
        match.parent.el.classList.remove('muted');
      } else {
        // final feeder: this is last side round -> put into center final boxes
        if(match.side === 'left'){
          finalObjA.present = true; finalObjA.name = winner; finalObjA.source = match.a || match.b || match;
          finalLeftBox.textContent = winner; finalLeftBox.style.display='flex'; finalLeftBox.classList.remove('muted');
        } else {
          finalObjB.present = true; finalObjB.name = winner; finalObjB.source = match.a || match.b || match;
          finalRightBox.textContent = winner; finalRightBox.style.display='flex'; finalRightBox.classList.remove('muted');
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
          // if none of the participants are defined, draw thinner/faded line
          path.setAttribute('stroke-width', visible ? '3' : '1.2');
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

// run builder (called after init)
</script>
</body>
</html>
