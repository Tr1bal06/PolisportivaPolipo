<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Tabellone Dinamico - Mirror Right</title>
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
    html,body{height:100%;margin:0;background:linear-gradient(180deg,var(--bg) 0%, #0b2a5b 100%);color:var(--text);}
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
      position:absolute;height:var(--node-h);width:var(--node-w);
      display:flex;align-items:center;gap:10px;padding:6px 10px;box-sizing:border-box;border-radius:8px;
      background:linear-gradient(180deg,rgba(0,0,0,0.18),rgba(0,0,0,0.06));
      color:var(--text);font-weight:700;font-size:13px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;
      cursor:pointer; transition:transform .12s ease,box-shadow .12s ease;
    }
    .team-node .left-bar{width:6px;height:100%;border-radius:6px;background:var(--node);flex-shrink:0;}
    .team-node .label{padding-left:8px;}
    .team-node:hover{transform:translateY(-4px);box-shadow:0 10px 26px rgba(0,0,0,0.55);}
    .center-col{width:260px;display:flex;align-items:center;justify-content:center;flex-direction:column;z-index:3;position:relative;}
    .winner-title{font-weight:900;color:var(--accent);font-size:16px;margin-bottom:6px;display:none;}
    .winner-team{font-weight:900;background:linear-gradient(90deg,#ffd76b,#f6c84c);padding:6px 10px;border-radius:8px;color:#08102a;display:none;margin-bottom:10px;}
    .trophy-wrap{width:120px;height:120px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,rgba(246,200,76,0.12),rgba(246,200,76,0.04));box-shadow:inset 0 3px 12px rgba(246,200,76,0.06);}
    .trophy-wrap svg{width:72px;height:72px;}
    .center-finalists{display:flex;gap:12px;align-items:center;justify-content:center;margin-top:12px;z-index:4;}
    .final-team{width:150px;height:34px;border-radius:8px;display:flex;align-items:center;padding:6px;box-sizing:border-box;background:linear-gradient(180deg,rgba(0,0,0,0.08),rgba(0,0,0,0.03));font-weight:800;cursor:pointer;}
    .svg-lines{position:absolute;left:0;top:0;right:0;bottom:0;z-index:1;pointer-events:none;}
    .match-badge { font-size:11px; font-weight:800; pointer-events:none; }
    .modal-backdrop{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(2,6,23,0.6);z-index:9999;}
    .modal{width:420px;background:linear-gradient(180deg,#07172f,#05112a);padding:16px;border-radius:12px;color:var(--text);box-shadow:0 18px 40px rgba(0,0,0,0.6);}
    .modal h3{margin:0 0 8px 0;color:var(--accent);}
    .teams{display:flex;gap:10px;margin-bottom:10px;}
    .team{flex:1;padding:10px;background:rgba(255,255,255,0.03);border-radius:8px;text-align:center;font-weight:800;}
    .score-form{display:flex;gap:8px;margin-bottom:10px;align-items:center;}
    .score-form input[type="number"]{width:80px;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:var(--text);}
    .actions{display:flex;gap:8px;justify-content:flex-end;}
    .btn{padding:8px 12px;border-radius:8px;cursor:pointer;font-weight:700;border:0;}
    .btn.primary{background:var(--accent);color:#08102a;}
    .btn.ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted);}

    @media (max-width:1100px){
      :root{--node-w:150px;--col-w:160px;}
      .round{flex:0 0 var(--col-w);}
    }
    @media (max-width:720px){
      .bracket{flex-direction:column;align-items:center;}
      .center-col{order:-1;margin-bottom:14px;}
    }
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
  /* CONFIGURAZIONE */
  const SLOTS_COUNT = 16; // 4,8 o 16
  const teamSlots = {
    1: "Paris", 2: "Liverpool", 3: "Club Brugge", 4: "Aston Villa",
    5: "Real Madrid", 6: "Atleti", 7: "PSV", 8: "Arsenal",
    9: "Benfica", 10: "Barcellona", 11: "B. Dortmund", 12: "Lille",
    13: "Bayern", 14: "Leverkusen", 15: "Feyenoord", 16: "Inter"
  };
  let layoutLeft = []; // vuoto -> sarà 1..slots/2

  /* NON TOCCARE SOTTO */
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

  function clearAll(){ bracketEl.innerHTML = ''; while(svg.firstChild) svg.removeChild(svg.firstChild); }

  function createRound(title){
    const r = document.createElement('div'); r.className='round';
    const t = document.createElement('div'); t.className='title'; t.textContent = title;
    const body = document.createElement('div'); body.className='col-body';
    r.appendChild(t); r.appendChild(body); bracketEl.appendChild(r); return body;
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
    for(let r=0;r<rounds;r++) leftCols.push(createRound(r===0 ? 'Turno' : `Round ${r+1}`));

    // center column (trofeo + winner badge + finalists small boxes)
    const centerCol = document.createElement('div'); centerCol.className='center-col';
    const winnerTitle = document.createElement('div'); winnerTitle.className='winner-title'; winnerTitle.textContent='WINNER!';
    const winnerTeam = document.createElement('div'); winnerTeam.className='winner-team'; winnerTeam.textContent='';
    centerCol.appendChild(winnerTitle); centerCol.appendChild(winnerTeam);

    const trophy = document.createElement('div'); trophy.className='trophy-wrap';
    trophy.innerHTML = `<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M7 3a1 1 0 0 0-1 1v1a5 5 0 0 0 3 4.58V12.5a6.5 6.5 0 1 0 6 0v-.92A5 5 0 0 0 18 5V4a1 1 0 0 0-1-1H7z" stroke="url(#g1)" stroke-width="0.8" fill="url(#g2)"/>
      <defs>
        <linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#f6c84c"/><stop offset="1" stop-color="#ffd96a"/></linearGradient>
        <linearGradient id="g2" x1="0" x2="1"><stop offset="0" stop-color="#f6c84c" stop-opacity="0.9"/><stop offset="1" stop-color="#ffd96a" stop-opacity="0.7"/></linearGradient>
      </defs>
    </svg>`;
    centerCol.appendChild(trophy);

    const finalistsWrap = document.createElement('div'); finalistsWrap.className='center-finalists';
    const finalLeftBox = document.createElement('div'); finalLeftBox.className='final-team'; finalLeftBox.style.display='none';
    const finalRightBox = document.createElement('div'); finalRightBox.className='final-team'; finalRightBox.style.display='none';
    finalistsWrap.appendChild(finalLeftBox); finalistsWrap.appendChild(finalRightBox);
    centerCol.appendChild(finalistsWrap);
    bracketEl.appendChild(centerCol);

    // right columns (we will mirror vertical positions from left)
    const rightCols = [];
    for(let r=0;r<rounds;r++) rightCols.push(createRound(r===0 ? 'Turno' : `Round ${r+1}`));

    // helper create node
    const nodesBySlot = {};
    function makeNode(slot, container, alignLeft){
      const el = document.createElement('div');
      el.className='team-node';
      el.style.width = nodeW + 'px';
      el.style.left = alignLeft ? '0px' : (colW - nodeW) + 'px';
      el.innerHTML = `<div class="left-bar"></div><div class="label"></div>`;
      container.appendChild(el);
      return { el, slot, name:'', present:false, children:[], parent:null };
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

    // Right ordering of slots (half+1 .. slots)
    const layoutRightSlots = [];
    for(let i=0;i<half;i++) layoutRightSlots.push(half + 1 + i);

    // Mirroring vertically: right node index i gets left position from leftLeafs[length-1-i]
    function layoutFirstRight(body, rightOrder, leftPositions){
      const arr = [];
      const leftBodyHeight = leftCols[0].style.height || '';
      if(leftBodyHeight) body.style.height = leftBodyHeight;
      for(let i=0;i<rightOrder.length;i++){
        const slot = rightOrder[i];
        const n = makeNode(slot, body, false);
        const mirrorIndex = leftPositions.length - 1 - i;
        const leftNode = leftPositions[mirrorIndex];
        let top = 0;
        if(leftNode && leftNode.el && leftNode.el.style.top) top = parseFloat(leftNode.el.style.top);
        n.el.style.top = top + 'px';
        arr.push(n);
        nodesBySlot[slot] = n;
      }
      return arr;
    }

    const rightLeafs = layoutFirstRight(rightCols[0], layoutRightSlots, leftLeafs);

    // populate names from teamSlots, hide absent
    Object.keys(nodesBySlot).forEach(k=>{
      const slot = parseInt(k,10);
      const name = slotsMap[slot] || '';
      const node = nodesBySlot[k];
      if(!name){
        node.el.style.display='none';
        node.present=false;
      } else {
        node.el.querySelector('.label').textContent = name;
        node.name = name;
        node.present=true;
      }
    });

    // build parent levels
    function buildParentLevels(cols, leafs, alignLeft){
      let current = leafs.slice();
      const levels = [];
      for(let r=1;r<rounds;r++){
        const parentArr = [];
        const body = cols[r];
        const totalHeight = current.length * nodeH + (current.length-1)*gap;
        body.style.height = (totalHeight + 40) + 'px';
        for(let p=0;p<Math.ceil(current.length/2);p++){
          const el = document.createElement('div');
          el.className='team-node';
          el.style.width = nodeW + 'px';
          el.style.left = alignLeft ? '0px' : (colW - nodeW) + 'px';
          el.innerHTML = `<div class="left-bar"></div><div class="label"></div>`;
          body.appendChild(el);
          const childA = current[p*2] || null;
          const childB = current[p*2+1] || null;
          const top = (childA && childB) ? ((parseFloat(childA.el.style.top) + parseFloat(childB.el.style.top))/2) : (childA ? parseFloat(childA.el.style.top) : (childB ? parseFloat(childB.el.style.top) : 0));
          el.style.top = top + 'px';
          const obj = { el, children:[childA, childB], present:false, name:'', parent:null };
          if(childA) childA.parent = obj;
          if(childB) childB.parent = obj;
          const nA = childA && childA.present ? childA.name : '';
          const nB = childB && childB.present ? childB.name : '';
          if(!nA && !nB){
            obj.el.style.display='none';
            obj.present=false;
          } else {
            obj.present=true;
            obj.name = nA ? nA : nB;
            obj.el.querySelector('.label').textContent = obj.name + (nA && nB ? '' : ' (bye)');
          }
          parentArr.push(obj);
        }
        levels.push(parentArr);
        current = parentArr;
      }
      return levels;
    }

    const leftParents = buildParentLevels(leftCols, leftLeafs, true);
    const rightParents = buildParentLevels(rightCols, rightLeafs, false);

    // last-level sources
    const leftFinalSource = leftParents.length ? leftParents[leftParents.length-1][0] : leftLeafs[0];
    const rightFinalSource = rightParents.length ? rightParents[rightParents.length-1][0] : rightLeafs[0];

    // center finalists
    if(leftFinalSource && leftFinalSource.present){
      finalLeftBox.textContent = leftFinalSource.name;
      finalLeftBox.style.display='flex';
    } else finalLeftBox.style.display='none';
    if(rightFinalSource && rightFinalSource.present){
      finalRightBox.textContent = rightFinalSource.name;
      finalRightBox.style.display='flex';
    } else finalRightBox.style.display='none';

    // collect matches and assign sequential numeric ids (useful per immagine)
    let matchCounter = 1;
    function collectMatches(levels, leafs, sideName){
      const list = [];
      for(let i=0;i<leafs.length;i+=2){
        const a = leafs[i] || null; const b = leafs[i+1] || null;
        const parent = (levels && levels[0]) ? levels[0][Math.floor(i/2)] : null;
        list.push({ id: matchCounter++, side:sideName, round:0, index:Math.floor(i/2), a,b,parent });
      }
      for(let r=1;r<levels.length;r++){
        const prev = levels[r-1];
        for(let i=0;i<prev.length;i+=2){
          const a = prev[i] || null; const b = prev[i+1] || null;
          const parent = levels[r] ? levels[r][Math.floor(i/2)] : null;
          list.push({ id: matchCounter++, side:sideName, round:r, index:Math.floor(i/2), a,b,parent });
        }
      }
      return list;
    }

    const leftMatches = collectMatches(leftParents, leftLeafs, 'left');
    const rightMatches = collectMatches(rightParents, rightLeafs, 'right');

    // final center match gets its own id:
    const finalObjA = { present: !!(leftFinalSource && leftFinalSource.present), name: leftFinalSource ? leftFinalSource.name : '', source: leftFinalSource };
    const finalObjB = { present: !!(rightFinalSource && rightFinalSource.present), name: rightFinalSource ? rightFinalSource.name : '', source: rightFinalSource };
    const finalMatch = { id: matchCounter++, side:'center', round:rounds, index:0, a: finalObjA, b: finalObjB, parent:null };

    const allMatches = [...leftMatches, ...rightMatches, finalMatch];

    // find match by DOM element
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
              for(const ch2 of ch.children){
                if(ch2 && ch2.present && ch2.name) return ch2.name;
              }
            }
          }
        }
        if(part.present && part.name) return part.name;
        return 'TBA';
      }
      return [rep(match.a), rep(match.b)];
    }

    // clickable nodes
    const clickable = [];
    leftLeafs.forEach(n=>clickable.push(n));
    rightLeafs.forEach(n=>clickable.push(n));
    leftParents.forEach(lvl=>lvl.forEach(n=>clickable.push(n)));
    rightParents.forEach(lvl=>lvl.forEach(n=>clickable.push(n)));

    clickable.forEach(n=>{
      if(!n || !n.el) return;
      n.el.addEventListener('click', (ev)=>{
        ev.stopPropagation();
        if(!n.present) return;
        const match = findMatchForNode(n.el);
        if(!match) return;
        const [a,b] = resolveCompetitors(match);
        modalMatchLabel.textContent = `Match ${match.id} (${match.side} r${match.round} i${match.index})`;
        teamAEl.textContent = a;
        teamBEl.textContent = b;
        scoreA.value=''; scoreB.value='';
        modal.style.display='flex';
        modal.dataset.match = JSON.stringify({id:match.id});
      });
    });

    finalLeftBox.addEventListener('click', (ev)=>{
      ev.stopPropagation();
      const match = findMatchForNode(finalLeftBox);
      if(!match) return;
      const [a,b] = resolveCompetitors(match);
      modalMatchLabel.textContent = `Finale ${match.id}`;
      teamAEl.textContent = a;
      teamBEl.textContent = b;
      scoreA.value=''; scoreB.value='';
      modal.style.display='flex';
      modal.dataset.match = JSON.stringify({id:match.id});
    });
    finalRightBox.addEventListener('click', (ev)=>{
      ev.stopPropagation();
      const match = findMatchForNode(finalRightBox);
      if(!match) return;
      const [a,b] = resolveCompetitors(match);
      modalMatchLabel.textContent = `Finale ${match.id}`;
      teamAEl.textContent = a;
      teamBEl.textContent = b;
      scoreA.value=''; scoreB.value='';
      modal.style.display='flex';
      modal.dataset.match = JSON.stringify({id:match.id});
    });

    document.getElementById('closeModal').addEventListener('click', ()=> modal.style.display='none');
    modal.addEventListener('click', (ev)=>{ if(ev.target === modal) modal.style.display='none'; });

    // submit scores
    document.getElementById('scoreForm').addEventListener('submit', (ev)=>{
      ev.preventDefault();
      const aVal = parseInt(scoreA.value || '0',10);
      const bVal = parseInt(scoreB.value || '0',10);
      if(isNaN(aVal) || isNaN(bVal)) return;
      const dat = JSON.parse(modal.dataset.match || 'null');
      if(!dat) return;
      let match = null;
      for(const m of allMatches){
        if(m.id === dat.id){ match = m; break; }
      }
      if(!match) return;
      const [nameA, nameB] = resolveCompetitors(match);
      let winner = null;
      if(aVal > bVal) winner = nameA;
      else if(bVal > aVal) winner = nameB;
      else { alert("Pareggio: inserisci punteggio vincente."); return; }

      if(match.side === 'left' || match.side === 'right'){
        if(match.parent){
          match.parent.present = true;
          match.parent.name = winner;
          match.parent.el.style.display = '';
          match.parent.el.querySelector('.label').textContent = winner;
        } else {
          // feeder to center
          if(match.side === 'left'){
            finalObjA.present = true;
            finalObjA.name = winner;
            finalObjA.source = match.a || match.b || match;
            finalLeftBox.textContent = winner;
            finalLeftBox.style.display='flex';
          } else {
            finalObjB.present = true;
            finalObjB.name = winner;
            finalObjB.source = match.a || match.b || match;
            finalRightBox.textContent = winner;
            finalRightBox.style.display='flex';
          }
        }
      } else if(match.side === 'center'){
        // final decided
        winnerTitle.style.display = 'block';
        winnerTeam.style.display = 'block';
        winnerTeam.textContent = winner;
      }

      modal.style.display='none';
      drawLines();
    });

    // draw lines + badges
    function drawLines(){
      while(svg.firstChild) svg.removeChild(svg.firstChild);
      const br = bracketEl.getBoundingClientRect();
      svg.setAttribute('width', Math.max(900, br.width));
      svg.setAttribute('height', Math.max(520, br.height));
      function rect(el){
        const r = el.getBoundingClientRect();
        return {left:r.left-br.left, right:r.right-br.left, top:r.top-br.top, bottom:r.bottom-br.top, cx:r.left-br.left + r.width/2, cy:r.top-br.top + r.height/2};
      }
      const strokeColor = (getComputedStyle(document.documentElement).getPropertyValue('--line') || '#ffffff').trim();

      function drawList(list, isLeft){
        list.forEach(m=>{
          const a = m.a, b = m.b, parent = m.parent;
          if(!a || !b || !parent) return;
          const visible = ((a.present || (a.children && a.children.some(ch=>ch&&ch.present))) || (b.present || (b.children && b.children.some(ch=>ch&&ch.present))) || parent.present);

          [a,b].forEach(child=>{
            if(!child || !child.el || !parent.el) return;
            const c = rect(child.el); const p = rect(parent.el);
            const startX = isLeft ? c.right : c.left;
            const startY = c.cy;
            const endX = isLeft ? p.left : p.right;
            const endY = p.cy;
            const midX = (startX + endX) / 2;
            const path = document.createElementNS("http://www.w3.org/2000/svg","path");
            const d = `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`;
            path.setAttribute('d', d);
            path.setAttribute('stroke', strokeColor);
            path.setAttribute('stroke-width', visible ? '3' : '1.4');
            path.setAttribute('fill','none');
            path.setAttribute('stroke-linecap','round');
            path.setAttribute('stroke-linejoin','round');
            path.style.opacity = visible ? '1' : '0.22';
            svg.appendChild(path);

            // draw badge near midpoint (only once per match; use child's side to compute badge pos)
            // compute badge center: xBadge = midX, yBadge = (startY+endY)/2
            const xBadge = midX;
            const yBadge = (startY + endY) / 2;
            // circle background
            const g = document.createElementNS("http://www.w3.org/2000/svg","g");
            // small rounded rect background
            const rectW = 28; const rectH = 18;
            const rectEl = document.createElementNS("http://www.w3.org/2000/svg","rect");
            rectEl.setAttribute('x', xBadge - rectW/2);
            rectEl.setAttribute('y', yBadge - rectH/2 - 4);
            rectEl.setAttribute('rx', 6);
            rectEl.setAttribute('ry', 6);
            rectEl.setAttribute('width', rectW);
            rectEl.setAttribute('height', rectH);
            rectEl.setAttribute('fill', '#0b2a5b');
            rectEl.setAttribute('stroke', strokeColor);
            rectEl.setAttribute('stroke-width', visible ? '1.6' : '0.9');
            rectEl.style.opacity = visible ? '0.98' : '0.28';
            g.appendChild(rectEl);
            // text
            const txt = document.createElementNS("http://www.w3.org/2000/svg","text");
            txt.setAttribute('x', xBadge);
            txt.setAttribute('y', yBadge - 4 + rectH/2);
            txt.setAttribute('text-anchor','middle');
            txt.setAttribute('dominant-baseline','middle');
            txt.setAttribute('fill', '#ffffff');
            txt.setAttribute('font-size','11');
            txt.setAttribute('font-weight','700');
            txt.textContent = m.id;
            g.appendChild(txt);
            g.style.pointerEvents = 'none';
            svg.appendChild(g);
          });
        });
      }

      drawList(leftMatches, true);
      drawList(rightMatches, false);

      // draw connections to trophy
      const trophyEl = trophy;
      if(leftFinalSource && leftFinalSource.el && trophyEl){
        const a = rect(leftFinalSource.el); const t = rect(trophyEl);
        const startX = a.right; const startY = a.cy;
        const endX = t.left + 8; const endY = t.cy;
        const midX = (startX + endX)/2;
        const path = document.createElementNS("http://www.w3.org/2000/svg","path");
        path.setAttribute('d', `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`);
        path.setAttribute('stroke', strokeColor); path.setAttribute('stroke-width','3'); path.setAttribute('fill','none');
        path.style.opacity = leftFinalSource.present ? '1' : '0.22';
        svg.appendChild(path);
      }
      if(rightFinalSource && rightFinalSource.el && trophyEl){
        const b = rect(rightFinalSource.el); const t = rect(trophyEl);
        const startX = b.left; const startY = b.cy;
        const endX = t.right - 8; const endY = t.cy;
        const midX = (startX + endX)/2;
        const pathR = document.createElementNS("http://www.w3.org/2000/svg","path");
        pathR.setAttribute('d', `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`);
        pathR.setAttribute('stroke', strokeColor); pathR.setAttribute('stroke-width','3'); pathR.setAttribute('fill','none');
        pathR.style.opacity = rightFinalSource.present ? '1' : '0.22';
        svg.appendChild(pathR);
      }

      // center final badge number for the final match
      if(finalMatch && finalMatch.a && finalMatch.b){
        const box = centerCol.querySelector('.trophy-wrap').getBoundingClientRect ? centerCol.querySelector('.trophy-wrap') : trophy;
        // we draw final match id slightly above the trophy
        const tRect = rect(trophy);
        const x = tRect.cx; const y = tRect.top - 24;
        const g2 = document.createElementNS("http://www.w3.org/2000/svg","g");
        const rectW = 36; const rectH = 22;
        const rectEl = document.createElementNS("http://www.w3.org/2000/svg","rect");
        rectEl.setAttribute('x', x - rectW/2);
        rectEl.setAttribute('y', y - rectH/2);
        rectEl.setAttribute('rx', 6);
        rectEl.setAttribute('ry', 6);
        rectEl.setAttribute('width', rectW);
        rectEl.setAttribute('height', rectH);
        rectEl.setAttribute('fill', '#0b2a5b');
        rectEl.setAttribute('stroke', strokeColor);
        rectEl.setAttribute('stroke-width', '1.6');
        g2.appendChild(rectEl);
        const txt = document.createElementNS("http://www.w3.org/2000/svg","text");
        txt.setAttribute('x', x);
        txt.setAttribute('y', y - rectH/2 + rectH/2 + 2);
        txt.setAttribute('text-anchor','middle');
        txt.setAttribute('dominant-baseline','middle');
        txt.setAttribute('fill', '#ffffff');
        txt.setAttribute('font-size','12');
        txt.setAttribute('font-weight','800');
        txt.textContent = finalMatch.id;
        g2.appendChild(txt);
        svg.appendChild(g2);
      }
    }

    // initial draw + resize binding
    setTimeout(()=>{
      drawLines();
      window.addEventListener('resize', drawLines);
    }, 80);
  }

  // init
  build(SLOTS_COUNT, teamSlots);
  </script>
</body>
</html>