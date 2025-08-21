<?
include "../../back/connessione.php";
include "../../back/function.php";
session_start();
$numero = 7;
$permessi = ['admin', 'Medico'];
if (!controllo($_SESSION['ruolo'], $permessi)) {
    header("location: ../../front/404.php");
    exit();
}

            
$codiceAtleta = $_SESSION['caricheCodici']['Medico'];

$sql = "SELECT GiornoSettimanale FROM `DISPONIBILITA` WHERE CodiceMedico = '$codiceAtleta';";
$result = $conn->query($sql);

if ($result) {
    $query_giorni = $result->fetch_all(MYSQLI_NUM);
    $giorni = array_column($query_giorni, 0);
} else {
    header("location: ../../front/404.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/navbar.css">
    <script src="https://kit.fontawesome.com/e97255b1a1.js" crossorigin="anonymous"></script>
    <title>Disponibilità medico</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #4c5c96, #3a4a7d);
            color: white;
            display: flex;
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

        .days-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 10px;
            flex-wrap: nowrap;
        }

        .day {
            flex: 1 1 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            border-radius: 8px;
            background-color: #3a4a7d;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .day:hover {
            background-color: #5c7ae3;
        }

        .day.active {
            background-color: #88bde9;
            transform: translateY(2px);
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

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            /* Puoi regolare questo valore per dare più o meno spazio sopra il bottone */
        }

        @media (max-width: 1131px) {
            .days-container {
                flex-direction: column;
                gap: 0.5rem;
            }

            .day {
                width: 80%;
                margin: auto;
                flex: 1 1 100%;
            }
        }

        @media (max-width: 768px) {
            .blocco {
                display: none;
            }
        }
    </style>
</head>
<?php

include "../navbar.php";
?>

<body>
    <? include "../"; ?>
    <div class="blocco"></div>
    <div class="container">
             <? if(isset($_SESSION['error_message'])){
          echo $_SESSION['error_message'];
          $_SESSION['error_message'] = NULL ;
         } 
         

         if(isset($_SESSION['success_message'])){
          echo $_SESSION['success_message'];
          $_SESSION['success_message'] = NULL ;
         }
      ?>
        <h1>Gestione Disponibilità</h1>
        <form action="../../back/disponibilita/handler_disponibilita.php" id="formDisp" method="post">

            <div class="days-container">

                <div class="day <?= (in_array("Lunedi", $giorni) ? "active" : "") ?>" id="Lunedi">Lunedi</div>
                <div class="day <?= (in_array("Martedi", $giorni) ? "active" : "") ?>" id="Martedi">Martedi</div>
                <div class="day <?= (in_array("Mercoledi", $giorni) ? "active" : "") ?>" id="Mercoledi">Mercoledi</div>
                <div class="day <?= (in_array("Giovedi", $giorni) ? "active" : "") ?>" id="Giovedi">Giovedi</div>
                <div class="day <?= (in_array("Venerdi", $giorni) ? "active" : "") ?>" id="Venerdi">Venerdi</div>
                <div class="day <?= (in_array("Sabato", $giorni) ? "active" : "") ?>" id="Sabato">Sabato</div>
                <div class="day <?= (in_array("Domenica", $giorni) ? "active" : "") ?>" id="Domenica">Domenica</div>
            </div>

            <input type="hidden" name="giorni" id="giorni">
            <div class="button-container">
                <button type="submit">Modifica</button>
            </div>
       
        </form>
        <h1>I miei Tornei</h1>
        <div class="table-container">
      <table id="tabellaTornei">
        <thead>
          <tr>
          
            <th>Nome</th>
            <th>Sport</th>
            <th>Data</th>
            <th>Campo</th>
            <th>Stato</th>  
            
          </tr>
        </thead>
        <tbody>
          <!-- I record verranno caricati via JS -->
        </tbody>
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

        function formatOra(datetime) {
            const d = new Date(datetime);
            return d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
        }

      function caricaDati() {
      fetch(`../../back/disponibilita/get_medico_tornei.php`)
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#tabellaTornei tbody');
          tbody.innerHTML = '';
          console.log(data);
          data.forEach(torneo => {

            let stato = torneo.stato
            if(stato == null){
                stato = "Confermato";
            }

            const row = `
              <tr>
                <td>${torneo.Nome}</td>
                <td>${torneo.Sport}</td>
                <td>${torneo.Anno}</td>
                <td>${torneo.NomeCampo}</td>
                <td>${stato}</td>
              </tr>`;
            tbody.innerHTML += row;
            
          });
        })
        .catch(error => {
          console.error('Errore nel caricamento degli atti:', error);
        });
    }

        const days = document.querySelectorAll('.day');
        const giorniInput = document.getElementById('giorni');

        days.forEach(day => {
            day.addEventListener('click', () => {
                day.classList.toggle('active');
                updateSelectedDays();
            });
        });

        function updateSelectedDays() {
            const selectedDays = [];
            days.forEach(day => {
                if (day.classList.contains('active')) {
                    selectedDays.push(day.textContent);
                }
            });
            giorniInput.value = selectedDays.join(',');
        }
    </script>

</body>

</html>