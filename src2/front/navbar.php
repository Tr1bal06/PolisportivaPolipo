<?php
  // Controlla se la sessione non è già avviata
  if (session_status() == PHP_SESSION_NONE) {
      // Avvia la sessione
      session_start(); 
  }

  if(!isset($_SESSION['log'])){
    header('Location: 404.php');
    exit();
  }
  //Assegnazioni variabili di sessione
  $nome = $_SESSION['nome'];
  $cognome = $_SESSION['cognome'];
  $ruoli = $_SESSION['ruolo'];
?>


<link rel="stylesheet" href="/css/navbar.css">
<!-- Desktop Navbar -->
<div class="navbar">
  <div>
    <div class="user-info">
      <form action="/back/log_reg/handler_logout.php" method="post" class="logout " style=" margin: 8px 0px ;">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
      <img src="/assets/IconaPolipo.png" alt="Polipo">
      <p>WELCOME:<br><strong><?php echo $nome . ' ' . $cognome; ?></strong>&nbsp;<a href="/front/persone/utente.php"><i class="fa-solid fa-gear my-icon"></i></a></p>
    </div>
    <div class="nav-links">
      <a href="/front/home.php" class="<?= ($numero == 0) ? "active" : "" ?>">Home</a>
      <?php
        // Mappa: link => [ruoli ammessi, numero, etichetta]
        $menu = [
          "/front/persone/persone.php" => [['admin'], 1, 'Gestione Persone'],
          "/front/atti/atti.php" => [['admin', 'Consigliere', 'Allenatore', 'Socio'], 2, 'Atti'],
          "/front/prenotanti/prenotazione_form.php" => [['admin', 'Allenatore', 'Socio', 'Atleta'], 3, 'Prenotazione'],
          "/front/sponsor/sponsorizzazioni.php" => [['admin', 'Sponsor'], 4, 'Sponsorizzazione'],
          "/front/tornei/squadre.php" => [['admin', 'Allenatore'], 5, 'Crea squadra'],
          //"/front/atleti/atleta.php" => [['admin', 'Atleta'], 6, 'Iscrizione allo sport'],
          "/front/disponibilita/disponibilita_medico.php" => [['admin', 'Medico'], 7, 'Disponibilità'],
          "/front/convocatori/assemblea.php" => [['admin', 'Consigliere'], 8, 'Crea Assemblea'],
          "/front/tornei/tornei.php" => [['admin', 'Allenatore'], 9, 'Partecipa Torneo'],
        ];

        // Funzione che controlla se l’utente ha almeno un ruolo ammesso
        function hasRole($userRoles, $allowedRoles) {
          if (!is_array($userRoles)) return false;
          return count(array_intersect($userRoles, $allowedRoles)) > 0;
        }


        // Per evitare duplicati di href + titolo
        $rendered = [];

        foreach ($menu as $href => [$allowedRoles, $num, $label]) {
          if (hasRole($ruoli, $allowedRoles)) {
            $key = $href . $label;
            if (!in_array($key, $rendered)) {
              echo '<a href="' . $href . '" class="' . (($numero == $num) ? "active" : "") . '">' . $label . '</a>';
              $rendered[] = $key;
            }
          }
        }
      ?>
    </div>
  </div>
  <div class="footer">
    <img src="/assets/polipo.png" alt="Polipo" style="padding-right:5px ;">
    <p>&copy;PolipoPolisportivaSRL</p>
  </div>

</div>

<!-- Mobile Navbar -->
<div class="mobile-navbar">
  <div class="mobileNome">

    <strong style="padding-top: 20px; margin-right:20px"><?php echo "$nome $cognome"; ?><br><a href="/front/persone/utente.php"><i style="margin-left: 30px;" class="fa-solid fa-gear my-icon"></i></a></strong>

    <form action="logout.php" method="post" class="logout " style=" margin: 8px 0px ;">
      <button type="submit" class="logout-btn">Logout</button>
    </form>
  </div>
  <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
</div>

<div class="mobile-menu" id="mobileMenu">
  <div class="mobile-menu-content">
    <button class="close-btn" onclick="toggleMenu()">&times;</button>
    <?php
    $rendered = [];

    foreach ($menu as $href => [$allowedRoles, $num, $label]) {
      if (hasRole($ruoli, $allowedRoles)) {
        $key = $href . $label;
        if (!in_array($key, $rendered)) {
          echo '<a href="' . $href . '" class="' . (($numero == $num) ? "active" : "") . '">' . $label . '</a>';
          $rendered[] = $key;
        }
      }
    }
    ?>
    <a href="/front/tornei/tornei.php" class="<?= ($numero == 9) ? "active" : "" ?>">Partecipa Torneo</a>
  </div>
</div>

<script>
  function toggleMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu.style.display === 'flex') {
      menu.style.display = 'none';
    } else {
      menu.style.display = 'flex';
    }
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
</script>

