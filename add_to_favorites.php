<?php
session_start();
include 'bdd.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit;
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['username'];

$data = json_decode(file_get_contents('php://input'), true);
$video_id = $data['video_id'] ?? null;

if (!$video_id) {
    echo json_encode(['success' => false, 'message' => 'ID de la vidéo manquant.']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE user_id = :user_id AND video_id = :video_id");
    $stmt->execute(['user_id' => $user_id, 'video_id' =>$video_id]);
    $is_favorite = $stmt->fetchColumn() > 0;

    if ($is_favorite) {
        $stmt = $pdo->prepare("DELETE FROM favoris WHERE user_id = :user_id AND video_id = :video_id");
        $stmt->execute(['user_id' => $user_id, 'video_id' => $video_id]);
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Vidéo retirée aux favoris.']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO favoris (user_id, video_id) VALUES (:user_id, :video_id)");
        $stmt->execute(['user_id' => $user_id, 'video_id' => $video_id]);
        echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Vidéo ajoutée aux favoris.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>