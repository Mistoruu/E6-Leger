<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include "bdd.php";

if (!isset($_GET['table'])) {
    echo "Aucune table spécifiée.";
    exit();
}

$tableName = $_GET['table'];


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("DESCRIBE $tableName");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fields = [];
        $placeholders = [];
        $values = [];

        foreach ($columns as $column) {
            $field = $column['Field'];
            if ($field === 'id') {
                continue;
            }
            $fields[] = $field;
            $placeholders[] = ":$field";
            $values[":$field"] = $_POST[$field] ?? null;
        }

        $sql = "INSERT INTO $tableName (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $placeholders) . ")";
        $insertStmt = $conn->prepare($sql);
        $insertStmt->execute($values);

        echo "Enregistrement ajouté avec succès.";
        echo "<p><a href='admin_dashboard.php'>Retour au tableau de bord</a></p>";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un enregistrement</title>
</head>
<body>
    <h1>Ajouter un enregistrement dans la table "<?php echo htmlspecialchars($tableName); ?>"</h1>

    <form method="POST">
        <?php foreach ($columns as $column): ?>
            <?php if ($column['Field'] === 'id') continue; ?>
            <label for="<?php echo $column['Field']; ?>">
                <?php echo htmlspecialchars($column['Field']); ?>
            </label>
            <input type="text" name="<?php echo $column['Field']; ?>" id="<?php echo $column['Field']; ?>" required>
        <?php endforeach; ?>
        <button type="submit">Ajouter</button>
    </form>

    <a href="admin_dashboard.php">Retour au tableau de bord</a>
</body>
</html>