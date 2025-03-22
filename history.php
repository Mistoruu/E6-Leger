<?php
session_start();

include 'bdd.php';
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM commandes");
    $stmt->execute();
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de commande</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Historique de commande</h1>
    <table>
        <thead>
            <tr>
                <th>Nom du produit</th>
                <th>Prix</th>
                <th>Date commande</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= $commande['name'] ?></td>
                    <td><?= $commande['price'] ?></td>
                    <td><?= $commande['commande_date'] ?></td>
                    <td><?= $commande['description'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>