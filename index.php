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

    $sql = "SELECT id, name, image, video, price FROM products WHERE 1=1";
    $params = [];

    $filterConditions = [];
    if (isset($_GET['filter_beginner'])) {
        $filterConditions[] = "type = 'beginner'";
    }
    if (isset($_GET['filter_N5'])) {
        $filterConditions[] = "type = 'N5'";
    }
    if (isset($_GET['filter_N4'])) {
        $filterConditions[] = "type = 'N4'";
    }

    if (!empty($filterConditions)) {
        $sql .= " AND (" . implode(" OR ", $filterConditions) . ")";
    }

    if (isset($_GET['price_min']) && isset($_GET['price_max'])) {
        $min_price = (float) $_GET['price_min'];
        $max_price = (float) $_GET['price_max'];

        if ($min_price >= $minPrice && $max_price <= $maxPrice && $min_price <= $max_price) {
            $sql .= " AND price BETWEEN :min_price AND :max_price";
            $params[':min_price'] = $min_price;
            $params[':max_price'] = $max_price;
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<h1>Japan Ease ! Simplifiez votre apprentissage du japonais, étape par étape.</h1>

<form method="GET" id="filterForm">
    <label>
        <input type="checkbox" name="filter_beginner" <?php echo isset($_GET['filter_beginner']) ? 'checked' : ''; ?>> Débutant
        <input type="checkbox" name="filter_N5" <?php echo isset($_GET['filter_N5']) ? 'checked' : ''; ?>> N5
        <input type="checkbox" name="filter_N4" <?php echo isset($_GET['filter_N4']) ? 'checked' : ''; ?>> N4
    </label>

    <div class="price-slider">
        <input type="range" name="price_min" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : $minPrice; ?>" step="1" style="width: 45%;" id="minPrice">
        <input type="range" name="price_max" min="<?php echo $minPrice; ?>" max="<?php echo $maxPrice; ?>" value="<?php echo isset($_GET['price_max']) ? $_GET['price_max'] : $maxPrice; ?>" step="1" style="width: 45%;" id="maxPrice">
    </div>

    <div class="price-values">
        <span>Prix min: <span id="price-min"><?php echo isset($_GET['price_min']) ? $_GET['price_min'] : $minPrice; ?></span> €</span>
        <span>Prix max: <span id="price-max"><?php echo isset($_GET['price_max']) ? $_GET['price_max'] : $maxPrice; ?></span> €</span>
    </div>
</form>

<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-item">
            <div class="product-media">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
            <video class="product-video" controls autoplay loop muted>
                <source src="<?php echo htmlspecialchars($product['video']); ?>" type="video/mp4">
                Votre navigateur ne supporte pas les vidéos.
            </video>
            </div>
            <h2>
                <a href="video.php?id=<?= htmlspecialchars($product['id']) ?>">
                    <?php echo htmlspecialchars($product['name']); ?>
                </a>
            </h2>
            <p>Prix : €<?php echo htmlspecialchars($product['price']); ?></p>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <button type="submit" name="add_to_cart">Ajouter au panier</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<a href="cart.php">Voir le panier</a>
<?php include 'footer.php'; ?>

<script>
    const minSlider = document.getElementById('minPrice');
    const maxSlider = document.getElementById('maxPrice');
    const minPriceLabel = document.getElementById('price-min');
    const maxPriceLabel = document.getElementById('price-max');
    const filterForm = document.getElementById('filterForm');

    minSlider.addEventListener('input', function() {
        if (parseInt(minSlider.value) > parseInt(maxSlider.value)) {
            minSlider.value = maxSlider.value;
        }
        minPriceLabel.textContent = minSlider.value;
    });

    maxSlider.addEventListener('input', function() {
        if (parseInt(maxSlider.value) < parseInt(minSlider.value)) {
            maxSlider.value = minSlider.value;
        }
        maxPriceLabel.textContent = maxSlider.value;
    });

    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            filterForm.submit();
        });
    });

    minSlider.addEventListener('change', () => filterForm.submit());
    maxSlider.addEventListener('change', () => filterForm.submit());
</script>

</body>
</html>
