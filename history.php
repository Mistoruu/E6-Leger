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
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

h1 {
    text-align: center;
    margin-top: 20px;
    color: #444;
}

/* Table styles */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
    text-transform: uppercase;
    font-size: 14px;
}

tr:hover {
    background-color: #f1f1f1;
}

td {
    font-size: 14px;
    color: #555;
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    table {
        width: 100%;
    }

    th, td {
        padding: 10px;
        font-size: 12px;
    }
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