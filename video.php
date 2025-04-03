
<?php 
session_start();

include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])){
        $video_id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $video_id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$video) {
            die("Video introuvable");
        }
        $isFavorite = false;
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $favStmt = $pdo->prepare(
                "SELECT COUNT(*) FROM favoris WHERE user_id = :user_id AND video_id = :video_id"
            );
            $favStmt->execute(['user_id' => $user, 'video_id' => $video_id]);
            $isFavorite = $favStmt->fetchColumn() > 0;
        }
    } else {
        die("ID de vidéo non spécifié.");
    }
} catch (PDOException $e) {
    echo "erreur " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .video-details {
            text-align: center;
            margin: 20px auto;
        }

        .video-details img {
            max-width: 100%;
            height: auto;
        }
        
        .video-details video {
            max-width: 100%;
            height: auto;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .text {
            margin: 10px 0;
            line-height: 1.6;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link:hover{
            text-decoration: underline;
        }

        .video-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .details-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin-top: 15px;
        }

        .details-left {
            flex: 1;
            text-align: left;
        }

        .details-right {
            flex: 0 0 200px;
            text-align: right;
        }

        button[name="add_to_cart"] {
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
        }

        button[name="add_to_cart"]:hover {
            background-color: #45a049;
        }

        .h1-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .favorite-icon {
            width: 30px;
            height: 30px;
            background: url('src/images/heart-empty.png') no-repeat center;
            background-size: contain;
            cursor: pointer;
        }

        .favorite-icon.added {
            background: url('src/images/heart-full.png') no-repeat center;
            background-size: contain;
        }
        
        video {
            max-width: 100%;
            height: auto;
        }
        
    </style>
    <title><?php echo htmlspecialchars($video['name']); ?></title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="video-details">
            <div class="h1-container">
            <h1><?= htmlspecialchars($video['name']); ?></h1>
            <?php if (isset($_SESSION['username'])): ?>
                <div id="favorite-icon" class="favorite-icon <?= $isFavorite ? 'added' : '' ?>" data-video-id="<?= $video_id ?>"></div>
            <?php endif; ?>
            </div>
            <video src="<?= htmlspecialchars($video['video']); ?>" controls type="video/mp4">
                Votre navigateur ne supporte pas les vidéos.
            </video>
            <p class="text"><strong>Description : </strong> <?= htmlspecialchars($video['description']);?><br></p>
            <div class="details-container">
                <div class="details-left">
                <p class="text">
                    <strong>Date de mise en ligne : </strong> <?= htmlspecialchars($video['upload_date']);?><br>
                    <strong>Durée : </strong> <?= htmlspecialchars($video['duration']);?><br>
                </p>
                </div>
                <div class="details-right">
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($video['id']); ?>">
                        <button type="submit" name="add_to_cart">Ajouter au Panier</button>
                    </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const favoriteIcon = document.getElementById('favorite-icon');

            if (favoriteIcon){
                favoriteIcon.addEventListener('click', function () {
                    const videoId = favoriteIcon.getAttribute('data-video-id');

                    fetch('add_to_favorites.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ video_id: videoId})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            favoriteIcon.classList.toggle('added');
                        } else {
                            alert('Erreur lors de l\'ajout aux favoris.');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
                });
            }
        });
    </script>
</body>
</html>