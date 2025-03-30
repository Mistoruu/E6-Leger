<?php
session_start();

date_default_timezone_set('Europe/Paris');


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil - Japan Ease</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: url('src/images/accueil.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 36px;
            margin-top: 100px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
        }

        .intro {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
            padding: 15px;
            border-radius: 10px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #c0c0c0;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #e6b800;
        }

        .logout-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .logout-button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <?php include "navbar.php"; ?>
    <main>
        <h1>Bienvenue sur Japan Ease</h1>
        <p class="intro">Simplifiez votre apprentissage du japonais, étape par étape.</p>
        <a href="index.php" class="btn">Voir nos programmes</a>
    </main>
    <?php include "footer.php"; ?>
</body>
</html>
