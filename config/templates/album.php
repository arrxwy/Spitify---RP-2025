<?php
    session_start();
    require_once "../db.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit;
    }

    $album_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM albums WHERE id = ?");
        $stmt->execute([$album_id]);
        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$album) {
            die("Album not found");
        }

        $favStmt = $pdo->prepare("SELECT 1 FROM favourites WHERE user_id = ? AND album_id = ?");
        $favStmt->execute([$user_id, $album_id]);
        $isFavourite = $favStmt->fetchColumn() ? true : false;

        $stmt = $pdo->prepare("SELECT * FROM tracks WHERE album_id = ? ORDER BY track_number ASC");
        $stmt->execute([$album_id]);
        $tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($album['title']); ?></title>
    <!-- <link rel="preload" href="/fonts/Dongle-Bold.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaOutline-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaSolid-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaShadow-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous"> -->
    <link rel = "icon" href = "favicon2.ico" type = "image/x-icon">
    <script src="https://kit.fontawesome.com/195e96a87a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="album.css">
</head>

<body>
    <div class="header">
        <a href="main.php">
            <div class="home" id = "home">
                <i class="fa-solid fa-house house"></i>
            </div>
        </a>
        <a href="profile.php">
            <div class="uzivatel" id = "uzivatel">
                <i class="fa-solid fa-circle-user user"></i>
            </div>
        </a>
    </div>

    <div class="artist_container">
        <div class="favourite" id="favourite" data-album-id="<?php echo $album_id; ?>" data-fav="<?php echo $isFavourite ? '1' : '0'; ?>">
            <i class="fa-solid fa-star fa-beat star" style="color: <?php echo $isFavourite ? 'yellow' : 'white'; ?>"></i>
        </div>
        <div class="album_table_wrap" id="album-content">
            <img class="albumPfp" id = "albumPfp" src="<?php echo htmlspecialchars($album['cover_url']); ?>">
            <table class="album_table">
                <thead>
                    <tr>
                        <!-- <td rowspan="3"><img class="albumPfp" src="<?php echo htmlspecialchars($album['cover_url']); ?>"></td> -->
                        <td rowspan="1" class="album_name"><?php echo htmlspecialchars($album['title']); ?></td>
                    </tr>
                    <tr>
                        <td class="album_mini">
                            <span class="album_rdate"><?php echo htmlspecialchars($album['release_year']); ?></span>
                            <span class="album_nos"><?php echo htmlspecialchars($album['number_of_tracks']) ?> songs</span>
                            <span class="album_duration"><?php echo htmlspecialchars($album['duration']); ?></span>                       
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="spodnicara"></div>
        <div class="tracklist" id="trackList">
            <?php foreach ($tracks as $track): ?>
            <div class="track">
                <div class="trackinfo">
                    <span class="tracknumber"><?php echo htmlspecialchars($track['track_number']); ?></span>
                    <span class="trackname"><?php echo htmlspecialchars($track['title']); ?></span>
                    <span class="trackduration"><?php echo htmlspecialchars($track['duration']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="album.js"></script>
</body>
</html>