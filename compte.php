<?php
session_start();
include 'bdd.php';

if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit();
}

$username = $_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style.css">
    <style>

        .profile {
    text-align: center;
    margin: 20px auto;
        }
        .cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    width: calc(25% - 20px); 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.card h3 {
    margin-bottom: 10px;
    font-size: 1.5em;
}

.card p {
    margin-bottom: 15px;
    color: #555;
}

.card .btn {
    display: inline-block;
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.card .btn:hover {
    background-color: #0056b3;
}
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="profile">
    <h1>Bienvenue, <?= htmlspecialchars($username); ?> !</h1>
    <p>Voici les options disponibles pour gérer votre compte :</p>
    </div>
    <div class="cards-container">
    <!-- Security Card -->
    <div class="card">
        <h3>Connexion & sécurité</h3>
        <p>Modifier l'email et le mot de passe de votre compte</p>
        <a href="update_profile.php" class="btn">Modifier le Profil</a>
    </div>

    <!-- History Card -->
    <div class="card">
        <h3>Historique</h3>
        <p>Consultez votre historique d'activités.</p>
        <a href="history.php" class="btn">Voir l'historique</a>
    </div>

    <!-- Contact Card -->
    <div class="card">
        <h3>Contact</h3>
        <p>Besoin d'aide ? Contactez notre support.</p>
        <a href="contact.php" class="btn">Nous contacter</a>
    </div>

    <!-- Address Card -->
    <div class="card">
        <h3>Adresse</h3>
        <p>Gérez vos informations d'adresse.</p>
        <a href="address.php" class="btn">Modifier l'adresse</a>
    </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
