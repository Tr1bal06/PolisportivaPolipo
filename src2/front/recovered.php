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
  <title>Cambia il tuo polipo!</title>
  <style>
    /* Stili di base */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
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
      margin-bottom: 1rem;
      font-size: 2rem;
    }

    .logo {
      position: absolute;
      top: -148px;
      left: -78px;
      width: 220px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input[type="password"] {
      padding: 0.75rem;
      margin-bottom: 1rem;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
    }

    button {
      padding: 0.75rem;
      border: none;
      border-radius: 25px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
      width: 100%;
    }

    /* Bottone Conferma */
    button.confirm {
      background-color: #3a4a7d;
      color: #fff;
    }

    button.confirm:hover {
      background-color: #2e3a63;
    }

    /* Bottone Torna al Login */
    button.login {
      background-color: #5a6fa5;
      color: #fff;
    }

    button.login:hover {
      background-color: #4c5c96;
    }

    /* Media query per schermi molto piccoli */
    @media (max-width: 500px) {
      .container {
        width: 80%;
      }
    }
  </style>
  <script>
    function validatePasswords() {
      var password1 = document.getElementById("password1").value;
      var password2 = document.getElementById("password2").value;
      if (password1 !== password2) {
        alert("Le password non coincidono. Riprova!");
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div class="container">
    <img src="../assets/polipoVestiti.png" class="logo" alt="Left Image">
    <h1>Cambia il tuo polipo!</h1>
    <form action="../back/log_reg/handler_controllo_password.php" method="post" onsubmit="return validatePasswords()">
      <input type="password" id="OTP" name="OTP" placeholder="One Time Password" required>
      <input type="password" id="password1" name="password1" placeholder="Nuova Password" required>
      <input type="password" id="password2" name="password2" placeholder="Ripeti Password" required>
      <button type="submit" class="confirm">Conferma</button>
    </form>
    <? if (isset($errorMessage)) { ?>
      <h3 style="color:red;"><?= $errorMessage ?></h3>
      <style>
        .container {
          padding-bottom: 3px !important;
        }
      </style>
    <? } ?>
  </div>
</body>

</html>