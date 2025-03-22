<?php 
session_start();

include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, name, image, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion" . $e->getMessage();
    exit();
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
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

if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];

    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $key = array_search($item_id, $_SESSION['cart']);
        
        if ($key == false){
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css">
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
    color: #007BFF;
}

table img {
    max-width: 100px;
    border-radius: 5px;
}

.cart p {
    font-size: 18px;
    font-weight: bold;
    text-align: right;
    margin-top: 10px;
    color: #333;
}


table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

table thead {
    color: black;
}

table th, table td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

table th {
    font-size: 16px;
    font-weight: bold;
}

table tbody tr:last-child td {
    border-bottom: none;
}
button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    transition: background-color 0.3s;
    margin: 5px;
}
button:hover {
    background-color: #45a049;
}

button[name="remove_item"] {
    background-color: #FF4C4C;
}

button[name="remove_item"]:hover {
    background-color: #FF3333;
}

button[name="clear_cart"] {
    background-color: #FF9800;
}

button[name="clear_cart"]:hover {
    background-color: #FF7700;
}
    </style>
</head>
<body>
    <?php include 'navbar.php' ?>

    <h1>Votre Panier</h1>

    <?php if (!empty($cart_items)): ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom du produit</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cart_items as $item_id): ?> 
                <?php
                
                $product = array_filter($products, function($prod) use ($item_id) {
                    return $prod['id'] == $item_id;
                });
                    $product = array_values($product)[0]; 
                    $total += $product['price'];  
                ?>
                <tr>
                    <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100"></td>
                    <td><?php echo $product['name']; ?> - €<?php echo $product['price']; ?></td>
                    <td>€<?php echo $product['price']; ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="add_to_cart">Ajouter</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                            <button type="submit" name="remove_item">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <p class="cart">Total : €<?php echo $total; ?></p>
        <form method="POST">
            <button type="submit" name="clear_cart">Vider le Panier</button>
        </form>
        <form method="get" action="checkout.php">
            <button type="submit">Finaliser le Panier</button>
        </form>
        <?php else: ?>
            <p class="cart">Votre panier est vide.</p>
        <?php endif; ?>
        <a href="index.php">Retourner au Catalogue</a>

        <?php include 'footer.php' ?>
</body>
</html>