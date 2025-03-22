<?php
session_start();
include 'bdd.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Votre panier est vide.");
}

if (!isset($_POST['adress'])) {
    die("Adresse non fournie.");
}

$cart_items = $_SESSION['cart'];
$adress = trim($_POST['adress']);  // Suppression des espaces

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id, name, image, price, description FROM products WHERE id = :id");
    $insert_stmt = $pdo->prepare("
        INSERT INTO commandes (name, image, price, commande_date, description, adress) 
        VALUES (:name, :image, :price, NOW(), :description, :adress)
    ");

    // Boucle pour insérer chaque élément du panier dans la table commandes
    foreach ($cart_items as $product_id) {
        $stmt->execute(['id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $insert_stmt->execute([
                'name' => $product['name'],
                'image' => $product['image'],
                'price' => $product['price'],
                'description' => $product['description'],
                'adress' => $adress
            ]);
        }
    }

    unset($_SESSION['cart']);  // Vider le panier après la commande
    header("Location: index.php");  // Rediriger vers la page d'accueil après la commande
    exit();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
