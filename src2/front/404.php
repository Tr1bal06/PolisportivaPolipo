<!-- 404.php -->
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Polipo non trovato!</title>
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

        p {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        button {
            padding: 0.75rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            background-color: #5a6fa5;
            color: #fff;
        }

        button:hover {
            background-color: #4c5c96;
        }

        .octopus {
            max-width: 300px;
            width: 100%;
        }

        /* Media query per schermi molto piccoli */
        @media (max-width: 500px) {
            .container {
                width: 80%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Polipo non trovato</h1>
        <img src="/assets/polipoFebbre.png" alt="Polipo" class="octopus">
        <a href="/index/index.html">
  <button>Torna alla Home</button>
</a>



    </div> 
</body>

</html>