<?php 
session_start();

if (!isset($_SESSION['username'])) {
    die("L'utilisateur n'est pas connecté.");
}

$user_id = $_SESSION['username'];

include 'bdd.php';

try {
    if (!isset($pdo)){
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.image, p.video, p.price, p.type
        FROM favoris f
        JOIN products p ON f.video_id = p.id
        WHERE f.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes favoris</title>
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

        p {
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
</head>
<body>
    <?php include 'navbar.php' ?>
    <h1>Mes Favoris</h1>
    <div class="product-list">
        <?php if (empty($favoris)) : ?>
            <p>Aucun favoris trouvé.</p>
        <?php else: ?>
            <?php foreach ($favoris as $favori): ?>
                <div class="product-item">
                    <div class="product-media">
                    <img src="<?php echo htmlspecialchars($favori['image']); ?>" alt="<?php echo htmlspecialchars($favori['name']); ?>" class="product-img">
                    <video controls mute autoplay loop class="product-video">
                    <source src="<?php echo htmlspecialchars($favori['video']); ?>" type="video/mp4">
                    Votre navigateur ne supporte pas les vidéos
                    </video>
                    </div>
                    <h2>
                    <a href="video.php?id=<?php echo htmlspecialchars($favori['id']); ?>">
                        <?php echo htmlspecialchars($favori['name']); ?>
                    </a>
                    </h2>
                    <p>Prix: €<?php echo htmlspecialchars($favori['price']); ?></p>
                <div class="favorite-icon <?php echo in_array($favori['id'], array_column($favoris, 'id')) ? 'added' : ''; ?>" data-video-id="<?php echo htmlspecialchars($favori['id']); ?>"></div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const favoriteIcons = document.querySelectorAll('.favorite-icon');

    favoriteIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const videoId = icon.getAttribute('data-video-id');

            fetch('add_to_favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ video_id: videoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'removed') {
                        icon.classList.remove('added');
                        icon.classList.add('removing');

                        setTimeout(() => {
                            icon.parentElement.remove();
                        }, 1000);
                    } else if (data.action === 'added') {
                        icon.classList.add('added');
                        icon.classList.remove('removing');
                    }
                } else {
                    alert(data.message || 'Erreur lors de la modification des favoris.');
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });
});
    </script>
</body>

</html>