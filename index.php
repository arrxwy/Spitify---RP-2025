<?php
require_once "./config/db.php";

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $errorMessage = "Please fill in all fields";
        } else {
            try {
                $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $checkStmt->execute([$username]);
                
                if ($checkStmt->rowCount() > 0) {
                    $errorMessage = "Username already exists";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt->execute([$username, $hashedPassword]);

                    if ($stmt->rowCount() > 0) {
                        session_start();
                        $_SESSION['user_id'] = $pdo->lastInsertId();
                        $_SESSION['username'] = $username;
                        header("Location: ./config/templates/main.php");
                        exit;
                    } else {
                        $errorMessage = "Registration failed";
                    }
                }
            } catch (PDOException $e) {
                $errorMessage = "Error: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $errorMessage = "Please fill in all fields";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    header("Location: ./config/templates/main.php");
                    exit;
                } else {
                    $errorMessage = "Invalid username or password";
                }
            } catch (PDOException $e) {
                $errorMessage = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Welcome</title>
    <!-- <link rel="preload" href="/fonts/Dongle-Bold.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaOutline-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaSolid-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/LondrinaShadow-Regular.ttf" as="font" type="font/ttf" crossorigin="anonymous"> -->
    <link rel = "icon" href = "favicon2.ico" type = "image/x-icon">
    <script src="https://kit.fontawesome.com/195e96a87a.js" crossorigin="anonymous"></script>
    <link rel = "stylesheet" href = "./config/templates/login.css">
</head>
<body>
    <form class="login-container" method="post">
        <h1>Welcome!</h1>
        <h2>Create an account or Log In!</h2>
        <input class = "username" type = "text" id = "username" name = "username" placeholder = "Username">
        <input class = "password" type = "password" id = "password" name = "password" placeholder = "Password">
        <div class = "buttons">
            <button class = "register" type = "submit" id = "register" name = "register">Register</button>
            <button class = "login" type = "submit" id = "login" name = "login">Log In</button>
        <br>
        </div>
        <?php if (!empty($errorMessage)): ?>
            <div style="position:relative;top:20px;color: red;display: flex;align-items: center;justify-content: center; font-family: 'Londrina', sans-serif; font-size: 1.1em;">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
    </form>
    <!-- <script src = "login.js"></script> -->
</body>
</html>