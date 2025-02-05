<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Inscription</title>
</head>
<body>
    <div class="signup-container">
        <h2>Inscription</h2>
        <form action="" method="POST" onsubmit="return validateForm()">
            <label for="username" >Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="email" >Email :</label>
            <input type="" id="" name="" required>

            <label for="password" >Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm-password" >Confirmer le mot de passe :</label>
            <input type="password" id="password" name="confirm-password" required>

            <inout type="submit" value="S'inscrire"> 
            <p id="error-msg"></p>
        </form>
    </div>
    <script>
        function validateForm() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm-password").value;
            let errorMsg = document.getElementById("error-msg").value;

            if(password!==confirmPassword){
                errorMsg.textContent = "Les mots de passe ne correspondent pas.";
                errorMsg.style.color = "red";
                return false;
            }
            return true;
        }
    </script>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));
        $confirmPassword = htmlspecialchars(trim($_POST['confirm-password']));
        if($password !== $confirmPassword){
            echo "<p style='color: red;'>Les mots de passe ne correspondent pas.<p>";
            exit;
        }

        include 'bdd.php';

        try {
            $conn = new PDO("mysql:host=$servename;dbname=$dbname", $dbusername, $dbpassword);
            $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam('email', $email);
            $stmt->execute();

            if ($stmt->rowCount() >0) {
                echo "<p style='color: red;'> Cet email est déjà utilisé. </p>";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam('username', $username);
                $stmt->bindParam('email', $email);
                $stmt->bindParam('password', $hashedPassword);

                if ($stmt->execute() ) {
                    header("Location: connexion.php");
                    exit();
                }
                echo "<p style='color: red;'> Une erreur est survenue. </p>";
            } 
        } catch(PDOException $e) {
            echo "Erreur :" . $e->getMessage();
        }
        $conn = null;
    }
    ?>
</body>
</html>