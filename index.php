<?php
session_start();
include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $priceQuery = $pdo->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products");
    $priceResult = $priceQuery->fetch(PDO::FETCH_ASSOC);
    $minPrice = $priceResult['min_price'] ?? 0;
    $maxPrice = $priceResult['max_price'] ?? 35.00;

    $filters = [];
    $sql = "SELECT id, name, image, price FROM products WHERE 1=1";

    $filterConditions = [];
    if (isset($_GET['filter_php'])) {
        $filterConditions[] = "type = 'PHP'";
    }
    if (isset($_GET['filter_css'])) {
        $filterConditions[] = "type = 'CSS'";
    }
    if (isset($_GET['filter_js'])) {
        $filterConditions[] = "type = 'JS'";
    }
    if (isset($_GET['filter_mysql'])) {
        $filterConditions[] = "type = 'MYSQL'";
    }

    if (!empty($filterConditions)) {
        $sql .= "AND (" . implode(" OR ", $filterConditions) . ")";
    }
    if (isset($_GET['price_min']) && isset($_GET['price_max'])) {
        $min_price = $_GET['price_min'];
        $max_price = $_GET['price_max'];
        $sql .= " AND price BETWEEN $min_price AND $max_price";
    }

    $stmt = $pdo->query($sql);
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

<form>
    <label>
        <input type="checkbox" name="filter_php" <?php echo isset($_GET['filter_php']) ? 'checked' : ''; ?>>
        EN PHP
        <input type="checkbox" name="filter_php" <?php echo isset($_GET['filter_css']) ? 'checked' : ''; ?>>
        EN css
        <input type="checkbox" name="filter_php" <?php echo isset($_GET['filter_js']) ? 'checked' : ''; ?>>
        EN js
        <input type="checkbox" name="filter_php" <?php echo isset($_GET['filter_mysql']) ? 'checked' : ''; ?>>
        EN mysql
    </label>
    <div class="price-slider">
        <input type="range" name="price_min" min="<?php echo $min_price;?>" max="<?php echo $max_price?>" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : $minPrice;?>" >
    </div>
</form>
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