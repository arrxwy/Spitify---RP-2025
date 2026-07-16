<?php
session_start();
header('Content-Type: application/json');
require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$album_id = isset($_POST['album_id']) ? (int)$_POST['album_id'] : 0;

if (!$album_id) {
    echo json_encode(['success' => false, 'error' => 'No album id']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT 1 FROM favourites WHERE user_id = ? AND album_id = ?");
    $stmt->execute([$user_id, $album_id]);
    $isFavourite = $stmt->fetchColumn();

    if ($isFavourite) {
        $stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND album_id = ?");
        $stmt->execute([$user_id, $album_id]);
        echo json_encode(['success' => true, 'isFavourite' => false]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO favourites (user_id, album_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $album_id]);
        echo json_encode(['success' => true, 'isFavourite' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
