<?php
session_start();
include 'bdd.php';

if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit();
}

$username = $_SESSION['username'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur introuvable";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newUsername = htmlspecialchars(trim($_POST['username']));
        $newEmail = htmlspecialchars(trim($_POST['email']));
        $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        
        try {
            $sql = "UPDATE users SET username = :username, email = :email" . ($newPassword ? ", password = :password" : "") . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $newUsername);
            $stmt->bindParam(':email', $newEmail);
            if ($newPassword) {
                $stmt->bindParam(':password', $newPassword); 
            }
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();

            $_SESSION['username'] = $newUsername;
            $_SESSION['email'] = $newEmail;

            echo "<p style='color: green;'>Informations mises à jour avec succès.</p>"; 
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } 
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
$conn = null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="profile-container">
        <h2>Modifier le Profil</h2>
        <form action="update_profile.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password">

            <input type="submit" value="Mettre à jour">
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>