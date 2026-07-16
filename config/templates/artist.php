<?php
    session_start();
    require_once "../db.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit;
    }

    $artist_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    try {
        $stmt = $pdo->prepare("SELECT * FROM artists WHERE id = ?");
        $stmt->execute([$artist_id]);
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$artist) {
            die("Artist not found");
        }

        $stmt = $pdo->prepare("SELECT * FROM albums WHERE artist_id = ? ORDER BY release_year ASC");
        $stmt->execute([$artist_id]);
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($artist['name']); ?> - Spitify</title>
    <!-- <link rel="preload" href="/fonts/Dongle-Bold.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaOutline-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaSolid-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaShadow-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous"> -->
    <link rel = "icon" href = "favicon2.ico" type = "image/x-icon">
    <script src="https://kit.fontawesome.com/195e96a87a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="artist.css">
</head>

<body id = "bodyArtist">
    <div class="header">
        <a href="main.php">
            <div class="home" id = "home">
                <i class="fa-solid fa-house house"></i>
            </div>
        </a>
        <a href = "profile.php">
            <div class="uzivatel" id = "uzivatel">
                <i class="fa-solid fa-circle-user user"></i>
            </div>
        </a>
    </div>

    <div class="artist_container">
        <div class="artist_table_wrap" id="artist-content">
            <table class="artist_table">
                <thead>
                    <tr style = "height: 100px;">
                        <td rowspan="3" style = "width: 0px"><img src="<?php echo htmlspecialchars($artist['photo_url']); ?>" class="artistpfp" id = "artistPfp"></td>
                        <td rowspan="1" class="artist_name" id = "artistName"><?php echo htmlspecialchars($artist['name']); ?></td>
                    </tr>
                    <tr style = "height: 35px;">
                        <td class="artist_mini" id = "artistMini">
                            <span class="artist_mini_name" id = "artistMiniName"><?php echo htmlspecialchars($artist['real_name']); ?></span>
                            <span class="artist_mini_age" id = "artistMiniAge"><?php echo htmlspecialchars($artist['age']); ?></span>
                            <span class="artist_mini_place" id = "artistMiniPlace"><?php echo htmlspecialchars($artist['born']); ?></span>
                        </td>
                    </tr>
                    <tr style = "position: relative; top: -20px;">
                        <td rowspan="2" class="artist_desc" id = "artistDesc"><?php echo htmlspecialchars($artist['info']); ?></td>
                    </tr>
                </thead>
            </table>
        </div>    
        <div class="spodnicara"></div>

        <div class="discography" id = "discography">
            <?php foreach ($albums as $album): ?>
            <a href="album.php?id=<?php echo htmlspecialchars($album['id']); ?>" class="album_link">
                <div class="album_card" id="albumCard" data-album-id="<?php echo htmlspecialchars($album['id']); ?>">
                    <img class = "albumcover" src="<?php echo htmlspecialchars($album['cover_url']); ?>" alt="<?php echo htmlspecialchars($album['title']); ?>">
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="artist.js"></script>
</body>

</html>