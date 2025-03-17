<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavBar</title>
    <style>
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: black;
            padding: 10px 20px;
            z-index: 1000;
        }
        body {
            margin-top: 80px;
        }
        .navbar-logo {
            height: 60px;
        }
        .navbar-right a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .navbar-right a:hover{
            background-color: #555;
            border-radius: 5px;
        }

        .cart-link {
            display: inline-block;
            padding: 5px;
        }
        
        .cart-icon {
            height: 30px;
            width: 30px;
            vertical-align: middle;
        }
        
        .cart-link:hover .cart-icon {
            filter: brightness(0.8);
        }
    </style>
</head>

<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <img src="images/logo.jpg" alt="Logo" class="navbar-logo">
        </div>

        <div class="search-bar">
            <form action="search.php" method="get" style="display: flex; align-items: center;">
                <input type="text" name="query" placeholder="Rechercher" required>
                <button type="submit" style="background: none; border: none; padding: 0; margin-left: 5px;">
                    <img src="src/images/loupe.png" alt="rechercher" class="search-icon">
                </button>
            </form>
        </div>

        <div class="navbar-right">
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="accueil.php">Accueil</a>
                <a href="index.php">Catalogue</a>
                <a href="compte.php">Profil</a>
                <a href="cart.php">
                    <img src="src/images/cart-icon-white.png" alt="Panier" class="cart-icon" title="panier">
                </a>
                <a href="connexion.php">
                    <img src="src/images/user-icon-white.png" alt="Connexion" class="cart-icon" title="connexion">
                </a>   
                <a href="inscription.php">
                    <img src="src/images/signin-icon-white.png" alt="S'inscrire" class="cart-icon">
                </a>
            <?php else:?>
                <a href="accueil.php">Accueil</a>
                <a href="index.php">Catalogue</a>
                <a href="cart.php">
                    <img src="src/images/cart-icon-white.png" alt="Panier" class="cart-icon" title="panier">
                </a>
                <a href="compte.php">
                    <img src="src/images/user-icon-white.png" alt="Utilisateur" class="cart-icon" title="compte">
                </a>    
                <a href="logout.php">
                    <img src="src/images/logout-white.png" alt="Déconnexion" class="cart-icon" title="Déconnexion">
                </a>
            <?php endif;?>
        </div>
    </div>
</body>
</html>