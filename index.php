<?php
session_start();
include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, name, image, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}
if (isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $product_id;
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue | Japan Ease!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<h1>Japan Ease ! Simplifiez votre apprentissage du japonais, étape par étape.</h1>
<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']);?>" class="product-img">
            
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p>Prix :€<?php echo htmlspecialchars($product['price']); ?></p>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <button type="submit" name="add_to_cart">Ajouter au panier </button>
            </form>
        </div>
    <?php endforeach ?>
</div>
        <a href="cart.php">Voir le panier</a>
    <?php include 'footer.php' ?>
</body>
</html>