<? session_start();
// $_SESSION['error_message'] = "Si è verificato un errore!"; // Per test
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
$_SESSION['error_message'] = null;
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrazione</title>
  <style>
    /* Stili di base */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
      /* Gradienti basati sulla palette con #4c5c96 */
      background: linear-gradient(135deg, #4c5c96, #3a4a7d);
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      position: relative;
      overflow: hidden;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      width: 90%;
      max-width: 450px;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    h1 {
      margin-bottom: 1.5rem;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] {
      padding: 0.75rem;
      margin-bottom: 1rem;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
    }

    button {
      padding: 0.75rem;
      margin: 0.5rem 0;
      border: none;
      border-radius: 25px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
      width: 100%;
    }

    /* Bottone Registrazione */
    button.login {
      background-color: #3a4a7d;
      color: #fff;
    }

    button.login:hover {
      background-color: #2e3a63;
    }

    /* Bottone Vai al Login */
    button.home {
      width: 45%;
      background-color: #5a6fa5;
      color: #fff;
    }

    button.home:hover {
      background-color: #4c5c96;
    }

    /* Media query per desktop */
    @media (max-width: 992px) {
      .container {
        max-width: 500px;
        padding: 3rem;
      }

      input,
      button {
        font-size: 1.2rem;
      }

      .wave {
        display: none;
      }
    }

    /* Media query per tablet */
    @media (max-width: 600px) {

      .container {
        max-width: 300px;
        padding: 2.5rem;
      }

      .logo {
        display: none;
      }

      input,
      button {
        font-size: 1.1rem;
      }
    }

    /* Media query per schermi molto piccoli: 
       cambia la larghezza del container dal 90% all'80% */
    @media (max-width: 500px) {
      .container {
        width: 80%;
      }

    }

    .logo {
      position: absolute;
      top: -60px;
      left: -60px;
      width: 150px;
      transform: rotate(-20deg);
    }

    /* Effetto onda */
    .wave {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
      z-index: 0;
    }

    /* Bottone Home */
    button.home {
      background-color: #5a6fa5;
      color: #fff;
    }

    button.home:hover {
      background-color: #4c5c96;
    }

    .wave svg {
      position: relative;
      display: block;
      width: calc(200% + 1.3px);
      height: 100px;
    }

    .wave .shape-fill {
      fill: #fff;
      /* colore della wave: contrasto con il blu */
      opacity: 0.2;
    }

    .bottoni {
      margin-top: 1rem;
      display: flex;
      justify-content: space-between;
    }

    <? if (isset($errorMessage)) { ?>.container {


      padding-bottom: 2.5px !important;

    }

    <? } ?>
  </style>
  <script>
    function validateForm() {
      var pass1 = document.getElementById("password1").value;
      var pass2 = document.getElementById("password2").value;
      var phone = document.getElementById("telefono").value;
      if (pass1 !== pass2) {
        alert("Le password non coincidono.");
        return false;
      }
      if (phone.trim() === "") {
        alert("Inserisci il numero di telefono.");
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div>
    <h1>POLISPORTIVA POLIPO</h1>
  </div>
  <div class="container">
    <img src="../assets/polipo.png" class="logo" alt="Logo">
    <h1>Registrazione</h1>
    <form action="../back/log_reg/handler_signup.php" method="post" onsubmit="return validateForm();">
      <input type="text" name="cf" placeholder="Codice Fiscale" maxlength="16" minlength="16" required>
      <input type="text" name="nome" placeholder="Nome" required>
      <input type="text" name="cognome" placeholder="Cognome" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="tel" name="telefono" id="telefono" placeholder="Numero di Telefono" maxlength="10" minlength="10">
      <input type="password" name="password1" id="password1" placeholder="Password" required>
      <input type="password" name="password2" id="password2" placeholder="Conferma Password" required>
      <button type="submit" class="login">Registrati</button>
    </form>
    <div class="bottoni">
      <button class="home" onclick="window.location.href='login.php'">Vai al Login</button>
      <button class="home linea" onclick="window.location.href='../index/index.html'">Torna alla Home</button>
    </div>
    <? if (isset($errorMessage)) { ?>
      <h3 style="color:red;"><?= $errorMessage ?></h3>
      <style>
        .container {
          padding-bottom: 3px !important;
        }
      </style>
    <? } ?>
  </div>



  <!-- Effetto Wave nello sfondo -->
  <div class="wave">
    <svg viewBox="0 0 1200 100" preserveAspectRatio="none">
      <path d="M0,0 C300,100 900,-50 1200,0 L1200,100 L0,100 Z" class="shape-fill"></path>
    </svg>
  </div>
</body>

</html>