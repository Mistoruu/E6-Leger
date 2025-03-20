<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

include 'bdd.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['table']) && isset($_GET['id'])) {
        $table = htmlspecialchars($_GET['table']);
        $id = (int) $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "Aucun enregistrement trouvé avec cet ID.";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $columns = $_POST['columns'];
            $setClause = [];
            $values = [];

            foreach ($columns as $column => $value) {
                $setClause[] = "`$column` = :$column";
                $values[":$column"] = $value;
            }

            $setClause = implode(', ', $setClause);
            $values[':id'] = $id;

            $stmt = $conn->prepare("UPDATE `$table` SET $setClause WHERE id = :id");
            $stmt->execute($values);

            header("Location: admin_dashboard.php");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'enregistrement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Modifier l'enregistrement dans la table <?php echo htmlspecialchars($table); ?></h2>

    <form method="POST">
        <?php foreach ($row as $column => $value): ?>
            <?php if ($column !== 'password'): ?>
                <label for="<?php echo $column; ?>"><?php echo htmlspecialchars($column); ?>:</label>
                <input type="text" name="columns[<?php echo htmlspecialchars($column); ?>]" 
                    value="<?php echo htmlspecialchars($value); ?>" id="<?php echo htmlspecialchars($column); ?>">
                <br>
            <?php endif; ?>
        <?php endforeach; ?>
        <input type="submit" value="Enregistrer les modifications">
    </form>
</body>
</html>
