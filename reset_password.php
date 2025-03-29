<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'bdd.php';
    $token = htmlspecialchars(trim($_POST['token']));
    $newPassword = htmlspecialchars(trim($_POST['new_password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));

    if ($newPassword !== $confirmPassword) {
        echo "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
        exit();
    }
    if (strlen($newPassword) < 8) {
        echo "<p style='color: red;'>Le mot de passe doit contenir au moins 8 caractères.</p>";
        exit();
    }
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $email = $stmt->fetchColumn();
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            header("Location: connexion.php?reset=success");
            exit();

        } else {
            echo "<p style='color: red;'>Le lien de réinitialisation est invalide ou a expiré.</p>";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    $conn = null;
} else if (isset($_GET['token'])) {
    $token = htmlspecialchars(trim($_GET['token']));
    if (empty($token)) {
        echo "<p style='color: red;'>Token invalide.</p>";
        exit();
    }
} else {
    echo "<p style='color: red;'>Aucun token fourni.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="login-container">
        <div class="form-container">
            <h2>Réinitialisation du mot de passe</h2>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Réinitialiser</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>