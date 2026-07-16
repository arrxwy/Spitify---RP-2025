<?php
    session_start();
    require_once "../db.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit;
    }

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $isAdmin = ($username === 'admin');

    if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle Artist Form
        if (isset($_POST['add_artist']) && !empty($_POST['artist_name'])) {
            $stmt = $pdo->prepare("INSERT INTO artists (name, real_name, age, born, info, photo_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['artist_name'],
                $_POST['real_name'],
                $_POST['age'],
                $_POST['born'],
                $_POST['info'],
                $_POST['photo_url']
            ]);
        }
        
        // Handle Album Form
        if (isset($_POST['add_album']) && !empty($_POST['album_title'])) {
            $stmt = $pdo->prepare("INSERT INTO albums (artist_id, title, release_year, number_of_tracks, duration, cover_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['artist_id'],
                $_POST['album_title'],
                $_POST['release_year'],
                $_POST['tracks'],
                $_POST['duration'],
                $_POST['cover_url']
            ]);
        }

        // Handle Track Form
        if (isset($_POST['add_track']) && !empty($_POST['track_title'])) {
            $stmt = $pdo->prepare("INSERT INTO tracks (album_id, title, feature, duration, track_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['track_album_id'],
                $_POST['track_title'],
                $_POST['track_feature'],
                $_POST['track_duration'],
                $_POST['track_number']
            ]);
        }
    }

    function getFavouriteAlbums($pdo, $user_id) {
        $stmt = $pdo->prepare("
            SELECT albums.id AS album_id, albums.title, albums.cover_url
            FROM favourites
            JOIN albums ON favourites.album_id = albums.id
            WHERE favourites.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $favouriteAlbums = getFavouriteAlbums($pdo, $user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Hello <?php echo htmlspecialchars($username); ?>!</title>
    <!-- <link rel="preload" href="/fonts/Dongle-Bold.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaOutline-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaSolid-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaShadow-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous"> -->
    <link rel = "icon" href = "favicon2.ico" type = "image/x-icon">
    <script src="https://kit.fontawesome.com/195e96a87a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <div class="header">
        <a href="main.php">
            <div class="home">
                <i class="fa-solid fa-house house"></i>
            </div>
        </a>
        <a href="logout.php">
            <div class="logout">
                <i class="fa-solid fa-right-from-bracket logout-icon"></i>
            </div>
        </a>
        <a href="profile.php">
            <div class="uzivatel">
                <i class="fa-solid fa-circle-user user"></i>
            </div>
        </a>
    </div>

    <div class="artist_container">
        <h1 class="welcome">Hello <?php echo htmlspecialchars($username); ?>!</h1>
        <div class="spodnicara"></div>
        <h2 class="favourite">Your favourites:</h2>
        <div class="favAlbums" id="favAlbums">
            <?php if (empty($favouriteAlbums)): ?>
                <p style="color:white; font-family: 'Londrina', sans-serif; opacity: 25%; text-align: center;">No favourite albums yet.</p>
            <?php else: ?>
                <?php foreach ($favouriteAlbums as $album): ?>
                    <a href="album.php?id=<?php echo htmlspecialchars($album['album_id']); ?>">
                        <div class="album_card" style="background-image: url('<?php echo htmlspecialchars($album['cover_url']); ?>');">
                            <!-- <span style="color:white;"><?php echo htmlspecialchars($album['title']); ?></span> -->
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if ($isAdmin): ?>
            <button id="toggleAdmin" class="admin-button">Toggle Admin Panel</button>
            
            <div id="adminPanel" class="admin-panel" style="display: none;">
                <h2 style="font-family: LondrinaShadow, sans-serif; color: white; font-size: 3em; margin: 0 0 10px 0; text-align: center;">Add to database:</h2>
                <div class="admin-form">
                    <div class="form-section">
                        <h3>Add New Artist</h3>
                        <form method="POST">
                            <input type="text" name="artist_name" placeholder="Artist Name">
                            <input type="text" name="real_name" placeholder="Real Name">
                            <input type="number" name="age" placeholder="Age">
                            <input type="text" name="born" placeholder="Place of Birth">
                            <input type = "text" name = "info" placeholder="Artist Info"></textarea>
                            <input type="url" name="photo_url" placeholder="Photo URL">
                            <button type="submit" name="add_artist">Add Artist</button>
                        </form>
                    </div>
                    
                    <div class="form-section">
                        <h3>Add New Album</h3>
                        <form method="POST">
                            <input type="number" name="artist_id" placeholder="Artist ID">
                            <input type="text" name="album_title" placeholder="Album Title">
                            <input type="number" name="release_year" placeholder="Release Year">
                            <input type="number" name="tracks" placeholder="Number of Tracks">
                            <input type="text" name="duration" placeholder="Duration">
                            <input type="url" name="cover_url" placeholder="Album Cover URL">
                            <button type="submit" name="add_album">Add Album</button>
                        </form>
                    </div>

                    <div class="form-section">
                        <h3>Add New Track</h3>
                        <form method="POST">
                            <input type="number" name="track_album_id" placeholder="Album ID">
                            <input type="text" name="track_title" placeholder="Track Title">
                            <input type="text" name="track_feature" placeholder="Track Feature (optional)">
                            <input type="text" name="track_duration" placeholder="Duration">
                            <input type="number" name="track_number" placeholder="Track Number">
                            <input style = "visibility: hidden;">
                            <button type="submit" name="add_track">Add Track</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($isAdmin): ?>
    <script>
    document.getElementById('toggleAdmin').addEventListener('click', function() {
        const adminPanel = document.getElementById('adminPanel');
        const container = document.querySelector('.artist_container');
        const favContent = document.querySelector('.favAlbums');
        const welcome = document.querySelector('.welcome');
        const spodnicara = document.querySelector('.spodnicara');
        const favourite = document.querySelector('.favourite');
        
        if (adminPanel.style.display === 'none') {
            favContent.style.display = 'none';
            welcome.style.display = 'none';
            spodnicara.style.display = 'none';
            favourite.style.display = 'none';
            adminPanel.style.display = 'block';
        } else {
            favContent.style.display = 'flex';
            welcome.style.display = 'flex';
            spodnicara.style.display = 'block';
            favourite.style.display = 'flex';
            adminPanel.style.display = 'none';
        }
    });
    </script>
    <?php endif; ?>
</body>

</html>