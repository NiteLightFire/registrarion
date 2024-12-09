<?php
session_start();
if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
</head>
<body>
    <h1>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['login']); ?>!</h1>
    <p>Ваш email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <a href="logout.php"><button>Выйти</button></a>
</body>
</html>
