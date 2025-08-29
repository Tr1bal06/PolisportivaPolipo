<!DOCTYPE html>
<html lang="it">
  <?$numero = 5;
  if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
  
  ?>
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

    label {
      flex: 1 1 200px;
      display: flex;
      flex-direction: column;
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
<?php

  include '../../back/connessione.php';
  include '../../back/function.php';
  if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}

  $permessi = ['Allenatore', 'admin'];

  if(!controllo($_SESSION['ruolo'], $permessi)) {
      header("location: ../404.php");
      exit();
  }
  include "../navbar.php";
?>
<body>
    <? include "../";?>
    <div class="blocco"></div>
    <div class="container">
      <h1>Gli Sport che insegno </h1>
      <h2>Seleziona e insegna un nuovo sport!</h2>
        <form action="../../back/allenatore/handler_allenatore.php" method="post" id="formSport">
            <select name="sport" id="selectSport" class="input-style" required style=" margin-top: 0.3rem; background-color: #ffffffcc; color: #000; font-size: 1rem; width:90%">
                <option value="Basket">Basket</option>
                <option value="Calcio">Calcio</option>
                <option value="Volley">Volley</option>
                <option value="Tennis">Tennis</option>
        </select>
        
            

            <button style="width:150px" type="submit">Invia</button>
    
        </form>
        <? 
          if (session_status() == PHP_SESSION_NONE) {
    // Avvia la sessione
    session_start();
}
        if(isset($_SESSION['error_message'])){
          echo $_SESSION['error_message'];
          $_SESSION['error_message'] = NULL ;
         } 
         

         if(isset($_SESSION['success_message'])){
          echo $_SESSION['success_message'];
          $_SESSION['success_message'] = NULL ;
         }
      ?>
        <h2>I miei sport</h2>
        <div class="table-container">
      <table id="tabellaSport">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Elimina</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          caricaDati();

          window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
              window.scrollTo(0, 0);
            }
          });
        });
      function caricaDati() {
      fetch(`../../back/allenatore/get_allenatore.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaSport tbody');
          tbody.innerHTML = '';
          let count = 1;
          data.forEach(sport => {
            
            const row = `
              <tr>
                <td>${count+"°"}</td>
                <td>${sport.NomeSport}</td>
                <td>
                  <form action = '../../back/allenatore/elimina_sport.php' method = 'POST'  class="logout">
                    <input type="hidden" name="path" value="../../front/allenatore/allenatore.php">
                    <input type="hidden" name="sport" value="${sport.NomeSport}">
                    <input type="hidden" name="source" value="allenatore">
                    <button type="submit" style="padding: 0.4rem 1.2rem;" id="bottone${sport.Nome}" class="bottoniElimina" >Elimina</button>
                  </form>
                </td>
              </tr>`;
            tbody.innerHTML += row;
            count++;
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }

    </script>
</body>
</html>