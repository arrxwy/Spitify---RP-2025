<?php
    session_start();
    require_once "../db.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit;
    }

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $albums = [];

    try {
        if (!empty($search)) {
            $stmt = $pdo->prepare("SELECT * FROM artists WHERE name LIKE ?");
            $stmt->execute(["%$search%"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM artists ORDER BY RAND() LIMIT 4");
        }
        $albums = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = "Error loading artists: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Spitify</title>
    <!-- <link rel = "preload" href = "/fonts/Dongle-Bold.ttf" as = "font" type = "font/ttf" crossorigin = "anonymous">
    <link rel = "preload" href = "/fonts/LondrinaOutline-Regular.ttf" as = "font" type = "font/ttf" crossorigin = "anonymous">
    <link rel = "preload" href = "/fonts/LondrinaSolid-Regular.ttf" as = "font" type = "font/ttf" crossorigin = "anonymous">
    <link rel = "preload" href = "/fonts/LondrinaShadow-Regular.ttf" as = "font" type = "font/ttf" crossorigin = "anonymous"> -->
    <link rel = "icon" href = "favicon2.ico" type = "image/x-icon">
    <script src="https://kit.fontawesome.com/195e96a87a.js" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "main.css">
    <link rel="stylesheet" href="responsive.css">
</head>
<body>
        <div class = "header">
            <a href = "main.php">
                <div class = "home">
                    <i class="fa-solid fa-house house"></i>
                </div>
            </a>
            <a href = "profile.php">
                <div class="uzivatel">
                    <i class="fa-solid fa-circle-user user"></i>
                </div>
            </a>
        </div>

    <div class = "obsah">
        <div class = "nadpis"> 
            <h1 class = "spitify">Spitify</h1>
            <form method="GET" class="searchbar">
                <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" style="display:none"><i class="fa-solid fa-search"></i></button>
            </form>
            <div class = "spodnicara"></div>
        </div>
        <div class = "podnadpis">
            <h2><?php echo !empty($search) ? 'Search Results 🔍' : "What's HOT 🔥"; ?></h2>
        </div>
        <div class = "list">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php else: ?>
                <?php foreach ($albums as $album): ?>
                    <a href="artist.php?id=<?php echo htmlspecialchars($album['id']); ?>" class="karta-link karta-animation">
                        <div class="karta">
                            <img class="artist-image" src="<?php echo htmlspecialchars($album['photo_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($album['real_name'], ENT_QUOTES, 'UTF-8'); ?>">
                            <h3 class="artist_title"><?php echo htmlspecialchars($album['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php if (empty($albums)): ?>
                    <p class="no-results" style = "font-family: 'Londrina', sans-serif;">No albums found</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>    
    <script src = "main.js"></script>
</body>
</html>